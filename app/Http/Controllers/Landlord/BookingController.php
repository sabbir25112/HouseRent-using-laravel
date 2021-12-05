<?php

namespace App\Http\Controllers\Landlord;

use App\BachelorHouse;
use App\Booking;
use App\House;
use App\Http\Controllers\Controller;
use App\SubletHouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function bookingRequestListForLandlord(){
        $books = Booking::where('landlord_id', Auth::id())->where('booking_status', 'requested')->get();
        return view('landlord.booking.requested', compact('books'));
    }

    public function bookingRequestAccept($id){
        $book = Booking::findOrFail($id);


        if($book->house_type == 1 && Booking::where('address', $book->address)->where('booking_status', "booked")->count() > 0){
            session()->flash('danger', 'This house is already booked. Please cancel his/her booking request');
            return redirect()->back();
        }

        if ($book->house_type == 1)
        {
            $house = House::where('id', $book->house_id)->first();
            $house->status = 0;

            $renterContact = $book->renter->contact;
            $renterName = $book->renter->name;
            $houseAddress = $book->address;

            $house->save();
        } elseif ($book->house_type == 2) {
            $house = BachelorHouse::where('id', $book->house_id)->first();
            if ($book->booking_for == 'seat') {
                $house->number_of_available_seat = $house->number_of_available_seat - 1;
            } else {
                $house->number_of_available_room = $house->number_of_available_room - 1;
            }
            $house->save();
        } elseif ($book->house_type == 2) {
            $house = SubletHouse::where('id', $book->house_id)->first();
            $house->status = 0;

            $renterContact = $book->renter->contact;
            $renterName = $book->renter->name;
            $houseAddress = $book->address;

            $house->save();
        }

        $book->leave = "Currently Staying";
        $book->booking_status = "booked";
        $book->save();

        session()->flash('success', 'Booking Accepted Successfully');
        return redirect()->back();
    }

    public function bookingRequestReject($id){

        $book = Booking::find($id);
        $book->delete();

        session()->flash('success', 'Booking Rejected Successfully');
        return redirect()->back();

    }


    public function bookingHistory(){
        $books = Booking::where('landlord_id', Auth::id())->where('booking_status', '!=', 'requested')->where('booking_status', '!=', 'booked')->get();
        return view('landlord.booking.history', compact('books'));
    }

    public function currentlyStaying(){
        $books = Booking::where('landlord_id', Auth::id())->where('booking_status', '=', 'booked')->get();
        return view('landlord.booking.currentRenter', compact('books'));
    }

    public function leaveRenter($id){
        $book = Booking::findOrFail($id);

        $house = House::where('address', $book->address)->first();
        $house->status = 1;
        $house->save();

        $now = Carbon::now();
        $now = $now->format('F d, Y');

        $book->leave = $now;
        $book->booking_status = "";
        $book->save();

        session()->flash('success', 'Renter Leave Successfully');
        return redirect()->back();
    }
}
