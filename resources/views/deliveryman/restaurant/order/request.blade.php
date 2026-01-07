<div class="osahan-filter">
    <div class="filter">
        <p class="h1 text-primary text-center"><i class="fas fa-clipboard-check"></i></p>
        <h6 class="text-center">New Order Request </h6>
        @foreach ($orders as $order)  
        {{-- @dd($order)   --}}
        @php($customer = $order->customer)

        <div class="row">
            <div class="col-6 mb-0">
                {{-- <p class="mb-0"> <i class="feather-clipboard"></i>item</p> --}}
                <p class="mb-0"> <i class="feather-user"></i>
                    @if($order->lovedOne)
                        {{$order->lovedOne->name}}
                        <small class="badge bg-warning text-dark ms-1">❤️ Loved One</small>
                    @else
                        {{Str::ucfirst($customer->f_name??'')}} {{Str::ucfirst($customer->l_name??'')}}
                    @endif
                </p>
                @php($address = json_decode($order->delivery_address))
                <a class="mb-0 d-flex flex-row" src='http://maps.google.com/maps?z=12&amp;t=m&amp;q=loc:{{$address->latitude ?? ''}}+{{$address->longitude ?? ''}}'>
                    <i class="feather-map-pin "></i>
                    <div class="d-flex flex-column pb-0">
                        <p class="mb-0 pb-0">
                            @if($address->contact_person_name)
                                {{ Str::ucfirst($address->contact_person_name) }}
                            @elseif($order->lovedOne)
                                {{ $order->lovedOne->name }}
                            @else
                                {{ Str::ucfirst($customer->f_name??'') }} {{ Str::ucfirst($customer->l_name??'') }}
                            @endif
                        </p>
                        <p class="mb-0 pb-0">
                            @if($address->contact_person_number)
                                {{ $address->contact_person_number }}
                            @elseif($order->lovedOne)
                                {{ $order->lovedOne->phone }}
                            @else
                                {{ $customer->phone }}
                            @endif
                        </p>
                        <p class="mb-0 pb-0">{{ Str::ucfirst($address->address ?? '') }}</p>
                    </div>
                </a>
            </div>
            <div class="col-3">
                {{-- <p class="mb-0">{{Str::ucfirst()}}</p> --}}
            </div>
            <div class="col-3 p-2">
                <button type="button" data-order-id="{{$order->id}}" data-acceptance="accept" class="btn btn-primary rounded-pill btn-sm w-100 mb-2 py-1">Accept</button>
                <button type="button" class="btn btn-secondary rounded-pill border-top btn-sm  w-100 py-1"
                data-order-id="{{$order->id}}" data-acceptance="reject">Reject</button>
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