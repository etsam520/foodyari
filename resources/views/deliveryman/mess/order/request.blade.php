<div class="osahan-filter">
    <div class="filter">
        <p class="h1 text-primary text-center"><i class="fas fa-clipboard-check"></i></p>
        <h6 class="text-center">New Order Request </h6>
        @foreach ($orders as $order)  
        {{-- @dd($order)   --}}
        @php($customer = $order->customerSubscriptionTxns->customer)

        <div class="row">
            <div class="col-9 mb-0">
                {{-- <p class="mb-0"> <i class="feather-clipboard"></i>item</p> --}}
                <p class="mb-0"> <i class="feather-user"></i>{{Str::ucfirst($customer->f_name)}} {{Str::ucfirst($customer->l_name)}}</p>
                <p class="mb-0"> <i class="feather-map-pin"></i>{{Str::ucfirst($order->customerSubscriptionTxns->delivery_address)}}</p>
            </div>
            <div class="col-3 p-2">
                <button type="button" data-coupon-id="{{$order->id}}" data-acceptance="accept" class="btn btn-primary rounded-pill btn-sm w-100 mb-2 py-1">Accept</button>
                <button type="button" class="btn btn-secondary rounded-pill border-top btn-sm  w-100 py-1"
                data-coupon-id="{{$order->id}}" data-acceptance="reject">Reject</button>
            </div>
        </div>
        <hr>
        @endforeach
        @if(count($orders) == 0)
        <p class="mb-0 text-center text-white p-2 bg-primary">NO Orders</p>
        @endif
     
        <div class="row mt-3">
            <div class="col-6">
                <button type="button" class="btn btn-secondary border-top btn-lg w-100 py-1"
                    data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>