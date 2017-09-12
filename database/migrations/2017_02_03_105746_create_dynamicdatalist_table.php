<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDynamicdatalistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dynamicdatalist', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->unsignedInteger('structure_id')->index();
            $table->foreign('structure_id')->references('id')->on('structure')->onDelete('cascade');
            $table->tinyInteger('status_id')->default(1);
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
        Schema::dropIfExists('dynamicdatalist');
    }
}
