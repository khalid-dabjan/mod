<?php

use Illuminate\Database\Migrations\Migration;

class CreateSetsPostsTable extends Migration
{
    /*
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("sets_posts", function ($table) {
            $table->integer('post_id')->default(0)->index();
            $table->integer('set_id')->default(0)->index();
        });

    }

    /*
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sets_posts');
    }
}
