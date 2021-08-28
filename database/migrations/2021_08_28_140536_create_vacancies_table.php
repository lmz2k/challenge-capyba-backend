<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVacanciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'vacancies',
            function (Blueprint $table) {
                $table->increments('id');

                $table->string('title')
                    ->nullable(false);

                $table->text('description')
                    ->nullable(false);

                $table->double('salary')
                    ->nullable(false);

                $table->enum('occupation', ["BACK", "FRONT", "FULL"])
                    ->nullable(false);

                $table->boolean('is_home_office')
                    ->nullable(false);

                $table->enum('hiring_mode', ["PJ", "CLT", "BOTH"])
                    ->nullable(false);

                $table->integer('city_id')
                    ->unsigned();

                $table->unsignedInteger('announcement_by')
                    ->unsigned();

                $table->foreign('city_id')
                    ->references('id')
                    ->on('cities');

                $table->foreign('announcement_by')
                    ->references('id')
                    ->on('users');

                $table->timestamps();
                $table->softDeletes();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vacancies');
    }
}
