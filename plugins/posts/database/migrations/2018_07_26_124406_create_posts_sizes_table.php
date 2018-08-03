<?php

use Illuminate\Database\Migrations\Migration;

class CreatePostsSizesTable extends Migration
{
    /*
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("posts_sizes", function ($table) {
            $table->integer('post_id')->index();
            $table->string('size')->index();
        });

    }

    /*
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('posts_sizes');
    }
}
