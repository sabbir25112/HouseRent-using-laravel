@extends('layouts.frontend.app')

@section('title','Home')

@section('content')

    <div id="content">
        <div class="container">
            <div class="row justify-content-center py-5">
                @if ($type == 1)
                    <h2 class="text-center"><strong>All Available Houses List</strong></h2>
                @elseif ($type == 3)
                    <h2 class="text-center"><strong>All Available Sublet Houses List</strong></h2>
                @else
                    <h2 class="text-center"><strong>All Available Bachelor Houses List</strong></h2>
                @endif
                <hr>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        @forelse ($houses as $house)
                            <div class="col-md-4">
                                <div class="card m-3">
                                    <div class="card-header">
                                        <img src="{{ asset('storage/featured_house/'. $house->featured_image) }}"
                                             width="100%"
                                             class="img-fluid" alt="Card image">
                                    </div>
                                    <div class="card-body">
                                        <h4>
                                            <strong><i class="fas fa-map-marker-alt"> {{ $house->area->name }}</i>
                                            </strong>
                                        </h4>

                                        <p class="grey">
                                            <a class="address" href="{{ route('house.details', $house->id) }}">
                                                <i class="fas fa-warehouse"> {{ $house->address }}</i>
                                            </a>
                                        </p>
                                        <hr>
                                        <p class="grey">
                                            @if ($type == 1 || $type == 3)
                                                <i class="fas fa-bed"></i> {{ $house->number_of_room }} Bedrooms
                                            @else
                                                <i class="fas fa-bed"></i> {{ $house->number_of_available_room }}
                                                Bedrooms
                                            @endif
                                            @if ($type == 1 || $type == 3)
                                                <i class="fas fa-bath float-right">
                                                    {{ $house->number_of_toilet }} Bathrooms
                                                </i>
                                            @else
                                                <i class="fas fa-bed float-right">
                                                    {{ $house->number_of_available_seat }} Seats
                                                </i>
                                            @endif
                                        </p>
                                        <p class="grey">
                                        <h4>
                                            ৳ {{ $type == 1 || $type == 3 ? $house->rent : $house->rent_per_room . ' / ' . $house->rent_per_seat }}
                                            BDT</i></h4>
                                        </p>
                                    </div>
                                    <div class="card-footer">
                                        <div class="d-flex justify-content-between">
                                            @if ($type == 1)
                                                <div>
                                                    <a href="{{ route('house.details', $house->id) }}"
                                                       class="btn btn-info">Details</a>
                                                </div>
                                            @elseif ($type == 3)
                                                <div>
                                                    <a href="{{ route('sublet-house.details', $house->id) }}"
                                                       class="btn btn-info">Details</a>
                                                </div>
                                            @else
                                                <div>
                                                    <a href="{{ route('bachelor-house.details', $house->id) }}"
                                                       class="btn btn-info">Details</a>
                                                </div>
                                            @endif
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
                    {{ $houses->links() }}
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
    <script>
        function guestBooking() {
            Swal.fire({
                title: 'To book a house, You Need To Login First as a Renter!',
                showClass: {
                    popup: 'animated fadeInDown faster'
                },
                hideClass: {
                    popup: 'animated fadeOutUp faster'
                }
            })
        }
    </script>
@endsection
