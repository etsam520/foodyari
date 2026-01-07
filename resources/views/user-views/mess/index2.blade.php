@extends('user-views.layouts.main')

@section('content')

    @push('css')
       <link rel="stylesheet" href="{{asset('assets/user/vendor/slick/slick/slick.css')}}">
    @endpush
    {{-- @dd($mess) --}}
    <div class="osahan-home-page">
        <!-- Moblile header -->
        @include('user-views.layouts.m-header')
        <!-- Moblile header end -->
        <div class="main " style="margin-bottom: 100px;">
            <div class="container position-relative">
                @include('user-views.layouts.slider')
            </div>

            <div class="container  position-relative">
                <div class="row">
                    <div class="col-12 pt-3">
                        <div class="shadow-sm rounded offer-section overflow-hidden p-2">
                            <div class="row">
                                <div class="col-5 col-lg-2">
                                    <div class="position-relative list-card">
                                        <img alt="#" src="{{ asset('vendorMess/' . $mess->logo) }}" class="restaurant-pic">
                                        <div class="star mess-info position-absolute"><span class="badge text-bg-success"><i class="feather-user"></i> Mess ID : #{{ Str::upper($mess->mess_no ?? 'NA') }}</span></div>
                                        <div class="member-plan position-absolute"><span class=""><img alt="#" src="{{ asset('assets/user/img/veg.png') }}" class="img-fluid item-img w-100"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-7 col-lg-10 px-0 px-lg-2">
                                    <div class="text-white">
                                        <h2 class="fw-bolder mess-title">{{ Str::upper($mess->name) }}</h2>

                                        <div class="position-relative">
                                            <div class="mb-1 text-wrap ">{{ Str::ucfirst($mess->description ?? '') }}</div>
                                            <div class="bookmark-icon bookmark-icon-two pe-2 ps-3 text-nowrap position-absolute" style="top: 0px;right:0px;">
                                                @php($badges = json_decode($mess->badges))
                                                @if ($badges)
                                                    <span class="text-warning" style="border-radius: 8px 0px 0px 8px !important;font-size: 14px;">{{ Str::ucfirst($badges->b1) }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        @php($address = json_decode($mess->address))
                                        <a href="https://www.google.com/maps?q={{ $mess->latitude . ',' . $mess->longitude }}" class="text-white m-0"><i class="feather-map-pin me-1"></i>
                                            {{ Str::ucfirst($address->street ?? null) }}, {{ Str::ucfirst($address->city ?? null) }} - {{ Str::ucfirst($address->pincode ?? null) }}
                                        </a>
                                    </div>
                                    <div class="d-flex mb-1">

                                        <div class="bg-success text-white rounded px-2 me-1">
                                            <p class="mb-0 text-white py-1" style="font-size: 15px;"><i class="feather-star star_active me-2"></i>5.0</p>
                                        </div>
                                        <a href="javascript:void(0)" class="badge text-white one-diet-info" style="font-size: 15px;" data-bs-toggle="modal" data-bs-target="#one_diet_cost">
                                            One Diet Cost<i class="feather-eye ms-2"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @php($messTiming = App\Models\MessTiming::where('mess_id', $mess->id)->first())


            {{-- (messTiming) --}}
            <div class="container ">
                <div class="mt-4 row">
                    <div class="col-md-12">
                        <div>

                            @if ($messTiming)
                                @php($dine_inTiming = json_decode($messTiming->dine_in))
                                @php($deliveryTiming = json_decode($messTiming->delivery))


                                <div class="osahan-cart-item mb-3 rounded shadow-sm bg-white overflow-hidden">
                                    <div class="osahan-cart-item-profile bg-white">
                                        <div id="insert-mess-timing">
                                            <div class="tabs-section p-0">
                                                <div class="tab-buttons">
                                                    <button class="tab-button d-lg-flex active" data-tab="tab-delivery">

                                                        <i class="feather-calendar me-3"></i><span>Delivery</span>
                                                    </button>
                                                    <button class="tab-button d-lg-flex" data-tab="tab-dine-in">

                                                        <i class="feather-calendar me-3"></i><span>Dine In</span>
                                                    </button>
                                                </div>
                                                <div class="tab-content" id="tab-delivery"
                                                    style="border-top: 1px solid #ccc;
                                                                                            border-left: 1px solid rgb(204, 204, 204);
                                                                                            border-right: 1px solid rgb(204, 204, 204);
                                                                                            border-bottom: 1px solid rgb(204, 204, 204);">
                                                    <div class="p-3 bg-white rounded rounded-bottom-0 w-100">
                                                        <div class="row">
                                                            <div class="col-4 d-block text-center border-end">
                                                                <h6><small class="text-black-50">BREAKFAST</small>
                                                                </h6>
                                                                <p class="mb-0 mb-lg-2 ms-auto"><span class="bg-light text-dark rounded py-1 px-2"><i class="feather-clock"></i> {{ App\CentralLogics\Helpers::format_time($deliveryTiming->breakfast) }}</span></p>
                                                            </div>
                                                            <div class="col-4 d-block text-center border-end">
                                                                <h6><small class="text-black-50">LUNCH</small>
                                                                </h6>
                                                                <p class="mb-0 mb-lg-2 ms-auto"><span class="bg-light text-dark rounded py-1 px-2"><i class="feather-clock"></i> {{ App\CentralLogics\Helpers::format_time($deliveryTiming->lunch) }}</span></p>
                                                            </div>
                                                            <div class="col-4 d-block text-center">
                                                                <h6><small class="text-black-50">DINNER</small>
                                                                </h6>
                                                                <p class="mb-0 mb-lg-2 ms-auto"><span class="bg-light text-dark rounded py-1 px-2"><i class="feather-clock"></i> {{ App\CentralLogics\Helpers::format_time($deliveryTiming->dinner) }}</span></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-content" id="tab-dine-in"
                                                    style="border-top: 1px solid #ccc;
                                                                                            border-left: 1px solid rgb(204, 204, 204);
                                                                                            border-right: 1px solid rgb(204, 204, 204);
                                                                                            border-bottom: 1px solid rgb(204, 204, 204);">
                                                    {{-- <div class="p-3 bg-white rounded rounded-bottom-0 w-100"> --}}
                                                    <div class="p-3 bg-white rounded rounded-bottom-0 w-100">
                                                        <div class="row">
                                                            <div class="col-4 d-block text-center border-end">
                                                                <h6><small class="text-black-50">BREAKFAST</small>
                                                                </h6>
                                                                <p class="mb-0 mb-lg-2 ms-auto"><span class="bg-light text-dark rounded py-1 px-2"><i class="feather-clock"></i> {{ App\CentralLogics\Helpers::format_time($dine_inTiming->breakfast) }}</span></p>
                                                            </div>
                                                            <div class="col-4 d-block text-center border-end">
                                                                <h6><small class="text-black-50">LUNCH</small>
                                                                </h6>
                                                                <p class="mb-0 mb-lg-2 ms-auto"><span class="bg-light text-dark rounded py-1 px-2"><i class="feather-clock"></i> {{ App\CentralLogics\Helpers::format_time($dine_inTiming->lunch) }}</span></p>
                                                            </div>
                                                            <div class="col-4 d-block text-center">
                                                                <h6><small class="text-black-50">DINNER</small>
                                                                </h6>
                                                                <p class="mb-0 mb-lg-2 ms-auto"><span class="bg-light text-dark rounded py-1 px-2"><i class="feather-clock"></i> {{ App\CentralLogics\Helpers::format_time($dine_inTiming->dinner) }}</span></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{-- </div> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="osahan-card bg-white border-bottom overflow-hidden">
                                <div class="osahan-card-header" id="headingTwo">
                                    {{-- <h2 class="mb-0"> --}}
                                    <button class="d-flex p-3 align-items-center btn btn-link w-100 text-warning" style="font-size: 22px;font-weight: 700;" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        <i class="feather-calendar me-3"></i> Weekly Menu
                                        <i class="feather-chevron-down ms-auto"></i>
                                    </button>
                                    {{-- </h2> --}}
                                </div>
                                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                    <div class="osahan-card-body border-top p-3">
                                        <div class="btn-group w-100" role="group" aria-label="Basic radio toggle button group">
                                            <input type="radio" class="btn-check" data-type="V" data-mess-id="{{ $mess->id }}" name="btnradio" id="btnradio1">
                                            <label class="btn btn-veg-outline" for="btnradio1">Veg</label>
                                            <input type="radio" class="btn-check" data-type="N" data-mess-id="{{ $mess->id }}" name="btnradio" id="btnradio2">
                                            <label class="btn btn-nv-outline" for="btnradio2">Non - Veg</label>
                                        </div>
                                        <!-- <hr> menu inserticion -->
                                        <div id="menu-for-days"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Menu end -->


            <div class="container position-relative">
                <div class="row">
                    <div class="col-md-12 pt-3">
                        <div class="shadow-sm rounded bg-white mb-3 overflow-hidden">
                            <div class="d-flex item-aligns-center">
                                <p class="p-3 mb-0 w-100 text-warning" style="font-size: 22px;font-weight: 700;border-bottom: 2px solid #ff810a;"> Package Details </p>
                                <!-- <a class="small text-primary fw-bold ms-auto" href="javascript:void(0)">View all <i class="feather-chevrons-right"></i></a> -->
                            </div>

                            <div class="row m-0">
                                <div class="col-md-12 px-0 border-top">
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    <form action="{{ route('user.mess.package-add-to-cart') }}" method="POST">
                                        @csrf
                                        <div class="">
                                            {{-- @dd(Session::get('cart')) --}}
                                            @foreach ($mess->subscription as $package)
                                                <div class="d-flex gap-2 px-lg-3 px-3 py-4 border-bottom border-primary gold-members">
                                                    <div class="w-100">
                                                        <div class="d-flex gap-2 mb-2">
                                                            <div class="d-flex align-items-start me-auto">
                                                                <img alt="#" src="{{ $package->type == 'veg' ? asset('assets/user/img/veg.png') : asset('assets/user/img/non-veg.png') }}" class="img-fluid me-2 food-type mt-1">
                                                                <div>
                                                                    <h6 class="mb-1 fs-6 text-wrap text-start" style="font-weight: 850">{{ Str::upper($package->title) }} &nbsp; &nbsp;<br>
                                                                    </h6>
                                                                    <div class="text-start">
                                                                        <span class="text-danger mb-0"> <strike>{{ App\CentralLogics\Helpers::format_currency($package->price) }}</strike></span>
                                                                        <span class="text-success mb-0 fs-5 fw-bolder"> {{ App\CentralLogics\Helpers::format_currency(App\CentralLogics\Helpers::food_discount($package->price, $package->discount)) }}</span>
                                                                    </div>
                                                                    <button type="button" class="text-dark btn btn-outline-secondary mt-3" onclick="event.stopPropagation();" data-bs-toggle="collapse" data-bs-target="#collapse{{ Str::upper($package->id) }}"
                                                                        aria-expanded="false" aria-controls="collapse{{ Str::upper($package->id) }}">
                                                                        View More
                                                                        <i class="feather-chevrons-right"></i>
                                                                    </button>

                                                                </div>

                                                            </div>

                                                            <div class="">
                                                                <div class="position-relative">
                                                                    <div class="slick-group shadow-lg package-img">
                                                                        @if($package->images)
                                                                            @php($images = json_decode($package->images))
                                                                            @foreach ($images as $image)
                                                                                <div class="slick-item">
                                                                                    <img src="{{asset('messSubscriptionToCustomers/'.$image)}}" alt="food image" class="w-100 rounded-2">
                                                                                </div>
                                                                            @endforeach
                                                                        @else
                                                                        <div class="slick-item">
                                                                            <img src="{{ asset('vendorMess/' . $mess->logo) }}" alt=" mess logo" class="w-100 rounded-2">
                                                                        </div>
                                                                        @endif


                                                                    </div>

                                                                    <div class="position-absolute package-view">
                                                                        <div class="count-number shadow bg-white" style="border-radius: 15px;">
                                                                            <button type="button" class="btn-sm left dec btn text-warning item-decrement">
                                                                                <i class="feather-minus fw-bolder"></i>
                                                                            </button>
                                                                            <input type="hidden" name="package[{{$loop->index}}][id]" value="{{$package->id}}">
                                                                            <input class="count-number-input text-warning" type="text" readonly="" name="package[{{$loop->index}}][quantity]" value="0">
                                                                            <button type="button" class="btn-sm right inc btn text-warning item-increment">
                                                                                <i class="feather-plus fw-bolder"></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div id="collapse{{ Str::upper($package->id) }}" class="collapse" aria-labelledby="headingThree">
                                                            @php($diets = json_decode($package->diets))
                                                            <div class="row p-3 mt-lg-5 mt-0">
                                                                <div class="col-lg-4 d-flex border px-0">
                                                                    <p class="border-end ps-3 text-fw-bold col-9 mb-0">No. of Normal Diet -
                                                                    </p>
                                                                    <span class="text-muted text-center col-3 mb-0">{{ (int) $diets->breakfast + (int) $diets->lunch + (int) $diets->dinner }} </span>
                                                                </div>
                                                                <div class="col-lg-4 d-flex border px-0">
                                                                    <p class="border-end ps-3 text-fw-bold col-9 mb-0">No. of Special Diet -
                                                                    </p>
                                                                    <span class="text-muted text-center col-3 mb-0">{{ (int) $diets->special }} </span>
                                                                </div>
                                                                <div class="col-lg-4 d-flex border px-0">
                                                                    <p class="border-end ps-3 text-fw-bold col-9 mb-0">Total Diet -
                                                                    </p>
                                                                    <span class="text-muted text-center col-3 mb-0">{{ (int) $diets->breakfast + (int) $diets->lunch + (int) $diets->dinner + (int) $diets->special }}</span>
                                                                </div>
                                                                <div class="col-lg-4 d-flex border px-0">
                                                                    <p class="border-end ps-3 text-fw-bold col-9 mb-0">Total Breakfast -
                                                                    </p>
                                                                    <span class="text-muted text-center col-3 mb-0">{{ (int) $diets->breakfast }} </span>
                                                                </div>
                                                                <div class="col-lg-4 d-flex border px-0">
                                                                    {{-- <div class="d-flex border-bottom border-end"> --}}
                                                                    <p class="border-end ps-3 text-fw-bold col-9 mb-0">Total Lunch -
                                                                    </p>
                                                                    <span class="text-muted text-center col-3 mb-0">{{ (int) $diets->lunch }}</span>
                                                                    {{-- </div> --}}
                                                                </div>
                                                                <div class="col-lg-4 d-flex border px-0">
                                                                    {{-- <div class="d-flex border-bottom border-end"> --}}
                                                                    <p class="border-end ps-3 text-fw-bold col-9 mb-0">Total Dinner -
                                                                    </p>
                                                                    <span class="text-muted text-center col-3 mb-0">{{ (int) $diets->dinner }} </span>
                                                                    {{-- </div> --}}
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            @endforeach

                                            @if ($mess->subscription->count() > 0)
                                                <div class="text-center m-3"><button type="submit" class="btn btn-primary w-100 btn-sm">Continue</button>
                                                </div>
                                            @else
                                                <div class="text-center text-white p-2 bg-primary w-100 ">
                                                    No Package Available
                                                </div>
                                            @endif


                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- one diet cost modal --}}
    <div class="modal fade" id="one_diet_cost" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="osahan-filter">
                        <div class="filter">
                            <div class="border px-4 py-3">
                                <p class="h1 text-primary text-center"><i class="fas fa-clipboard-check"></i></p>
                                <h6 class="text-center">One Diet Cost</h6>
                                <hr>
                                @if ($mess->diet_cost)
                                    @php($diet_cost = json_decode($mess->diet_cost))
                                    <p class="mb-0 normal"><b>Normal : {{ App\CentralLogics\Helpers::format_currency($diet_cost->normal) }}</b> </p>
                                    <p class="mb-0 special"><b>Special : {{ App\CentralLogics\Helpers::format_currency($diet_cost->special) }} </b></p>
                                @else
                                    <p class="mb-0 normal"><b>Normal : NA</b> </p>
                                    <p class="mb-0 special"><b>Special : </b> NA</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>





