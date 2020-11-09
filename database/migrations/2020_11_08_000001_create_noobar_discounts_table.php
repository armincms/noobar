<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Armincms\Sofre\Helper;

class CreateNoobarDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('noobar_discounts', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->string('title')->nullable();  
            $table->enum('meal', array_keys(Helper::meals()))->nullable();
            $table->boolean('manual')->default(0);  
            $table->boolean('active')->default(0);  
            $table->integer('count')->default(5);  
            $table->json('items')->nullable();  
            $table->string('filter')->default('latest');   
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('noobar_discounts');
    }
}
