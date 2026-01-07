@extends('deliveryman.admin.layouts.main')
@push('css')
    <link rel="manifest" href="/manifest.json">
@endpush
@section('content')

{{-- @include('deliveryman.admin.layouts.m-header')       --}}
<div class="osahan-home-page">
    <style>
        /* body {
            margin: 0;
            font-family: 'Arial', sans-serif;
            background-color: #4A3ADB;
        } */

        .map-box {
            /* max-width: 375px; */
            margin: auto;
            padding: 15px;
            /* background-color: #FFFFFF; */
            border-radius: 25px;
            overflow: hidden;
            /* box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); */
        }

        .map {
            height: 350px;
            background-color: #E4E2FD;
            position: relative;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            position: absolute;
            width: 100%;
            top: 0;
            z-index: 1;
        }

        .menu-icon,
        .status-icon {
            background-color: #ff8a00;
            padding: 10px;
            border-radius: 8px;
            cursor: pointer;
            color: white;
        }

        .map img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .delivery_boy-details {
            background-color: #fff;
            padding: 20px;
            border-bottom-right-radius: 25px;
            border-top-right-radius: 25px;
            border-right: 5px solid #ff810a;
            border-left: 5px solid #ff810a;
            /* color: #ff810a; */
        }

        .order-details p {
            margin: 5px 0;
        }

        .live-order-box {
            /* background: #ff810a36; */
            background-color: white;
            border: 1px solid #ff810a;
            /* padding: 20px; */
            border-radius: 15px;
            /* border-top-right-radius: 25px; */
            /* color: #FFFFFF; */
            /* color: #ff810a; */
        }

        .live-order-details {
            background: #ff810a36;
            border-bottom: 1px solid #ff810a;
            padding: 20px;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            /* color: #FFFFFF; */
            color: #ff810a;
        }

        .live-order-details-box {
            padding: 20px;
            color: #ff810a;
            border-bottom: 2px dashed #80808052;
        }

        .live-order-details-box:last-child {
            padding: 20px;
            color: #ff810a;
            border-bottom: none;
        }

        .locations {
            padding: 20px 0px 20px 0px;
        }

        .location {
            /* display: flex;
            justify-content: space-between;
            align-items: end;*/
            margin-bottom: 15px;
            padding: 15px;
            background-color: #fff;
            border-radius: 15px;
        }

        .location img {
            width: 50px;
            height: 50px;
            border-radius: 15px;
            margin-right: 15px;
        }

        .location-details {
            color: #333;
        }

        .order-btn {
            display: flex;
            justify-content: space-between;
            padding: 20px;
        }

        .order-btn button {
            width: 48%;
            padding: 15px 9px;
            border-radius: 15px;
            border: none;
            cursor: pointer;
            font-weight: bold;
            font-size: 16px;
        }

        .decline {
            background-color: #ff8a00;
            color: #FFFFFF;
        }

        .accept {
            background-color: #ff8a00;
            color: #FFFFFF;
        }

        .restaurant-fix {
            padding: 15px;
            background-color: #F6F6F6;
            border-radius: 15px;
        }

        @media (max-width: 576px) {
            .buttons button {
                width: auto;
                padding: 15px 11px;
            }

            .live-order-details-box {
                padding: 12px 8px;
            }

            .live-order-details-box:last-child {
                padding: 12px 8px;
                color: #ff810a;
                border-bottom: none;
            }

            .restaurant-fix {
                padding-top: 28px;
            }
        }
    </style>
    <div class="res-section">

        <div class="container">
            <div class="row">
                <div class="map-box col-lg-8">
                    <!-- activate -->
                    <?php
                         $today = Carbon\Carbon::now()->toDateString();
                         $status = App\Models\DeliverymanAttendance::whereDate('created_at', $today)->where('deliveryman_id', $dm->id)->latest()->limit(1)->first();
                    ?>
                    <div class="delivery_boy-details shadow-sm d-flex justify-content-between">

                        <!-- <p>Food order</p>
                        <p>2.4 Km - 35 minutes to deliver</p> -->
                        <h5 class="fw-bolder mb-0 text-warning">{{Str::ucfirst($dm->f_name).' '.Str::ucfirst($dm->l_name)}}</h5>
                        <div class>
                            <div class="form-check form-switch d-flex justify-content-end align-items-center p-0 m-0">
                                <input class="form-check-input p-0 m-0" type="checkbox" data-active="offline" role="switch" style="font-size: 35px;width:75px;"
                                    id="flexSwitchCheckChecked"  {{$status? $status->is_online == 1? 'checked': null : null}}>
                            </div>
                            <p class="mb-0 text-center" id="checkIn-context">{{$status? $status->is_online == 1? 'Online': "Offline" : "Offline"}}</p>
                        </div>
                    </div>
                    <!-- activate end -->
                    <div class="locations">
                        <a href="javascript:void(0)">
                            <p class="mb-0 fs-6"></p>
                            <div class="location">
                                <!-- <img src="restaurant.png" alt="Restaurant"> -->
                                <div class="location-details">
                                    @php( $cashInHand = \App\Models\DeliveryManCashInHand::where('deliveryman_id', $dm->id)->first())
                                    @php($todayCashInHand = $cashInHand->cashTxns()->where('txn_type', 'received')
                                    ->whereDate('created_at', Carbon\Carbon::today())
                                    ->sum('amount'))
                                    {{-- @dd($cashInHand) --}}
                                    <h6 class="text-dark"><strong>Cash in hand : {{App\CentralLogics\Helpers::format_currency($cashInHand->balance)}} </strong></h6>
                                    <p class="mb-0"><span class="text-secondary">Today : {{App\CentralLogics\Helpers::format_currency($todayCashInHand)}}</span> {{--  <a href="">Pay</a> --}}</p>
                                </div>
                            </div>
                        </a>
                        <?php
                        $accepted_orders = \App\Models\Order::where('delivery_man_id', $dm->id)->where('order_status', 'accepted')->whereDate('created_at', Carbon\Carbon::today()->toDateString())->count();
                        $delivered_orders = \App\Models\Order::where('delivery_man_id', $dm->id)->where('order_status', 'delivered')->whereDate('created_at', Carbon\Carbon::today()->toDateString())->count();
                        ?>
                        <div class="order-btn p-0">
                            <button class="decline w-50 me-3" onclick="loadOrders('accepted')" >Ongoing Orders ({{$accepted_orders}})</button>
                            <button class="accept w-50" onclick="loadOrders('delivered')">Delivered Orders ({{$delivered_orders}}) </button>
                        </div>
                    </div>
                    <div class="live-order-box" id="live-order-box">
                        <div class="live-order-details">
                            <h6 class="text-center fw-bolder mb-0">Live Orders (5)</h6>
                        </div>
                        {{-- <div class="live-order-details-box" style="filter: blur(3px);">
                            <div class="restaurant-fix position-relative mb-0">
                                <!-- <img src="user.png" alt="User"> -->
                                <div class="d-flex align-items-end justify-content-between">
                                    <div class="location-details">
                                        <div>
                                            <p class="fs-6 mb-2"><strong>Restaurant Name </strong></p>
                                            <p class="fs-6 mb-0">Raja Bazaar</p>
                                        </div>
                                    </div>
                                    <div>
                                        <div>20:38 PM</div>
                                    </div>
                                </div>
                                <hr>
                                <div>
                                    <button class="bg-white px-2 border-0 shadow-sm fs-2 rounded-3 text-warning">
                                        <i class="fa-solid fa-street-view"></i>
                                    </button>
                                    <button class="bg-white px-2 border-0 shadow-sm fs-2 rounded-3 text-warning">
                                        <i class="fa-brands fa-readme"></i>
                                    </button>
                                </div>
                                <div class="bg-success position-absolute px-2 py-1 rounded-bottom-3"
                                    style="top:0;right:100px;">
                                    <div class="text-white"><i class="fa-solid fa-user-pen me-2"></i>Order ID</div>
                                </div>
                                <div class="position-absolute px-2 py-1 rounded-bottom-3"
                                    style="background:#1b1b84;top:0;right:16px;">
                                    <div class="text-white"><i class="fa-solid fa-indian-rupee-sign me-2"></i>COD</div>
                                </div>
                            </div>
                        </div> --}}

                    </div>
                </div>
            </div>
        </div>
    </div>



