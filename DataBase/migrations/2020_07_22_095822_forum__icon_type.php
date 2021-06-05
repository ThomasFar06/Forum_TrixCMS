<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ForumIconType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("forum__icon_type", function (Blueprint $table) {
            $table->increments("id");
            $table->string("name");
            $table->string("website");
            $table->string("format");
            $table->text("import");
            $table->integer("type");
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
        Schema::dropIfExists("forum__icon_type");
    }
}