<?php

use Illuminate\Database\Migrations\Migration;

class CreateCategoriesPostsOrderTable extends Migration
{
    /*
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts_categories_orders', function ($table) {
            $table->integer("post_id")->default(0)->index();
            $table->integer("category_id")->default(0)->index();
            $table->integer("order")->default(0)->index();
        });

    }

    /*
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('posts_categories_orders');
    }
}
