<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ForumCustomization extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("forum__customization", function(Blueprint $table) {
            $table->increments("id");
            $table->string("color__main")->nullable();
            $table->string("color__second")->nullable();
            $table->string("color__background")->nullable();
            $table->boolean("forum__description_tooltip")->nullable();
            $table->integer("forum__icon__default")->nullable();
            $table->boolean("user__profile__tooltip")->nullable();
            $table->integer("user__profile__avatar")->nullable();
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
        Schema::dropIfExists("forum__customization");
    }
}