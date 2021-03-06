<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCharactersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('characters', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('class');
            $table->string('thumbnail');
            $table->string('battlegroup');
            $table->tinyInteger('faction');
            $table->tinyInteger('gender');
            $table->tinyInteger('race');
            $table->integer('level');
            $table->integer('totalHonorableKills');
            $table->integer('achievementPoints');
			$table->integer('user_id');
			$table->string('name');
			$table->string('realm');
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
        Schema::dropIfExists('characters');
    }
}
