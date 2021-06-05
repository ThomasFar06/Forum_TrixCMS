<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ForumRanks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("forum__ranks", function(Blueprint $table) {
            $table->increments("id");
            $table->integer('power')->nullable();
            $table->string('name');
            $table->string('background');
            $table->string('color');
            $table->boolean('staff')->nullable();
            $table->boolean('default')->nullable();
            $table->engine = 'InnoDB';
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
        Schema::dropIfExists("forum__ranks");
    }
}
