{{-- @dd($food) --}}
<div class="card">
    <div class="container-fliud">
    <div class="row">
        <div class="preview col-md-6">

        <div class="preview-pic">
            <div class="tab-pane active" id="pic-1"><img
                src="{{asset("product/$food->image")}}"></div>
        </div>

        </div>
        <div class=" col-md-6">
        <h3 class="product-title">{{Str::upper($food->name)}}</h3>
        <div class="rating">
            <div class="stars">
            <span class="fa fa-star checked"></span>
            <span class="fa fa-star checked"></span>
            <span class="fa fa-star checked"></span>
            <span class="fa fa-star"></span>
            <span class="fa fa-star"></span>
            </div>
            <span class="review-no">41 reviews</span>
        </div>
        <p class="product-description">{{Str::upper($food->description)}}</p>
        <h4 class="price">current price: 
            <span class="mx-2">
                <strike class="text-danger">
                    {{App\CentralLogics\Helpers::format_currency(App\CentralLogics\Helpers::food_discount($food->price, $food->discount, $food->discount_type))}}
                </strike>
            </span><br>
            <span class="mx-2"><b class="text-success" data-current-price="{{$food->price}}">{{App\CentralLogics\Helpers::format_currency($food->price)}}</b></span>
        </h4>
        {{-- <p class="vote"><strong>91%</strong> of buyers enjoyed this product! <strong>(87 votes)</strong></p> --}}
        @php($addons = App\Models\Addon::whereIn('id',json_decode($food->add_ons))->get())
        @php ($variations = json_decode($food->variations))
        {{-- @dd($variations) --}}
        <div class="addons-container my-3" >
            <div class="addons mb-3 ">
                <h5>Addons</h5>
                @foreach ($addons as $addon ) 
                <div class="d-flex mt-2 justify-content-around list-group-item list-group-item-action list-group-item-light">
                    <span><img src="{{asset('assets/images/icons/veg.png')}}" alt="veg" style="width: 20px"></i></span>
                    <span>{{Str::upper($addon->name)}}</span>
                    <span>{{App\CentralLogics\Helpers::format_currency($addon->price)}}</span>
                    {{-- <span>Qty : <b>4</b></span> --}}
                    <span class="form-check">
                        <input type="checkbox" name="addon_id[{{$addon->id}}]"
                         data-price="{{$addon->price}}" 
                         data-addon-checkbox="{{$addon->id}}"
                         data-check="0" 
                         class="form-check-input" >
                        </span>
                </div>
                @endforeach
            </div>

            @foreach ($variations as $variation ) 
            <div class="addons mb-3 ">
                <h5>{{$variation->name}}</h5>
                @foreach ($variation->values as $value )
                {{-- @dd($value)  --}}
                <div class="d-flex mt-2 justify-content-around list-group-item list-group-item-action list-group-item-light">
                <span><img src="{{asset('assets/images/icons/veg.png')}}" alt="veg" style="width: 20px"></i></span>
                <span>{{Str::upper($value->label)}}</span>
                <span>{{App\CentralLogics\Helpers::format_currency($value->optionPrice)}}</span>
                {{-- <span>Qty : <b>4</b></span> --}}
                  
                <span>
                    <input type="checkbox" name="" {{$variation->required != 'off'? 'checked desabled data-check="1" ': 'data-check="0'}} 
                    class="input-checkbox" id=""
                    data-variation-name="{{$variation->name}}" data-option-label="{{$value->label}}" data-option-price="{{$value->optionPrice}}">
                </span>
                </div>
                @endforeach
            </div>
            @endforeach
        </div>
        <div class="d-flex justify-content-start mb-3 product-quantity">
            <h5 class="">Quantity :</h5>
            <div class="custom-input mx-3"> 
                <button class="decrease-btn" data-product-increment="-1" id="decrease-btn">-</button>
                <input type="text" id="input-value" data-product-qty="" value="1">
                <button class="increase-btn" data-product-increment="1" id="increase-btn">+</button>
            </div>
        </div>
        <div class="action">
            <button class="btn btn-outline-danger fa fa-shopping-cart" data-add-to-cart="true" type="button">&nbsp;Add to cart</button>
            {{-- <button class="btn btn-outline-danger fa fa-heart" type="button"></span></button> --}}
        </div>
        </div>
    </div>
    </div>
</div>