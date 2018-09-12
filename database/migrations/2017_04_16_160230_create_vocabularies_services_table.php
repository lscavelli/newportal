<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVocabulariesServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vocabularies_services', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('vocabulary_id')->unsigned()->index();
            $table->foreign('vocabulary_id')->references('id')->on('vocabularies')->onDelete('cascade');
            $table->integer('service_id')->unsigned()->index();
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
            $table->tinyInteger('type_order')->default(0);
            $table->tinyInteger('type_dir')->default(0);
            $table->tinyInteger('required')->default(0);
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
        Schema::dropIfExists('vocabularies_services');
    }
}
