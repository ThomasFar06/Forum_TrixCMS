<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ForumThreadEdits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("forum__thread_edits", function(Blueprint $table) {
            $table->increments("id");
            $table->integer("author_id");
            $table->integer("thread_id");
            $table->string("name")->nullable();
            $table->longText("message")->nullable();
            $table->boolean("show")->nullable();
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
        Schema::dropIfExists("forum__thread_edits");

    }
}
