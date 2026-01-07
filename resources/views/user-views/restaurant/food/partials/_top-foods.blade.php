@php
    $isLoggedin = Auth::guard('customer')->check();
if ($isLoggedin) {
    $favoriteFoods = Auth::guard('customer')->user()->favoriteFoods()->get()->toArray();
}
@endphp
<div class="pt-2 pb-3 title d-flex align-items-center justify-content-center">
    <h4 class="m-0 fw-bolder">Top Orders</h4>
</div>

<div class="top-order-slider" >
    @foreach ($foodSelected as $item)
        @php
        $food = $item['food'];
        @endphp
        <div class="top-order-item card border-0 rounded-4 mx-2 position-relative" style="background:#ff810a4d ">
            <div class="front ">
                <div class="">
                    <img class="initial-57-2 mw-100 rounded-top-4" id="coverImageViewer" src="{{ Helpers::getUploadFile($food->image, 'product') }}" alt="Product thumbnail">
                </div>
                <div class="bg-warning text-white py-3 px-3 rounded-bottom-4  fw-bolder">
                    <div class="truncate-text">{{Str::ucfirst($food->name)}}</div>
                    <!-- price of food -->
                    <div>
                        @if ($food->isCustomize == 1)
                            <?php
                            $variations = json_decode($food->variations);
                            if (!empty($variations)) {
                                $startingPrice = $variations[0]->values[0]->optionPrice + $food->admin_margin;
                            } else {
                                $startingPrice = 0;
                            }
                            ?>
                            Starting {{ App\CentralLogics\Helpers::format_currency(floor($startingPrice)) }}
                        @else
                            {{ App\CentralLogics\Helpers::format_currency(floor(App\CentralLogics\Helpers::food_discount($food->price, $food->discount, $food->discount_type))) }}
                        @endif
                    </div>
                    <!-- price of end -->
                </div>
                <div class="position-absolute top-0 end-0" style="margin-top:20px;margin-right:10px;">
                    <div class="bg-dark rounded-2 p-2">
                        <div class="text-white small">{{$item['quantity']}} Times Ordered</div>
                    </div>
                </div>
            </div>
            <div class="back">
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
                <div class="d-flex px-lg-3 px-2 py-5 bg-white rounded-3 shadow-sm mb-2 gold-members" data-sub-menu="{{ $food->submenu->id ?? 0 }}">
                    <div class="w-100">
                        <div class="d-flex flex-column gap-2 mb-2">

                            <div class="d-flex align-items-start me-auto">
                                <div>
                                    <div class="mb-1 text-center text-info" style="font-weight: 1000;">
                                        <svg fill="#962222" width="100px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 463 463" xml:space="preserve"
                                            stroke="#962222"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <g> <g>
                                            <path d="M463,187.5v-74c0-5.238-4.262-9.5-9.5-9.5H447V79.5c0-12.958-10.542-23.5-23.5-23.5H343v-0.5 c0-12.958-10.542-23.5-23.5-23.5h-176C130.542,32,120,42.542,120,55.5V56H39.5C26.542,56,16,66.542,16,79.5V104H9.5 c-5.238,0-9.5,4.262-9.5,9.5v74c0,12.376,6.37,23.288,16,29.644V416H7.5c-4.142,0-7.5,3.358-7.5,7.5c0,4.142,3.358,7.5,7.5,7.5 h448c4.142,0,7.5-3.358,7.5-7.5c0-4.142-3.358-7.5-7.5-7.5H447V217.144C456.63,210.788,463,199.876,463,187.5z M135,55.5 c0-4.687,3.813-8.5,8.5-8.5h176c4.687,0,8.5,3.813,8.5,8.5v7.961c0,0.013-0.002,0.025-0.002,0.039 c0,0.006,0.001,0.013,0.001,0.02C327.988,68.197,324.18,72,319.5,72h-176c-4.687,0-8.5-3.813-8.5-8.5V55.5z M31,79.5 c0-4.687,3.813-8.5,8.5-8.5h81.734c3.138,9.29,11.93,16,22.266,16h176c10.336,0,19.128-6.71,22.266-16H423.5 c4.687,0,8.5,3.813,8.5,8.5V104H31V79.5z M392,119v68.5c0,11.304-9.196,20.5-20.5,20.5c-11.304,0-20.5-9.196-20.5-20.5V119H392z M336,119v68.5c0,11.304-9.196,20.5-20.5,20.5c-11.304,0-20.5-9.196-20.5-20.5V119H336z M280,119v68.5 c0,11.304-9.196,20.5-20.5,20.5c-11.304,0-20.5-9.196-20.5-20.5V119H280z M224,119v68.5c0,11.304-9.196,20.5-20.5,20.5 s-20.5-9.196-20.5-20.5V119H224z M168,119v68.5c0,11.304-9.196,20.5-20.5,20.5s-20.5-9.196-20.5-20.5V119H168z M112,119v68.5 c0,11.304-9.196,20.5-20.5,20.5S71,198.804,71,187.5V119H112z M15,187.5V119h41v68.5c0,11.304-9.196,20.5-20.5,20.5 S15,198.804,15,187.5z M144,416H63v-17h81V416z M144,384H63V255h81V384z M432,416H159V247.5c0-4.142-3.358-7.5-7.5-7.5h-96 c-4.142,0-7.5,3.358-7.5,7.5V416H31V222.705c1.475,0.188,2.975,0.295,4.5,0.295c11.368,0,21.498-5.378,28-13.716 C70.002,217.622,80.132,223,91.5,223s21.498-5.378,28-13.716c6.502,8.338,16.632,13.716,28,13.716s21.498-5.378,28-13.716 c6.502,8.338,16.632,13.716,28,13.716s21.498-5.378,28-13.716c6.502,8.338,16.632,13.716,28,13.716s21.498-5.378,28-13.716 c6.502,8.338,16.632,13.716,28,13.716c11.368,0,21.498-5.378,28-13.716c6.502,8.338,16.632,13.716,28,13.716 c11.368,0,21.498-5.378,28-13.716c6.502,8.338,16.632,13.716,28,13.716c1.525,0,3.025-0.107,4.5-0.295V416z M427.5,208 c-11.304,0-20.5-9.196-20.5-20.5V119h41v68.5C448,198.804,438.804,208,427.5,208z"></path>
                                            <path d="M407.5,240h-224c-4.142,0-7.5,3.358-7.5,7.5v144c0,4.142,3.358,7.5,7.5,7.5h224c4.142,0,7.5-3.358,7.5-7.5v-144 C415,243.358,411.642,240,407.5,240z M400,384h-49v-25h24.5c4.142,0,7.5-3.358,7.5-7.5c0-4.142-3.358-7.5-7.5-7.5h-64 c-4.142,0-7.5,3.358-7.5,7.5c0,4.142,3.358,7.5,7.5,7.5H336v25h-81v-25h24.5c4.142,0,7.5-3.358,7.5-7.5 c0-4.142-3.358-7.5-7.5-7.5h-64c-4.142,0-7.5,3.358-7.5,7.5c0,4.142,3.358,7.5,7.5,7.5H240v25h-49V255h49v9.909 c-13.759,3.375-24,15.806-24,30.591c0,4.142,3.358,7.5,7.5,7.5H240v0.5c0,4.142,3.358,7.5,7.5,7.5c4.142,0,7.5-3.358,7.5-7.5V303 h16.5c4.142,0,7.5-3.358,7.5-7.5c0-14.785-10.241-27.216-24-30.591V255h81v9.909c-13.759,3.375-24,15.806-24,30.591 c0,4.142,3.358,7.5,7.5,7.5H336v0.5c0,4.142,3.358,7.5,7.5,7.5c4.142,0,7.5-3.358,7.5-7.5V303h16.5c4.142,0,7.5-3.358,7.5-7.5 c0-14.785-10.241-27.216-24-30.591V255h49V384z M247.5,279c6.4,0,11.959,3.662,14.695,9h-29.39 C235.54,282.662,241.1,279,247.5,279z M343.5,279c6.4,0,11.959,3.662,14.695,9h-29.39C331.54,282.662,337.1,279,343.5,279z"></path>
                                            <path d="M127.5,343c4.142,0,7.5-3.358,7.5-7.5v-16c0-4.142-3.358-7.5-7.5-7.5c-4.142,0-7.5,3.358-7.5,7.5v16 C120,339.642,123.358,343,127.5,343z"></path></g> </g> </g> </g>
                                        </svg>
                                        <h4>{{ Str::ucfirst($food->restaurant->name) }}</h4>
                                    </div>
                                    <div class="mb-3 text-primary" style="font-weight: 750;">
                                        {{ Str::ucfirst($food->name) }}
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

                                        {{-- <div class="d-flex"> --}}
                                            <div class="text-success mb-3 fs-5 fw-bolder mb-3 ms-lg-2 ms-0 text-nowrap">
                                                Starting {{ App\CentralLogics\Helpers::format_currency(floor($startingPrice)) }}</div>
                                        {{-- </div> --}}
                                    @else
                                        <div class="d-flex">
                                            @if ($food->discount > 0 && $food->price != 0)
                                                <div class="text-danger mb-3 align-self-center text-nowrap me-2"> <strike>
                                                        {{ App\CentralLogics\Helpers::format_currency($food->price) }}</strike></div>
                                            @endif
                                            <div class="text-success mb-3 fs-5 fw-bolder ms-lg-2 ms-0 text-nowrap">
                                                {{ App\CentralLogics\Helpers::format_currency(floor(App\CentralLogics\Helpers::food_discount($food->price, $food->discount, $food->discount_type))) }}</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="mx-auto">
                                <div class="position-relative">
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
                    </div>
                </div>
            </div>
        </div>

    @endforeach

