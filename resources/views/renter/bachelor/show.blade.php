@extends('layouts.backend.app')
@section('title')
    Details - {{ $house->address }}
@endsection
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="card mt-5">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h3><strong>Bachelor House Details</strong></h3>
                            </div>
                            <div>
                                @if (Auth::user()->role_id == 3 && $house->status == 1 && 0)
                                    <button class="btn btn-warning" type="button" onclick="renterBooking({{ $house->id }})">
                                        Apply for booking
                                    </button>

                                    <form id="booking-form-{{ $house->id }}" action="{{ route('bachelor-booking', $house->id) }}" method="POST" style="display: none;">
                                        @csrf

                                    </form>
                                @endif
                                <a class="btn btn-danger" href="{{ route('renter.allBachelorHouses') }}"> Back</a>
                            </div>
                        </div>

                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <th>Address</th>
                                    <td>{{ $house->address }}</td>
                                </tr>
                                <tr>
                                    <th>Area</th>
                                    <td>{{ $house->area->name }}</td>
                                </tr>
                                <tr>
                                    <th>Owner</th>
                                    <td>{{ $house->user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Contact</th>
                                    <td>{{ $house->contact }}</td>
                                </tr>
                                <tr>
                                    <th>Number of rooms</th>
                                    <td>{{ $house->number_of_room }}</td>
                                </tr>

                                <tr>
                                    <th>Number of seats</th>
                                    <td>{{ $house->number_of_seat }}</td>
                                </tr>

                                <tr>
                                    <th>Renter Preference</th>
                                    <td>{{ $house->house_for == 'jobholder' ? "Job Holder" : "Student"  }}</td>
                                </tr>

                                <tr>
                                    <th>Gender Preference</th>
                                    <td>{{ $house->is_for_male == '1' ? "Male" : "Female"  }}</td>
                                </tr>

                                <tr>
                                    <th>Air Conditioning Facility</th>
                                    <td>{{ $house->has_ac == '1' ? "Yes" : "No"  }}</td>
                                </tr>

                                <tr>
                                    <th>Rent Per Room</th>
                                    <td>{{ $house->rent_per_room }}</td>
                                </tr>

                                <tr>
                                    <th>Rent Per Seat</th>
                                    <td>{{ $house->rent_per_seat }}</td>
                                </tr>


                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if ($house->status == 1)
                                            <span class="btn btn-success">Available</span>
                                        @else
                                            <span class="btn btn-danger">Not Available</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="row gallery">
                            @foreach (json_decode($house->images) as $picture)
                                <div class="col-md-3">
                                    <a href="{{ asset('images/'.$picture) }}">
                                        <img  src="{{ asset('images/'.$picture) }}" class="img-fluid m-2" style="height: 150px;width: 100%; ">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div><!-- /.container -->
@endsection


@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.11.1/baguetteBox.min.js"></script>
    <script>
        window.addEventListener('load', function() {
            baguetteBox.run('.gallery', {
                animation: 'fadeIn',
                noScrollbars: true
            });
        });
    </script>
@endsection
@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.11.1/baguetteBox.min.css">
@endsection
