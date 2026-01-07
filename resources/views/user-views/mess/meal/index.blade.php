@extends('user-views.layouts.main')

@section('content')

<div class="osahan-home-page">
    <!-- Moblile header -->  
    @include('user-views.layouts.m-header')      
    <!-- Moblile header end -->  
    <div class="main ">
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
                                        <div class="mb-1 text-wrap ">{{Str::ucfirst($mess->description??'')}}</div>
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
     
        @php ($messTiming = App\Models\MessTiming::where('mess_id', $mess->id)->first())
        {{-- @dd($messWeeklyMenuVeg) --}}

        {{-- @dd($messTiming) --}}
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
                                        <div class="tabs-section">
                                            <div class="tab-buttons">
                                                <button class="tab-button d-lg-flex active" data-tab="tab-delivery">
                                                    
                                                    <i class="feather-calendar me-3"></i><span>Delivery</span>
                                                </button>
                                                <button class="tab-button d-lg-flex" data-tab="tab-dine-in">
                                                     
                                                    <i class="feather-calendar me-3"></i><span>Dine In</span>
                                                </button>
                                            </div>
                                            <div class="tab-content" id="tab-delivery">
                                                <div class="p-3 bg-white rounded rounded-bottom-0 shadow-sm w-100">
                                                    {{-- <div class="row">
                                                        <div class="col-4 d-block text-center border-end">
                                                            <h6><span class="text-black-50" style="font-weight: 900;">BREAKFAST</span>
                                                            </h6>
                                                            <p class="mb-0 mb-lg-2 ms-auto text-nowrap" style="font-size: 18px;"><span class="bg-light text-dark rounded py-1 px-2">{{ App\CentralLogics\Helpers::format_time($deliveryTiming->breakfast) }}</span></p>
                                                        </div>
                                                        <div class="col-4 d-block text-center border-end">
                                                            <h6><span class="text-black-50" style="font-weight: 900;">LUNCH</span>
                                                            </h6>
                                                            <p class="mb-0 mb-lg-2 ms-auto text-nowrap" style="font-size: 18px;"><span class="bg-light text-dark rounded py-1 px-2">{{ App\CentralLogics\Helpers::format_time($deliveryTiming->lunch) }}</span></p>
                                                        </div>
                                                        <div class="col-4 d-block text-center">
                                                            <h6><span class="text-black-50" style="font-weight: 900;">DINNER</span>
                                                            </h6>
                                                            <p class="mb-0 mb-lg-2 ms-auto text-nowrap" style="font-size: 18px;"><span class="bg-light text-dark rounded py-1 px-2">{{ App\CentralLogics\Helpers::format_time($deliveryTiming->dinner) }}</span></p>
                                                        </div>
                                                    </div> --}}
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
                                            <div class="tab-content" id="tab-dine-in">
                                                <div class="p-3 bg-white rounded rounded-bottom-0 shadow-sm w-100">
                                                    {{-- <div class="row">
                                                        <div class="col-4 d-block text-center border-end">
                                                            <h6><span class="text-black-50" style="font-weight: 900;">BREAKFAST</sp>
                                                            </h6>
                                                            <p class="mb-0 mb-lg-2 ms-auto text-nowrap" style="font-size: 18px;"><span
                                                                    class="bg-light text-dark rounded py-1 px-2">{{
                                                                    App\CentralLogics\Helpers::format_time($dine_inTiming->breakfast) }}</span></p>
                                                        </div>
                                                        <div class="col-4 d-block text-center border-end">
                                                            <h6><span class="text-black-50" style="font-weight: 900;">LUNCH</span>
                                                            </h6>
                                                            <p class="mb-0 mb-lg-2 ms-auto text-nowrap" style="font-size: 18px;"><span
                                                                    class="bg-light text-dark rounded py-1 px-2">{{
                                                                    App\CentralLogics\Helpers::format_time($dine_inTiming->lunch) }}</span></p>
                                                        </div>
                                                        <div class="col-4 d-block text-center">
                                                            <h6><span class="text-black-50" style="font-weight: 900;">DINNER</span>
                                                            </h6>
                                                            <p class="mb-0 mb-lg-2 ms-auto text-nowrap" style="font-size: 18px;"><span
                                                                    class="bg-light text-dark rounded py-1 px-2">{{
                                                                    App\CentralLogics\Helpers::format_time($dine_inTiming->dinner) }}</span></p>
                                                        </div>
                                                    </div> --}}
                                                    <div class="p-3 bg-white rounded rounded-bottom-0 shadow-sm w-100">
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
                                                </div>
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
        <!-- Menu -->
        
        <div class="container position-relative">
            <div class="row">
                <div class="col-md-12 pt-3">
                    <h6 class="p-3 m-0 bg-light w-100">Today's Attendance</h6>
                    <div class="shadow-sm rounded bg-white mb-3 overflow-hidden">
                        <div class="row m-0">
                            <div class="col-md-12 px-0 border-top">
                                <div class="">
                                    @foreach ($todayMenu as $item) 
                                    <div class="d-flex align-items-center gap-2 p-3 border-bottom gold-members">
                                        <img alt="#" src="{{asset('assets/user/img/veg.png')}}" class="img-fluid package-img">
                                        <div class="w-100">
                                            <div class="d-flex align-items-end gap-2">
                                                <h5 class="mb-1 text-muted">{{Str::ucfirst($item->service)}} </h5>
                                                <div>
                                                    <h6 class="mb-1">{{Str::ucfirst($item->name)}}&nbsp; - &nbsp;
                                                        {{-- <span class="text-muted mb-0">₹250</span> --}}
                                                        {{-- <a href=""><i class="fas fa-eye ms-2 text-warning"></i></a> --}}
                                                    </h6>
                                                </div>
                                                <div class="ms-auto">
                                                    @if($attendance)
                                                        @php($qty = 0)
                                                        @foreach ($attendance->checklist as $list )
                                                        @php($qty = $list->service == $item->service? +1 : 0)
                                                        @endforeach
                                                        @if($qty > 0)
                                                        <span class="badge bg-primary">Checked <sup>{{$qty}}</sup></span>
                                                        @else
                                                        <span class="count-number float-end d-flex"><button type="button"
                                                            class="btn-sm left dec btn btn-outline-secondary"> <i
                                                                class="feather-minus"></i>
                                                            </button><input class="count-number-input" type="text" readonly=""
                                                                value="1"><button type="button"
                                                                class="btn-sm right inc btn btn-outline-secondary"> <i
                                                                    class="feather-plus"></i> </button>
                                                        </span>
                                                        @endif    
                                                    @else
                                                    <span class="count-number float-end d-flex"><button type="button"
                                                            class="btn-sm left dec btn btn-outline-secondary"> <i
                                                                class="feather-minus"></i>
                                                        </button><input class="count-number-input" type="text" readonly=""
                                                            value="1"><button type="button"
                                                            class="btn-sm right inc btn btn-outline-secondary"> <i
                                                                class="feather-plus"></i> </button>
                                                    </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                    
                                    <h6 class="p-3 m-0 bg-light w-100 border-bottom">Confirm Your Presence</h6>
                                    <div class="d-flex gap-2 border-bottom gold-members">
                                        <div class="w-100">
                                            <div class="row px-4 py-3">
                                                <div class="col-md-4 col-12 pb-3 mt-3">
                                                    <div class="d-flex flex-wrap justify-content-between" id="qrcode"></div>
                                                    
                                                </div>
                                                {{-- <div class="col-md-4 col-12 pb-3 mt-3">
                                                    <div
                                                        class="list-card bg-white h-100 rounded overflow-hidden position-relative shadow-sm">
                                                        <div class="list-card-image">
                                                                <img alt="#" src="{{asset('assets/user/img/qr-code.png')}}"
                                                                    class="img-fluid item-img w-100">
                                                            </a>
                                                        </div>
                                                        <div class="p-3 position-relative">
                                                            <div class="list-card-body">
                                                            <h5 class="mb-1 text-center" style="letter-spacing:12px;font-weight:800;">0384
                                                                </h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> --}}
                                            </div>
                                        </div>
                                    </div>

                                    
                                    <h6 class="p-3 m-0 bg-light w-100 border-bottom">Package <small
                                            class="text-black-50">(Expire in - {{App\CentralLogics\Helpers::daysUntilExpiry($customerSubscriptionTxns->expiry)}} Days)</small></h6>
                                    <div class="bg-white p-3 clearfix border-bottom">
                                        <p class="mb-1 text-success">Remaining Coupons <span
                                                class="float-end text-success">{{App\Models\DietCoupon::countCoupon($customerSubscriptionTxns->id, ['active','pending'])}}</span></p>
                                        <p class="mb-1">Used Coupons <span class="text-info ms-1"><i
                                                    class="feather-info"></i></span><span
                                                class="float-end text-dark">{{App\Models\DietCoupon::countCoupon($customerSubscriptionTxns->id, 'redeem')}}</span></p>
                                                {{-- @dd($coupons->get()) --}}
                                        {{-- <p class="mb-1">Coupons Forwarded<span class="text-info ms-1"><i
                                                    class="feather-eye"></i></span><span class="float-end text-dark">₹10</span>
                                        </p> --}}
                                        <p class="mb-1  text-warning">Expired Coupons<span
                                                class="float-end text-warning">{{App\Models\DietCoupon::countCoupon($customerSubscriptionTxns->id,'expired')}}</span>
                                        </p>
                                        <hr>
                                        {{-- @dd($subscribedPackage) --}}
                                        <div class="d-flex justify-content-between">
                                            <h6 class="mb-0">Start Date - <span class="float-end text-black-50">{{App\CentralLogics\Helpers::format_date($customerSubscriptionTxns->start)}}</span></h6>
                                            <h6 class="mb-0">Expire Date -<span class="float-end text-black-50">{{App\CentralLogics\Helpers::format_date($customerSubscriptionTxns->expiry)}}</span></h6>
                                        </div>
                                    </div>
                                    <div class="p-3">
                                        <a class="btn btn-success w-100 btn-lg" href="successful.html">Explorer more Coupons</a>
                                    </div>
                                </div>
                            </div>
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
    $(document).ready(function () {
        // console.log("Initial checked radio button ID:", $('input[name="btnradio"]:checked').attr('id'));

        $('.btn-group input[type="radio"]').change(function () {
            const checkedRadioID = $('input[name="btnradio"]:checked').attr('id');

            if (checkedRadioID === 'btnradio2') {
                $('.btndays').removeClass('btn-veg-outline').addClass('btn-nv-outline');
                $('#three-meal').removeClass('three-veg-meal').addClass('three-nv-meal');
            } else {
                $('.btndays').removeClass('btn-nv-outline').addClass('btn-veg-outline');
                $('#three-meal').removeClass('three-nv-meal').addClass('three-veg-meal');
            }

            console.log("Changed radio button ID:", checkedRadioID);
        });

        $('.btn-days').change(function () {
            const selectedDayID = $('input[name="btndays"]:checked').attr('id');
            console.log("Selected day:", selectedDayID);
        });
    });
