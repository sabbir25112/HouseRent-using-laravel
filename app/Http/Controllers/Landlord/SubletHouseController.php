<?php namespace App\Http\Controllers\Landlord;

use App\Area;
use App\Http\Controllers\Controller;
use App\SubletHouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class SubletHouseController extends Controller
{
    public function index()
    {
        $houses = SubletHouse::latest()->where('user_id', Auth::id())->paginate(8);
        $housecount = SubletHouse::where('user_id', Auth::id())->count();
        return view('landlord.sublet.index', compact('houses', 'housecount'));
    }

    public function create()
    {
        if(Area::count() < 1){
            session()->flash('danger','To add new house you have to add area first');
            return redirect()->back();
        }

        $areas = Area::all();
        return view('landlord.sublet.create', compact('areas'));
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'address' => 'required',
            'area_id' => 'required',
            'number_of_room' => 'required|numeric|integer',
            'number_of_toilet' => 'required|numeric|integer',
            'is_for_married' => 'required',
            'is_for_male' => 'required',
            'rent' => 'required|numeric',
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

        $house = new SubletHouse();
        $house->address = $request->address;
        $house->user_id = Auth::id();
        $house->contact = Auth::user()->contact;
        $house->area_id = $request->area_id;
        $house->number_of_toilet = $request->number_of_toilet;
        $house->number_of_room = $request->number_of_room;
        $house->is_for_married = $request->is_for_married;
        $house->is_for_male = $request->is_for_male;
        $house->rent = $request->rent;
        $house->images = isset($data) ? json_encode($data) : null;
        $house->featured_image = $featured_image_name;
        $house->save();

        return redirect(route('landlord.sublet-house.index'))->with('success', 'Sublet House Added successfully');
    }

    public function switch($id)
    {
        $house = SubletHouse::find($id);
        if($house->status == 1){
            $house->status = 0;
        }else{
            $house->status = 1;
        }
        $house->save();

        session()->flash('success', 'Sublet House Status Changed Successfully');
        return redirect()->back();
    }

    public function show(SubletHouse $subletHouse)
    {
        return view('landlord.sublet.show')->with('house', $subletHouse);
    }

    public function edit(SubletHouse $subletHouse)
    {
        $house = $subletHouse;
        $areas = Area::all();
        return view('landlord.sublet.edit', compact('areas', 'house'));
    }

    public function update(Request $request, $house)
    {
        $this->validate($request,[
            'address' => 'required',
            'area_id' => 'required',
            'number_of_room' => 'required|numeric|integer',
            'number_of_toilet' => 'required|numeric|integer',
            'is_for_married' => 'required',
            'is_for_male' => 'required',
            'rent' => 'required|numeric',
            'featured_image' => 'mimes:jpeg,png,jpg',
            'images.*' => 'mimes:jpeg,png,jpg',
        ]);

        $house = SubletHouse::find($house);

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
        $house->area_id = $request->area_id;
        $house->number_of_toilet = $request->number_of_toilet;
        $house->number_of_room = $request->number_of_room;
        $house->is_for_married = $request->is_for_married;
        $house->is_for_male = $request->is_for_male;
        $house->rent = $request->rent;
        $house->save();

        return redirect(route('landlord.sublet-house.index'))->with('success', 'Sublet House Updated successfully');
    }

    public function destroy($house)
    {
        $house = SubletHouse::findOrFail($house);
        //delete multiple added images
        foreach(json_decode($house->images) as $picture){
            @unlink("images/". $picture);
        }

        //delete old featured image
        if(Storage::disk('public')->exists('featured_house/'.$house->featured_image)){
            Storage::disk('public')->delete('featured_house/'.$house->featured_image);
        }

        $house->delete();
        return redirect(route('landlord.sublet-house.index'))->with('success', 'Sublet House Removed Successfully');
    }
}
