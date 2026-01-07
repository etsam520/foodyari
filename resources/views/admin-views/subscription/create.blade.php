@extends('layouts.dashboard-main')

@section('content')
    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div>
            
            <div class="row">
                <div class="col-sm-12">
                    <div class="card ">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                            <h4 class="card-title">Create Subscription Package</h4>
                            </div>
                        </div>
                        <form action="{{route('admin.subscription.subscription_store')}}" method="POST">
                            @csrf
                            <div class="card mb-3 ">
                                <div class="card-header">
                                    <h5 class="card-title d-flex align-items-center font-medium">
                                        <span class="card-header-icon mr-1">
                                            <img src="{{asset('assets/images/icons/ion_information-circle-sharp.svg')}}" alt="">
                                        </span>
                                        <span>
                                            General Information
                                        </span>
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <div class="form-group mb-0">
                                                <label class="form-label input-label qcont" for="name">Package Name</label>
                                                <input type="text" name="package_name" class="form-control" id="name" placeholder="Package Name" required="" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-0">
                                                <label class="form-label input-label qcont" for="package_price">Package Price {{App\CentralLogics\Helpers::currency_symbol()}}</label>
                
                                                <input type="number" name="package_price" class="form-control" id="package_price" min="1" step="0.01" aria-describedby="emailHelp" placeholder="Package price" required="" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-0">
                                                <label class="form-label input-label qcont" for="package_validity">Package Validity
                                                    Days</label>
                                                <input type="number" name="package_validity" class="form-control" id="package_validity" aria-describedby="emailHelp" placeholder="Package Validity" min="1" ,step="1" required="" value="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label for="">Choose Subscription Available For</label>
                                            <div class="form-group">
                                                <div class="form-check form-check-inline mt-4">
                                                    <input class="form-check-input" type="radio" name="subscription_for" id="restaurant_subscription"  checked="" value="restaurant">
                                                    <label class="form-check-label text-dark" for="restaurant_subscription">Restaurant</label>
                                                </div>
                                                <div class="form-check form-check-inline mt-4">
                                                    <input class="form-check-input" type="radio" name="subscription_for" id="restaurant_subscription" value="mess">
                                                    <label class="form-check-label text-dark" for="restaurant_subscription">Mess</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label input-label qcont text-capitalize" for="package_info">Package Info</label>
                                                <textarea class="form-control" name="text" id="package_info"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-0">
                                                <div class="col-sm-4">
                                                    <label class="form-label input-label qcont text-capitalize" for="package_price">Choose Color</label>
                                                    <input name="colour" type="color" class="form-control form-control-color" value="#ed9d24">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>    
                            </div>
                            <div class="card mb-3">
                                <div class="card-header d-flex align-items-baseline">
                                    <h5 class="card-title d-flex align-items-center font-medium">
                                        <span class="card-header-icon mr-1">
                                            <img src="{{asset('assets/images/icons/feature.svg')}}" alt="">
                                        </span>
                                        <span>
                                            Select Features
                                        </span>
                                    </h5>
                                    <div class="form-group form-check form-check m-0  mx-2 mr-auto">
                                        <input type="checkbox" name="features[]" value="account" class="form-check-input" id="select-all">
                                        <label class="form-check-label ml-2" for="select-all">Select All</label>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="check-item-wrapper mt-0 d-flex align-items-baseline">
                                        <div class="check-item mx-2">
                                            <div class="form-group form-check form-check">
                                                <input type="checkbox" name="pos_system" value="1" class="form-check-input" id="pos_system">
                                                <label class="form-check-label ml-2 ml-sm-3 qcont text-dark" for="pos_system">Pos System</label>
                                            </div>
                                        </div>
                
                                        <div class="check-item mx-2">
                                            <div class="form-group form-check form--check">
                                                <input type="checkbox" name="self_delivery" value="1" class="form-check-input" id="self_delivery">
                                                <label class="form-check-label ml-2 ml-sm-3 qcont text-dark" for="self_delivery">Self Delivery</label>
                                            </div>
                                        </div>
                
                                        <div class="check-item mx-2">
                                            <div class="form-group form-check form--check">
                                                <input type="checkbox" name="mobile_app" value="1" class="form-check-input" id="mobile_app">
                                                <label class="form-check-label ml-2 ml-sm-3 qcont text-dark" for="mobile_app">Mobile App</label>
                                            </div>
                                        </div>
                                        <div class="check-item mx-2">
                                            <div class="form-group form-check form--check">
                                                <input type="checkbox" name="review" value="1" class="form-check-input" id="review">
                                                <label class="form-check-label ml-2 ml-sm-3 qcont text-dark" for="review">Review</label>
                                            </div>
                                        </div>
                
                
                                        <div class="check-item mx-2">
                                            <div class="form-group form-check form--check">
                                                <input type="checkbox" name="chat" value="1" class="form-check-input" id="chat">
                                                <label class="form-check-label ml-2 ml-sm-3 qcont text-dark" for="chat">Chat</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
    
                            <div class="card ">
                                <div class="card-header">
                                    <h5 class="card-title d-flex align-items-center font-medium">
                                        <span class="card-header-icon mr-1">
                                            <img src="{{asset('assets/images/icons/dollar.svg')}}" alt="">
                                        </span>
                                        <span>
                                            Set Limit
                                        </span>
                                    </h5>
                
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-sm col-lg-4">
                                            <div class="form-group m-0">
                                                <label class="form-label text-capitalize input-label font-medium" for="name">Maximum Order Limit</label> <br>
                                                <div class="form-check form-check-inline mt-4">
                                                    <input class="form-check-input" type="radio" name="Maximum_Order_Limited" id="Maximum_Order_Limit_unlimited" onclick="hide_order_input()" checked="" value="option1">
                                                    <label class="form-check-label text-dark" for="Maximum_Order_Limit_unlimited">Unlimited
                                                        (Default)</label>
                                                </div>
                                                {{-- &nbsp; --}}
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="Maximum_Order_Limited" id="Maximum_Order_Limited" onclick="show_order_input()" value="option2">
                                                    <label class="form-check-label text-dark" for="Maximum_Order_Limited">Use Limit</label>
                                                </div>
                                            </div>
                                            <div class="form-group mt-4 m-0">
                                                <input type="number" name="max_order" min="1" step="1" hidden="" id="max_o" class="form-control" placeholder="Ex : 1000 ">
                                            </div>
                                        </div>
                                        <div class="col-md-sm col-lg-4">
                                            <div class="form-group m-0">
                                                <label class="form-label text-capitalize input-label font-medium" for="name">Maximum product Limit</label> <br>
                                                    <div class="form-check form-check-inline mt-4">
                                                        <input class="form-check-input" type="radio" name="Maximum_product_Limit" id="Maximum_product_Limit_unlimited" onclick="hide_product_input()" checked="">
                                                        <label class="form-check-label text-dark" for="Maximum_product_Limit_unlimited">Unlimited
                                                            (Default)</label>
                                                    </div>
                                                    &nbsp;
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="Maximum_product_Limit" id="Maximum_Product_Limited" onclick="show_product_input()">
                                                        <label class="form-check-label text-dark" for="Maximum_Product_Limited">Use Limit</label>
                                                    </div>
                                                    <div class="form-group mt-4 m-0">
                                                <input type="number" hidden="" name="max_product" min="1" step="1" class="form-control" id="max_p" placeholder="Ex : 1000 ">
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
    
                            <div class="mt-4 mx-3 pb-3">
                                <div class="btn-container justify-content-end">
                                    <button type="reset" id="reset_btn" class="btn btn-outline-secondary">
                                        Reset
                                    </button>
                                    <button type="submit" class="btn btn-outline-primary">Submit</button>
                                </div>
                            </div>
                        </form>
                   </div> 
                </div>
            </div>
        </div>
    </div>
@endsection

@push('javascript')
<script>

    function show_order_input(){
            $('#max_o').removeAttr("hidden");
        }
    function hide_order_input(){
            $('#max_o').attr("hidden","true");
            $('#max_o').val(null).trigger('change');
        }
    function show_product_input(){
            $('#max_p').removeAttr("hidden");
        }
    function hide_product_input(){
            $('#max_p').attr("hidden","true");
            $('#max_p').val(null).trigger('change');
        }

        $('#select-all').on('change', function() {
            if (this.checked === true) {
                $('.check-item-wrapper .check-item .form-check-input').attr('checked', true)
            } else {
                $('.check-item-wrapper .check-item .form-check-input').attr('checked', false)
            }
        })

        $('#reset_btn').click(function() {
            location.reload(true);
        })
    </script>    
@endpush
