@extends('layouts.frontend.app')

@section('title')
    House Rent - Homepage
@endsection

@section('content')
    <div id="search">
        <div class="container-fluid">
            <div class="row justify-content-center py-4">
                <h2 class="text-center"><strong>Search a house of your choice</strong></h2>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-9">
                    <form action="{{ route('search') }}" method="GET">
                        @csrf
                        <div class="row justify-content-center">
                            @if(session('search'))
                                <div class="alert alert-danger mt-3" id="alert" roles="alert">
                                    {{ session('search') }}
                                </div>
                            @endif
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <input type="text" name="address" placeholder="search an area" class="form-control">
                            </div>
                            <div class="form-group col-md-2">
                                {{-- <input type="text" name="room" placeholder="room" class="form-control"> --}}
                                <select name="room" class="form-control">
                                    <option value="">rooms</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                </select>
                            </div>
                            <div class="form-group col-md-2">
                                {{-- <input type="text" name="bathroom" placeholder="bathroom" class="form-control"> --}}
                                <select name="house_category" class="form-control">
                                    <option value="">House Category</option>
                                    <option value="1">Family</option>
                                    <option value="2">Bachelor</option>
                                    <option value="3">Sublet</option>
                                </select>
                            </div>
                            <div class="form-group col-md-2">
                                <input type="text" name="rent" placeholder="rent" class="form-control">
                            </div>
                            <div class="form-group col-md-2">
                                <button type="submit" class="btn btn-success">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div id="content">
        <div class="container">
            <div class="row justify-content-center py-5">
                <h1><strong>Available Houses</strong></h1>
                <hr>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        @forelse ($houses as $house)
                            <div class="col-md-4">
                                <div class="card m-3 house-card">
                                    <div class="card-header">
                                        <img src="{{ asset('storage/featured_house/'. $house->featured_image) }}"
                                             width="100%" class="img-fluid" alt="Card image">
                                    </div>
                                    <div class="card-body">
                                        <p><h4><strong><i class="fas fa-map-marker-alt"> {{ $house->area->name }}</i> </strong></h4></p>

                                        <p class="grey"><a class="address"
                                                           href="{{ route('house.details', $house->id) }}"><i
                                                    class="fas fa-warehouse"> {{ $house->address }}</i></a></p>
                                        <hr>
                                        <p class="grey"><i class="fas fa-bed"></i> {{ $house->number_of_room }} Bedrooms
                                            <i class="fas fa-bath float-right"> {{ $house->number_of_toilet }}
                                                Bathrooms</i></p>
                                        <p class="grey"><h4>৳ {{ $house->rent }} BDT</i></h4> </p>
                                    </div>
                                    <div class="card-footer">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <a href="{{ route('house.details', $house->id) }}" class="btn btn-info">Details</a>
                                            </div>
                                            <div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <h2 class="m-auto py-2 text-white bg-dark p-3">House Not Available right now</h2>
                        @endforelse
                    </div>

                    <div class="panel-heading my-4" style="display:flex; justify-content:center;align-items:center;">
                        <a href="{{ route('house.all') }}" class="btn btn-dark">See All Houses</a>
                    </div>
                </div>
            </div>

        </div>
        <div class="container">
            <div class="row justify-content-center py-5">
                <h1><strong>Available Bachelor Houses</strong></h1>
                <hr>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        @forelse ($bachelorHouses as $house)
                            <div class="col-md-4">
                                <div class="card m-3 house-card">
                                    <div class="card-header">
                                        <img src="{{ asset('storage/featured_house/'. $house->featured_image) }}"
                                             width="100%" class="img-fluid" alt="Card image">
                                    </div>
                                    <div class="card-body">
                                        <p><h4><strong><i class="fas fa-map-marker-alt"> {{ $house->area->name }}</i> </strong></h4></p>

                                        <p class="grey"><a class="address"
                                                           href="{{ route('bachelor-house.details', $house->id) }}"><i
                                                    class="fas fa-warehouse"> {{ $house->address }}</i></a></p>
                                        <hr>
                                        <p class="grey"><i class="fas fa-bed"></i> {{ $house->number_of_available_room }} Bedrooms
                                            <i class="fas fa-bed float-right"> {{ $house->number_of_available_seat }}
                                                Seats</i></p>
                                        <p class="grey"><h4>৳ {{ $house->rent_per_room }} / {{ $house->rent_per_seat }} BDT</i></h4> </p>
                                    </div>
                                    <div class="card-footer">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <a href="{{ route('bachelor-house.details', $house->id) }}" class="btn btn-info">Details</a>
                                            </div>
                                            <div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <h2 class="m-auto py-2 text-white bg-dark p-3">House Not Available right now</h2>
                        @endforelse
                    </div>

                    <div class="panel-heading my-4" style="display:flex; justify-content:center;align-items:center;">
                        <a href="{{ route('bachelor-house.all') }}" class="btn btn-dark">See All Houses</a>
                    </div>
                </div>
            </div>

        </div>
        <div class="container">
            <div class="row justify-content-center py-5">
                <h1><strong>Available Sublet Houses</strong></h1>
                <hr>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        @forelse ($subletHouses as $house)
                            <div class="col-md-4">
                                <div class="card m-3 house-card">
                                    <div class="card-header">
                                        <img src="{{ asset('storage/featured_house/'. $house->featured_image) }}"
                                             width="100%" class="img-fluid" alt="Card image">
                                    </div>
                                    <div class="card-body">
                                        <p><h4><strong><i class="fas fa-map-marker-alt"> {{ $house->area->name }}</i> </strong></h4></p>

                                        <p class="grey"><a class="address"
                                                           href="{{ route('sublet-house.details', $house->id) }}"><i
                                                    class="fas fa-warehouse"> {{ $house->address }}</i></a></p>
                                        <hr>
                                        <p class="grey"><i class="fas fa-bed"></i> {{ $house->number_of_room }} Bedrooms
                                            <i class="fas fa-bath float-right"> {{ $house->number_of_toilet }}
                                                Bathrooms</i></p>
                                        <p class="grey"><h4>৳ {{ $house->rent }} BDT</i></h4> </p>
                                    </div>
                                    <div class="card-footer">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <a href="{{ route('sublet-house.details', $house->id) }}" class="btn btn-info">Details</a>
                                            </div>
                                            <div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <h2 class="m-auto py-2 text-white bg-dark p-3">House Not Available right now</h2>
                        @endforelse
                    </div>

                    <div class="panel-heading my-4" style="display:flex; justify-content:center;align-items:center;">
                        <a href="{{ route('sublet-house.all') }}" class="btn btn-dark">See All Houses</a>
                    </div>
                </div>
            </div>

        </div>
    </div>



    <div class="section-4 bg-dark">
        <div class="container">
            <div class="row">
                <div class="col-md-7">
                    <img src="{{ asset('frontend/img/why.jpg') }}" class="section-4-img img-fluid" width="500px;"
                         height="500px;">
                </div>
                <div class="col-md-5">
                    <h1 class="text-white">Why Choose Us?</h1>

                    <p class="para-1">Lorem ipsum dolor sit amet, consectetur adipisicing elitim id est laborum.dolore
                        magna alsint occaecat cupidatat non
                        proident, sunt in culpa qui officia deserunt mollit anim id est laboro. </p>
                    <a href="#" style="text-decoration: none">Join Us</a>
                </div>
            </div>
        </div>
    </div>



    <section id="our-story">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h1 class="story">Our Story</h1>
                    <p class="pera">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                        quis nostrud exercitation ullamco laboris nisi ut aliquip.</p>

                    <p class="pera">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                        tempor incididunt ut labore et dolore magna aliqua Ut enim.</p>
                </div>
                <div class="col-md-6">
                    <img src="{{ asset('frontend/img/about-us.png') }}" class="img-fluid">
                </div>
            </div>
        </div>
    </section>



@endsection
