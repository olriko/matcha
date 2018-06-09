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
            $table->increments('id');

            $table->string('username');
            $table->string('first_name');
            $table->string('last_name');

            $table->string('password');

            $table->string('email');
            $table->boolean('is_activated')->default(0);

            $table->string('gender')->default('other');
            $table->string('sexual_orientation')->default('bisexual');

            $table->text('description')->nullable();

            $table->integer('score')->default(0);

            $table->string('lat')->nullable();
            $table->string('lng')->nullable();

            $table->date('birthday')->nullable();
            $table->timestamp('last_connexion')->nullable();

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
