<?php namespace BeEasy\Slider\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateSlidesTable extends Migration
{

    public function up()
    {
        Schema::create('beeasy_slider_slides', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('position')->unsigned()->nullable();
            $table->string('name')->nullable();
            $table->string('href')->nullable();
            $table->string('type', 8)->nullable();
            $table->string('youtube', 11)->nullable();
            $table->boolean('is_new_window')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('beeasy_slider_slides');
    }

}
