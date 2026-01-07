{{-- <div class="bg-white p-3 rounded-4"> --}}
    @foreach ($data['items'] as $key => $food)
    <div class="d-flex p-3 gold-members border-bottom">
        <div class="d-flex justify-content-between w-100 ms-3 ">
            <div class="d-flex gap-2 mb-0">
                <div>
                    <div class="d-flex">
                        {{-- <img alt="{{$food->type}}" src="{{ $food->type == 'veg' ? asset('assets/user/img/veg.png') : asset('assets/user/img/non-veg.png') }}" class="img-fluid food-type me-2"> --}}

                        <h6 class="mb-0 fw-bold">{{ Str::ucfirst($food['name']) }}</h6>

                    </div>
                    @if($food['packing_charge'] > 0)
                    <p>
                        <small class="text-muted">Excluding Packing Charge :{{Helpers::format_currency($food['packing_charge'])}} / Food Qty.</small>
                    </p>
                    @endif
                    <div class="text-start">
                        <span class="text-success mb-0 fs-6 fw-bolder">
                            {{ App\CentralLogics\Helpers::format_currency($food['amount']) }}</div>
                        </span>
                    </div>
                </div>

                <div class="ms-auto gold-members align-self-center">
                    <span class="product-count-number d-flex p-0" >
                        <button type="button"  data-increase-by="-1" class="btn-sm left dec btn px-2 py-0 border-0">
                            <i class="feather-minus"></i>
                        </button>
                        <input class="product-count-number-input w-100 border-0" type="text"
                            name="quantity" data-item-cart-id="{{$food['cart_item_id']}}"
                            data-item-index="{{$food['index']}}"
                            data-item-type="{{$food['item_type']}}"
                            data-item-position="{{$food['position']}}"
                            value="{{$food['quantity']}}"
                         >
                        <button type="button" class="btn-sm right inc btn px-2 py-0 border-0" data-increase-by="1" >
                            <i class="feather-plus"></i>
                        </button>
                    </span>
                </div>
            </div>
        </div>
    </div>
    @endforeach

{{-- </div> --}}


{{--  --}}
