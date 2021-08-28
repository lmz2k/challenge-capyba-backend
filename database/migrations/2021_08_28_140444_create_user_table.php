<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Users', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name')
                ->unique()
                ->nullable(false);

            $table->string('password')
                ->nullable(false);

            $table->string('photo')
                ->nullable(false);

            $table->boolean('verified')
                ->nullable(false)
                ->default(false);

            $table->dateTime('last_login');
            $table->dateTime('last_logout');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Users');
    }
}
