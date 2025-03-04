<?php

namespace App\Http\Controllers;

use App\Area;
use App\BachelorHouse;
use App\Booking;
use App\House;
use App\SubletHouse;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $houses = House::where('status', 1)->latest()->paginate(3);
        $bachelorHouses = BachelorHouse::where('status', 1)->latest()->paginate(3);
        $subletHouses = SubletHouse::where('status', 1)->latest()->paginate(3);
        $areas = Area::all();
        return view('welcome', compact('houses', 'areas', 'bachelorHouses', 'subletHouses'));
    }

    public function highToLow()
    {
        $houses = House::where('status', 1)->orderBy('rent', 'DESC')->paginate(6);
        $areas = Area::all();
        return view('welcome', compact('houses', 'areas'));
    }

    public function lowToHigh()
    {
        $houses = House::where('status', 1)->orderBy('rent', 'ASC')->paginate(6);
        $areas = Area::all();
        return view('welcome', compact('houses', 'areas'));
    }

    public function details($id){
        $house = House::findOrFail($id);
        return view('houseDetails', compact('house'));
    }

    public function bachelorHouseDetails($id){
        $house = BachelorHouse::findOrFail($id);
        return view('bachelorHouseDetails', compact('house'));
    }

    public function subletHouseDetails($id)
    {
        $house = SubletHouse::findOrFail($id);
        return view('subletHouseDetails', compact('house'));
    }


    public function allHouses(){
        $houses = House::latest()->where('status', 1)->paginate(12);
        $type = 1;
        return view('allHouses', compact('houses', 'type'));
    }
    public function allBachelorHouses(){
        $houses = BachelorHouse::latest()->where('status', 1)->paginate(12);
        $type = 2;
        return view('allHouses', compact('houses', 'type'));
    }

    public function allSubletHouses() {
        $houses = SubletHouse::latest()->where('status', 1)->paginate(12);
        $type = 3;
        return view('allHouses', compact('houses', 'type'));
    }


    public function areaWiseShow($id){
        $area = Area::findOrFail($id);
        $houses = House::where('area_id', $id)->get();
        return view('areaWiseShow', compact('houses', 'area'));
    }

    public function search(Request $request){

        $room = $request->room;
        $house_category = $request->house_category;

        if (!in_array($house_category, [1, 2, 3])) {
            session()->flash('search', 'Please Select House Category First');
            return redirect()->back();
        }

        $rent = $request->rent;
        $address = $request->address;


        if( $room == null && $rent == null && $address == null){
            session()->flash('search', 'Your have to fill up minimum one field for search');
            return redirect()->back();
        }

        if ($house_category == 1) {
            $houses = House::query();
        } elseif ($house_category == 2) {
            $houses = BachelorHouse::query();
        } elseif ($house_category == 3) {
            $houses = SubletHouse::query();
        }

        if ($rent && $house_category == 2) {
            $houses = $houses->where('rent_per_room', 'LIKE', $rent)
                ->orWhere('rent_per_seat', 'LIKE', $rent);
        } elseif ($rent) {
            $houses = $houses->where('rent', 'LIKE', $rent);
        }

        $houses = $houses->whereHas('area', function ($query) use ($address) {
                return $query->where('name', 'LIKE', "%$address%");
            })
            ->where('number_of_room', 'LIKE',  $room)
            // ->where('address', 'LIKE', "%$address%")
            ->get();
        return view('search', compact('houses'));
    }

    public function searchByRange(Request $request){
        $digit1 =  $request->digit1;
        $digit2 =  $request->digit2;
        if($digit1 > $digit2){
            $temp = $digit1;
            $digit1 =  $digit2;
            $digit2 = $temp;
        }
        $houses = House::whereBetween('rent', [$digit1, $digit2])
                        ->orderBy('rent', 'ASC')->get();
        return view('searchByRange', compact('houses'));
    }


    public function booking($house){

        // if(Auth::user()->role_id == 1 || Auth::user()->role_id == 2){
        //     session()->flash('danger', 'Sorry admin and landlord are not able to book any houses. Please login with renter account');
        //     return redirect()->back();
        // }


        $house = House::findOrFail($house);
        $landlord = User::where('id', $house->user_id)->first();

        if(Booking::where('house_type', 1)->where('address', $house->address)->where('booking_status', "booked")->count() > 0){
            session()->flash('danger', 'This house has already been booked!');
            return redirect()->back();
        }



        if(Booking::where('house_type', 1)->where('house_id', $house->id)->where('renter_id', Auth::id())->where('booking_status', "requested")->count() > 0){
            session()->flash('danger', 'Your have already sent booking request of this home');
            return redirect()->back();
        }





        //find current date month year
        // $now = Carbon::now();
        // $now = $now->format('F d, Y');


        $booking = new Booking();
        $booking->address = $house->address;
        $booking->rent = $house->rent;
        $booking->landlord_id = $landlord->id;
        $booking->renter_id = Auth::id();
        $booking->house_type = 1;
        $booking->house_id = $house->id;
        $booking->save();


        session()->flash('success', 'House Booking Request Send Successfully');
        return redirect()->back();


    }


    public function bachelorBooking($house)
    {
        $house = BachelorHouse::findOrFail($house);
        $landlord = User::where('id', $house->user_id)->first();

//        if(Booking::where('address', $house->address)->where('booking_status', "booked")->count() > 0){
//            session()->flash('danger', 'This house has already been booked!');
//            return redirect()->back();
//        }



        if(Booking::where('house_type', 2)->where('house_id', $house->id)->where('renter_id', Auth::id())->where('booking_status', "requested")->count() > 0){
            session()->flash('danger', 'Your have already sent booking request of this home');
            return redirect()->back();
        }





        //find current date month year
        // $now = Carbon::now();
        // $now = $now->format('F d, Y');

        $booking_for = \request()->get('booking-for');
        if ($booking_for == 'seat') $rent = $house->rent_per_seat;
        else $rent = $house->rent_per_room;

        $booking = new Booking();
        $booking->address = $house->address;
        $booking->rent = $rent;
        $booking->landlord_id = $landlord->id;
        $booking->renter_id = Auth::id();
        $booking->house_type = 2;
        $booking->house_id = $house->id;
        $booking->booking_for = $booking_for;
        $booking->save();


        session()->flash('success', 'Bachelor House Booking Request Send Successfully');
        return redirect()->back();
    }

    public function subletBooking($house)
    {
        $house = SubletHouse::findOrFail($house);
        $landlord = User::where('id', $house->user_id)->first();

//        if(Booking::where('address', $house->address)->where('booking_status', "booked")->count() > 0){
//            session()->flash('danger', 'This house has already been booked!');
//            return redirect()->back();
//        }



        if(Booking::where('house_type', 3)->where('house_id', $house->id)->where('renter_id', Auth::id())->where('booking_status', "requested")->count() > 0){
            session()->flash('danger', 'Your have already sent booking request of this home');
            return redirect()->back();
        }





        //find current date month year
        // $now = Carbon::now();
        // $now = $now->format('F d, Y');

        $booking_for = \request()->get('booking-for');

        $booking = new Booking();
        $booking->address = $house->address;
        $booking->rent = $house->rent;
        $booking->landlord_id = $landlord->id;
        $booking->renter_id = Auth::id();
        $booking->house_type = 3;
        $booking->house_id = $house->id;
        $booking->booking_for = $booking_for;
        $booking->save();


        session()->flash('success', 'Sublet House Booking Request Send Successfully');
        return redirect()->back();
    }
}
