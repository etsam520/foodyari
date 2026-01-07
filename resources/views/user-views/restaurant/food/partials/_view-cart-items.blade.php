@foreach ($cart as $item)
@php($food = App\Models\Food::find($item['product_id']))
<div class="filter">
    <div class="p-3 border-bottom">
        <div class="row">
            <div class="list-card-image col-2 position-relative">
                {{-- <div class="member-plan position-absolute"><span class=""><img alt="#"
                            src="img/veg.png" class="img-fluid item-img w-100"></span>
                </div> --}}
                <a href="mess.php">
                    <img alt="#" src="{{Helpers::getUploadFile($food->image,'product')}}" class="img-fluid item-img w-100">
                </a>
            </div>
            <div class=" ps-3 pe-0 position-relative col-8">
                <div class="d-flex justify-content-end">
                    <div class="fs-5 me-2">
                        <i class="fa-solid fa-share-nodes text-solid"></i>
                    </div>
                    <div class="fs-5 me-2">
                        <i class="fa-regular fa-heart text-danger"></i>
                    </div>
                    {{-- <div class="fs-5 me-2">
                        <i class="fa-solid fa-heart text-danger"></i>
                    </div> --}}
                    <div class="fs-5" type="button" remove-cart-item="{{$item['uuid']}}">
                        <i class="fa-solid fa-trash text-danger"></i>
                    </div>
                </div>
                <div class="list-card-body">
                    <h6 class="mb-1">
                        <a href="mess.php" class="text-black">
                            {{Str::ucfirst($food->name)}}
                        </a>
                    </h6>
                    <p class="mb-0 mt-2" style="font-size:15px;">Speciality/Description</p>
                    <div class="d-flex mt-2">
                        <div class="bg-success border border-success border-2 rounded p-1 me-2">
                            <p class="mb-0 text-white fw-bolder" style="font-size: 15px;"><i
                                    class="feather-star star_active me-2"></i>5.0</p>
                        </div>
                        <span class="text-success mb-0 fs-5 fw-bolder">
                             {{ App\CentralLogics\Helpers::format_currency(floor(App\CentralLogics\Helpers::food_discount($food->price, $food->discount, $food->discount_type)) * $item['quantity']) }}
                        </span>
                    </div>
                    <p class="mb-0 mt-2" style="font-size:15px;">
                        Quantity : {{$item['quantity']}}
                        <span class="fs-5" type="button" customizeCart="{{$item['uuid']}}" food-id="{{$food->id}}">
                            <i class="fa-solid fa-edit text-primary"></i>
                        </span>
                    </p>

                </div>
            </div>
        </div>
    </div>
</div>
@endforeach
