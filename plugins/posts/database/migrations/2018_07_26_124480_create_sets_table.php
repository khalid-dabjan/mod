<?php

use Illuminate\Database\Migrations\Migration;

class CreateSetsTable extends Migration
{
    /*
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("sets", function ($table) {
            $table->increments('id');
            $table->string('title')->index();
            $table->string('slug')->unique();
            $table->string('excerpt')->nullable()->index();
            $table->text('content')->nullable();
            $table->integer('image_id')->default(0)->index();
            $table->integer('front_page')->default(0)->index();
            $table->integer('user_id')->default(0)->index();
            $table->string("lang")->nullable()->index();
            $table->timestamp('created_at')->nullable()->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamp('updated_at')->nullable()->index();
        });

    }

    /*
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sets');
    }
}
