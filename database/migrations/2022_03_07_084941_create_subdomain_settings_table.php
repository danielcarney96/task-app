<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subdomain_settings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('subdomain_id')->unsigned()->nullable();
            $table->foreign('subdomain_id')->references('id')->on('subdomains');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_domain_settings');
    }
};
