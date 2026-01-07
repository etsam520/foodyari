@extends('layouts.dashboard-main')
@push('css')
<style>
    span.select2.select2-container{
        width: 100% !important;
    }
    .uses-link {
        color: #007bff !important;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .uses-link:hover {
        color: #0056b3 !important;
        text-decoration: underline;
    }
</style>
@endpush

@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">{{__('messages.add')}} {{__('messages.new')}} {{__('messages.coupon')}}</h4>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{route('admin.coupon.store')}}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-lg-3 col-sm-6">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{__('messages.title')}}</label>
                                    <input id="coupon_title" type="text" name="title" class="form-control" placeholder="{{__('messages.Coupon Name')}}" required maxlength="191">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="input-label" for="description">Description</label>
                                    <textarea type="text" id="description" name="description" class="form-control" ></textarea>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{__('messages.coupon')}} {{__('messages.type')}}</label>
                                    <select id="coupon_type" name="coupon_type" class="form-control" onchange="coupon_type_change(this.value)">
                                        <option value="" selected desabled>Select One</option>
                                        <option value="restaurant_wise">{{__('messages.restaurant')}} {{__('messages.wise')}}</option>
                                        <option value="zone_wise">{{__('messages.zone')}} {{__('messages.wise')}}</option>
                                        <option value="free_delivery">{{__('messages.free_delivery')}}</option>
                                        <option value="first_order">{{__('messages.first')}} {{__('messages.order')}}</option>
                                        <option value="default">{{__('messages.default')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-lg-3 col-sm-6" id="restaurant_wise">
                                <label class="input-label" for="exampleFormControlSelect1">{{__('messages.restaurant')}}<span
                                        class="input-label-secondary"></span></label>
                                <select id="select_restaurant" name="restaurant_ids[]" class="js-data-example-ajax select-2 form-control" data-placeholder="{{__('messages.select_restaurant')}}" title="{{__('messages.select_restaurant')}}">
                                    <option value="">{{__('messages.select_restaurant')}}</option>

                                    @foreach(\App\Models\Restaurant::where('status',1)->get() as $restaurant)
                                        <option value="{{$restaurant->id}}">{{$restaurant->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-lg-3 col-sm-6" id="customer_wise">
                                <label class="input-label" for="select_customer">{{__('messages.select_customer')}}</label>
                                <select name="customer_ids[]" id="select_customer"
                                    class="form-control js-select2-custom select-2"
                                    multiple="multiple" data-placeholder="{{__('messages.select_customer')}}">
                                    <option value="all">{{__('messages.all')}} </option>
                                @foreach(\App\Models\Customer::get(['id','f_name','l_name','phone']) as $user)
                                    <option value="{{$user->id}}">{{$user->f_name. ' ' .$user->l_name.' (' .$user->phone.')'}}</option>
                                @endforeach
                                </select>
                            </div>



                            <div class="form-group col-lg-3 col-sm-6" id="zone_wise">
                                <label class="input-label" for="exampleFormControlInput1">{{__('messages.select')}} {{__('messages.zone')}}</label>
                                <select name="zone_ids[]" id="choice_zones"
                                    class="form-control js-select2-custom select-2"
                                    multiple="multiple" data-placeholder="{{__('messages.select_zone')}}">
                                @foreach(\App\Models\Zone::where('status',1)->get(['id','name']) as $zone)
                                    <option value="{{$zone->id}}">{{$zone->name}}</option>
                                @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3 col-sm-6">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{__('messages.code')}}</label>
                                    <input id="coupon_code" type="text" name="code" class="form-control"
                                        placeholder="{{\Illuminate\Support\Str::random(8)}}" required maxlength="100">
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{__('messages.limit')}} {{__('messages.for')}} {{__('messages.same')}} {{__('messages.user')}}</label>
                                    <input type="number" name="limit" id="coupon_limit" class="form-control" placeholder="{{ __('messages.Ex :') }} 10" max="100000">
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{__('messages.start')}} {{__('messages.date')}}</label>
                                    <input type="date" name="start_date" class="form-control" id="date_from" required>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{__('messages.expire')}} {{__('messages.date')}}</label>
                                    <input type="date" name="expire_date" class="form-control" id="date_to" required>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{__('messages.discount')}} {{__('messages.type')}}</label>
                                    <select name="discount_type" required class="form-control" id="discount_type">
                                        <option value="amount">
                                                {{ __('messages.amount').' ('.\App\CentralLogics\Helpers::currency_symbol().')'  }}
                                        </option>
                                        <option value="percent"> {{ __('messages.percent').' (%)' }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{__('messages.discount')}}
                                        {{-- <span class="input-label-secondary text--title" data-toggle="tooltip"
                                            data-placement="right"
                                            data-original-title="{{ __('Currently you need to manage discount with the Restaurant.') }}">
                                            <i class="tio-info-outined"></i>
                                        </span> --}}
                                </label>
                                    <input type="number" step="0.01" min="1" max="999999999999.99" name="discount" id="discount" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6">
                                <div class="form-group">
                                    <label class="input-label" for="max_discount">{{__('messages.max')}} {{__('messages.discount')}}</label>
                                    <input type="number" step="0.01" min="0" value="0" max="999999999999.99" name="max_discount" id="max_discount" class="form-control"
                                    >
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{__('messages.min')}} {{__('messages.purchase')}}</label>
                                    <input id="min_purchase" type="number" step="0.01" name="min_purchase" value="0" min="0" max="999999999999.99" class="form-control"
                                        placeholder="100">
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3" id="deliveryRange" style="display:'none';">
                                <div class="form-group">
                                    <label class="input-label" for="deliveryRange-1">Delivery Range(in Km.)</label>
                                    <input id="deliveryRange-1" type="number" name="delivery_range" step="0.01" 
                                            min="0" max="999999999999.99" class="form-control"
                                            placeholder="100">
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3" id="enbleExtraDistance" style="display:none;">
                                <div class="form-check d-flex">
                                    <input id="enbleExtraDistance-1" type="checkbox" name="enble_ext_distance" onchange="this.value=this.checked?1:0"
                                             class="form-check-input me-2">
                                    <label class="form-check-label" for="enbleExtraDistance-1">Enable Free Delivery + Extra Distance</label>
                                </div>
                            </div>
                        </div>
                        <div class="btn--container justify-content-end">
                            <button id="reset_btn" type="button" class="btn btn--reset">{{__('messages.reset')}}</button>
                            <button type="submit" class="btn btn--primary">{{__('messages.submit')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header py-2">
                    <div class="search-button-wrapper">
                        <h5 class="card-title">{{__('messages.coupon')}} {{__('messages.list')}}<span class="badge badge-primary ml-2" id="itemCount">{{$coupons->total()}}</span></h5>
                    </div>
                </div>
                <!-- Table -->
                <div class="table-responsive" >
                    <table id="datatable"
                            class="table" data-toggle="data-table">
                        <thead class="thead-light">
                        <tr>
                            <th>{{ __('messages.sl') }}</th>
                            <th>{{__('messages.title')}}</th>
                            <th>{{__('messages.code')}}</th>
                            <th>{{__('messages.type')}}</th>
                            <th>{{__('messages.total_uses')}}</th>
                            <th>{{__('messages.min')}} {{__('messages.purchase')}}</th>
                            <th>{{__('messages.max')}} {{__('messages.discount')}}</th>
                            <th>
                                <div class="text-center">
                                    {{__('messages.discount')}}
                                </div>
                            </th>
                            <th>{{__('messages.discount')}} {{__('messages.type')}}</th>
                            <th>{{__('messages.start')}} {{__('messages.date')}}</th>
                            <th>{{__('messages.expire')}} {{__('messages.date')}}</th>
                            {{-- <th>{{__('messages.created_by')}}</th> --}}
                            <th>{{__('messages.Customer_type')}}</th>
                            <th>{{__('messages.status')}}</th>
                            <th class="text-center">{{__('messages.action')}}</th>
                        </tr>
                        </thead>

                        <tbody id="set-rows">
                        @foreach($coupons as $key=>$coupon)
                            <tr>
                                <td>{{$key+$coupons->firstItem()}}</td>
                                <td>
                                <span class="d-block font-size-sm text-body">
                                    {{Str::limit($coupon['title'],15,'...')}}
                                </span>
                                </td>
                                <td>{{$coupon['code']}}</td>
                                <td>{{__('messages.'.$coupon->coupon_type)}}</td>
                                <td>
                                    <a href="{{route('admin.coupon.uses-details', $coupon->id)}}" 
                                       class="uses-link" 
                                       title="{{__('messages.view')}} {{__('messages.uses')}} {{__('messages.details')}}">
                                        {{$coupon->total_uses}}
                                    </a>
                                </td>
                                <td>
                                    <div class="text-right mw-87px">
                                        {{\App\CentralLogics\Helpers::format_currency($coupon['min_purchase'])}}
                                    </div>
                                </td>
                                <td>
                                    <div class="text-right mw-87px">
                                        {{\App\CentralLogics\Helpers::format_currency($coupon['max_discount'])}}
                                    </div>
                                </td>
                                <td>
                                    <div class="text-center">
                                        {{$coupon['discount']}}
                                    </div>
                                </td>
                                @if ($coupon['discount_type'] == 'percent')
                                <td>{{ __('messages.percent')}}</td>
                                @elseif ($coupon['discount_type'] == 'amount')
                                <td>{{ __('messages.amount')}}</td>
                                @else
                                <td>{{$coupon['discount_type']}}</td>
                                @endif

                                <td>{{$coupon['start_date']}}</td>
                                <td>{{$coupon['expire_date']}}</td>

                                <td>
                                    <span class="d-block font-size-sm text-body">
                                        @if (in_array('all', json_decode($coupon->customer_id)))
                                        {{__('messages.all')}} {{__('messages.customers')}}
                                        @else
                                        {{__('messages.Selected')}} {{__('messages.customers')}}
                                        @endif
                                    </span>
                                </td>
                                <td>
                                    <label class="form-check form-switch" for="couponCheckbox{{$coupon->id}}">
                                        <input type="checkbox" onclick="location.href='{{route('admin.coupon.status',[$coupon['id'],$coupon->status?0:1])}}'" class="form-check-input" id="couponCheckbox{{$coupon->id}}" {{$coupon->status?'checked':''}}>
                                        <span class="toggle-switch-label">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                    </label>
                                </td>
                                <td>
                                    <div class="btn--container justify-content-center">
                                        <a class="btn btn-sm btn-outline-primary action-btn" href="{{route('admin.coupon.update',[$coupon['id']])}}"title="{{__('messages.edit')}} {{__('messages.coupon')}}">
                                            <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82812 10.921Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M15.1655 4.60254L19.7315 9.16854" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                             </svg>
                                        </a>
                                        <a class="btn btn-sm  btn-outline-danger action-btn"  href="{{route('admin.coupon.delete',[$coupon['id']])}}" onclick="form_alert(this,'{{__('Want to delete this Coupon')}}')" title="{{__('messages.delete')}} {{__('messages.coupon')}}">
                                            <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor">
                                                <path d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M20.708 6.23975H3.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @if(count($coupons) === 0)
                    <div class="empty--data">
                        <img src="{{asset('/public/assets/admin/img/empty.png')}}" alt="public">
                        <h5>
                            {{__('no_data_found')}}
                        </h5>
                    </div>
                    @endif
                    <div class="page-area px-4 pb-3">
                        <div class="d-flex align-items-center justify-content-end">
                            <div>
                                {!! $coupons->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


@endsection

@push('javascript')
<script>
    $("#date_from").on("change", function () {
        $('#date_to').attr('min',$(this).val());
    });

    $("#date_to").on("change", function () {
        $('#date_from').attr('max',$(this).val());
    });

    $(document).on('ready', function () {
        $('#discount_type').on('change', function() {
         if($('#discount_type').val() == 'amount')
            {
                $('#max_discount').attr("readonly","true");
                $('#max_discount').val(0);
            }
            else
            {
                $('#max_discount').removeAttr("readonly");
            }
        });

        $('#date_from').attr('min',(new Date()).toISOString().split('T')[0]);
        $('#date_to').attr('min',(new Date()).toISOString().split('T')[0]);


        $('#zone_wise').hide();
     });
        function coupon_type_change(coupon_type) {
           if(coupon_type=='zone_wise')
            {
                $('#restaurant_wise').hide();
                $('#customer_wise').hide();
                $('#select_customer').val(null).trigger('change');
                $('#zone_wise').show();
            }
            else if(coupon_type=='restaurant_wise')
            {
                $('#restaurant_wise').show();
                $('#customer_wise').show();
                $('#zone_wise').hide();
            }
            else if(coupon_type=='first_order')
            {
                $('#zone_wise').hide();
                $('#restaurant_wise').hide();
                $('#coupon_limit').val(1);
                $('#coupon_limit').attr("readonly","true");
                $('#select_customer').val(null).trigger('change');
                $('#customer_wise').hide();
            }
            else{
                $('#zone_wise').hide();
                $('#restaurant_wise').hide();
                $('#coupon_limit').val('');
                $('#coupon_limit').removeAttr("readonly");
                $('#customer_wise').show();
            }

            if(coupon_type=='free_delivery')
            {
                $('#discount_type').attr("disabled","true");
                $('#discount_type').val("").trigger( "change" );
                $('#discount_type').attr("required","false");
                $('#max_discount').val(0);
                $('#max_discount').attr("readonly","true");
                $('#discount').val(0);
                $('#discount').attr("readonly","true");
                $('#deliveryRange').show();
                $('#enbleExtraDistance').show();

            }
            else{
                $('#max_discount').removeAttr("readonly");
                $('#discount_type').removeAttr("disabled");
                $('#discount').removeAttr("readonly");
                $('#deliveryRange').hide();
                $('#deliveryRange input').val(0);
                $('#enbleExtraDistance').hide();
                $('#enbleExtraDistance input').prop('checked', false);
            }
        }

    </script>
    <script>
        $('#reset_btn').click(function(){
            $('#coupon_title').val('');
            $('#coupon_type').val('restaurant_wise');
            $('#restaurant_wise').show();
            $('#zone_wise').hide();
            $('#coupon_code').val(null);
            $('#coupon_limit').val(null);
            $('#date_from').val(null);
            $('#date_to').val(null);
            $('#discount_type').val('amount');
            $('#discount').val(null);
            $('#max_discount').val(0);
            $('#min_purchase').val(0);
            $('#select_restaurant').val(null).trigger('change');
            $('#choice_zones').val(null).trigger('change');
            $('#select_customer').val(null).trigger('change');
        })

    </script>
    <script>
        function form_alert(item, message) {
            event.preventDefault();  // Prevent the default anchor action (e.g., navigating to href)

            Swal.fire({
                title: '{{ __('messages.Are you sure ?') }}',
                text: message,
                icon: 'warning',  // Correct SweetAlert syntax
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#FC6A57',
                cancelButtonText: '{{ __('messages.No') }}',
                confirmButtonText: '{{ __('messages.Yes') }}',
                reverseButtons: true
            }).then((result) => {
        if (result.isConfirmed) {
            // Create a dynamic form
            const form = document.createElement('form');
            form.method = 'POST'; // Use POST for compatibility
            form.action = item.getAttribute('href'); // Set the URL from the href attribute

            // Add a hidden _method input to simulate DELETE
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);

            // Add a CSRF token if required (for Laravel or other frameworks)
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            if (csrfToken) {
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                form.appendChild(csrfInput);
            }

            // Append the form to the body and submit it
            document.body.appendChild(form);
            form.submit();
        }
    });
        }
    </script>
@endpush
