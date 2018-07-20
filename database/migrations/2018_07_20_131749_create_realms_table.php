<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRealmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('realms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
			$table->string("population");
			$table->boolean("queue");
			$table->boolean("status");
			$table->string("name");
			$table->string("slug");
			$table->string("battlegroup");
			$table->string("locale");
			$table->string("timezone");
			$table->text("connected_realms");
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
        Schema::dropIfExists('realms');
    }
}
