<div class="bg-white p-3 rounded-4">
    @foreach ($foods as $food)
    @php
        $customize = count(json_decode($food->add_ons)) > 0 || count(json_decode($food->variations)) > 0;
        $cartedItem = null;
        if ($cart) {
            foreach ($cart as $c) {
                if ($c['product_id'] == $food->id) {
                    $cartedItem = $c;
                    break;
                }
            }
        }
    @endphp
    <div class="d-flex py-2 gold-members border-bottom">
        <img alt="{{ Str::ucfirst($food->name) }}" src="{{ $food->image ? asset('product/'.$food->image) : asset('product/default-food.png') }}" class="img-fluid product-img">
        <div class="w-100 ms-3">
            <div class="d-flex gap-2 mb-2">
                <div>
                    <h6 class="mb-1 fw-bold">{{ Str::ucfirst($food->name) }}
                    </h6>
                    <div class="text-start">
                        <span class="text-danger mb-0 small"> <strike>{{ App\CentralLogics\Helpers::format_currency($food->price) }}</strike></span>
                        <span class="text-success mb-0 fs-6 fw-bolder">
                            {{ App\CentralLogics\Helpers::format_currency(floor(App\CentralLogics\Helpers::food_discount($food->price, $food->discount, $food->discount_type))) }}</div>
                        </span>
                    </div>
                    <div class="d-flex">
                        <div class="text-danger mb-0 align-self-center text-nowrap me-2"> <strike>
                                </strike></div>
                        <div class="text-success mb-0 fs-5 fw-bolder ms-lg-2 ms-0 text-nowrap">
                    </div>
                </div>
                <div class="ms-auto gold-members">
                    <div class="d-flex justify-content-end mb-2">
                        <div class="text-success me-2">@if($food->type == 'veg') Veg @elseif ($food->type == 'non veg') Non Veg @else Veg | Non veg  @endif</div>
                        <img alt="{{$food->type}}" src="{{ $food->type == 'veg' ? asset('assets/user/img/veg.png') : asset('assets/user/img/non-veg.png') }}" class="img-fluid food-type mt-1">
                    </div>
                    @if (!$customize)
                    <span class="product-count-number d-flex p-0" data-changer="{{ $food->id }}">
                        <button type="button" data-food-increment="-1" class="btn-sm left dec btn px-2 border-0">
                            <i class="feather-minus"></i>
                        </button>
                        <input class="product-count-number-input w-100 border-0" type="text" data-product-price="{{ floor(App\CentralLogics\Helpers::food_discount($food->price, $food->discount, $food->discount_type)) }}" data-product-qty="" readonly
                        name="food[{{ $food->id }}]" data-food-id="{{ $food->id }}" value="{{ $cartedItem ? $cartedItem['quantity'] : 1 }}">
                        <button type="button" class="btn-sm right inc btn px-2 border-0" data-food-increment="1" >
                            <i class="feather-plus"></i>
                        </button>
                    </span>
                    @else
                    <span  class="product-count-number d-flex flex-column-0">
                        
                        <span class="product-count-number-input w-100 border-0 py-2" data-food-id="{{ $food->id }}" data-customize="{{ $customize }}">Added</span>
                        
                        {{-- <button type="button"  data-food-id="{{ $food->id }}" data-customize="{{ $customize }}" 
                            class="btn-sm px-5 right inc btn text-warning item-increment fw-bolder"> {{ $cartedItem ? 'Added' : 'Add' }}
                        </button> --}}
                        
                        {{-- @if ($customize)
                        @endif --}}
                    </span>   
                    <p class="mb-0 mt-4 text-center small" style="color:goldenrod;">Customizable</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach
  
</div>


{{--  --}}
