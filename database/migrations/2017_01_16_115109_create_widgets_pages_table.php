<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWidgetsPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('widgets_pages', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('widget_id')->unsigned()->index();
            $table->foreign('widget_id')->references('id')->on('widgets')->onDelete('cascade');
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
        Schema::dropIfExists('widgets_pages');
    }
}
