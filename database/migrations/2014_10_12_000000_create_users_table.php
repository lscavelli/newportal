<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('nome')->nullable();
            $table->string('cognome')->nullable();
            $table->date('data_nascita')->nullable();
            $table->string('telefono')->nullable();
            $table->string('avatar')->nullable();
            $table->string('indirizzo')->nullable();
            $table->tinyInteger('status_id')->default(1);
            $table->unsignedInteger('country_id')->nullable();
            $table->unsignedInteger('city_id')->nullable();
            $table->text('note')->nullable();
            $table->timestamp('ultimo_accesso')->nullable();
            $table->string('email')->unique();
            $table->string('username')->nullable()->unique();
            $table->string('password', 60);
            $table->string('confirmation_token')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('google2fa_secret',255)->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
