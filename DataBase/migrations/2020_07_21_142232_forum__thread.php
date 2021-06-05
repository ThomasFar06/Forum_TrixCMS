<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ForumThread extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("forum__thread", function(Blueprint $table) {
            $table->increments("id");
            $table->integer("forum_id")->nullable();
            $table->integer("author_id");
            $table->integer("thread_id")->nullable();
            $table->json("tags")->nullable();
            $table->string("name")->nullable();
            $table->boolean("pin")->nullable();
            $table->boolean("lock")->nullable();
            $table->boolean("archive")->nullable();
            $table->boolean("delete")->nullable();
            $table->longText("message");
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
        Schema::dropIfExists("forum__thread");
    }
}