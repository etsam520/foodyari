@extends('user-views.restaurant.layouts.main')
@php

$total_addon_price = 0;
$total_variation_price = 0;
$productTotal = 0;
$subtotal = 0;

@endphp

@section('containt')

<div class="container  position-relative" >
    <div class="row mt-3">
        <div class="col-md-8">
            <!-- body -->
            <section class="bg-white osahan-main-body rounded shadow-sm overflow-hidden">
                <div class="container p-0">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="osahan-status">
                                <!-- status complete -->
                                <div class="p-3 status-order bg-white border-bottom d-flex align-items-center">
                                    <p class="m-0">
                                        <i class="feather-calendar text-primary"></i>
                                        {{App\CentralLogics\Helpers::format_date($order->updated_at).' - '.App\CentralLogics\Helpers::format_time($order->updated_at->toTimeString())}}
                                    </p>
                                    <a href="" class="feather-printer text-primary ms-2 text-decoration-none "> Invoice</a>
                                    <a href="javascript:void(0)" class="text-primary ms-auto text-decoration-none">Review</a>
                                </div>
                                <div class="p-3 border-bottom">
                                    <h6 class="fw-bold">Order Status</h6>
                                    <div class="tracking-wrap">
                                        @if($order->order_status == 'pending')
                                        <div class="my-1 step active">
                                            <span class="icon text-warning"><i class="feather-alert-circle"></i></span>
                                            <span class="text small">Pending</span>
                                            <span class="text small">{{App\CentralLogics\Helpers::format_time($order->pending)}}</span>
                                        </div>
                                        @elseif ($order->order_status == 'confirmed')
                                        <!-- step.// -->
                                        <div class="my-1 step active">
                                            <span class="icon text-success"><i class="feather-check"></i></span>
                                            <span class="text small">Confirmed</span>
                                            <span class="text small">{{App\CentralLogics\Helpers::format_time($order->confirmed)}}</span>

                                        </div>
                                        @elseif ($order->order_status == 'accepted')
                                        <!-- step.// -->
                                        <div class="my-1 step active">
                                            <span class="icon text-success"><i class="feather-check"></i></span>
                                            <span class="text small">Accepted</span>
                                            <span class="text small">{{App\CentralLogics\Helpers::format_time($order->accepted)}}</span>

                                        </div>
                                        <!-- step.// -->
                                        @elseif($order->order_status == 'canceled')
                                        <div class="my-1 step">
                                            <span class="icon text-danger"><i class="feather-alert-triangle"></i></span>
                                            <span class="text small">Reject/Cancelled</span>
                                        </div>
                                        @elseif($order->order_status == 'processing')
                                        <div class="my-1 step">
                                            <span class="icon text-success"><i class="feather-triangle"></i></span>
                                            <span class="text small">Processing</span>
                                            <span class="text small">{{App\CentralLogics\Helpers::format_time($order->processing)}}</span>
                                            <span class="icon text-warning"><i class="feather-watch"></i></span>
                                            <span class="text small">{{$order->processing_time}} Minutes</span>
                                        </div>
                                        @elseif($order->order_status == 'handover')
                                        <div class="my-1 step">
                                            <span class="icon text-danger"><i class="feather-box"></i></span>
                                            <span class="text small">Hand Over</span>
                                            <span class="text small">{{App\CentralLogics\Helpers::format_time($order->handover)}}</span>

                                        </div>
                                        @elseif($order->order_status == 'picked_up')
                                        <?php
                                            $dmData = new App\Services\JsonDataService($order->delivery_man_id);
                                            $dmData = $dmData->readData();
                                        ?>
                                        <div class="my-1 step">
                                            <span class="icon text-danger"><i class="feather-truck"></i></span>
                                            <span class="text small">Picked Up</span>
                                            <span class="text small">{{App\CentralLogics\Helpers::format_time($order->handover)}}</span>
                                            <span class="text text-warning">Delivery Otp</span>
                                            <span class="text text-warning">{{$order->otp}}</span>
                                        </div>
                                        @elseif($order->order_status == 'delivered')
                                        <div class="my-1 step">
                                            <span class="icon text-success"><i class="feather-check-circle"></i></span>
                                            <span class="text small">Delivered</span>
                                            <span class="text small">{{App\CentralLogics\Helpers::format_time($order->delivered)}}</span>

                                        </div>
                                        @endif
                                        {{-- @dd($dmData) --}}

                                    </div>
                                </div>
                                <div class="p-3 border-bottom bg-white">
                                    @foreach ($order->details as $orderItem)
                                    <?php
                                    $itemDetails = json_decode($orderItem->food_details);
                                    $total_addon_price += $orderItem->addon_price;
                                    $total_variation_price += $orderItem->variation_price;
                                    $productTotal += $orderItem->price * $orderItem->quantity;
                                    ?>
                                    <div class="order-body">
                                        <div class="pb-3">
                                            <div class="p-3 rounded shadow-sm bg-white">
                                                <div class="d-flex border-bottom pb-3">
                                                    <div class="text-muted me-3">
                                                        <img alt="#" src="{{Helpers::getUploadFile($itemDetails->image,'product')}}" class="img-fluid order_img rounded">
                                                    </div>
                                                    <div>
                                                        <p class="mb-0 fw-bold"><a href="{{route('user.restaurant.get-restaurant',['name' => Str::slug($itemDetails->name)])}}" class="text-dark">{{Str::ucfirst($itemDetails->name)}}</a></p>

                                                        <p>Quantity : {{$orderItem->quantity}}</p>
                                                        {{-- <p class="mb-0 small"><a href="http://localhost:8080/foodyari_etsam/mess/my-order/32">View Details</a></p> --}}
                                                    </div>
                                                </div>

                                                @if (!empty(json_decode($orderItem->add_ons)))
                                                <div class="d-flex gap-2 p-3 border-bottom gold-members">
                                                    {{-- <img alt="#" src="{{ asset('assets/user/img/veg.png') }}" class="img-fluid package-img"> --}}
                                                    <div class="w-100">
                                                        <div class="d-flex gap-2 mb-2">
                                                            <a href="javascript:void(0)">
                                                                <h6 class="mb-1">Addons</h6>
                                                            </a>
                                                        </div>
                                                        <div class="border-top pt-2">
                                                            <div class="row">
                                                                <div class="col-4">
                                                                    <p class="text-fw-bold mb-0">Name</p>
                                                                </div>
                                                                <div class="col-4">
                                                                    <p class="text-fw-bold mb-0">Quantity </p>
                                                                </div>
                                                                <div class="col-4">
                                                                    <p class="text-fw-bold mb-0">Amount</p>
                                                                </div>
                                                                @foreach (json_decode($orderItem->add_ons) as $addon)
                                                                <div class="col-4">
                                                                    <p class="text-fw-bold mb-0">{{Str::ucfirst($addon->name)}}
                                                                    </p>
                                                                </div>
                                                                <div class="col-4">
                                                                    <p class="text-fw-bold mb-0"> {{$addon->qty}}
                                                                        <span class="text-muted mb-0"> </span>
                                                                    </p>
                                                                </div>
                                                                <div class="col-4">
                                                                    <p class="text-fw-bold mb-0">{{App\CentralLogics\Helpers::format_currency($addon->price)}}
                                                                        <span class="text-muted mb-0"> </span>
                                                                    </p>
                                                                </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                                @if($orderItem->variation)
                                                @foreach(json_decode($orderItem->variation) as $variation)
                                                <div class="d-flex gap-2 p-3 border-bottom gold-members">
                                                    <div class="w-100">
                                                        <div class="d-flex gap-2 mb-2">
                                                            <a href="javascript:void(0)">
                                                                <h6 class="mb-1">{{Str::ucfirst($variation->option)}}</h6>
                                                            </a>
                                                        </div>
                                                        <div class="border-top pt-2">
                                                            <div class="row">
                                                                <div class="col-4">
                                                                    <p class="text-fw-bold mb-0">Name</p>
                                                                </div>
                                                                <div class="col-4">
                                                                    <p class="text-fw-bold mb-0">Quantity</p>
                                                                </div>
                                                                <div class="col-4">
                                                                    <p class="text-fw-bold mb-0">Amount </p>
                                                                </div>
                                                                @foreach ($variation->values as $value)
                                                                <div class="col-4">
                                                                    <p class="text-fw-bold mb-0">{{Str::ucfirst($value->label)}} </p>
                                                                </div>
                                                                <div class="col-4">
                                                                    <p class="text-fw-bold mb-0"> {{$value->qty}}</p>
                                                                </div>
                                                                <div class="col-4">
                                                                    <p class="text-fw-bold mb-0">{{App\CentralLogics\Helpers::format_currency($value->price *$value->qty)}} </p>
                                                                </div>
                                                                @endforeach

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    @endforeach
                                </div>
                                @php($address =  json_decode($order->delivery_address))
                                <!-- Destination -->
                                <div class="p-3 border-bottom bg-white">
                                    <h6 class="fw-bold">Destination</h6>
                                    <p class="m-0 small">{{Str::ucfirst($address->stringAddress)}}</p>
                                </div>
                                <!-- Destination -->
                                <div class="p-3 bg-white">
                                    <div class="d-flex align-items-center mb-2">
                                        <h6 class="fw-bold mb-1">Total Cost</h6>
                                        <h6 class="fw-bold ms-auto mb-1"> {{App\CentralLogics\Helpers::format_currency($order->order_amount)}}</h6>
                                    </div>
                                    <p class="m-0 small text-muted">You can check your order detail here,<br>Thank you for order.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <div class="col-md-4">
            <!-- body -->

            {{-- @dd($payment) --}}
            <section class="bg-white osahan-main-body rounded shadow-sm overflow-hidden">
                <div class="container p-0">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="osahan-status">
                                <!-- status complete -->
                                <div class="p-3 status-order bg-white border-bottom pb-4 ">
                                    <a href="review.html" class="text-primary float-end ms-auto text-decoration-none">Invoice</a>
                                </div>
                                <div class="p-3 border-bottom">
                                    <h6 class="fw-bold">Payment Details &nbsp;
                                        @if($order->payment_status == 'paid')
                                        <span class="badge bg-success">{{Str::ucfirst($order->payment_status)}}</span>
                                        @elseif ($order->payment_status=='unpaid')
                                        <span class="badge bg-primary">{{Str::ucfirst($order->payment_status)}}</span>
                                        @endif
                                    </h6>
                                    <?php
                                        $subtotal = $productTotal + $total_variation_price + $total_addon_price;
                                    ?>

                                    <div class="bg-white p-3 clearfix border-bottom">
                                        @if ($total_addon_price > 0)
                                        <p class="mb-1">Addon Cost <span class="float-end">{{ App\CentralLogics\Helpers::format_currency($total_addon_price)}}</span></p>
                                        @endif
                                        @if ($total_variation_price > 0)
                                        <p class="mb-1">Vatiations Cost <span class="float-end">{{ App\CentralLogics\Helpers::format_currency($total_variation_price)}}</span></p>
                                        @endif
                                        <hr>

                                        <p class="mb-1">Sub Total <span class="float-end">{{ App\CentralLogics\Helpers::format_currency($subtotal)}}</span></p>
                                        <p class="mb-1">Discount <span class="float-end">{{ App\CentralLogics\Helpers::format_currency($order->restaurant_discount_amount)}}</span></p>
                                        {{-- <p class="mb-1">Coupon Discount <span class="float-end">{{ App\CentralLogics\Helpers::format_currency($payment->coupon_discount)}}</span></p> --}}
                                        <p class="mb-1">Delivery Fee <span class="text-info ms-1"><span
                                                    class="float-end text-dark">{{ App\CentralLogics\Helpers::format_currency($order->delivery_charge)}}</span></p>
                                        <p class="mb-1">GST <span class="float-end">{{ App\CentralLogics\Helpers::format_currency($order->total_tax_amount)}}</span></p>
                                        <p class="mb-1">Deliveryman Tip <span class="float-end">{{ App\CentralLogics\Helpers::format_currency($order->dm_tips)}}</span></p>

                                        <hr>
                                        <h6 class="mb-0">Total <span class="float-end">{{ App\CentralLogics\Helpers::format_currency($order->order_amount)}}</span></h6>

                                        <hr>
                                        <h6 class="fw-bold mb-0">Payment Method <small class="float-end badge bg-primary">{{Str::ucfirst($order->payment_method)}}</small></h6>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 py-2 mx-auto ">
                            <div id="map-canvas2" style=" width:100%;height: 50vh"></div>
                        </div>
                    </div>
                </div>
            </section>
        </div>


    </div>
