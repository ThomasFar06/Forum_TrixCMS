<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ForumForum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("forum__forum", function(Blueprint $table) {
            $table->increments("id");
            $table->integer("position")->nullable();
            $table->integer("forum_id")->nullable();
            $table->boolean("category");
            $table->integer("size")->nullable();
            $table->string("name");
            $table->text("description")->nullable();
            $table->integer("write__rank_id");
            $table->integer("watch_rank_id");
            $table->integer("icon_type");
            $table->string("icon");
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
        Schema::dropIfExists("forum__forum");
    }
}