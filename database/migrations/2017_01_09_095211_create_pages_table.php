<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('name', 150);
            $table->string('slug', 150)->unique();
            $table->unsignedInteger('type_id')->nullable()->default(0);
            $table->string('url', 255)->nullable();
            $table->string('layout')->nullable();
            $table->string('theme')->nullable();
            $table->integer('parent_id')->unsigned()->nullable()->default(null);
            $table->string('title')->nullable();
            $table->text('keywords')->nullable();
            $table->text('description')->nullable();
            $table->text('robots')->nullable();
            $table->text('css')->nullable();
            $table->text('javascript')->nullable();
            $table->integer('user_id')->unsigned()->index();
            $table->string('username')->nullable();
            $table->boolean('hidden_')->default(0);
            $table->boolean('sitemap')->default(1);
            $table->tinyInteger('status_id')->default(1);
            $table->string('image')->nullable();
            $table->timestamps();
            $table->foreign('parent_id')->references('id')->on('pages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pages');
    }
}
