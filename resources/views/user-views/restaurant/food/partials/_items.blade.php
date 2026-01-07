<?php
$_isRecommended = false;
$isLoggedin = Auth::guard('customer')->check();
if ($isLoggedin) {
    $favoriteFoods = Auth::guard('customer')->user()->favoriteFoods()->get()->toArray();
}
foreach ($foods as $key => $foodList){
    $_brekThisLoop = false ;
    foreach ($foodList as $food){
        if($food->isRecommended){
            $_isRecommended = true ;
            $_brekThisLoop = true;
            break ;
        }
        if($_brekThisLoop){
            break;
        }
    }
}

?>
<style>
    .switch-container {
            display: flex;
            align-items: center;
            gap: 20px;
            /* margin: 20px; */
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 80px;
            height: 20px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .non-veg::before{
            background-color: red !important;
        }
        .veg::before{
            background-color: #4caf50 !important;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: 0.4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 35px;
            width: 35px;
            left: 0px;
            bottom: -7px;
            /* background-color: white !important; */
            transition: 0.4s;
            border-radius: 50%;
        }
        .veg{
            width: 80px;
            border: none;
            height: 20px;
        }

        input:checked + .non-veg {
            background-color: #ff00005c;
        }
        input:checked + .veg {
            background-color: #4caf5082;
        }

        input:checked + .slider:before {
            transform: translateX(50px);
        }

        .label {
            font-size: 16px;
        }
        .fav-heart {
            font-size: 30px;
            color: red; /* Ensure color is red */
            fill: red; /* This is for SVG elements */
        }
</style>
{{-- LAST ORDER HISTORY SECTION (EXTRA WORK)--}}
<div class="rounded d-none shadow-sm mt-4 mb-4">
    <div class="osahan-cart-item-profile bg-white rounded shadow-sm p-4">
        <div class="w-100">
            <div class="d-flex justify-content-between align-items-center gap-2 mb-2">
                <a href="javascript:void(0)" class="fs-4">
                    <h5 class="mb-1 fw-bolder">Repeat your last Order</h5>
                </a>
                <a href="javascript:void(0)" class="btn bg-warning text-white">
                    <i class="fas fa-refresh me-1"></i>
                    Repeat your last Order
                </a>
            </div>
            <div class=" pt-2">
                <div class="border">
                    <div class="p-3">
                        <div class="row border-bottom pb-2">
                            <div class="col-1">
                                <p class="text-fw-bold fw-bolder mb-0">SI. </p>
                            </div>
                            <div class="col-4">
                                <p class="text-fw-bold fw-bolder mb-0">Name</p>
                            </div>
                            <div class="col-3">
                                <p class="text-fw-bold fw-bolder mb-0">Quantity </p>
                            </div>
                            <div class="col-4">
                                <p class="text-fw-bold fw-bolder mb-0">Amount</p>
                            </div>
                        </div>
                        <div class="row pt-2">
                            <div class="col-1">
                                <p class="text-fw-bold mb-0">1
                                </p>
                            </div>
                            <div class="col-4">
                                <p class="text-fw-bold mb-0">Food Name
                                </p>
                            </div>
                            <div class="col-3">
                                <p class="text-fw-bold mb-0"> 3
                                    <span class="text-muted mb-0"> </span>
                                </p>
                            </div>
                            <div class="col-4">
                                <p class="text-fw-bold mb-0">300
                                    <span class="text-muted mb-0"> </span>
                                </p>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- LAST ORDER HISTORY SECTION --}}

{{-- START RECOMMENDED SECTION (EXTRA WORK) --}}
@if($_isRecommended)
<div class="d-flex item-aligns-center justify-content-center bg-white shadow-sm mb-4" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample" style="border-bottom: 2px solid #ff810a;">
    <p class="p-3 mb-0 text-secondary w-100 text-center" style="font-size: 15px;font-weight: 700;">
        <span class="text-warning" style="font-size:25px;">Recommended</span>
    </p>
    <i class="fa fa-angle-down ms-auto align-self-center me-3" aria-hidden="true"></i>
</div>



