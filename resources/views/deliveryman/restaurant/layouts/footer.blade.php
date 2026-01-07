

<div class="bg-white container-fluid fixed-bottom">
    <div class="row">
        <div class="col-3 text-center" onclick="location.href='{{route('deliveryman.restaurant.order-list',['state'=>'newly'])}}'" type="button">
            <h1 class="text-warning counter mb-0 mt-1" style="visibility: visible;"><i class="fas fa-bell"></i></h1>
            <p class="text-warning mb-1">New Order</p>
        </div>
        <div class="col-3 text-center" onclick="location.href='{{route('deliveryman.restaurant.order-list',['state'=>'accepted'])}}'" type="button">
            <h1 class="text-warning counter mb-0 mt-1" style="visibility: visible;"><i class="fas fa-clipboard-check"></i></h1>
            <p class="text-warning mb-1">Accepted</p>
        </div>
        <div class="col-3 text-center" onclick="location.href='{{route('deliveryman.restaurant.order-list',['state'=>'pickedUp'])}}'" type="button">
            <h1 class="text-warning counter mb-0 mt-1" style="visibility: visible;"><i class="fas fa-heart"></i></h1>
            <p class="text-warning mb-1">Picked Up</p>
        </div>
        <div class="col-3 text-center">
            <h1 class="text-warning counter mb-0 mt-1" style="visibility: visible;"><i class="fas fa-lock"></i></h1>
            <p class="text-warning mb-1">Account</p>
        </div>
    </div>
</div>   

