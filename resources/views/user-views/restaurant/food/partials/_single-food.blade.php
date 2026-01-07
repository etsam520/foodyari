@php
$addons = App\Models\Addon::whereIn('id', json_decode($food->add_ons))->get();
$variations = json_decode($food->variations);
$cartedItem = null;
if($cart){
    foreach ($cart as $c) {
        if($c['product_id'] == $food->id){
        $cartedItem = $c;
        break;
        }
    }
}
// dd($cartedItem);
@endphp
    <style>
       .product-count-number {
        border: 1px solid #ff810a !important;
        border-radius: 5px !important;
        font-weight: 900 !important;
        width: 98px !important;
        }
        .product-count-number-input {
        height: 30px!important;
        }
        .product-count-number .btn {
        font-size: 15px;
        padding: 1px 8px;
        font-weight: 900;
        color: #ff810a;
    }

    </style>
<div class="modal-header bg-body-secondary">
    <div>
        <small>{{ Str::upper($food->name) }}</small><br>
        <h6 class="modal-title fw-bolder">Customise as per your taste</h6>
    </div>
    {{-- <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button> --}}
</div>
<div class="modal-body bg-body-secondary">

    @if($variations)
    {{-- @dd($variations) --}}
    @foreach ($variations as $variation)
    <?php
    $tempVariation = null;
    if($cartedItem){
        if(isset($cartedItem['variations'])){
            foreach ($cartedItem['variations'] as $vars) {
               if($vars['option'] == $variation->name){
                $tempVariation = $vars;break;
               }
            }
        }
    }
    ?>
    <div class="row">
        <div class="fw-bolder text-secondary mb-2 mt-3">Choose Your {{ Str::upper($variation->name) }}</div>
        @foreach ($variation->values as $value)
        <?php
            $tempValue = null;
            if($tempVariation){
                if(isset($tempVariation['values'])){
                    foreach ($tempVariation['values'] as $v) {
                    if($v['label'] == $value->label){
                        $tempValue = $v;break;
                    }
                    }
                }
            }

        ?>
        <div class="col-12">
            <div class="bg-white rounded-3 p-3">
                <div class="member-plan d-flex justify-content-between mb-2">
                    <div class="d-flex">
                        <img alt="#" src="{{asset('assets/user/img/veg.png')}}"
                            class="img-fluid me-2 item-img mt-1">
                        <div>{{ Str::upper($value->label) }}</div>
                    </div>
                    <div class="d-flex">
                        <div class="me-3">{{ App\CentralLogics\Helpers::format_currency($value->optionPrice +  ($value->optionMargin??0) ) }}</div>
                        <span class="product-count-number d-flex  align-self-center">
                            <button type="button" class="btn-sm left dec btn" data-variation-increment="-1" data-option-label="{{$value->label}}">
                                <i class="feather-minus"></i>
                            </button>
                            <input class="product-count-number-input" type="text" name="{{$value->label}}" value="{{$tempValue ? $tempValue['qty'] : 0}}"
                            data-variation-name="{{$variation->name}}"
                            data-price="{{ $value->optionPrice + ($value->optionMargin??0) }}">
                            <button type="button" class="btn-sm right inc btn" data-variation-increment="1" data-option-label="{{$value->label}}">
                                <i class="feather-plus"></i>
                            </button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

    </div>
    @endforeach

    @endif

    @if ($addons)
    {{-- @dd($addons) --}}
    <div class="row">
        <div class="fw-bolder text-secondary mb-2">Choose Your Beverage (Optional)</div>
        <div class="col-12">
            <div class="bg-white rounded-3 p-3">
                @foreach ($addons as $addon)
                <?php
                $tempAddon = null;
                if($cartedItem){
                    if(isset($cartedItem['addons'])){
                        foreach ($cartedItem['addons'] as $adn) {
                           if($adn['name'] == $addon->name){
                            $tempAddon = $adn;break;
                           }
                        }
                    }
                }

                ?>
                <div class="member-plan d-flex justify-content-between mb-2">
                    <div class="d-flex align-self-center">
                        <img alt="#" src="{{ asset('assets/images/icons/veg.png') }}"
                            class="img-fluid me-2 item-img mt-1">
                        <div>{{ Str::upper($addon->name) }}</div>
                    </div>
                    <div class="d-flex">
                        <div class="me-3 align-self-center">{{ App\CentralLogics\Helpers::format_currency($addon->price) }}</div>
                            <span class="product-count-number d-flex  align-self-center">
                                <button type="button" class="btn-sm left dec btn" data-addon-id="{{$addon->id}}" data-addon-increment="-1">
                                    <i class="feather-minus"></i>
                                </button>
                                <input class="product-count-number-input" type="text" data-addon-id="{{$addon->id}}" value="{{$tempAddon ?$tempAddon['qty'] : 0 }}" readonly
                                name="addon_id[{{ $addon->id }}]" data-price="{{ $addon->price }}">
                                <button type="button" class="btn-sm right inc btn" data-addon-id="{{$addon->id}}" data-addon-increment="1">
                                    <i class="feather-plus"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
<div class="modal-footer border-0 justify-content-between">
    {{-- @dd($cartedItem) --}}
    <?php
    $currentPrice = floor(App\CentralLogics\Helpers::food_discount($food->price, $food->discount, $food->discount_type));
    $discountPercent = ceil(
            ($food->discount_type == 'percent')
                ? $food->discount
                : (($food->discount / $food->price) * 100)
        );
    ?> 

    <h6 class="fw-bolder" data-current-price="{{ $currentPrice }}">{{ App\CentralLogics\Helpers::format_currency($currentPrice) }}</h6>


    <div class="m-0 p-0">

        <button class="btn btn-primary btn-lg w-100 px-2 py-1 rounded-3" data-add-to-cart="true" type="button">Add Item to
            Cart</button>
    </div>
    <div class="badge bg-success text-white px-2 py-1 rounded">
        {{ $discountPercent }}% OFF
    </div>
</div>
