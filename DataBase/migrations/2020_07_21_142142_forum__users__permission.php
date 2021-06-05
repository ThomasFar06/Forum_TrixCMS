<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ForumUsersPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("forum__users__permission", function(Blueprint $table) {
            $table->integer("id");
            $table->integer("permission_id");
            $table->engine = 'InnoDB';
            $table->timestamps();

            $table->primary(['id', 'permission_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("forum__users__permission");
    }
}
