<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBachelorHousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bachelor_houses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('address');
            $table->integer('area_id');
            $table->integer('user_id');
            $table->string('contact');
            $table->integer('number_of_room')->nullable();
            $table->integer('number_of_available_room')->nullable();
            $table->integer('number_of_seat')->nullable();
            $table->integer('number_of_available_seat')->nullable();
            $table->string('house_for')
                ->nullable()
                ->comment('Job Holder, Student');
            $table->boolean('is_for_male')->default(true);
            $table->boolean('has_ac')->default(false);
            $table->integer('rent_per_room');
            $table->integer('rent_per_seat');
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
        Schema::dropIfExists('bachelor_houses');
    }
}
