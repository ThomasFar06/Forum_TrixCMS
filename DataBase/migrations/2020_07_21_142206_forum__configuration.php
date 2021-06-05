<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ForumConfiguration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("forum__configuration", function(Blueprint $table) {
            $table->increments("id");
            $table->boolean("forum")->nullable();
            $table->boolean('theme')->nullable();
            $table->boolean("widget__button")->nullable();
            $table->string("button_link")->nullable();
            $table->string("button_name")->nullable();
            $table->boolean("widget__staff")->nullable();
            $table->boolean("widget__online")->nullable();
            $table->boolean("widget__discord")->nullable();
            $table->string("discord")->nullable();
            $table->boolean("widget__statistics")->nullable();
            $table->boolean("widget__latest_post")->nullable();
            $table->boolean("widget__share")->nullable();
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
        Schema::dropIfExists("forum__configuration");
    }
}