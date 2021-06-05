<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ForumThreadTags extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("forum__thread_tags", function (Blueprint $table) {
            $table->increments("id");
            $table->json("rank_id")->nullable();
            $table->json("thread_id")->nullable();
            $table->string("name");
            $table->string("text_color");
            $table->string("background_color");
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
        Schema::dropIfExists("forum__thread_tags");
    }
}

