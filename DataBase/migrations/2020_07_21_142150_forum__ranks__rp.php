<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ForumRanksRp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("forum__ranks__rp", function(Blueprint $table) {
            $table->integer("rank_id");
            $table->integer("permission_id");
            $table->boolean("action");
            $table->engine = 'InnoDB';
            $table->timestamps();

            $table->primary(['rank_id', 'permission_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("forum__ranks__rp");
    }
}
