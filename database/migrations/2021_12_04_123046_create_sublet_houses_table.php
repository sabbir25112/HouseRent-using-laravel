<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubletHousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sublet_houses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('address');
            $table->integer('area_id');
            $table->integer('user_id');
            $table->string('contact');
            $table->integer('number_of_room');
            $table->integer('number_of_toilet');
            $table->boolean('is_for_male')->default(true);
            $table->boolean('is_for_married')->default(true);
            $table->integer('rent');
            $table->string('featured_image');
            $table->text('images');
            $table->string('status')->default(1);  //1 means available
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
        Schema::dropIfExists('sublet_houses');
    }
}
