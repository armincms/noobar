<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Armincms\Sofre\Helper;

class CreateNoobarAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('noobar_addresses', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->string('name')->nullable();   
            $table->unsignedBigInteger('zone_id')->nullable();  
            $table->json('config');  
            $table->coordinates();  
            $table->auth();       
            $table->timestamps();   

            $table
                ->foreign('zone_id')->references('id')
                ->on('locations')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('noobar_addresses');
    }
}
