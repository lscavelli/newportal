<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebcontentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('webcontent', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->text('content')->nullable();
            $table->unsignedInteger('structure_id')->index();
            $table->foreign('structure_id')->references('id')->on('structure')->onDelete('cascade');
            $table->unsignedInteger('model_id')->nullable();
            $table->tinyInteger('status_id')->default(1);
            $table->timestamp('displaydate')->nullable();
            $table->timestamp('expirationdate')->nullable();
            $table->tinyInteger('inevidence')->default(0);
            $table->timestamp('expirationdate_evidence')->nullable();
            $table->integer('user_id')->unsigned()->index();
            $table->string('username')->nullable();
            $table->boolean('hidden_')->default(0);
            $table->integer('hits')->unsigned()->default(0);
            $table->string('image')->nullable();
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
        Schema::dropIfExists('webcontent');
    }
}
