<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePortletsPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('portlets_pages', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('portlet_id')->unsigned()->index();
            $table->foreign('portlet_id')->references('id')->on('portlets')->onDelete('cascade');
            $table->integer('page_id')->unsigned()->index();
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
            $table->string('frame',150);
            $table->string('template')->nullable();
            $table->tinyInteger('position')->default(0);
            $table->tinyInteger('comunication')->default(0);
            $table->string('name', 150)->nullable();
            $table->string('title')->nullable();
            $table->text('css')->nullable();
            $table->text('js')->nullable();
            $table->text('setting')->nullable();
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
        Schema::dropIfExists('portlets_pages');
    }
}
