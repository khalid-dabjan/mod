<?php

use Illuminate\Database\Migrations\Migration;

class CreateContestsTable extends Migration
{
    /*
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("contests", function ($table) {
            $table->increments('id');
            $table->string('title')->index();
            $table->string('slug')->unique();
            $table->string('reward_code')->nullable()->index();
            $table->integer('reward')->nullable()->index();
            $table->string('hash_tag')->nullable()->index();
            $table->text('content')->nullable();
            $table->string("lang")->nullable()->index();
            $table->integer('status')->default(0)->nullable();
            $table->integer('image_id')->default(0)->index();
            $table->integer('user_id')->default(0)->index();
            $table->timestamp('expired_at')->nullable()->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamp('created_at')->nullable()->index();
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
        Schema::drop('contests');
    }
}
