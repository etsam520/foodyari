@extends('vendor-views.layouts.dashboard-main')

@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div>
        <div class="row">
            <div class="col-sm-12 col-lg-12">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form class="needs-validation" method="POST" enctype="multipart/form-data"
                    action="{{route('vendor.food.store')}}">
                    @csrf
                    <div class=" card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h5 class="page-header-title">{{ __('Add Food') }}</h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="input-label" for="name">Food Name<span
                                                class="input-label-secondary text-danger">*</span></label>
                                        <input id="name" type="text" name="name" class="form-control h--45px"
                                            placeholder="Enter Food Name" value="{{old('name')}}">
                                    </div>

                                    <div class="form-group">
                                        <label class="input-label" for="description">Description</label>
                                        <textarea type="text" id="description" name="description" class="form-control"
                                            cols="3">{{old('description')}}</textarea>
                                    </div>
                                </div>

                                <div class="col-md-6 align-self-center">
                                    <img class="initial-57-2 w-100" id="viewer"
                                        src="{{asset('assets/images/icons/restaurant-default-image.png') }}"
                                        alt="delivery-man image">

                                    <div class="form-group mb-0 text-center">
                                        <label class="input-label">Image<small class="text-danger">
                                                (Ratio 1:1)</small></label>
                                        <div class="custom-file">
                                            <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                                style="width: 220px;"
                                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="page-header-title">
                                <span class="card-header-icon">
                                    <i class="tio-dashboard-outlined"></i>
                                </span>
                                <span> Food Details</span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6 col-lg-4">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlSelect1">Category</label>
                                        <select name="category_id" id="category_id" class="form-control ">
                                            <option value="" selected disabled>
                                                Select Category</option>
                                        </select>
                                    </div>
                                </div>
                                @php($menu = App\Models\RestaurantMenu::where('restaurant_id', Session::get('restaurant')->id)->isActive()->get())
                                <div class="col-sm-6 col-lg-4">
                                    <div class="form-group">
                                        <label class="input-label" fo.r="restaurant_menu_id">Menu<span
                                                class="input-label-secondary text-danger">*</span></label>

                                        <select name="restaurant_menu_id" id="restaurant_menu_id"
                                            onchange="get_options('{{route('vendor.food.get-submenu-option') . '?menu_id='}}'+this.value , '#sub-menu-input')"
                                            class="form-control ">
                                            <option value="" selected disabled>
                                                Select Menu</option>
                                            @foreach ($menu as $item)
                                                <option value="{{$item->id}}">
                                                    {{Str::ucfirst($item->name)}}
                                                </option>
                                            @endforeach()
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-lg-4">
                                    <div class="form-group">
                                        <label class="input-label" for="sub-menu-input">SubMenu<span
                                                class="input-label-secondary" data-toggle="tooltip"
                                                data-placement="right"
                                                data-original-title="Make sure you have selected a category first!"></span></label>

                                        <select name="restaurant_submenu_id" id="sub-menu-input"
                                            class="form-control js-select2-custom">
                                            <option value="" selected disabled>
                                                Select Sub Menu</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-lg-4">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">Item Type<span
                                                class="input-label-secondary text-danger">*</span></label>
                                        <select name="food_type" id="veg" class="form-control js-select2-custom">
                                            <option value="" selected disabled>
                                                Select Preferences</option>
                                            <option value="V">Veg</option>
                                            <option value="N">Non Veg</option>
                                            <option value="B">Both</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-4">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlSelect1">Addon<span
                                                class="input-label-secondary" data-toggle="tooltip"
                                                data-placement="right"
                                                data-original-title="Make sure you have selected a restaurant first!"></span></label>
                                        <select name="addon_ids[]" class="form-select select-2" multiple="multiple"
                                            id="add_on">

                                        </select>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="page-header-title">
                                <span class="card-header-icon"><i class="tio-dollar-outlined"></i></span>
                                <span>Discount</span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="discount_by">Discount By</label>
                                        <select name="discount_by" id="discount_by"
                                            class="form-control js-select2-custom">
                                            <option value="">Not Required</option>
                                            <option value="admin">Admin</option>
                                            <option value="restaurant">Restaurant</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="exampleFormControlInput1">Discount
                                            Type

                                        </label>
                                        <select name="discount_type" id="discount_type"
                                            class="form-control js-select2-custom">
                                            <option value="percent">Percent (%)</option>
                                            <option value="amount">Amount ($)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="exampleFormControlInput1">Discount
                                            <span class="input-label-secondary text--title" data-toggle="tooltip"
                                                data-placement="right"
                                                data-original-title="Currently you need to manage discount with the Restaurant.">
                                                <i class="tio-info-outined"></i>
                                            </span>
                                        </label>
                                        <input type="number" min="0" max="9999999999999999999999" name="discount"
                                            class="form-control" value="{{old('discount') ?? 0}}"
                                            placeholder="Enter Discount">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mb-3 px-3">
                        <div class="form-group form-check  form-switch mb-0">
                            <label class="input-label" for="isRecommended">Is Recommended ?</label>
                            <input type="checkbox" id="isRecommended" name="isRecommended"
                                onchange="this.value = this.checked?1:0" class="form-check-input form-control">
                        </div>

                        <div class="form-group form-check  form-switch mb-0">
                            <label class="input-label" for="isCustomizable">Is Customizable ?</label>
                            <input type="checkbox" id="isCustomizable" name="isCustomize"
                                onchange="this.value = this.checked?1:0" class="form-check-input form-control">
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="page-header-title">
                                <span class="card-header-icon"><i class="tio-dollar-outlined"></i></span>
                                <span>Amount</span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-md-4" data-food-mode="fixed">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="exampleFormControlInput1">Price</label>
                                        <input type="number" min="0" max="999999999999.99" step="0.01"
                                            name="restaurant_price" value="0" class="form-control"
                                            placeholder="Enter Price" required>
                                    </div>
                                </div>
                                <div class="col-md-4" data-food-mode="fixed">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="admin_margin">Admin Margin</label>
                                        <input type="number" min="0" max="999999999999.99" step="0.01" id="admin_margin"
                                            name="admin_margin" value="0" class="form-control"
                                            placeholder="Enter Margin" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="packing_charge">Packing Charge</label>
                                        <input type="number" min="0" max="999999999999.99" step="0.01"
                                            id="packing_charge" name="packing_charge" value="0" class="form-control"
                                            placeholder="Enter Packing Charge" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card d-none" data-food-mode="customize">
                        <div class="card-header">
                            <h5 class="page-header-title">
                                <span class="card-header-icon">
                                    <i class="tio-canvas-text"></i>
                                </span>
                                <span>Food Variations</span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-md-12">
                                    <div id="add_new_option">
                                        {{-- <div class="card view_new_option mb-2">
                                            <div class="card-header">
                                                <label for="" id="new_option_name_1"> Add New</label>
                                            </div>
                                            <div class="card-body">
                                                <div class="row g-2">
                                                    <div class="col-lg-3 col-md-6">
                                                        <label for="">Name</label>
                                                        <input required="" name="options[1][name]" class="form-control"
                                                            type="text" onkeyup="new_option_name(this.value,1)">
                                                    </div>

                                                    <div class="col-lg-3 col-md-6">
                                                        <div class="form-group">
                                                            <label
                                                                class="input-label text-capitalize d-flex alig-items-center"><span
                                                                    class="line--limit-1">Selcetion Type </span>
                                                            </label>
                                                            <div class="resturant-type-group border">
                                                                <label class="form-check form--check mr-2 mr-md-4">
                                                                    <input class="form-check-input" type="radio"
                                                                        value="multi" name="options[1][type]" id="type1"
                                                                        checked="" onchange="show_min_max(1)">
                                                                    <span class="form-check-label">
                                                                        Multiple
                                                                    </span>
                                                                </label>

                                                                <label class="form-check form--check mr-2 mr-md-4">
                                                                    <input class="form-check-input" type="radio"
                                                                        value="single" name="options[1][type]"
                                                                        id="type1" onchange="hide_min_max(1)">
                                                                    <span class="form-check-label">
                                                                        Single
                                                                    </span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-lg-6">
                                                        <div class="row g-2">
                                                            <div class="col-sm-6 col-md-4">
                                                                <label for="">Min</label>
                                                                <input id="min_max1_1" required=""
                                                                    name="options[1][min]" class="form-control"
                                                                    type="number" min="1">
                                                            </div>
                                                            <div class="col-sm-6 col-md-4">
                                                                <label for="">Max</label>
                                                                <input id="min_max2_1" required=""
                                                                    name="options[1][max]" class="form-control"
                                                                    type="number" min="1">
                                                            </div>

                                                            <div class="col-md-4">
                                                                <label class="d-md-block d-none">&nbsp;</label>
                                                                <div
                                                                    class="d-flex align-items-center justify-content-between">
                                                                    <div>
                                                                        <input id="options[1][required]"
                                                                            name="options[1][required]" type="checkbox">
                                                                        <label for="options[1][required]"
                                                                            class="m-0">Required</label>
                                                                    </div>
                                                                    <div>
                                                                        <button type="button"
                                                                            class="btn btn-danger btn-sm delete_input_button"
                                                                            onclick="removeOption(this)" title="Delete">
                                                                            <i class="tio-add-to-trash"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div id="option_price_1">
                                                    <div class="border rounded p-3 pb-0 mt-3">
                                                        <div id="option_price_view_1">
                                                            <div class="row g-3 add_new_view_row_class mb-3">
                                                                <div class="col-md-4 col-sm-6">
                                                                    <label for="">Option Name</label>
                                                                    <input class="form-control" required="" type="text"
                                                                        name="options[1][values][0][label]" id="">
                                                                </div>
                                                                <div class="col-md-4 col-sm-6">
                                                                    <label for="">Price</label>
                                                                    <input class="form-control" required=""
                                                                        type="number" min="0" step="0.01"
                                                                        name="options[1][values][0][optionPrice]" id="">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-3 p-3 mr-1 d-flex " id="add_new_button_1">
                                                            <button type="button" class="btn btn-outline-primary"
                                                                onclick="add_new_row_button(1)">Add New
                                                                Option</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> --}}
                                    </div>
                                    <br>
                                    <div class="mt-2">
                                        <a class="btn btn-outline-success" id="add_new_option_button">Add New
                                            Variation</a>
                                    </div> <br><br>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="page-header-title">
                                <span class="card-header-icon"><i class="tio-label"></i></span>
                                <span>Tags</span>
                            </h5>
                        </div>
                        <div class="card-body pb-0">
                            <div class="row g-2">
                                <div class="col-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="tags" placeholder="Enter tags"
                                            value="{{old('tags')}}" data-role="tagsinput">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="page-header-title">
                                <span class="card-header-icon"><i class="tio-date-range"></i></span>
                                <span>Time Schedule</span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-sm-6">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="exampleFormControlInput1">Available
                                            time
                                            starts</label>
                                        <input type="time" name="available_time_starts" class="form-control"
                                            id="available_time_starts" value="{{old('available_time_starts')}}"
                                            placeholder="Ex : 10:30 am" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="exampleFormControlInput1">Available
                                            time
                                            ends</label>
                                        <input type="time" name="available_time_ends" class="form-control"
                                            id="available_time_ends" placeholder="5:45 pm"
                                            value="{{old('available_time_ends')}}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-3 bg-white mb-3 rounded-2 text-end">
                        <button type="reset" id="reset_btn" class="btn btn-danger">Reset</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('javascript')
    <script>
        var count = 0;
        // var countRow=0;
        $(document).ready(function () {


            $("#add_new_option_button").click(function (e) {
                count++;
                var add_option_view = `
                                            <div class="card view_new_option mb-2" >
                                                <div class="card-header">
                                                    <label for="" id=new_option_name_` + count + `> Add New</label>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row g-2">
                                                        <div class="col-lg-3 col-md-6">
                                                            <label for="">Name</label>
                                                            <input required name=options[` + count +
                    `][name] class="form-control" type="text" onkeyup="new_option_name(this.value,` +
                    count + `)">
                                                        </div>

                                                        <div class="col-lg-3 col-md-6">
                                                            <div class="form-group">
                                                                <label class="input-label text-capitalize d-flex alig-items-center"><span class="line--limit-1">Selcetion Type </span>
                                                                </label>
                                                                <div class="resturant-type-group border">
                                                                    <label class="form-check form--check mr-2 mr-md-4">
                                                                        <input class="form-check-input" type="radio" value="multi"
                                                                        name="options[` + count + `][type]" id="type` + count +
                    `" checked onchange="show_min_max(` + count + `)"
                                                                        >
                                                                        <span class="form-check-label">
                                                                            Multiple
                                                                        </span>
                                                                    </label>

                                                                    <label class="form-check form--check mr-2 mr-md-4">
                                                                        <input class="form-check-input" type="radio" value="single"
                                                                        name="options[` + count + `][type]" id="type` + count +
                    `" onchange="hide_min_max(` + count + `)"
                                                                        >
                                                                        <span class="form-check-label">
                                                                            Single
                                                                        </span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-lg-6">
                                                            <div class="row g-2">
                                                                <div class="col-sm-6 col-md-4">
                                                                    <label for="">Min</label>
                                                                    <input id="min_max1_` + count + `" required  name="options[` + count + `][min]" class="form-control" type="number" min="1">
                                                                </div>
                                                                <div class="col-sm-6 col-md-4">
                                                                    <label for="">Max</label>
                                                                    <input id="min_max2_` + count + `"   required name="options[` + count + `][max]" class="form-control" type="number" min="1">
                                                                </div>

                                                                <div class="col-md-4">
                                                                    <label class="d-md-block d-none">&nbsp;</label>
                                                                        <div class="d-flex align-items-center justify-content-between">
                                                                        <div>
                                                                            <input id="options[` + count + `][required]" name="options[` + count + `][required]" type="checkbox">
                                                                            <label for="options[` + count + `][required]" class="m-0">Required</label>
                                                                        </div>
                                                                        <div>
                                                                            <button type="button" class="btn  btn-sm delete_input_button" onclick="removeOption(this)"
                                                                                title="Delete">
                                                                                <svg class="icon-32" width="25" style="color:red;" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M20.2871 5.24297C20.6761 5.24297 21 5.56596 21 5.97696V6.35696C21 6.75795 20.6761 7.09095 20.2871 7.09095H3.71385C3.32386 7.09095 3 6.75795 3 6.35696V5.97696C3 5.56596 3.32386 5.24297 3.71385 5.24297H6.62957C7.22185 5.24297 7.7373 4.82197 7.87054 4.22798L8.02323 3.54598C8.26054 2.61699 9.0415 2 9.93527 2H14.0647C14.9488 2 15.7385 2.61699 15.967 3.49699L16.1304 4.22698C16.2627 4.82197 16.7781 5.24297 17.3714 5.24297H20.2871ZM18.8058 19.134C19.1102 16.2971 19.6432 9.55712 19.6432 9.48913C19.6626 9.28313 19.5955 9.08813 19.4623 8.93113C19.3193 8.78413 19.1384 8.69713 18.9391 8.69713H5.06852C4.86818 8.69713 4.67756 8.78413 4.54529 8.93113C4.41108 9.08813 4.34494 9.28313 4.35467 9.48913C4.35646 9.50162 4.37558 9.73903 4.40755 10.1359C4.54958 11.8992 4.94517 16.8102 5.20079 19.134C5.38168 20.846 6.50498 21.922 8.13206 21.961C9.38763 21.99 10.6811 22 12.0038 22C13.2496 22 14.5149 21.99 15.8094 21.961C17.4929 21.932 18.6152 20.875 18.8058 19.134Z" fill="currentColor"></path></svg>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div id="option_price_` + count + `" >
                                                        <div class="border rounded p-3 pb-0 mt-3">
                                                            <div  id="option_price_view_` + count + `">
                                                                <div class="row g-3 add_new_view_row_class mb-3">
                                                                    <div class="col-md-4 col-sm-6">
                                                                        <label for="">Option Name</label>
                                                                        <input class="form-control" required type="text" name="options[` + count + `][values][0][label]" id="">
                                                                    </div>
                                                                    <div class="col-md-4 col-sm-6">
                                                                        <label for="">Price</label>
                                                                        <input class="form-control" required type="number" min="0" step="0.01" name="options[` + count + `][values][0][optionPrice]" id="">
                                                                    </div>
                                                                    <div class="col-md-4 col-sm-6">
                                                                        <label for="">Margin</label>
                                                                        <input class="form-control" required type="number" min="0" step="0.01" name="options[` + count + `][values][0][optionMargin]" id="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row mt-3 p-3 mr-1 d-flex "  id="add_new_button_` + count + `">
                                                                <div class="col-md-12">
                                                                    <button type="button" class="btn btn-outline-primary" onclick="add_new_row_button(` +
                    count + `)" >Add New Option</button>
                                                                    </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>`;

                $("#add_new_option").append(add_option_view);
            });
        });

        function show_min_max(data) {
            $('#min_max1_' + data).removeAttr("readonly");
            $('#min_max2_' + data).removeAttr("readonly");
            $('#min_max1_' + data).attr("required", "true");
            $('#min_max2_' + data).attr("required", "true");
        }

        function hide_min_max(data) {
            $('#min_max1_' + data).val(null).trigger('change');
            $('#min_max2_' + data).val(null).trigger('change');
            $('#min_max1_' + data).attr("readonly", "true");
            $('#min_max2_' + data).attr("readonly", "true");
            $('#min_max1_' + data).attr("required", "false");
            $('#min_max2_' + data).attr("required", "false");
        }




        function new_option_name(value, data) {
            $("#new_option_name_" + data).empty();
            $("#new_option_name_" + data).text(value)
            console.log(value);
        }

        function removeOption(e) {
            element = $(e);
            element.parents('.view_new_option').remove();
        }

        function deleteRow(e) {
            element = $(e);
            element.parents('.add_new_view_row_class').remove();
        }


        function add_new_row_button(data) {
            count = data;
            countRow = 1 + $('#option_price_view_' + data).children('.add_new_view_row_class').length;
            var add_new_row_view = `
                                                    <div class="row add_new_view_row_class mb-3 position-relative pt-3 pt-sm-0">
                                                        <div class="col-md-4 col-sm-5">
                                                                <label for="">Option Name</label>
                                                                <input class="form-control" required type="text" name="options[` + count + `][values][` +
                countRow + `][label]" id="">
                                                            </div>
                                                            <div class="col-md-4 col-sm-5">
                                                                <label for="">Price</label>
                                                                <input class="form-control"  required type="number" min="0" step="0.01" name="options[` + count +
                `][values][` + countRow + `][optionPrice]" id="">
                                                            </div>
                                                            <div class="col-md-4 col-sm-5">
                                                                <label for="">Margin</label>
                                                                <input class="form-control"  required type="number" min="0" step="0.01" name="options[` + count +
                `][values][` + countRow + `][optionMargin]" id="">
                                                            </div>
                                                            <div class="col-sm-2 max-sm-absolute">
                                                                <label class="d-none d-sm-block">&nbsp;</label>
                                                                <div class="mt-1">
                                                                    <button type="button" class="btn  btn-sm" onclick="deleteRow(this)"
                                                                        title="Delete">
                                                                        <svg class="icon-32" width="25" style="color:red;" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M20.2871 5.24297C20.6761 5.24297 21 5.56596 21 5.97696V6.35696C21 6.75795 20.6761 7.09095 20.2871 7.09095H3.71385C3.32386 7.09095 3 6.75795 3 6.35696V5.97696C3 5.56596 3.32386 5.24297 3.71385 5.24297H6.62957C7.22185 5.24297 7.7373 4.82197 7.87054 4.22798L8.02323 3.54598C8.26054 2.61699 9.0415 2 9.93527 2H14.0647C14.9488 2 15.7385 2.61699 15.967 3.49699L16.1304 4.22698C16.2627 4.82197 16.7781 5.24297 17.3714 5.24297H20.2871ZM18.8058 19.134C19.1102 16.2971 19.6432 9.55712 19.6432 9.48913C19.6626 9.28313 19.5955 9.08813 19.4623 8.93113C19.3193 8.78413 19.1384 8.69713 18.9391 8.69713H5.06852C4.86818 8.69713 4.67756 8.78413 4.54529 8.93113C4.41108 9.08813 4.34494 9.28313 4.35467 9.48913C4.35646 9.50162 4.37558 9.73903 4.40755 10.1359C4.54958 11.8992 4.94517 16.8102 5.20079 19.134C5.38168 20.846 6.50498 21.922 8.13206 21.961C9.38763 21.99 10.6811 22 12.0038 22C13.2496 22 14.5149 21.99 15.8094 21.961C17.4929 21.932 18.6152 20.875 18.8058 19.134Z" fill="currentColor"></path></svg>
                                                                    </button>
                                                                </div>
                                                        </div>
                                                    </div>`;
            $('#option_price_view_' + data).append(add_new_row_view);

        }

        $('#isCustomizable').on('change', function (event) {
            event.stopPropagation();

            const customizableElement = $('[data-food-mode=customize]');
            const noncustomizableElement = $('[data-food-mode=fixed]');

            if (event.target.checked) {
                customizableElement.hasClass('d-none') ? customizableElement.removeClass('d-none') : null;
                !noncustomizableElement.hasClass('d-none') ? noncustomizableElement.addClass('d-none') : null;
            } else { // Corrected 'esle' typo
                !customizableElement.hasClass('d-none') ? customizableElement.addClass('d-none') : null;
                noncustomizableElement.hasClass('d-none') ? noncustomizableElement.removeClass('d-none') : null; // Corrected noncustomizableElement logic
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            try {
                const resp = await fetch('{{ route("vendor.category.get_categories") }}');
                const result = await resp.json();
                if (resp.ok && result !== null) {
                    console.log(result);
                    const fragment = document.createDocumentFragment();
                    result.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item.id;
                        option.textContent = item.name;
                        fragment.appendChild(option);
                    });
                    document.querySelector('#category_id').appendChild(fragment);
                }
            } catch (error) {
                console.error('Error fetching data:', error);
            }
        });

    </script>
    <script>
        const discountBy = document.getElementById('discount_by');
        const discountType = document.getElementById('discount_type')
        const discountSelection = {
            discountBy: discountType.value,
            discountType: discountType.value,
            updateDiscountType: function () {
                for (let option of discountType.options) {
                    option.disabled = false;
                    if (this.discountBy == "restaurant" && option.value == "percent") {
                        option.disabled = true;
                        option.selected = false;
                    }
                }
            }
        }

        discountBy.addEventListener('change', () => {
            discountSelection.discountBy = discountBy.value;
            discountSelection.updateDiscountType();
        });
        discountSelection.updateDiscountType();

        async function get_options(url, element_id) {
            try {
                const resp = await fetch(url);
                const result = await resp.json();
                if (resp.ok && result !== null) {
                    console.log(result);
                    const fragment = document.createDocumentFragment();
                    result.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item.id;
                        option.textContent = item.name;
                        fragment.appendChild(option);
                    });
                    const targetElement = document.querySelector(element_id);
                    targetElement.innerHTML = '<option value="" desabled >Choose One....</option>';
                    targetElement.appendChild(fragment);
                }
            } catch (error) {
                console.error('Error fetching data:', error);
            }
        }


        get_options("{{route('vendor.addon.get_addons')}}/", '#add_on')

    </script>
    <script>
        function validateForm() {
            let isValid = true;
            return isValid;

        }
    </script>

@endpush
