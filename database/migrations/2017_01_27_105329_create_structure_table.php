<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStructureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('structure', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->text('content')->nullable();
            $table->string('color', 50)->nullable();
            $table->tinyInteger('status_id')->default(1);
            $table->integer('service_id')->unsigned()->index()->nullable();
            $table->foreign('service_id')->references('id')->on('services');
            $table->integer('user_id')->unsigned()->index();
            $table->string('username')->nullable();
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
        Schema::dropIfExists('structure');
    }
}
