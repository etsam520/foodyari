<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Best Food Providing Meal">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="Givni Private Limited">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/icons/foodYariLogo.png') }}">
    <title>Foodyari</title>
    <link href="{{ asset('assets/user/vendor/slick/slick/slick.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/user/vendor/slick/slick/slick-theme.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/user/vendor/icons/feather.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/user/vendor/bootstrap/css/bootstrap.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/user/css/style.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/user/css/custom.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/user/vendor/sidebar/demo.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/user/css/restaurant.css') }}" rel="stylesheet" type="text/css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/vendor/toastr/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/toastr/toastr.min.css') }}">
    <style>
    /* .timeline-container {
        padding: 20px;
        background-color: #fff;
        border-radius: 10px;
        max-width: 300px;
        margin: auto;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    } */

    .timeline {
        position: relative;
        padding-left: 6px;
        margin: 0;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 10px;
        top: 0;
        bottom: 0;
        width: 2px;
        background-color: #80808045;
    }

    .timeline-item {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }

    .timeline-item .dot {
        width: 12px;
        height: 12px;
        background-color: #ff810a;
        border-radius: 50%;
        position: relative;
        z-index: 1;
    }

    .timeline-item .content {
        margin-left: 20px;
    }

    .timeline-item h4 {
        margin: 0;
        font-size: 16px;
        color: #333;
    }

    .timeline-item p {
        margin: 5px 0 0;
        font-size: 12px;
        color: #777;
    }
</style>
</head>
<body class="fixed-bottom-bar">
@php
// dd($order);
$total_addon_price = 0;
$total_variation_price = 0;
$productTotal = 0;
$subtotal = 0;
$invoiceItemsList = [];
$deliveryAddress = json_decode($order->delivery_address);
$coupon_discount_amount = $order->coupon_discount_amount??0;
foreach ($order->details as $orderItem){
    $itemDetails = json_decode($orderItem->food_details);
    $total_addon_price += $orderItem->addon_price;
    $total_variation_price += $orderItem->variation_price;
    $productTotal += $orderItem->price * $orderItem->quantity;

    if($itemDetails->isCustomize != 1){
        $invoiceItemsList[] =[
            'name' => $itemDetails->name,
            'quantity' => $orderItem->quantity,
            'price' => $orderItem->quantity * $orderItem->price ,
        ];
    }else{
        foreach(json_decode($orderItem->variation) as $variation){
            // $variation->option;
            foreach ($variation->values as $value){
                $invoiceItemsList[] = [
                'name' =>$itemDetails->name." ($value->label)",
                'quantity' =>$value->qty,
                'price' => $value->price * $value->qty ,
            ];
            }
        }
    }

    foreach (json_decode($orderItem->add_ons) as $addon){
        $invoiceItemsList[] = [
            'name' =>$addon->name,
            'quantity' => $addon->qty,
            'price' => $addon->price * $addon->qty ,
        ];
    }
}
@endphp

{{-- @dd($deliveryAddress) --}}