<div class="collapse show" id="collapseExample">
    @foreach ($foods as $key => $foodList)
        @foreach ($foodList as $food)
            @php
                // dd($food->isRecommended);
                if($food->isRecommended == false){
                    continue;
                }
                $customize = $food->isCustomize == 1;
                $cartedItem = null;
                // dd($cart);
                if ($cart) {
                    foreach ($cart as $c) {
                        if ($c['product_id'] == $food->id) {
                            $cartedItem = $c;
                            break;
                        }
                    }
                }
            @endphp

            <div class="d-flex px-lg-3 px-2 py-4 bg-white rounded-3 shadow-sm mb-3 gold-members" >
                <div class="w-100">
                    <div class="d-flex gap-2 mb-2">
                        <div class="d-flex align-items-start me-auto">
                            <div>
                                <div class="d-flex">
                                    <img alt="#" src="{{ $food->type == 'veg' ? asset('assets/user/img/veg.png') : asset('assets/user/img/non-veg.png') }}" class="img-fluid me-2 food-type mt-1">
                                    <div class="mb-1" style="font-weight: 750;">
                                        {{ Str::ucfirst($food->name) }}
                                    </div>

                                </div>
                                @if ($food->isCustomize == 1)
                                    <?php
                                    $variations = json_decode($food->variations);
                                    if (!empty($variations)) {
                                        $optiontMargin = 0;
                                        if(isset($variations[0]->values[0]->optionMargin)){
                                            $optiontMargin = $variations[0]->values[0]->optionMargin;
                                        }
                                        $startingPrice = $variations[0]->values[0]->optionPrice + $optiontMargin;
                                    } else {
                                        $startingPrice = 0;
                                    }
                                    ?>

                                    <div class="d-flex">
                                        <div class="text-success mb-0 fs-5 fw-bolder ms-lg-2 ms-0 text-nowrap">
                                            Starting {{ App\CentralLogics\Helpers::format_currency(floor($startingPrice)) }}</div>
                                    </div>
                                    <div class="d-flex">
                                    @if($food->discount_type == "percentage" && $food->discount > 0)
                                        <div class="text-primary mb-0 align-self-center text-nowrap me-2">
                                            <span>
                                                {{$food->discount . "%"}} 
                                            </span>
                                            Off
                                         </div>
                                    @elseif($food->discount_type == "amount" && $food->discount > 0)
                                        <div class="text-primary mb-0 align-self-center text-nowrap me-2">
                                            <span>
                                              Flat  {{ App\CentralLogics\Helpers::format_currency($food->discount) }} 
                                            </span>
                                            Off
                                         </div>
                                    @endif
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
                                    @if ($isLoggedin)
                                        <span class="favourite-heart text-danger position-absolute rounded-circle">
                                            <?php
                                            $foundFood = null;
                                            $foodId = $food->id;
                                            $foundFood = array_filter($favoriteFoods, function ($food) use ($foodId) {
                                                return $food['id'] == $foodId;
                                            });
                                            $foundFood = array_values($foundFood);
                                            ?>
                                            @if (isset($foundFood[0]))
                                                {{-- <img src="{{ asset('assets/user/img/favourite.png') }}" onclick="unfavoriteFood(this)" data-id="{{ $food->id }}" class="img-fluid" style="width: 30px" alt="non-fav-food"> --}}
                                                <div onclick="unfavoriteFood(this)" data-id="{{ $food->id }}" style="font-size:23px;">
                                                    <i class="fas fa-heart text-danger bg-white"></i>
                                                </div>
                                            @else
                                                {{-- <img src="{{ asset('assets/user/img/non_favourite.png') }}" onclick="favoriteFood(this)" data-id="{{ $food->id }}" class="img-fluid" style="width: 30px" alt="fav-food"> --}}
                                                <div onclick="favoriteFood(this)" data-id="{{ $food->id }}" style="font-size:23px;">
                                                    <i class="feather-heart text-dark"></i>
                                                </div>
                                            @endif
                                        </span>
                                    @endif
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
                    </div>
                    <div id="collapse-{{ $food->id }}" class="collapse" aria-labelledby="headingThree" style="">
                        <p class="ms-4 mt-3 mt-lg-5 mb-0">{{ Str::ucfirst($food->description) }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    @endforeach
</div>
@endif
{{-- END RECOMMENDED SECTION (EXTRA WORK) --}}

<!--div class="my-2 d-flex justify-content-between bg-white shadow p-3 rounded-4">
    <a class="fw-bolder fs-3 d-flex align-items-center" href="javascript:void(0)" data-bs-toggle="modal" style="font-size: 18px;" data-bs-target="#filters"><i class="fa fa-filter ms-2"></i>Filters </a>
    <div class="switch-container">
        <div class="text-center">
            <label class="switch d-block">
                <input type="checkbox" id="vegSwitch" >
                <span class="slider veg"></span>
            </label>
            <div class="label text-center fs-5 fw-bolder text-success mt-2">Veg</div>
        </div>
        <div class="text-center">
            <label class="switch d-block">
                <input type="checkbox" id="nonVegSwitch">
                <span class="slider non-veg"></span>
            </label>
            <div class="label text-center fs-5 fw-bolder text-danger mt-2">Non-Veg</div>
        </div>
    </div>
</!--div>
<div class="d-flex item-aligns-center justify-content-center bg-white shadow-sm" style="border-bottom: 2px solid #ff810a;">
    <p class="p-3 mb-0 text-secondary" style="font-size: 15px;font-weight: 700;">
        <i class="fa-solid fa-utensils me-2"></i><i class="fa-solid fa-leaf me-3"></i><span class="text-warning" style="font-size:25px;">Menu</span><i class="fa-solid fa-leaf ms-3 fa-rotate-90 fa-flip-horizontal"></i><i
            class="fa-solid fa-utensils ms-2 fa-rotate-90 fa-flip-horizontal"></i>
    </p>
</div -->

@foreach ($foods as $key => $foodList)
    @php
        $menu = App\Models\RestaurantMenu::find($key);
    @endphp

    <h3 id="menu_{{ $menu?->id }}" class="text-center text-muted fw-bolder">{{ $menu?->name }}</h3>
    @foreach ($foodList as $food)
        @php
            $customize = $food->isCustomize == 1;
            $cartedItem = null;
            // dd($cart);
            if ($cart) {
                foreach ($cart as $c) {
                    if ($c['product_id'] == $food->id) {
                        $cartedItem = $c;
                        break;
                    }
                }
            }
        @endphp
        <div class="d-flex px-lg-3 px-3 py-4 rounded-5 shadow bg-white mb-3 gold-members" data-sub-menu="{{ $food->submenu->id ?? 0 }}">
            <div class="w-100">
                <div class="d-flex gap-2 mb-2">
                    <div class="d-flex align-items-start me-auto">
                        <div>
                            @php
                                $isLongDescription = strlen($food->description) > 100;
                                $shortDescription = $isLongDescription ? Str::ucfirst(Str::limit($food->description, 100)) : Str::ucfirst($food->description);
                                $moreDescription = $isLongDescription ? substr($food->description, 100) : '';
                            @endphp
                            <div class="d-flex">
                                <img alt="#" src="{{ $food->type == 'veg' ? asset('assets/user/img/veg.png') : asset('assets/user/img/non-veg.png') }}" class="img-fluid me-2 food-type mt-1">
                                <div class="mb-1" style="font-weight: 750;">
                                    {{ Str::ucfirst($food->name) }}
                                    {{-- &#11088;
                                &#x2B50; --}}

                                </div>

                            </div>
                            <p class="mb-0 menu-description">
                                {{ $shortDescription }}
                                @if ($isLongDescription)
                                    <span class="more-content">{{ $moreDescription }}</span>
                                @endif
                            </p>

                            {{-- <div class="d-flex">
                            <div class="text-white my-2 me-1">
                                <span class="mb-0 rounded  px-2 bg-success text-white py-1 fw-bolder"><i class="feather-star star_active me-2"></i>5.0</span>
                            </div>
                            <p class="mb-0 align-self-center fs-6">(<i class="fas fa-user me-1"></i>459)
                            </p>
                        </div> --}}
                            @if ($food->isCustomize == 1)
                                <?php
                                $variations = json_decode($food->variations);
                                if (!empty($variations)) {
                                    $optiontMargin = 0;
                                    if(isset($variations[0]->values[0]->optionMargin)){
                                        $optiontMargin = $variations[0]->values[0]->optionMargin;
                                    }
                                    $startingPrice = $variations[0]->values[0]->optionPrice + $optiontMargin;
                                } else {
                                    $startingPrice = 0;
                                }
                                ?>

                                <div class="d-flex">
                                    <div class="text-success mb-0 fs-5 fw-bolder ms-lg-2 ms-0 text-nowrap">
                                        Starting {{ App\CentralLogics\Helpers::format_currency(floor($startingPrice)) }}</div>
                                </div>
                                <div class="d-flex">
                                    @if($food->discount_type == "percentage" && $food->discount > 0)
                                        <div class="text-primary mb-0 align-self-center text-nowrap me-2">
                                            <span>
                                                {{$food->discount . "%"}} 
                                            </span>
                                            Off
                                         </div>
                                    @elseif($food->discount_type == "amount" && $food->discount > 0)
                                        <div class="text-primary mb-0 align-self-center text-nowrap me-2">
                                            <span>
                                              Flat  {{ App\CentralLogics\Helpers::format_currency($food->discount) }} 
                                            </span>
                                            Off
                                         </div>
                                    @endif
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
                                @if ($food->discount > 0 && $food->price != 0)
                                    <div class="d-flex">
                                        <div class="text-primary mb-0 align-self-center text-nowrap me-2">
                                            <span>
                                                {{ floor((($food->price - Helpers::food_discount($food->price, $food->discount, $food->discount_type)) / $food->price) * 100) . "%" }}
                                            </span>
                                            Off
                                         </div>
                                    </div>
                                    @endif
                            @endif
                            {{-- <button type="button" class="text-dark btn btn-outline-secondary mt-3 collapsed" onclick="event.stopPropagation();" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $food->id }}" aria-expanded="false"
                            aria-controls="collapse-{{ $food->id }}">
                            View More
                            <i class="feather-chevrons-right"></i>
                        </button> --}}
                            {{-- <p class="mb-0 menu-description">{{ Str::ucfirst($food->description) }}</p>
                        <button>Read more</button> --}}


                            <div class="menu-item">
                                {{-- <div class="favourite-heart text-danger rounded-circle"><a href="javascript:void(0)"><img srcset="{{ asset('assets/user/img/favourite.png') }} 50px"></img></a></div> --}}

                                @if ($isLoggedin)
                                <div class="mb-1 d-flex justify-content-start">
                                    @if(!empty(array_filter($collectionItems, function ($item) use($food) {
                                        return $item['item_id'] === $food->id && $item['type'] === 'food';
                                    })))
                                    @php($found_collection = array_filter($collectionItems, function ($item) use($food) {
                                        return $item['item_id'] === $food->id && $item['type'] === 'food';
                                    }))
                                    <div class="align-self-center" data-id="{{$food->id}}" data-type="food" onclick="undoFromCollection(this)" data-collection-id="{{array_values($found_collection)[0]['collection_id']}}" style="font-size:21px; padding: 0px 5px;">
                                        <i class="fas fa-bookmark text-success bg-white"></i>
                                    </div>
                                    @else
                                    <div class="align-self-center" data-id="{{$food->id}}" data-type="food" onclick="addToCollection(this)" style="font-size:21px; padding: 0px 5px;">
                                        <i class="feather-bookmark text-muted"></i>
                                    </div>
                                    @endif
                                        <?php
                                        $foundFood = null;
                                        $foodId = $food->id;
                                        $foundFood = array_filter($favoriteFoods, function ($food) use ($foodId) {
                                            return $food['id'] == $foodId;
                                        });
                                        $foundFood = array_values($foundFood);
                                        ?>
                                        @if (isset($foundFood[0]))
                                            {{-- <img src="{{ asset('assets/user/img/favourite.png') }}" onclick="unfavoriteFood(this)" data-id="{{ $food->id }}" class="img-fluid" style="width: 30px" alt="non-fav-food"> --}}
                                            <div class="" onclick="unfavoriteFood(this)" data-id="{{ $food->id }}" style="font-size:25px;">
                                                <i class="fas fa-heart text-danger bg-white"></i>
                                            </div>
                                        @else
                                            {{-- <img src="{{ asset('assets/user/img/non_favourite.png') }}" onclick="favoriteFood(this)" data-id="{{ $food->id }}" class="img-fluid" style="width: 30px" alt="fav-food"> --}}
                                            <div class="" onclick="favoriteFood(this)" data-id="{{ $food->id }}" style="font-size:25px;">
                                                <i class="feather-heart text-muted"></i>
                                            </div>
                                        @endif
                                    {{-- </span> --}}
                                </div>

                                @endif
                                @if ($isLongDescription)
                                    <button class="read-more-btn">Read more</button>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <div class="position-relative">
                            <img src="{{ Helpers::getUploadFile($food->image, 'product') }}" alt=" mess logo" class="package-img rounded-2">
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
                </div>
                <div id="collapse-{{ $food->id }}" class="collapse" aria-labelledby="headingThree" style="">
                    <p class="ms-4 mt-3 mt-lg-5 mb-0">{{ Str::ucfirst($food->description) }}</p>
                </div>
            </div>
        </div>
    @endforeach
@endforeach
<div class="container res-section">
    <div class="text-center">
        <h1 class="fw-bolder text-muted" style="font-size: 62px;">Just Order!</h1>
        <p class="">Crafted with <span class="text-danger"><i class="fas fa-heart"></i></span> in Madhepura, Bihar</p>
    </div>
</div>
@push('javascript')
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('.read-more-btn');

            buttons.forEach(button => {
                button.addEventListener('click', function() {
                    const moreContent = this.previousElementSibling;

                    if (moreContent.style.display === 'none' || moreContent.style.display === '') {
                        moreContent.style.display = 'inline';
                        this.textContent = 'Read Less';
                    } else {
                        moreContent.style.display = 'none';
                        this.textContent = 'Read More';
                    }
                });
            });
        });
    </script> --}}
    <script>
        const vegSwitch = document.getElementById('vegSwitch');
        const nonVegSwitch = document.getElementById('nonVegSwitch');

        vegSwitch.addEventListener('change', () => {
            if (vegSwitch.checked) {
                nonVegSwitch.checked = false;
                console.log('Veg Mode Activated');
            } else {
                console.log('Veg Mode Deactivated');
            }
        });

        nonVegSwitch.addEventListener('change', () => {
            if (nonVegSwitch.checked) {
                vegSwitch.checked = false;
                console.log('Non-Veg Mode Activated');
            } else {
                console.log('Non-Veg Mode Deactivated');
            }
        });
    </script>
@endpush
