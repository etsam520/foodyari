@php
    $pageHead = "Live Orders";

    if ($filter == 'accepted') {
        $pageHead = "Accepted Orders";
    }
@endphp

<div class="live-order-details">
    <h6 class="text-center fw-bolder mb-0">{{$pageHead}}</h6>
</div>
@foreach ($orders as $order)
{{-- @dd($order)   --}}
@php($customer = $order->customer)
<div class="live-order-details-box" onclick="location.href = '{{route('deliveryman.admin.order',['order_id'=>$order->id])}}'">
    <div class="restaurant-fix position-relative mb-0">
        <!-- <img src="user.png" alt="User"> -->
        <div class="d-flex align-items-end justify-content-between mt-3">
            <div class="location-details mt-3">
                <div>
                    <p class="fs-6 mb-2"><strong>{{Str::ucfirst($order->restaurant->name)}}</strong></p>
                    @php($restaurantAddress = json_decode($order->restaurant->address))
                    <p class="fs-6 mb-0">
                        {{ isset($restaurantAddress->street) ? Str::ucfirst($restaurantAddress->street) : null }}
                        {{ isset($restaurantAddress->city) ? Str::ucfirst($restaurantAddress->city) : null }} -
                        {{ isset($restaurantAddress->pincode) ? Str::ucfirst($restaurantAddress->pincode) : null }}
                    </p>
                </div>
            </div>
            <div>
                <span class="badge ms-2 bg-info mb-2"> {{ __('messages.'.$order['order_status']) ??  __(str_replace('_', ' ', $order['order_status'])) }}</span>
                @if($order->lovedOne)
                    <span class="badge ms-1 bg-warning mb-2 text-dark">❤️ Loved One</span>
                @endif
                <div class="text-end">{{\Carbon\Carbon::parse($order->created_at)->format('h:i A') }}</div>
            </div>
        </div>
        <hr>
        <div class="d-flex">
            @php($deliveryAddress = json_decode($order->delivery_address)??null)
            <a href="https://www.google.com/maps/dir/?api=1&destination={{ $deliveryAddress->position->lat . ',' . $deliveryAddress->position->lon }}&travelmode=driving&maptype=satellite" class="bg-white px-2 py-2 border-0 shadow-sm rounded-3 text-warning me-2 w-50 text-center d-flex align-items-center justify-content-center">
                <div class="fs-4 me-2"><i class="fa-solid fa-location-dot"></i></div>
                                        <div class="fw-bolder">MAP</div>
            </a>

            <a href="tel:@if($order->lovedOne){{$order->lovedOne->phone}}@else{{$customer->phone}}@endif" class="bg-white px-2 py-2 border-0 shadow-sm rounded-3 text-warning me-2 w-50 text-center d-flex align-items-center justify-content-center">
                <div class="fs-4 me-2"><i class="fa-solid fa-phone"></i> </div>
                <div class="fw-bolder">CALL</div>
            </a>
        </div>
        <div class="position-absolute px-2 py-1 rounded-bottom-3 me-2 "
            style="top:0;right:100px;background-color:#6B47F3 !important;">
            <div class="text-white"><i class="fa-solid fa-user-pen me-2"></i>Order ID : #{{$order->id}}</div>
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
    </div>
</div>
@endforeach

{{-- <div class="live-order-details-box">
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
            <div class="text-white"><i class="fa-solid fa-indian-rupee-sign me-2"></i>UPI</div>
        </div>
    </div>
</div> --}}
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
