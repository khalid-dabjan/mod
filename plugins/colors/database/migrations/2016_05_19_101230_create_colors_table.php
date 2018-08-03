<?php

use Illuminate\Database\Migrations\Migration;

class CreateColorsTable extends Migration
{
    /*
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("colors", function ($table) {
            $table->increments('id');
            $table->string('name')->unique()->index();
            $table->string('value')->unique();
            $table->integer('add_to_filter')->default(0)->index();
            $table->integer('user_id')->default(0)->index();
            $table->string('lang')->index();
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
        Schema::drop('colors');
    }
}