</div>

































{{-- <div class="pt-2 pb-3 title d-flex align-items-center justify-content-center">
    <h4 class="m-0 fw-bolder">Top Orders</h4>
</div>
<div class="top-order-slider" t>
    @foreach ($foodSelected as $item)
        @php($food = $item['food'])
        <div class="top-order-item card border-0 rounded-4 mx-2 position-relative">
            <img class="initial-57-2 mw-100 rounded-top-4" id="coverImageViewer" src="{{ Helpers::getUploadFile($food->image, 'product') }}" alt="Product thumbnail">
            <div class="bg-warning text-white py-3 px-3 rounded-bottom-4  fw-bolder">
                <div class="truncate-text">{{Str::ucfirst($food->name)}}</div>
                <!-- price of food -->
                <div>
                    @if ($food->isCustomize == 1)
                        <?php
                        $variations = json_decode($food->variations);
                        if (!empty($variations)) {
                            $startingPrice = $variations[0]->values[0]->optionPrice + $food->admin_margin;
                        } else {
                            $startingPrice = 0;
                        }
                        ?>
                        Starting {{ App\CentralLogics\Helpers::format_currency(floor($startingPrice)) }}
                    @else
                        {{ App\CentralLogics\Helpers::format_currency(floor(App\CentralLogics\Helpers::food_discount($food->price, $food->discount, $food->discount_type))) }}
                    @endif
                </div>
                <!-- price of end -->
            </div>
            <div class="position-absolute top-0 end-0" style="margin-top:20px;margin-right:10px;">
                <div class="bg-dark rounded-2 p-2">
                    <div class="text-white small">{{$item['quantity']}} Times Ordered</div>
                </div>
            </div>
        </div>
    @endforeach

</div> --}}