<div class="osahan-profile">
    <!-- profile -->
    <div class="container position-relative">
        <div class="py-5 osahan-profile row d-flex justify-content-center">
            <div class="col-md-8 mb-3">
                <div class="bg-white rounded shadow-sm sticky_sidebar overflow-hidden">
                    <div class="osahan-credits d-flex align-items-center p-3 bg-white">
                        <h5 class="m-0  text-primary">Order ID : #{{$order->id}}</h5>
                        @if($order->order_status != "delivered")
                        <h6 class="m-0 ms-auto text-primary">OTP : {{$order->otp}}</h6>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-8 mb-3">
                <div class="mapouter">
                     <div id="map-canvas2" style=" width:100%;height: 50vh"></div>
                </div>
                {{-- @dd($order); --}}
                @if($order->delivery_man)
                <div class="rounded shadow-sm mt-4">
                    <div class="osahan-cart-item-profile bg-white rounded shadow-sm p-4">
                        <a href="javascript:void(0)" class="">
                            <div class="d-flex align-items-center">
                                <div class="right">
                                    <h5 class="mb-1 fw-bold text-dark">{{Str::ucfirst($order->delivery_man->f_name)}} {{Str::ucfirst($order->delivery_man->l_name??null)}}  <i
                                            class="feather-check-circle text-success"></i></h5>
                                    <div class="d-flex">
                                        <div class="bg-success text-white rounded px-2 me-1">
                                            <p class="mb-0 text-white py-1" style="font-size: 15px;"><i
                                                    class="feather-star star_active me-2"></i>5.0</p>
                                        </div>
                                        {{-- <p class="text-muted align-self-center m-0">(367 Orders Delivered)</p> --}}
                                    </div>
                                </div>
                                <div class="left ms-auto w-25">
                                    <img alt="delivery-man" src="{{$order->delivery_man->image?asset('delivery-man/'.$order->delivery_man->image) : null}}" class="rounded-circle w-100">
                                </div>
                            </div>
                        </a>
                        <hr>
                        <div class="text-end">
                            <a href="tel:{{$order->delivery_man->phone}}" class="badge-two px-3 py-2 fw-bolder fs-6">Call</a>
                            <a href="javascript:void(0)" class="badge-two px-3 py-2 fw-bolder fs-6">Message</a>
                        </div>
                    </div>
                </div>
                @endif
                <div class="rounded shadow-sm mt-4">
                    <div class="osahan-cart-item-profile bg-white rounded shadow-sm p-4">
                        <div class="flex-column">
                            <h5 class="fw-bolder mb-4">History</h5>
                            <div class="timeline-container">
                                <div class="timeline">
                                    @if($order->order_status == 'delivered')
                                    <div class="timeline-item">
                                        <div class="dot"></div>
                                        <div class="content">
                                            <h4>Delivered <i class="feather-check-circle text-success"></i></h4>
                                            <p>{{App\CentralLogics\Helpers::format_time($order->delivered)}}</p>
                                        </div>
                                    </div>
                                    @endif
                                    @if($order->order_status == 'canceled')
                                    <div class="timeline-item">
                                        <div class="dot"></div>
                                        <div class="content">
                                            <h4>Reject/Cancelled <i class="feather-alert-triangle"></i></h4>
                                        </div>
                                    </div>
                                    @endif
                                    @if($order->order_status == 'picked_up')
                                    <div class="timeline-item">
                                        <div class="dot"></div>
                                        <div class="content">
                                            <h4>Your Order on Delivery by Courier</h4>
                                        </div>
                                    </div>
                                    @endif
                                    @if($order->order_status == 'handover')
                                    <div class="timeline-item">
                                        <div class="dot"></div>
                                        <div class="content">
                                            <h4>Driver Arrived at Restaurant</h4>
                                        </div>
                                    </div>
                                    @endif
                                    @if($order->order_status == 'processing')
                                    <div class="timeline-item">
                                        <div class="dot"></div>
                                        <div class="content">
                                            <h4>Preparing Your Order</h4>
                                        </div>
                                    </div>
                                    @endif
                                    @if(!empty($order->confirmed) && ($order->status != 'canceled'))
                                    <div class="timeline-item">
                                        <div class="dot"></div>
                                        <div class="content">
                                            <h4> Order Confirmed <i class="feather-check text-primary"></i></h4>
                                            <p>{{App\CentralLogics\Helpers::format_time($order->confirmed)}}</p>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="timeline-item">
                                        <div class="dot"></div>
                                        <div class="content">
                                            <h4>Placed Order</h4>
                                            <p>{{App\CentralLogics\Helpers::format_time($order->pending)}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="rounded shadow-sm mt-4">
                    <div class="osahan-cart-item-profile bg-white rounded shadow-sm p-4">
                        <div class="w-100">
                            <div class="d-flex gap-2 mb-2">
                                <a href="javascript:void(0)">
                                    <h6 class="mb-1">Items</h6>
                                </a>
                            </div>
                            <div class="border-top pt-2">
                                <div class="row">
                                    <div class="col-2">
                                        <p class="text-fw-bold mb-0">SI. </p>
                                    </div>
                                    <div class="col-4">
                                        <p class="text-fw-bold mb-0">Name</p>
                                    </div>
                                    <div class="col-2">
                                        <p class="text-fw-bold mb-0">Quantity </p>
                                    </div>
                                    <div class="col-4">
                                        <p class="text-fw-bold mb-0">Amount</p>
                                    </div>
                                    @foreach ($invoiceItemsList as $key => $listItem)
                                    <div class="col-2">
                                        <p class="text-fw-bold mb-0">{{$key+1}}
                                        </p>
                                    </div>
                                    <div class="col-4">
                                        <p class="text-fw-bold mb-0">{{Str::ucfirst($listItem['name'])}}
                                        </p>
                                    </div>
                                    <div class="col-2">
                                        <p class="text-fw-bold mb-0"> {{$listItem['quantity']}}
                                            <span class="text-muted mb-0"> </span>
                                        </p>
                                    </div>
                                    <div class="col-4">
                                        <p class="text-fw-bold mb-0">{{App\CentralLogics\Helpers::format_currency($listItem['price'])}}
                                            <span class="text-muted mb-0"> </span>
                                        </p>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="rounded shadow-sm mt-4">
                    <div class="osahan-cart-item-profile bg-white rounded shadow-sm p-4">
                        <div class="flex-column">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="osahan-status">
                                        <!-- status complete -->
                                        <div class="p-3 status-order bg-white border-bottom pb-4 ">
                                            <a href="{{route('user.restaurant.order-invoice', ['order_id'=>$order->id])}}" class="text-primary float-end ms-auto text-decoration-none">Invoice</a>
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

                                                <hr>

                                                <p class="mb-1">Sub Total <span class="float-end">{{ App\CentralLogics\Helpers::format_currency($subtotal)}}</span></p>
                                                <p class="mb-1">Discount <span class="float-end">{{ App\CentralLogics\Helpers::format_currency($order->restaurant_discount_amount)}}</span></p>
                                                @if($coupon_discount_amount > 0)
                                                <p class="mb-1">Coupon Discount <span class="float-end">{{ App\CentralLogics\Helpers::format_currency($coupon_discount_amount)}}</span></p>
                                                @endif
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

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script type="text/javascript" src="{{ asset('assets/user/vendor/jquery/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/user/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/user/vendor/slick/slick/slick.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/user/vendor/sidebar/hc-offcanvas-nav.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/user/js/osahan.js') }}"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/js/all.min.js"></script>
    <script src="{{ asset('assets/vendor/toastr/toastr.min.js') }}"></script>
    <script src="{{asset('assets/vendor/sweetalert2/sweetalert2@11.js')}}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBPVVxuVkmdE1GzSgIqm_9dx64tea7Wltk&libraries=geometry,places"></script>
    <script src="{{ asset('assets/js/Helpers/custom.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.28.0/feather.min.js"></script>
    <script>
        feather.replace(); // This is crucial for Feather Icons to work
    </script>

    @if (Session::has('success'))
        <script>
            toastr.success('{{ Session::get('success') }}');
        </script>
    @endif
    @if (Session::has('info'))
        <script>
            toastr.info('{{ Session::get('info') }}');
        </script>
    @endif

    @if (Session::has('error'))
        <script>
            toastr.info('{{ Session::get('error') }}');
        </script>
    @endif
    @if (Session::has('warning'))
        <script>
            toastr.warning('{{ Session::get('warning') }}');
        </script>
    @endif
<script src="{{ asset('assets/js/Helpers/mapHelperClass.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const map2 = new CreateMap();
        let previousDmPosition = null; // Keep track of the delivery man's previous position

        const currentLocation = {
            lat: {{$deliveryAddress->position->lat}} ,
            lng: {{$deliveryAddress->position->lon}}
        };

        const restaurantPosition = {
            lat: {{$order->restaurant->latitude}},
            lng: {{$order->restaurant->longitude}}
        };

        // Create the map with custom styles
        map2.createMap(currentLocation, {
            selector: "#map-canvas2",
            mapClick: false,
            mapDrag: false,
            styles: [
                { elementType: 'geometry', stylers: [{ color: '#ebe3cd' }] },
                { elementType: 'labels.text.fill', stylers: [{ color: '#523735' }] },
                { elementType: 'labels.text.stroke', stylers: [{ color: '#f5f1e6' }] },
                { featureType: 'water', elementType: 'geometry', stylers: [{ color: '#c9c9c9' }] }
            ]
        });

        // Create markers for customer and restaurant
        const customerMarker = map2.makeMarker(currentLocation, "{{asset('assets/user/img/icons/marker-icon.png')}}", false);
        const restaurantMarker = map2.makeMarker(restaurantPosition, "{{asset('assets/user/img/icons/restaurant-map-icon.png')}}", false);

        // Center the map between customer and restaurant
        map2.map.setCenter({
            lat: (currentLocation.lat + restaurantPosition.lat) / 2,
            lng: (currentLocation.lng + restaurantPosition.lng) / 2
        });
        map2.map.setZoom(12); // Set a fixed zoom level

        // Directions service and renderer for restaurant to customer
        const directionsService = new google.maps.DirectionsService();
        const directionsRenderer = new google.maps.DirectionsRenderer({
            suppressMarkers: true,
            polylineOptions: {
                strokeColor: '#0000FF',
                strokeOpacity: 1.0,
                strokeWeight: 6
            }
        });

        directionsRenderer.setMap(map2.map);

        // Calculate and display the route from restaurant to customer
        directionsService.route({
            origin: restaurantPosition,
            destination: currentLocation,
            travelMode: google.maps.TravelMode.DRIVING
        }, (response, status) => {
            if (status === google.maps.DirectionsStatus.OK) {
                directionsRenderer.setDirections(response);

                // Extract travel time and display it
                const travelTime = response.routes[0].legs[0].duration.text;
                const infowindow = new google.maps.InfoWindow({
                    content: `<div style="text-align: center;">
                                <b>Restaurant: {{$order->restaurant->name}}<br>Distance: ${response.routes[0].legs[0].distance.text}<br>Travel Time: ${travelTime}</b>
                                </div>`
                });

                restaurantMarker.addListener('click', () => {
                    infowindow.open(map2.map, restaurantMarker);
                });
            } else {
                console.error('Directions request failed due to ' + status);
            }
        });

        // Function to update delivery man's position every 10 seconds
        const updateDeliveryManPosition = async () => {
            try {
                const response = await fetch(`{!! route('user.restaurant.dm-postion',['order_id'=>$order->id]) !!}`);
                if (!response.ok) {
                    throw new Error('Failed to fetch delivery man position');
                }

                const result = await response.json();
                console.log('Delivery man position:', result);

                if (result.lat && result.lng) {
                    const newDmPosition = { lat: result.lat, lng: result.lng };

                    // Check if delivery man's position has changed significantly
                    if (!previousDmPosition || distanceBetween(previousDmPosition, newDmPosition) > 0.01) {
                        map2.map.setCenter({
                            lat: (currentLocation.lat + newDmPosition.lat) / 2,
                            lng: (currentLocation.lng + newDmPosition.lng) / 2
                        });
                        previousDmPosition = newDmPosition; // Update the previous position
                    }


                    // Create or update the delivery man marker
                    const dmMarker = map2.makeMarker(newDmPosition, "{{asset('assets/user/img/icons/deliveryman-map-icon.png')}}", false);

                    // Directions renderer for delivery man to customer
                    const dmDirectionsRenderer = new google.maps.DirectionsRenderer({
                        suppressMarkers: true,
                        polylineOptions: {
                            strokeColor: '#FFA500',
                            strokeOpacity: 1.0,
                            strokeWeight: 6
                        }
                    });

                    dmDirectionsRenderer.setMap(map2.map);

                    // Calculate and display the route from delivery man to customer
                    directionsService.route({
                        origin: newDmPosition,
                        destination: currentLocation,
                        travelMode: google.maps.TravelMode.DRIVING
                    }, (response, status) => {
                        if (status === google.maps.DirectionsStatus.OK) {
                            dmDirectionsRenderer.setDirections(response);

                            // Extract delivery man's travel time and distance
                            const dmTravelTime = response.routes[0].legs[0].duration.text;
                            const dmDistance = response.routes[0].legs[0].distance.text;
                            const dmInfowindow = new google.maps.InfoWindow({
                                content: `<div style="text-align: center;">
                                            <b>Delivery Man: ${result.name}<br>Distance: ${dmDistance}<br>Travel Time: ${dmTravelTime}</b>
                                            </div>`
                            });

                            // Add a click listener to the delivery man marker
                            dmMarker.addListener('click', () => {
                                dmInfowindow.open(map2.map, dmMarker);
                            });
                        } else {
                            console.error('Directions request failed for delivery man due to ' + status);
                        }
                    });
                } else {
                    console.error('Invalid delivery man position data');
                }
            } catch (error) {
                console.error('Error updating delivery man position:', error);
            }
        };

        // Update delivery man position every 10 seconds
        @if($order->order_status != 'canceled' && $order->order_status != 'delivered')
            setInterval(updateDeliveryManPosition, 10000); // 10 seconds
        @endif

    });

    function distanceBetween(point1, point2) {
        const latDiff = point1.lat - point2.lat;
        const lngDiff = point1.lng - point2.lng;
        return Math.sqrt(latDiff * latDiff + lngDiff * lngDiff);
    }

</script>


</body>

</html>
