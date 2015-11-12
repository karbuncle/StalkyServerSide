<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id_from')->unsigned();
            $table->integer('user_id_to')->unsigned();
            $table->foreign('user_id_from')->references('id')->on('users')->onDelete('set null');
            $table->foreign('user_id_to')->references('id')->on('users')->onDelete('set null');
            $table->String('comment');
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
        Schema::drop('comments');
    }
}
