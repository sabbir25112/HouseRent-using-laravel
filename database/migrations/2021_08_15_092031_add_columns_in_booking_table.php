<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsInBookingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->integer('house_type')
                ->after('address')
                ->comment('1: house, 2: bachelor house');
            $table->integer('house_id')->after('house_type');
            $table->string('booking_for')
                ->after('house_type')
                ->nullable()
                ->comment('seat, room');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('house_type');
            $table->dropColumn('house_id');
            $table->dropColumn('booking_for');
        });
    }
}
