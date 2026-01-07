<div class="d-flex justify-content-between py-3 rounded-4 px-3 bg-dark live-order">
    <a href="{{route('user.restaurant.order-trace',['order_id' => $order->id])}}" class="d-flex">

        <div class="bg-white align-self-center p-2 rounded-1 me-3">
            <i class="fa fa-cutlery text-dark fs-2" aria-hidden="true"></i>
        </div>
        <div>
            <div class="text-white fw-bolder mb-0 align-self-center ordered-restaurant-truncate">{{Str::ucfirst($order->restaurant->name)}}</div>
            <div class="text-white fw-bolder mb-0 align-self-center">
                {{-- <span class="border-end pe-2">Preparing your order </span> --}}
                <span class="border-end pe-2">
                    @if($order->order_status == "pending")
                        Live Order
                    @elseif ($order->order_status == "dm_at_restaurant")
                        Delivery Man At Restaurant
                    @elseif ($order->order_status == "order_on_way")
                        Order On Way
                    @elseif ($order->order_status == "arrived_at_door")
                        Deliveryman Arrived
                    @else
                    {{Str::ucfirst($order->order_status)}}
                    @endif

                </span>
                {{-- <span class="ps-2">Pay </span> --}}

            </div>
        </div>
        <div class="d-flex">
            <a href="#" id="countdownButton" class="btn btn-success fw-bolder">
                Arriving in<br><span id="countdownTimer">53 mins</span>
            </a>>
        </div>
    </a>

</div>
