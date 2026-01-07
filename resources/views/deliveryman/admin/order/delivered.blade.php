@extends('deliveryman.admin.layouts.main')
@push('css')
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

    .buttons {
        display: flex;
        justify-content: space-between;
        padding: 20px;
    }

    .buttons button {
        width: 48%;
        padding: 15px;
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
@endpush
@section('content')

<div class="osahan-home-page">

    <div class="res-section">

        <div class="container">

            <div class="row justify-content-center pt-3">
                <div class="map-box col-lg-8">

                    <div class="live-order-box">
                        <div class="live-order-details">
                            <h6 class="text-center fw-bolder mb-0">{{__('messages.orders')}}
                                {{__("messages." . $state)}}
                            </h6>
                        </div>
                        <div class="p-3">
                            <div class="d-flex justify-content-between">
                                <div class="">
                                    <button type="button" class="btn btn-white border-0 dropdown-toggle"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        Filter:
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="{{ url()->current() }}?filter=day">By Day</a>
                                        </li>
                                        <li><a class="dropdown-item" href="{{ url()->current() }}?filter=month">By Month</a>
                                        </li>
                                        <li><a class="dropdown-item" href="{{ url()->current() }}?filter=year">By Year</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="d-flex">
                                    <div class="fw-bolder"><i class="feather-refresh-cw me-2"></i></div>
                                    <div class="fw-bolder"><i class="feather-download"></i></div>
                                </div>
                            </div>
                            <hr>
                            <div class="text-center">
                                <div>Order History</div>
                                <h6>No. of Delivered Order </h6>
                                <h2 class="fw-bolder">{{ $orders->count() }}</h2>
                            </div>
                        </div>
                        @foreach ($orders as $order)
                        <div class="live-order-details-box">
                            <div class="restaurant-fix position-relative mb-0">
                                <div class="d-flex align-items-end justify-content-between mt-3">
                                    <div class="location-details mt-3">
                                        <div>
                                            <h4 class="fs-6 mb-2">
                                                <strong>{{Str::ucfirst($order->restaurant->name)}}</strong>
                                            </h4>
                                            @php($restaurantAddress = json_decode($order->restaurant->address))
                                            <p class="fs-6 mb-0">
                                                {{ isset($restaurantAddress->street) ? Str::ucfirst($restaurantAddress->street) : null }}
                                                {{ isset($restaurantAddress->city) ? Str::ucfirst($restaurantAddress->city) : null }}
                                                -
                                                {{ isset($restaurantAddress->pincode) ? Str::ucfirst($restaurantAddress->pincode) : null }}
                                            </p>
                                        </div>
                                    </div>
                                    <div>
                                        <span
                                            class="badge bg-info h-auto mb-2">{{Str::ucfirst($order->order_status)}}</span>
                                        @if($order->lovedOne)
                                            <span class="badge bg-warning text-dark h-auto mb-2 ms-1">❤️ Loved One</span>
                                        @endif
                                        <div>{{\Carbon\Carbon::parse($order->delivered)->format('h:i A') }}</div>
                                    </div>
                                </div>
                                <hr>
                                {{-- <div class="d-flex align-items-center"> --}}
                                    {{-- orderITems --}}
                                    <?php
                                        $customerOrderData = json_decode($order->orderCalculationStmt?->customerData);
                                    ?>
                                    @if($customerOrderData?->foodItemList != null)
                                    @foreach($customerOrderData->foodItemList as $key => $listItem)

                                    <div class="d-flex justify-content-between align-items-center py-2 ">
                                        <div class="text-start">
                                            <p class="mb-0 fw-semibold text-capitalize">{{ Str::ucfirst($listItem->foodName) }}</p>
                                        </div>
                                        <div class="text-end text-muted">
                                            <p class="mb-0">{{ $listItem->quantity }} <span class="text-sm">Qty</span></p>
                                        </div>
                                    </div>
                                    @endforeach
                                    @endif
                                {{-- </div> --}}
                                <div class="position-absolute px-2 py-1 rounded-bottom-3"
                                    style="top:0;right:100px;background-color:#6B47F3 !important;">
                                    <div class="text-white"><i class="fa-solid fa-user-pen me-2"></i>Order ID :
                                        #{{$order->id}}</div>
                                </div>
                                @if ($order->payment_method == 'cash')
                                <div class="position-absolute px-2 py-1 rounded-bottom-3"
                                    style="background:#FFD700;top:0;right:16px;">
                                    <div class="text-dark fw-bolder"><i class="fa-solid fa-indian-rupee-sign me-2"></i>{{Str::upper($order->payment_method)}}</div>
                                </div>
                                @else
                                <div class="position-absolute px-2 py-1 rounded-bottom-3"
                                    style="background:rgb(57 197 73) !important;top:0;right:16px;">
                                    <div class="text-dark fw-bolder"><i class="fa-solid fa-indian-rupee-sign me-2"></i>{{Str::upper($order->payment_method)}}</div>
                                </div>
                                @endif
                                <hr>
                                <div class="d-flex justify-content-between align-items-center py-1 ">
                                    <div class="text-start">
                                        <p class="mb-0 fw-semibold text-capitalize">Order Amount :</p>
                                    </div>
                                    <div class="text-end text-muted">
                                        <p class="mb-0">{{Helpers::format_currency($order->order_amount)}}
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center py-1 ">
                                    <div class="text-start">
                                        <p class="mb-0 fw-semibold text-capitalize">Collected :</p>
                                    </div>
                                    <div class="text-end text-muted">
                                        <p class="mb-0">{{Helpers::format_currency($order->cash_to_collect)}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        @if(count($orders) == 0)
                            <div class="live-order-details-box">
                                <div class="restaurant-fix position-relative mb-0">
                                    <!-- <img src="user.png" alt="User"> -->
                                    <div class="d-flex align-items-end justify-content-between">
                                        <div class="text-center">
                                            <p class="fs-6 mb-2"><strong>NO Orders</strong></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@push('javascript')
    <script>
        //dm order status acceptance
        document.querySelectorAll('[data-stage="pickedUp"]').forEach(element => {
            element.addEventListener('click', async () => {
                Swal.fire({
                    title: `Do you want to ${element.textContent} the Order?`,
                    showDenyButton: false,
                    showCancelButton: true,
                    confirmButtonText: element.dataset.stage.toUpperCase(),
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        try {
                            const resp = await fetch('{{route('deliveryman.admin.order-stage-changer')}}?order_id=' + element.dataset.orderId + "&stage=" + element.dataset.stage);
                            if (!resp.ok) {
                                const error = await resp.json();
                                throw new Error(error.message)
                            } else {
                                const data = await resp.json();
                                Swal.fire('Saved!', data.message, 'success');
                                location.reload();
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

    </script>

@endpush
