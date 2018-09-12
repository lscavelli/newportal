<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->char('code', 2);
            $table->string('name', 255)->default('');
            $table->string('capital', 255)->nullable();
            $table->integer('isoNumeric')->default(0);
            $table->string('continentName', 255)->default('');
            $table->char('continentCode', 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('countries');
    }
}
