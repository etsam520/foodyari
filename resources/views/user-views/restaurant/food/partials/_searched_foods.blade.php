<style>
    overflow-x-scroll {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .overflow-x-scroll {
        display: flex;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        white-space: nowrap;
    }

    .overflow-x-scroll::-webkit-scrollbar {
        display: none;
    }

    .overflow-x-scroll {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
@foreach ($groupedFoods as $key => $foods)
    @php
    $restaurant = App\Models\Restaurant::find($key) ;
    @endphp

    <div class="d-flex rounded-3 shadow-sm mb-3 gold-members alert alert-primary border-0">
        <div class="w-100">
            <div class="d-flex px-3 pt-4">
                <img alt="#" src="{{ Helpers::getUploadFile($restaurant->logo, 'restaurant') }}" style="width:48px" class="img-fluid me-2 rounded-circle">
                <h5 class="mb-1 text-primary" style="font-weight: 1000;">
                    {{ Str::ucfirst($restaurant->name) }}
                </h5>

            </div>

            {{-- <hr> --}}
            <div class="overflow-x-scroll w-100">
                @foreach ($foods as $food)

                    @php
                        $customize = $food->isCustomize == 1;
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
                    <div class="d-flex gap-2 mb-2 p-3 my-3 m-2 bg-secondary-subtle rounded-5">
                        <div class="d-flex align-items-start me-3">
                            <div>
                                <div class="d-flex">
                                    <img alt="#" src="{{ $food->type == 'veg' ? asset('assets/user/img/veg.png') : asset('assets/user/img/non-veg.png') }}" class="img-fluid me-2 food-type mt-1">
                                    <h4 class="mb-1" style="font-weight: 750;">
                                        {{ Str::ucfirst($food->name) }}
                                    </h4>
                                </div>
                                @if ($food->isCustomize == 1)
                                    <?php
                                    $variations = json_decode($food->variations);
                                    if (!empty($variations)) {
                                        $startingPrice = $variations[0]->values[0]->optionPrice + $food->admin_margin;
                                    } else {
                                        $startingPrice = 0;
                                    }
                                    ?>

                                    <div class="d-flex">
                                        <div class="text-success mb-0 fs-6 fw-bolder ms-lg-2 ms-0 text-nowrap">
                                            Starting {{ App\CentralLogics\Helpers::format_currency(floor($startingPrice)) }}</div>
                                    </div>
                                @else
                                    <div class="d-flex">
                                        @if ($food->discount > 0 && $food->price != 0)
                                            <div class="text-danger mb-0 align-self-center text-nowrap me-2"> <strike>
                                                    {{ App\CentralLogics\Helpers::format_currency($food->price) }}</strike></div>
                                        @endif
                                        <div class="text-success mb-0 fs-5 fw-bolder ms-lg-2 ms-0 text-nowrap">
                                            {{ App\CentralLogics\Helpers::format_currency(floor(App\CentralLogics\Helpers::food_discount($food->price, $food->discount, $food->discount_type))) }}</div>
                                    </div>
                                @endif

                                @php
                                    $isLongDescription = strlen($food->description) > 100;
                                    $shortDescription = $isLongDescription ? Str::ucfirst(Str::limit($food->description, 100)) : Str::ucfirst($food->description);
                                    $moreDescription = $isLongDescription ? substr($food->description, 100) : '';
                                @endphp

                                <div class="menu-item">
                                    <div class="favourite-heart text-danger rounded-circle"><a href="javascript:void(0)"><img srcset="{{ asset('assets/user/img/favourite.png') }} 50px"></img></a></div>
                                    <p class="mb-0 menu-description">
                                        {{ $shortDescription }}
                                        @if ($isLongDescription)
                                            <span class="more-content">{{ $moreDescription }}</span>
                                        @endif
                                    </p>
                                    @if ($isLongDescription)
                                        <button class="read-more-btn">Read more</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="">
                            <div class="position-relative package-img">
                                <img src="{{ Helpers::getUploadFile($food->image, 'product') }}" alt=" mess logo" class="w-100 rounded-2 ">
                                <div class="position-absolute package-view {{ !$customize && $cartedItem ? 'd-none' : null }}">
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
                            @if ($food->isCustomize == 1)
                                <p class="mb-0 mt-4 text-center" style="color:goldenrod;">Customizable</p>
                            @endif
                        </div>
                        <div id="collapse-{{ $food->id }}" class="collapse" aria-labelledby="headingThree" style="">
                            <p class="ms-4 mt-3 mt-lg-5 mb-0">{{ Str::ucfirst($food->description) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endforeach
