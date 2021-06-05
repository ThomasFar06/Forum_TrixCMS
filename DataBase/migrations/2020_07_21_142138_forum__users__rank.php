<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ForumUsersRank extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("forum__users__rank", function(Blueprint $table) {
            $table->integer("id");
            $table->integer("rank_id");
            $table->engine = 'InnoDB';
            $table->timestamps();

            $table->primary(['id', 'rank_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("forum__users__rank");
    }
}