</script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.btn-group input[type="radio"]').forEach(item => {

        item.addEventListener('change', async (event) => {
            const checkedRadioID = document.querySelector('.btn-group input[type="radio"]:checked').id;
            try {
                const resp = await fetch(`{{ route("user.mess.weeklymenudays") }}?type=${item.dataset.type}&mess_id=${item.dataset.messId}`);
                const result = await resp.text();
                if (resp.ok && result !== null) {
                    document.querySelector('#menu-for-days').innerHTML = result;
                    console.log(result);
                }
    
                document.querySelectorAll('input[name="btndays"]').forEach(item2 => {

                    item2.addEventListener('change', async (event2) => {
                        const resp = await fetch(`{{ route("user.mess.weeklymenu") }}?type=${item2.dataset.type}&mess_id=${item2.dataset.messId}&day=${item2.value}`);
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

                })

                document.querySelectorAll('.btndays').forEach(labelElement => {
                    if (checkedRadioID === 'btnradio2') {
                        labelElement.classList.remove('btn-veg-outline');
                        labelElement.classList.add('btn-nv-outline');
                    } else {
                        labelElement.classList.remove('btn-nv-outline');
                        labelElement.classList.add('btn-veg-outline');
                    }
                })
            } catch (error) {
                console.error('Error fetching data:', error);
            }
        });
    })
});
</script>



