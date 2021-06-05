<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ForumUsersNotification extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("forum__users__notification", function(Blueprint $table) {
            $table->integer("id")->unique();
            $table->boolean("post__watched_forum")->nullable();
            $table->boolean("post__watched_thread")->nullable();
            $table->boolean("post_mention")->nullable();
            $table->boolean("message_quoting")->nullable();
            $table->boolean("message_react")->nullable();
            $table->boolean("chat__private")->nullable();
            $table->boolean("chat__poke")->nullable();
            $table->boolean("chat__message")->nullable();
            $table->boolean("profile__react")->nullable();
            $table->boolean("profile__comment")->nullable();
            $table->boolean("profile__comment_reply")->nullable();
            $table->boolean("profile__friend_request")->nullable();
            $table->boolean("profile__trophy")->nullable();
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
        Schema::dropIfExists("forum__users__notification");
    }
}
