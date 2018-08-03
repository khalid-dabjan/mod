<?php

use Illuminate\Database\Migrations\Migration;

class CreateCollectionsPostsTable extends Migration
{
    /*
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("collections_posts", function ($table) {
            $table->integer('post_id')->default(0)->index();
            $table->integer('collection_id')->default(0)->index();
        });

    }

    /*
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('collections_posts');
    }
}
