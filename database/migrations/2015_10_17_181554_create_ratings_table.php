<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id_from');
            $table->string('user_id_to');
            $table->tinyInteger('rating_friendliness')->unsigned()->default(0);
            $table->tinyInteger('rating_skill')->unsigned()->default(0);
            $table->tinyInteger('rating_teamwork')->unsigned()->default(0);
            $table->tinyInteger('rating_funfactor')->unsigned()->default(0);
            $table->foreign('user_id_from')->references('facebook_id')->on('users')->onDelete('cascade');
            $table->foreign('user_id_to')->references('facebook_id')->on('users')->onDelete('cascade');
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
        Schema::drop('ratings');
    }
}
