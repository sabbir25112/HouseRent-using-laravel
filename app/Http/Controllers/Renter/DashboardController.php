<?php

namespace App\Http\Controllers\Renter;

use App\BachelorHouse;
use App\Booking;
use App\Area;
use App\House;
use App\Http\Controllers\Controller;
use App\Review;
use App\SubletHouse;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(){
        $houses = House::latest()->get();
        $areas = Area::latest()->get();
        $renters = User::where('role_id', 3)->get();
        $landlords = User::where('role_id', 2)->get();
        return view('renter.dashboard', compact('renters', 'houses', 'areas', 'landlords'));
    }

    public function areas(){
        $areas = Area::latest()->paginate(8);
        $areacount = Area::all()->count();
        return view('renter.area.index', compact('areas', 'areacount'));
    }



    public function allHouses(){
        $houses = House::latest()->paginate(8);
        $housecount = House::all()->count();
        return view('renter.house.index', compact('houses', 'housecount'));
    }

    public function allSubletHouses(){
        $houses = SubletHouse::latest()->paginate(8);
        $housecount = SubletHouse::all()->count();
        return view('renter.sublet.index', compact('houses', 'housecount'));
    }

    public function allBachelorHouses(){
        $houses = BachelorHouse::latest()->paginate(8);
        $housecount = BachelorHouse::all()->count();
        return view('renter.bachelor.index', compact('houses', 'housecount'));
    }

    public function housesDetails($id){
        $house = House::find($id);
        $stayOnceUponATime = Booking::
            where('renter_id', Auth::id())
            ->where('leave', '!=' ,"null")
            ->where('leave', '!=', "Currently Staying")
            ->where('address', $house->address)
            ->first();
            //dd($stayOnceUponATime);
        $alreadyReviewed = Review::where('house_id', $house->id)
                            ->where('user_id', Auth::id())
                            ->first();

        return view('renter.house.show', compact('house', 'stayOnceUponATime', 'alreadyReviewed'));
    }

    public function subletHousesDetails($id)
    {
        $house = SubletHouse::find($id);
        $stayOnceUponATime = Booking::where('renter_id', Auth::id())
            ->where('leave', '!=' ,"null")
            ->where('leave', '!=', "Currently Staying")
            ->where('address', $house->address)
            ->first();
        //dd($stayOnceUponATime);
        $alreadyReviewed = Review::where('house_id', $house->id)
            ->where('user_id', Auth::id())
            ->first();

        return view('renter.sublet.show', compact('house', 'stayOnceUponATime', 'alreadyReviewed'));
    }

    public function bachelorHousesDetails($id)
    {
        $house = BachelorHouse::find($id);
        $stayOnceUponATime = Booking::where('renter_id', Auth::id())
            ->where('leave', '!=' ,"null")
            ->where('leave', '!=', "Currently Staying")
            ->where('address', $house->address)
            ->first();
        //dd($stayOnceUponATime);
        $alreadyReviewed = Review::where('house_id', $house->id)
            ->where('user_id', Auth::id())
            ->first();

        return view('renter.bachelor.show', compact('house', 'stayOnceUponATime', 'alreadyReviewed'));
    }

    public function review(Request $request){
        $this->validate($request, [
            'opinion' => 'required'
        ]);
        $review = new Review();
        $review->house_id = $request->house_id;
        $review->user_id = Auth::id();
        $review->opinion = $request->opinion;
        $review->save();
        session()->flash('success', 'Review Added Successfully');
        return redirect()->back();
    }

    public function reviewEdit($id){
        $review = Review::find($id);
        return view('renter.review.edit', compact('review'));
    }

    public function reviewUpdate(Request $request,$id){
        $this->validate($request, [
            'opinion' => 'required|min:10'
        ]);
        $review = Review::find($id);
        $review->opinion = $request->opinion;
        $review->save();
        return redirect()->route('renter.houses.details', $review->house_id)->with('success', 'Review Updated Successfully');
    }




    public function bookingHistory(){
        $books = Booking::where('renter_id', Auth::id())->where('booking_status', '!=' , "requested")->get();
        return view('renter.booking.history', compact('books'));
    }

    public function bookingPending(){
        $books = Booking::where('renter_id', Auth::id())->where('booking_status', "requested")->get();
        return view('renter.booking.pending', compact('books'));
    }

    public function cancelBookingRequest($id){
        Booking::find($id)->delete();

        session()->flash('success', 'Booking Request Removed Successfully');
        return redirect()->back();
    }
}