<script src="{{asset('assets/vendor/qrcode/qrcode.min.js')}}"></script>
<script>
    document.addEventListener('DOMContentLoaded', async () => {
    try {
        const resp = await fetch("{{ route('user.mess.my-diet-qr-image') }}");
        const service = {breakfast : "BreakFast", lunch : "Lunch" , dinner : "Dinner"};
        const result = await resp.json();
        if (result.error) {
            throw new Error(result.error);
        }
        
        result.forEach(item => {
            const qrDiv = document.createElement('div');
            qrDiv.id = `qr-${item.service}-${item.attendance_checklist_id}`;
            qrDiv.classList.add('mx-3','mt-3', 'd-inline')

            const otpDiv = document.createElement('div');
            const otpspan = document.createElement('span');
            otpDiv.dataset.otp = `otp-${item.service}-${item.attendance_checklist_id}`;
            otpDiv.textContent = "OTP: " + item.otp;
            otpspan.textContent = "OTP For " + service[item.ckecklist.service];

            var qrcode = new QRCode(qrDiv, {
                text: item.encrypted_code,
                width: 250,
                height: 250,
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });

            document.querySelector('#qrcode').append(qrDiv);
            qrDiv.append(otpDiv);
            qrDiv.append(otpspan);
        });

    } catch (error) {
        toastr.error(error.message);
    }
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

    
@endpush
