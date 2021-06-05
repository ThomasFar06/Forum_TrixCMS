<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ForumUsersSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("forum__users__setting", function(Blueprint $table) {
            $table->integer("id")->unique();
            $table->string("timezone")->nullable();
            $table->boolean("profile__show_birth")->nullable();
            $table->boolean("profile__show_birth_year")->nullable();
            $table->boolean("email__receive_onpost")->nullable();
            $table->boolean("email__receive_onreply")->nullable();
            $table->boolean("content__show_signature")->nullable();
            $table->boolean("privacy__show_location")->nullable();
            $table->boolean("privacy__show_online")->nullable();
            $table->boolean("privacy__show_activity")->nullable();
            $table->boolean("rank__show")->nullable();
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
        Schema::dropIfExists("forum__users__setting");
    }
}