</div>

@endsection

@push('javascript')
@if($order->order_status == 'picked_up')
<script src="{{ asset('assets/js/Helpers/mapHelperClass.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const map2 = new CreateMap();

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition((position) => {
                const currentLocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };

                const dmPosition = {
                    lat: {{$dmData->last_location['lat']}},
                    lng: {{$dmData->last_location['lng']}}
                };

                map2.createMap(currentLocation, {
                    selector: "#map-canvas2",
                    mapClick: false,
                    mapDrag: false
                });

                map2.map.setCenter({
                    lat: (currentLocation.lat + dmPosition.lat) / 2,
                    lng: (currentLocation.lng + dmPosition.lng) / 2
                });

                const customerMarker  = map2.makeMarker(currentLocation, false);
                const  dmMarker= map2.makeMarker(dmPosition, false);

                const path = new google.maps.Polyline({
                    path: [currentLocation, dmPosition],
                    geodesic: true, // Make the line follow the curvature of the Earth
                    strokeColor: '#FF0000',
                    strokeOpacity: 1.0,
                    strokeWeight: 2
                });

                path.setMap(map2.map);

                const distance = google.maps.geometry.spherical.computeDistanceBetween(
                    new google.maps.LatLng(currentLocation.lat, currentLocation.lng),
                    new google.maps.LatLng(dmPosition.lat, dmPosition.lng)
                );

                const distanceInKm = (distance / 1000).toFixed(2);
                const infowindow = new google.maps.InfoWindow({
                    content: `Distance: ${distanceInKm} km`
                });

                infowindow.setPosition({
                    lat: (currentLocation.lat + dmPosition.lat) / 2,
                    lng: (currentLocation.lng + dmPosition.lng) / 2
                });

                infowindow.open(map2.map);
            });
        } else {
            console.error("Geolocation is not supported by this browser.");
        }
    });
    </script>
@endif

@endpush

