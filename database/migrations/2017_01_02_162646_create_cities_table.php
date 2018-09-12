<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->char('code', 6);
            $table->string('name', 255)->default('');
            $table->integer('regionCode')->default(0);
            $table->string('region', 150)->nullable();
            $table->integer('provinceCode')->default(0);
            $table->string('province', 150)->nullable();
            $table->integer('cmCode')->default(0);
            $table->string('cm', 150)->nullable();
            $table->char('initials', 2);
            $table->char('cadastralCode', 4);
            $table->char('cap',5);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cities');
    }
}
