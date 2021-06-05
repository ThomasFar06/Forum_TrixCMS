<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ForumThreadLikes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("forum__thread_likes", function(Blueprint $table) {
            $table->integer("user_id");
            $table->integer("thread_id");
            $table->engine = 'InnoDB';
            $table->timestamps();

            $table->primary(['user_id', 'thread_id']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("forum__thread_likes");
    }
}
