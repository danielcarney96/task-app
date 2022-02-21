#!/bin/bash

set -e

cmd="$@"

# Colours
grn="\033[0;92m"
ylw="\033[1;33m"
nc="\033[0m"

# Variables
env=".env"
env_example=".env.example"

# Steps to run when the project is starting up, these are usually any commands
# that need to be run that aren't depending on the database container being ready.
steps_to_run_on_project_start()
{
    # Copy .env file
    if [ ! -f $env ]; then
        echo "...copying .env.example file to .env...";
        cp $env_example $env
    fi

    # Install composer
    echo "...running composer install..."
    composer install

    # Install npm
    echo "...running npm install..."
    npm install

    # Compile assets
    echo "...running npm run dev..."
    npm run dev

    # Setup Laravel keys
    echo "...running php artisan key:generate..."
    php artisan key:generate
}

# Steps to run when the database container is ready and already has database tables,
# this is usually when the project has already been started previously.
steps_to_run_when_database_is_available_and_seeded()
{
    # Run migrations
    echo "...running migrations..."
    php artisan migrate
}

# Steps to run when the database container is ready but has no tables yet,
# this is usually because this is the first time the project has been started,
# or the database volumes were removed.
steps_to_run_when_database_is_available_and_not_seeded()
{
    # Run migrations
    echo "...running migrations and seeders..."
    php artisan migrate:fresh --seed
}

display_ready_message()
{
    echo -e "${grn}"
    echo -e "${grn}########################################################################"
    echo -e "${grn}###                                                                  ###"
    echo -e "${grn}###                  Project is booted and ready!                    ###"
    echo -e "${grn}###                                                                  ###"
    echo -e "${grn}###             Visit http://localhost to get started.               ###"
    echo -e "${grn}###                                                                  ###"
    echo -e "${grn}########################################################################"
    echo -e "${grn}"
    echo -e "${nc}"
}

echo "...setting up project..."

steps_to_run_on_project_start

# Wait for database container to be ready
until mysql -h "$DB_HOST" -u "$DB_USERNAME" -e "USE $DB_DATABASE;"; do
    echo "...database container not ready yet, retrying..."
    sleep 1
done

# Check if database has any tables
if [[ $(echo -n `mysql -h $DB_HOST -u $DB_USERNAME -sse "SELECT count(*) FROM information_schema.tables WHERE table_schema='$DB_DATABASE';"`) -gt 0 ]]; then

    # Run steps when database already has tables
    steps_to_run_when_database_is_available_and_seeded

    display_ready_message

    exec $cmd
    exit 0
fi

# Run steps when database does not have any tables yet
steps_to_run_when_database_is_available_and_not_seeded

display_ready_message

exec $cmd
