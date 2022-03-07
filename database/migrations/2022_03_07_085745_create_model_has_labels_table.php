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
        Schema::create('model_has_labels', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('label_id')->unsigned()->nullable();
            $table->foreign('label_id')->references('id')->on('labels');
            $table->string('model');
            $table->string('model_id');
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
        Schema::dropIfExists('model_has_labels');
    }
};
