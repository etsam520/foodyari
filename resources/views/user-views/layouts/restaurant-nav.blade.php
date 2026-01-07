@extends('user-views.layouts.main')
@section('content')
    <div class="osahan-home-page">
        <!-- Moblile header -->
        @include('user-views.layouts.m-header')
        <!-- Moblile header end -->
        <div class="main " style="margin-bottom: 100px;">
            <div class="container">
                @include('user-views.layouts.slider')

                <div class="box bg-white mb-3 mt-3 shadow-sm rounded">
                    <div class="overflow-hidden border-top d-flex align-items-center p-2">
                        @include('user-views.layouts.marquee')
                    </div>
                </div>
                <div class="pt-2 pb-3 title d-flex align-items-center">
                    <h5 class="m-0">{{ $nearBymess ? 'Nearest Mess ' : 'Total Mess' }} <span class="badge bg-primary rounded-pill">{{ $messes->count() }}</span>
                    </h5>
                    <a class="fw-bold ms-auto" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#filters">Filters <i class="feather-chevrons-right"></i></a>
                    <!-- <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#filters" class="ms-auto btn btn-primary">Filters</a> -->
                </div>
                <div class="most_sale">
                    <div class="row mb-3">
                        @foreach ($messes as $mess)
                            {{-- @dd($mess) --}}
                            <div class="col-md-6 mb-3" onclick="location.href = '{{ route('user.mess.view', $mess->id) }}'">
                                <div class="d-flex align-items-center list-card bg-white h-100 rounded overflow-hidden position-relative shadow-sm">
                                    <div class="list-card-image">
                                        <div class="star mess-info position-absolute"><span class="badge text-bg-success"><i class="feather-user"></i>Mess ID : #{{Str::upper($mess->mess_no??"NA")}}</span>
                                        </div>
                                        <div class="favourite-heart text-danger position-absolute rounded-circle"><a href="javascript:void(0)"><i class="feather-heart"></i></a></div>
                                        <div class="member-plan position-absolute"><span class=""><img alt="#" src="{{ asset('assets/user/img/veg.png') }}" class="img-fluid item-img w-100"></span>
                                        </div>
                                        <a href="{{ route('user.mess.view', $mess->id) }}">
                                            <img alt="#" src="{{ asset("vendorMess/$mess->logo") }}" class="img-fluid item-img w-100">
                                        </a>
                                    </div>
                                    <div class="p-3 position-relative">
                                        <div class="list-card-body">
                                            <h6 class="mb-1">
                                                <a href="{{ route('user.mess.view', $mess->id) }}" class="text-black">
                                                    {{ Str::upper($mess->name) }}
                                                </a>
                                            </h6>
                                            @php($badges = json_decode($mess->badges))

                                            <div class="list-card-badge mb-1">
                                                {{-- @if ($badges)
                                                    <span class="badge text-bg-danger me-1">{{ Str::ucfirst($badges->b1) }}</span>
                                                @endif --}}
                                                <div>Speciality/Description</div>
                                            </div>
                                            <div class="d-flex mb-1">
                                                {{-- <ul class="rating-stars list-unstyled mb-0">
                                                    <li>
                                                        <i class="feather-star star_active"></i>
                                                    </li>
                                                </ul> --}}
                                                <div class="bg-success text-white rounded px-2 me-3">
                                                    <p class="mb-0 text-white"><i class="feather-star star_active me-2"></i>5.0</p>
                                                </div>
                                                @php($address = json_decode($mess->address))
                                                @if($address)
                                                <a href="https://www.google.com/maps?q={{ $mess->latitude . ',' . $mess->longitude }}" class="mb-0 location align-self-center"><i class="feather-map-pin me-1"></i>
                                                    {{Str::ucfirst($address->street)}} {{Str::ucfirst($address->city)}} - {{Str::ucfirst($address->pincode)}}</a>
                                                @endif
                                            </div>
                                            
                                            <div class="list-card-badge mb-1">
                                                @if ($badges)
                                                    <span class="badge text-bg-danger badge-two me-1">{{ Str::ucfirst($badges->b2) }}</span>
                                                @endif
                                                <div class="badge-two px-3 py-2 mt-2">
                                                    <div class="text-center">One Diet Cost</div>
                                                    <div class="text-secondary diet-info">Normal : Rs 100</div>
                                                    <div class="text-secondary diet-info">Special : Rs 100</div>
                                                </div>
                                            </div>
                                            {{-- <div class="text-gray mb-0">Lunch/Breakfast/Dinner</div> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="filters" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Filters</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="osahan-filter">
                        <div class="filter">
                            <div class="p-3 bg-light border-bottom">
                                <h6 class="m-0">FILTER</h6>
                            </div>
                            <div class="px-3 pt-3">
                                <div class="mt-2">
                                    <div class="d-flex justify-content-between">
                                        <label class="form-label mb-0 align-self-center" for="email">No. of Normal
                                            Diet</label>
                                        <div class="mess-custom-input">
                                            <button class="decrease-btn" id="decrease-btn">-</button>
                                            <input type="text" id="input-value" value="0">
                                            <button class="increase-btn" id="increase-btn">+</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <div class="d-flex justify-content-between">
                                        <label class="form-label mb-0 align-self-center" for="email">No. of Special
                                            Diet</label>
                                        <div class="mess-custom-input">
                                            <button class="decrease-btn" id="decrease-btn">-</button>
                                            <input type="text" id="input-value" value="0">
                                            <button class="increase-btn" id="increase-btn">+</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <div class="d-flex justify-content-between">
                                        <label class="form-label mb-0 align-self-center" for="email">No. of Close
                                            Days</label>
                                        <div class="mess-custom-input">
                                            <button class="decrease-btn" id="decrease-btn">-</button>
                                            <input type="text" id="input-value" value="0">
                                            <button class="increase-btn" id="increase-btn">+</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <div class="d-flex justify-content-between">
                                        <label class="form-label mb-0 align-self-center" for="email">No. of Coupons
                                            Sells</label>
                                        <div class="mess-custom-input">
                                            <button class="decrease-btn" id="decrease-btn">-</button>
                                            <input type="text" id="input-value" value="0">
                                            <button class="increase-btn" id="increase-btn">+</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <input type="text" class="form-control form-control-sm" placeholder="Diet Cost">
                                </div>
                                <div class="mt-3">
                                    <input type="text" class="form-control form-control-sm" placeholder="Price Range">
                                </div>
                                <div class="mt-3">
                                    <select class="form-select form-select-sm mb-3 shadow-none">
                                        <option selected="">Select Food Type</option>
                                        <option value="1">Veg</option>
                                        <option value="2">Non Veg</option>
                                    </select>
                                </div>
                                <div class="mt-3">
                                    <select class="form-select form-select-sm mb-3 shadow-none">
                                        <option selected="">Select Service</option>
                                        <option value="1">Delivery</option>
                                        <option value="2">Dine in</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-0 border-0">
                    <div class="col-6 m-0 p-0">
                        <a href="javascript:void(0)" class="btn border-top btn-lg w-100" data-bs-dismiss="modal">Close</a>
                    </div>
                    <div class="col-6 m-0 p-0">
                        <a href="most_popular.html" class="btn btn-primary btn-lg w-100">Apply</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('javascript')
    <script>
        //increment value
        document.addEventListener('DOMContentLoaded', function() {
            const decreaseBtns = document.querySelectorAll('.decrease-btn');
            const increaseBtns = document.querySelectorAll('.increase-btn');
            const inputValues = document.querySelectorAll('.input-value');

            decreaseBtns.forEach(function(btn) {
                btn.addEventListener('click', function(event) {
                    event.preventDefault();
                    let input = btn.nextElementSibling;
                    let value = parseInt(input.value);
                    if (value > 0) {
                        input.value = value - 1;
                    }
                });
            });

            increaseBtns.forEach(function(btn) {
                btn.addEventListener('click', function(event) {
                    event.preventDefault();
                    let input = btn.previousElementSibling;
                    let value = parseInt(input.value);
                    input.value = value + 1;
                });
            });
        });
    </script>
@endpush
