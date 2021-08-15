<?php namespace App\Http\Controllers\Landlord;

use App\Area;
use App\BachelorHouse;
use App\House;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class BachelorHouseController extends Controller
{
    public function index()
    {
        $houses = BachelorHouse::latest()->where('user_id', Auth::id())->paginate(8);
        $housecount = BachelorHouse::all()->count();
        return view('landlord.bachelor.index', compact('houses', 'housecount'));
    }

    public function create()
    {
        if(Area::count() < 1){
            session()->flash('danger','To add new house you have to add area first');
            return redirect()->back();
        }

        $areas = Area::all();
        return view('landlord.bachelor.create', compact('areas'));
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'address' => 'required',
            'area_id' => 'required',
            'house_for' => 'required',
            'is_for_male' => 'required',
            'has_ac' => 'required',
            'number_of_room' => 'required|numeric|integer',
            'number_of_seat' => 'required|numeric|integer',
            'rent_per_room' => 'required|numeric',
            'rent_per_seat' => 'required|numeric',
            'featured_image' => 'required|mimes:jpeg,png,jpg',
            'images.*' => 'required|mimes:jpeg,png,jpg',
        ]);


        //handle featured image
        $featured_image = $request->file('featured_image');
        if($featured_image)
        {
            // Make Unique Name for Image
            $currentDate = Carbon::now()->toDateString();
            $featured_image_name = $currentDate.'-'.uniqid().'.'.$featured_image->getClientOriginalExtension();


            // Check Dir is exists

            if (!Storage::disk('public')->exists('featured_house')) {
                Storage::disk('public')->makeDirectory('featured_house');
            }


            // Resize Image  and upload
            $cropImage = Image::make($featured_image)->resize(400,300)->stream();
            Storage::disk('public')->put('featured_house/'.$featured_image_name,$cropImage);

        }


        $data = [];
        if($request->hasfile('images'))
        {
            foreach($request->file('images') as $file)
            {
                $name = time() . '-'. uniqid() . '.'.$file->extension();
                $file->move(public_path().'/images/', $name);
                $data[] = $name;
            }
        }

        $house = new BachelorHouse();
        $house->address = $request->address;
        $house->user_id = Auth::id();
        $house->contact = Auth::user()->contact;
        $house->area_id = $request->area_id;
        $house->number_of_room = $request->number_of_room;
        $house->number_of_seat = $request->number_of_seat;
        $house->house_for = $request->house_for;
        $house->is_for_male = $request->is_for_male;
        $house->has_ac = $request->has_ac;
        $house->rent_per_room = $request->rent_per_room;
        $house->rent_per_seat = $request->rent_per_seat;
        $house->images = isset($data) ? json_encode($data) : null;
        $house->featured_image = $featured_image_name;
        $house->save();
        return redirect(route('landlord.bachelor-house.index'))->with('success', 'Bachelor House Added successfully');
    }

    public function show(BachelorHouse $bachelorHouse)
    {
        return view('landlord.bachelor.show')->with('house', $bachelorHouse);
    }

    public function edit(BachelorHouse $bachelorHouse)
    {
        $house = $bachelorHouse;
        $areas = Area::all();
        return view('landlord.bachelor.edit', compact('areas', 'house'));
    }

    public function update(Request $request, BachelorHouse $bachelorHouse)
    {
        $this->validate($request,[
            'address' => 'required',
            'area_id' => 'required',
            'house_for' => 'required',
            'is_for_male' => 'required',
            'has_ac' => 'required',
            'number_of_room' => 'required|numeric|integer',
            'number_of_seat' => 'required|numeric|integer',
            'rent_per_room' => 'required|numeric',
            'rent_per_seat' => 'required|numeric',
            'featured_image' => 'mimes:jpeg,png,jpg',
            'images.*' => 'mimes:jpeg,png,jpg',
        ]);

        $house = $bachelorHouse;

        //handle featured image

        $featured_image = $request->file('featured_image');

        if($featured_image)
        {

            // Make Unique Name for Image
            $currentDate = Carbon::now()->toDateString();
            $featured_image_name =$currentDate.'-'.uniqid().'.'.$featured_image->getClientOriginalExtension();


            // Check Dir is exists
            if (!Storage::disk('public')->exists('featured_house')) {
                Storage::disk('public')->makeDirectory('featured_house');
            }


            // Resize Image and upload
            $cropImage = Image::make($featured_image)->resize(400,300)->stream();
            Storage::disk('public')->put('featured_house/'.$featured_image_name,$cropImage);

            if(Storage::disk('public')->exists('featured_house/'.$house->featured_image)){
                Storage::disk('public')->delete('featured_house/'.$house->featured_image);
            }
            $house->featured_image = $featured_image_name;
        }


        //handle multiple images update
        if($request->hasfile('images'))
        {

            foreach(json_decode($house->images) as $picture){
                @unlink("images/". $picture);
            }

            foreach($request->file('images') as $file)
            {
                $name = time() . '-'. uniqid() . '.'.$file->extension();
                $file->move(public_path().'/images/', $name);
                $data[] = $name;
            }

            $house->images=json_encode($data);
        }

        $house->address = $request->address;
        $house->user_id = Auth::id();
        $house->contact = Auth::user()->contact;
        $house->area_id = $request->area_id;
        $house->number_of_room = $request->number_of_room;
        $house->number_of_seat = $request->number_of_seat;
        $house->house_for = $request->house_for;
        $house->is_for_male = $request->is_for_male;
        $house->has_ac = $request->has_ac;
        $house->rent_per_room = $request->rent_per_room;
        $house->rent_per_seat = $request->rent_per_seat;
        $house->save();
        return redirect(route('landlord.bachelor-house.index'))->with('success', 'Bachelor House Updated successfully');



    }

    public function switch($id)
    {
        $house = BachelorHouse::find($id);
        if($house->status == 1){
            $house->status = 0;
        }else{
            $house->status = 1;
        }
        $house->save();

        session()->flash('success', 'Bachelor House Status Changed Successfully');
        return redirect()->back();
    }
}
