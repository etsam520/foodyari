 <!-- STICKY sTAR  -->
@php
     $deliveryManId = auth('delivery_men')->user()->id;
$today = Carbon\Carbon::now()->toDateString();

// Accepted Orders Query
$accepted = \App\Models\Order::whereDate('created_at', $today)
    ->where('delivery_man_id', $deliveryManId)
    ->whereNull('delivered')
    ->whereNull('canceled')
    ->where(function($query) {
        $query->whereNotNull('accepted')
              ->orWhere('order_status', 'accepted')
              ->orWhere('order_status', 'picked_up')
              ->orWhere('order_status', 'arrived_at_door')
              ->orWhere('order_status', 'dm_at_restaurant')
              ->orWhere('order_status', 'order_on_way');
    })
    ->count();

// Picked-Up Orders Query
$picked_up = \App\Models\Order::whereDate('created_at', $today)
    ->where('delivery_man_id', $deliveryManId)
    ->whereNull('delivered')
    ->whereNull('canceled')
    ->where(function($query) {
        $query->whereNotNull('picked_up')
              ->orWhere('order_status', 'picked_up')
              ->orWhere('order_status', 'arrived_at_door')
              ->orWhere('order_status', 'dm_at_restaurant')
              ->orWhere('order_status', 'order_on_way');
    })
    ->count();

@endphp

 <div class="osahan-menu-fotter fixed-bottom bg-white px-3 py-2 text-center">
    <div class="d-flex justify-content-evenly">
        <a href="{{route('deliveryman.dashboard')}}" class="text-dark fw-bolder text-decoration-none position-relative">
            <p class="fw-bolder fs-4 m-0"><i class="fa-solid fa-home"></i></p>
            Home
        </a>
        <div style="border-left:1px solid #80808066;"></div>
        <a href="{{route('deliveryman.admin.order-list',['state'=>'newly'])}}" class="text-dark fw-bolder text-decoration-none position-relative">
            <div class="fw-bolder fs-4 m-0"><i class="fa-solid fa-utensils"></i></div>
            New Order
            <span class="position-absolute top-0 start-100 translate-middle border border-light d-flex align-items-center justify-content-center"
                style="background-color:#ff810a;height:40px;width:40px; border-radius: 50%;">
                <span class="text-white" id="setCurrentOrders">
                    <script>
                    const setCurrentOredrs = document.getElementById('setCurrentOrders');
                    cookieStore.get('current_orders_count').then(result => {
                        setCurrentOredrs.textContent = result.value;
                        return true })
                        .catch(error => {
                            setCurrentOredrs.textContent = 0;return false;
                        });
                    </script>
                </span>
            </span>
        </a>
        <div style="border-left:1px solid #80808066;"></div>
        <a href="{{route('deliveryman.admin.order-list',['state'=>'accepted'])}}" class="text-dark fw-bolder text-decoration-none position-relative">
            <p class="fw-bolder fs-4 m-0"><i class="fa-solid fa-clipboard-check"></i></p>
            Accepted
            <span class="position-absolute top-0 start-100 translate-middle border border-light d-flex align-items-center justify-content-center"
            style="background-color:#ff810a;height:40px;width:40px; border-radius: 50%;">
                <span class="text-white">
                   {{$accepted??0}}
                </span>
            </span>
        </a>
        <div style="border-left:1px solid #80808066;"></div>
        <a href="{{route('deliveryman.admin.order-list',['state'=>'pickedUp'])}}" class="text-dark fw-bolder text-decoration-none position-relative">
            <p class="fw-bolder fs-4 m-0"><i class="fa-solid fa-person-biking"></i></p>
            Picked Up
            <span class="position-absolute top-0 start-100 translate-middle border border-light d-flex align-items-center justify-content-center"
            style="background-color:#ff810a;height:40px;width:40px; border-radius: 50%;">
                <span class="text-white">
                    {{$picked_up??0}}
                </span>
            </span>
        </a>
    </div>
</div>
<!-- STICKY BOTTOM  END-->
