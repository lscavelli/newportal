<?php

use Illuminate\Support\Facades\Schema;
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
            $table->increments('id')->unsigned();
            $table->string('name')->nullable();
            $table->text('content')->nullable();
            $table->integer('commentable_id')->unsigned()->index();
            $table->string('commentable_type')->nullable();
            $table->string('email')->nullable();
            $table->string('author_ip', 100);
            $table->string('author')->nullable();
            $table->tinyInteger('approved')->default(0);
            $table->integer('user_id')->unsigned()->nullable(); // per i guest
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('comments');
    }
}
