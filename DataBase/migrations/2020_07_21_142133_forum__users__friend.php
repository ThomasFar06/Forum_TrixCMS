<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ForumUsersFriend extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("forum__users__friend", function(Blueprint $table) {
            $table->integer("id");
            $table->integer("friend_id");
            $table->engine = 'InnoDB';
            $table->timestamps();

            $table->primary(['id', 'friend_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("forum__users__friend");
    }
}

/**
 * Table forum__users__friend {
user_id int
friend_id int
}
 */