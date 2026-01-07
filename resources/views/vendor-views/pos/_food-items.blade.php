

<div class="row g-3 mb-auto">
@foreach ($products as $food)
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
    <div class="col-6 col-md-3  px-lg-3 px-2 py-4 bg-white rounded-3 shadow-sm mb-3 gold-members">
        <div class="w-100">
            {{-- <div class="d-flex gap-2 mb-2"> --}}
                <div class="d-flex align-items-start me-auto">
                    <div>
                        <div class="d-flex">
                            <img alt="#" src="{{ $food->type == 'veg' ? asset('assets/user/img/veg.png') : asset('assets/user/img/non-veg.png') }}" class="img-fluid me-2 food-type mt-1">
                            <small class="mb-1 small">
                                {{ Str::ucfirst($food->name) }}
                            </small>
                        </div>
                        @if($food->isCustomize == 1)
                       <?php
                       $variations = json_decode($food->variations);
                        if(!empty($variations))
                        $startingPrice = $variations[0]->values[0]->optionPrice;
                        else{
                        $startingPrice = 0;
                        }
                        ?>

                        <div class="d-flex">
                            <div class="text-success mb-0  fw-bolder ms-lg-2 ms-0 text-nowrap">
                            Starting {{ App\CentralLogics\Helpers::format_currency(floor($startingPrice))}}</div>
                        </div>
                        @else
                        <div class="d-flex flex-column">
                            @php($discounted_price = App\CentralLogics\Helpers::food_discount($food->price, $food->discount, $food->discount_type) )
                            @if($food->price != $discounted_price)
                            <div class="text-danger mb-0 align-self-start text-nowrap me-2"> <strike>
                                    {{ App\CentralLogics\Helpers::format_currency($food->price) }}</strike></div>
                            @endif
                            <div class="text-success mb-0  fw-bolder ms-lg-2 ms-0 text-nowrap">
                                {{ App\CentralLogics\Helpers::format_currency(floor($discounted_price)) }}</div>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="mb-0 w-100 d-block">
                    <div class="position-relative package-img">
                        <img src="{{ asset('product/' . $food->image) }}" alt=" mess logo" class="w-100 rounded-2 ">
                        <div class="position-absolute package-view {{(!$customize && $cartedItem)?'d-none' : null}}">
                            <div class="count-number shadow bg-white" style="border-radius: 15px;">
                                <button type="button" @if (!$customize) data-changer-target="{{ $food->id }}"
                              @else data-food-id="{{ $food->id }}" data-customize="{{ $customize }}" @endif
                                    class="btn-sm px-5 right inc btn text-warning item-increment fw-bolder"> {{ $cartedItem ? 'Added' : 'Add' }}
                                </button>
                            </div>
                        </div>
                        @if (!$customize)
                            <div class="position-absolute package-view {{ $cartedItem ? null : 'd-none' }} " data-changer="{{ $food->id }}">
                                <div class="count-number shadow bg-white" style="border-radius: 15px;">
                                    <button type="button" data-food-increment="-1" class="btn-sm left dec btn text-warning item-decrement">
                                        <i class="feather-minus fw-bolder"></i>
                                    </button>
                                    <input class="count-number-input text-warning bg-white" type="text" data-product-price="{{ floor(App\CentralLogics\Helpers::food_discount($food->price, $food->discount, $food->discount_type)) }}" data-product-qty="" readonly
                                        name="food[{{ $food->id }}]" data-food-id="{{ $food->id }}" value="{{ $cartedItem ? $cartedItem['quantity'] : 1 }}">
                                    <button type="button" data-food-increment="1" class="btn-sm right inc btn text-warning item-increment">
                                        <i class="feather-plus fw-bolder"></i>
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                    @if ($customize)
                        <p class="mb-0 mt-4 text-center" style="color:goldenrod;">Customizable</p>
                    @endif
                </div>
            {{-- </div> --}}
        </div>
    </div>
@endforeach()

{{-- <div class="w-100 d-block"> --}}
    {!! $products->links() !!}
{{-- </div> --}}
</div>
