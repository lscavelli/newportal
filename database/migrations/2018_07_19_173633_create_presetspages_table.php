<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePresetspagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('presetspages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->string('layout')->nullable();
            $table->string('theme')->nullable();
            $table->integer('portlet_id')->unsigned()->index();
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
        Schema::dropIfExists('presetspages');
    }
}
