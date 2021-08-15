@extends('layouts.backend.app')
@section('title')
    Add House
@endsection
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="card mt-5">
                    <div class="card-header">
                        <h3 class="card-title float-left"><strong>Add New Bachelor House</strong></h3>

                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @include('partial.errors')

                        <form action="{{ route('landlord.bachelor-house.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="address">Address: </label>
                                <input type="text" class="form-control" placeholder="Enter address" id="address"
                                       name="address" value="{{ old('address') }}">
                            </div>

                            <div class="form-group">
                                <label for="area">Area </label>
                                <select name="area_id" class="form-control" id="area_id">
                                    <option value="">select an area</option>
                                    @foreach ($areas as $area)
                                        <option
                                            value="{{ $area->id }}" {{ old('area_id') == $area->id ? 'selected' : '' }} >{{ $area->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="number_of_room">Number of rooms: </label>
                                <input type="text" class="form-control" placeholder="number_of_room" id="number_of_room"
                                       name="number_of_room" value="{{ old('number_of_room') }}">
                            </div>

                            <div class="form-group">
                                <label for="number_of_room">Number of seats: </label>
                                <input type="text" class="form-control" placeholder="number_of_seat" id="number_of_seat"
                                       name="number_of_seat" value="{{ old('number_of_seat') }}">
                            </div>

                            <div class="form-group">
                                <label for="house_for">Renter Preference </label>
                                <select name="house_for" class="form-control" id="house_for">
                                    <option value="">select preference</option>
                                    <option value="jobholder" {{ old('house_for') == 'jobholder' ? 'selected' : '' }} >Job Holder</option>
                                    <option value="student" {{ old('house_for') == 'student' ? 'selected' : '' }} >Student</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="is_for_male">Gender Preference </label>
                                <select name="is_for_male" class="form-control" id="is_for_male">
                                    <option value="">select gender</option>
                                    <option value="1" {{ old('is_for_male') == '1' ? 'selected' : '' }} >Male</option>
                                    <option value="0" {{ old('is_for_male') == '0' ? 'selected' : '' }} >Female</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="has_ac">Air Conditioning Facility </label>
                                <select name="has_ac" class="form-control" id="has_ac">
                                    <option value="">select answer</option>
                                    <option value="1" {{ old('has_ac') == '1' ? 'selected' : '' }} >Yes</option>
                                    <option value="0" {{ old('has_ac') == '0' ? 'selected' : '' }} >No</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="rent_per_room">Rent Per Room: </label>
                                <input type="text" class="form-control" placeholder="rent_per_room" id="rent_per_room" name="rent_per_room"
                                       value="{{ old('rent_per_room') }}">
                            </div>

                            <div class="form-group">
                                <label for="rent_per_seat">Rent Per Seat: </label>
                                <input type="text" class="form-control" placeholder="rent_per_seat" id="rent_per_seat" name="rent_per_seat"
                                       value="{{ old('rent_per_seat') }}">
                            </div>

                            <div class="form-group">
                                <label for="featured_image">Featured Image</label>
                                <input type="file" name="featured_image" class="form-control" id="featured_image">
                            </div>

                            <div class="form-group">
                                <label for="images">House Images</label>
                                <input type="file" name="images[]" class="form-control" multiple>
                            </div>


                            <div class="form-group">
                                <button type="submit" class="btn btn-success">Add</button>
                                <a href="{{ URL::previous() }}" class="btn btn-danger wave-effect">Back</a>
                            </div>
                        </form>


                    </div>

                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div><!-- /.container -->
@endsection
