<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDynamicdatalistcontentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dynamicdatalist_content', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->text('content')->nullable();
            $table->unsignedInteger('dynamicdatalist_id')->index();
            $table->foreign('dynamicdatalist_id')->references('id')->on('dynamicdatalist')->onDelete('cascade');
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
        Schema::dropIfExists('dynamicdatalist_content');
    }
}
