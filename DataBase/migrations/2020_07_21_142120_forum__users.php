<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ForumUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create("forum__users", function(Blueprint $table) {
            $table->integer("id")->unique();
            $table->integer("avatar_type")->nullable();
            $table->string("avatar")->nullable();
            $table->string("title")->nullable();
            $table->date("birth")->nullable();
            $table->string("location")->nullable();
            $table->string("website")->nullable();
            $table->text("about")->nullable();
            $table->text("signature")->nullable();
            $table->boolean("banned")->default(0);
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
        Schema::dropIfExists("forum__users");
    }
}
