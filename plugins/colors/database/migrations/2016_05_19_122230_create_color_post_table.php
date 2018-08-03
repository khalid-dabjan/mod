<?php

use Illuminate\Database\Migrations\Migration;

class CreateColorPostTable extends Migration
{
    /*
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("color_post", function ($table) {
            $table->integer('post_id')->default(0)->index();
            $table->integer('color_id')->default(0)->index();
        });

    }

    /*
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('color_post');
    }
}
