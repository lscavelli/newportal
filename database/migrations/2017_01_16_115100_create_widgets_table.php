<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWidgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('widgets', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('name', 150);
            $table->string('init', 150)->unique();
            $table->string('path', 150)->nullable();
            $table->unsignedInteger('type_id')->nullable();
            $table->tinyInteger('status_id')->default(1);
            $table->string('title')->nullable();
            $table->string('author')->nullable();
            $table->string('revision')->nullable();
            $table->string('date')->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('structure_id')->nullable();
            $table->string('service', 191)->nullable();
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
        Schema::dropIfExists('widgets');
    }
}