@endsection

@push('javascript')
    <script>
        $(document).ready(function() {
            // console.log("Initial checked radio button ID:", $('input[name="btnradio"]:checked').attr('id'));

            $('.btn-group input[type="radio"]').change(function() {
                const checkedRadioID = $('input[name="btnradio"]:checked').attr('id');

                if (checkedRadioID === 'btnradio2') {
                    $('.btndays').removeClass('btn-veg-outline').addClass('btn-nv-outline');
                    $('#three-meal').removeClass('three-veg-meal').addClass('three-nv-meal');
                    // $('.three-meal').addClass('text-center');
                } else {
                    $('.btndays').removeClass('btn-nv-outline').addClass('btn-veg-outline');
                    $('#three-meal').removeClass('three-nv-meal').addClass('three-veg-meal');
                    // $('.three-meal').addClass('text-center');
                }

            });

            $('.btn-days').change(function() {
                const selectedDayID = $('input[name="btndays"]:checked').attr('id');

            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.btn-group input[type="radio"]').forEach(item => {

                item.addEventListener('change', async (event) => {
                    const checkedRadioID = document.querySelector('.btn-group input[type="radio"]:checked').id;
                    console.log(checkedRadioID);
                    try {
                        const resp = await fetch(`{{ route('user.mess.weeklymenudays') }}?type=${item.dataset.type}&mess_id=${item.dataset.messId}`);
                        const result = await resp.text();
                        if (resp.ok && result !== null) {
                            document.querySelector('#menu-for-days').innerHTML = result;
                        }

                        document.querySelectorAll('input[name="btndays"]').forEach(item2 => {

                            item2.addEventListener('change', async (event2) => {
                                const resp = await fetch(`{{ route('user.mess.weeklymenu') }}?type=${item2.dataset.type}&mess_id=${item2.dataset.messId}&day=${item2.value}`);
                                const result = await resp.text();
                                if (resp.ok && result !== null) {
                                    document.querySelector('#insert-menu').innerHTML = result;


                                    const tabButtons = document.querySelectorAll(".tab-button");
                                    const tabContents = document.querySelectorAll(".tab-content");

                                    tabButtons.forEach(button => {
                                        button.addEventListener("click", function() {
                                            const tabId = button.getAttribute("data-tab");

                                            tabContents.forEach(content => {
                                                content.style.display = "none";
                                            });


                                            tabButtons.forEach(btn => {
                                                btn.classList.remove("active");
                                            });

                                            document.getElementById(tabId).style.display = "block";
                                            button.classList.add("active");
                                        });
                                    });
                                }
                            });


                            document.querySelectorAll('.btndays').forEach(labelElement => {
                                if (checkedRadioID === 'btnradio2') {
                                    labelElement.classList.remove('btn-veg-outline');
                                    labelElement.classList.add('btn-nv-outline');
                                } else {
                                    labelElement.classList.remove('btn-nv-outline');
                                    labelElement.classList.add('btn-veg-outline');
                                }
                            })
                        })
                    } catch (error) {
                        console.error('Error fetching data:', error);
                    }
                });
            })
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const tabButtons = document.querySelectorAll("#insert-mess-timing .tab-button");
            const tabContents = document.querySelectorAll("#insert-mess-timing .tab-content");

            tabButtons.forEach(button => {
                button.addEventListener("click", function() {
                    const tabId = button.getAttribute("data-tab");

                    // Hide all tab contents
                    tabContents.forEach(content => {
                        content.style.display = "none";
                    });

                    // Deactivate all tab buttons
                    tabButtons.forEach(btn => {
                        btn.classList.remove("active");
                    });

                    // Show the clicked tab content and activate the clicked tab button
                    document.getElementById(tabId).style.display = "block";
                    button.classList.add("active");
                });
            });
        });
    </script>

<script src="{{asset('assets/user/vendor/slick/slick/slick.min.js')}}"></script>


<script type="text/javascript">
    $(document).ready(function(){
        $('.slick-group').slick({
        arrows : false,
        infinite: true,
        speed: 300,
        fade: true,
        autoplay: true,
        cssEase: 'linear'
        });

        $('.item-increment').click(function() {
            let input = $(this).siblings('.count-number-input');
            let currentValue = parseInt(input.val());
            input.val(currentValue + 1);
        });

        $('.item-decrement').click(function() {
            let input = $(this).siblings('.count-number-input');
            let currentValue = parseInt(input.val());
            if (currentValue > 0) {
                input.val(currentValue - 1);
            }
        });

    });


  </script>
@endpush
