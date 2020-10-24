<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNoobarSlidesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('noobar_slides', function (Blueprint $table) {
            $table->increments('id');
            $table->string('caption')->nullable(); 
            $table->string('title')->nullable(); 
            $table->string('url')->nullable();  
            $table->integer('order')->default(time());  
            $table->integer('active')->default(0);  
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
        Schema::drop('noobar_slides');
    }
}