</div>
<button class="add-button btn float-end" title="Add to home screen"  style="position: absolute;
    bottom: 30px;
    right: 0px;
    z-index: 1111111;
    display: none;">
                <i class="feather-download h4 p-2 bg-primary rounded-circle shadow"></i>
            </button>
@endsection


@push('javascript')
@vite(['resources/js/app.js'])
<script>
    document.querySelectorAll('[data-active]').forEach(item => {
        item.addEventListener('change', () => {
            fetch(`{{ route('deliveryman.activate') }}?checked=${item.checked}`)
                .then(response => {
                    if (!response.ok) {
                        return response.text().then(text => {
                            item.checked = item.checked? false : true;
                            throw new Error(`${JSON.parse(text).message}`);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    // console.log(data.message);
                    document.querySelector('#checkIn-context').textContent = data?.checkIn ? 'Online' : 'Offline';
                    // Swal.fire({icon: 'success',title:data.message,timer: 2000,showConfirmButton: false});
                })
                .catch(error => {
                    Swal.fire({icon: 'error',title:error.message,timer: 2000,showConfirmButton: false});
                    console.error('Error updating location:', error);
                });
        });
    });
</script>
<script>
    async function loadOrders(filter="all") {
        try {
            const resp = await fetch('{{route('deliveryman.admin.get_latest_orders')}}?filter='+filter);
            const result = await resp.json();
            if (resp.ok && result !== null) {
                console.log(result);
                document.querySelector('#live-order-box').innerHTML = result.view;
                const setCurrentOredrs = document.getElementById('setCurrentOrders');
                cookieStore.get('current_orders_count').then(result => {
                    setCurrentOredrs.textContent = result.value;
                    return true;
                });

                OrderAcceptOrReject();

            }
        } catch (error) {
            console.error('Error fetching data:', error);
        }
    }
    async function OrderAcceptOrReject() {
        // console.log(document.querySelectorAll('[data-accepetance]'))
        document.querySelectorAll('[data-acceptance]').forEach(element => {
            element.addEventListener('click', ()=> {
                Swal.fire({
                    title: `Do you want to ${element.dataset.acceptance} the Order?`,
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: element.dataset.acceptance.toUpperCase(),
                    denyButtonText: 'Cancel',
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        try {
                            const resp = await fetch('{{route('deliveryman.admin.order-confirmation')}}?order_id=' + element.dataset.orderId+"&status="+element.dataset.acceptance);
                            if (!resp.ok){
                            const error = await resp.json();
                                throw new Error(error.message);
                            }
                            else{
                                const data = await resp.json();
                                Swal.fire('Saved!', data.message, 'success');
                                loadOrders();
                            }

                        } catch (error) {
                            console.error('Error fetching data:', error);
                            Swal.fire('Error', error.message, 'error');
                        }

                    } else if (result.isDenied) {
                        Swal.fire('Changes are not saved', '', 'info');
                    }
                });
            })
        });
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        //load orders
        loadOrders();
        
        const dmId = $('meta[name="dm_id"]').attr('content');
        window.Echo.private(`deliveryman.${dmId}`)
        .listen('.order.placed', (e) => {
            console.log('New Order Received:');
             loadOrders(); // reload orders
            console.log('Order ID:', e.order_id);
            console.log('Instructions:', e.instructions);
            console.log('Amount:', e.amount);
            console.log('Placed at:', e.placed_at);
        });

        
    });
</script>

<script type="text/javascript">


    let deferredPrompt;
    const addBtn = document.querySelector('.add-button');
    addBtn.style.display = 'none';

    window.addEventListener('beforeinstallprompt', (e) => {
    // Prevent Chrome 67 and earlier from automatically showing the prompt
    e.preventDefault();
    // Stash the event so it can be triggered later.
    deferredPrompt = e;
    // Update UI to notify the user they can add to home screen
    addBtn.style.display = 'block';

    addBtn.addEventListener('click', (e) => {
        // hide our user interface that shows our A2HS button
        addBtn.style.display = 'none';
        // Show the prompt
        deferredPrompt.prompt();
        // Wait for the user to respond to the prompt
        deferredPrompt.userChoice.then((choiceResult) => {
        if (choiceResult.outcome === 'accepted') {
            console.log('User accepted the A2HS prompt');
        } else {
            console.log('User dismissed the A2HS prompt');
        }
        deferredPrompt = null;
        });
    });
    });
</script>

@endpush
