@extends('layouts.dashboard-main')

@push('css')
<style>
    span.select2.select2-container{
        width: 100% !important;
    }

</style>
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-edit"></i> {{__('messages.coupon')}} {{__('messages.update')}}</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="card">
            <div class="card-body">
                <form action="{{route('admin.coupon.update',[$coupon['id']])}}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6 col-lg-3">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{__('messages.title')}}</label>
                                <input id="coupon_title" type="text" name="title" value="{{$coupon['title']}}" class="form-control"
                                        placeholder="{{__('messages.new_coupon')}}" required maxlength="191">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="input-label" for="description">Description</label>
                                <textarea type="text" id="description" name="description" class="form-control" >{{$coupon->description??null}}</textarea>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{__('messages.coupon')}} {{__('messages.type')}}</label>
                                <select id="coupon_type" name="coupon_type" class="form-control" onchange="coupon_type_change(this.value)">
                                    <option value="restaurant_wise" {{$coupon['coupon_type']=='restaurant_wise'?'selected':''}}>{{__('messages.restaurant')}} {{__('messages.wise')}}</option>
                                    <option value="zone_wise" {{$coupon['coupon_type']=='zone_wise'?'selected':''}}>{{__('messages.zone')}} {{__('messages.wise')}}</option>
                                    <option value="free_delivery" {{$coupon['coupon_type']=='free_delivery'?'selected':''}}>{{__('messages.free_delivery')}}</option>
                                    <option value="first_order" {{$coupon['coupon_type']=='first_order'?'selected':''}}>{{__('messages.first')}} {{__('messages.order')}}</option>
                                    <option value="default" {{$coupon['coupon_type']=='default'?'selected':''}}>{{__('messages.default')}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-sm-6 col-lg-3" id="restaurant_wise" style="display: {{$coupon['coupon_type']=='restaurant_wise'?'block':'none'}}">
                            <label class="input-label" for="exampleFormControlSelect1">{{__('messages.restaurant')}}<span
                                    class="input-label-secondary"></span></label>
                            <select name="restaurant_ids[]" class="js-data-example-ajax form-control"  title="Select Restaurant">
                                @if($coupon->coupon_type == 'restaurant_wise')
                                @php($restaurant=\App\Models\Restaurant::find(json_decode($coupon->data)[0]))
                                @if($restaurant)
                                <option value="{{$restaurant->id}}">{{$restaurant->name}}</option>
                                @endif
                                @else
                                <option selected>{{__('select_restaurant')}}</option>
                                @endif
                                @php($restaurants = \App\Models\Restaurant::isActive()->get())
                                @foreach ($restaurants as $res)
                                    <option value="{{ $res->id }}">{{ $res->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-sm-6 col-lg-3" id="zone_wise" style="display: {{$coupon['coupon_type']=='zone_wise'?'block':'none'}}">
                            <label class="input-label" for="exampleFormControlInput1">{{__('messages.select')}} {{__('messages.zone')}}</label>
                            <select name="zone_ids[]" id="choice_zones"
                                class="form-control js-select2-custom"
                                multiple="multiple" placeholder="{{__('messages.select_zone')}}">
                            @foreach(\App\Models\Zone::where('status',1)->get(['id','name']) as $zone)
                                <option value="{{$zone->id}}" {{($coupon->coupon_type=='zone_wise'&&json_decode($coupon->data))?(in_array($zone->id, json_decode($coupon->data))?'selected':''):''}}>{{$zone->name}}</option>
                            @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6 col-lg-3" >
                            <div class="form-group" id="customer_wise" style="display: {{$coupon['coupon_type'] =='zone_wise' || $coupon['coupon_type'] =='first_order' ?'none':'block'}}">
                                <label class="input-label" for="select_customer">{{__('messages.select_customer')}}</label>
                                <select name="customer_ids[]" id="select_customer"
                                    class="form-control select-2"
                                    multiple="multiple" placeholder="{{__('messages.select_customer')}}">
                                    <option value="all" {{in_array('all', json_decode($coupon->customer_id))?'selected':''}}>{{__('messages.all')}} </option>
                                    @foreach(\App\Models\Customer::get(['id','f_name','l_name','phone']) as $user)
                                    <option value="{{$user->id}}" {{in_array($user->id, json_decode($coupon->customer_id))?'selected':''}}>{{$user->f_name. ' ' .$user->l_name.' (' .$user->phone.')'}}</option>
                                @endforeach
                                </select>
                            </div>
                            </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-lg-3">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{__('messages.code')}}</label>
                                <input id="coupon_code" type="text" name="code" class="form-control" value="{{$coupon['code']}}"
                                        placeholder="{{\Illuminate\Support\Str::random(8)}}" required maxlength="100">
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="form-group">
                                <label class="input-label" for="limit">{{__('messages.limit')}} {{__('messages.for')}} {{__('messages.same')}} {{__('messages.user')}}</label>
                                <input type="number" name="limit" id="coupon_limit" value="{{$coupon['limit']}}" class="form-control" max="100000"
                                        placeholder="{{ __('messages.Ex :') }} 10">
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="form-group">
                                <label class="input-label" for="">{{__('messages.start')}} {{__('messages.date')}}</label>
                                <input type="date" name="start_date" class="form-control" id="date_from" placeholder="{{__('messages.select_date')}}" value="{{date('Y-m-d',strtotime($coupon['start_date']))}}">
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="form-group">
                                <label class="input-label" for="date_to">{{__('messages.expire')}} {{__('messages.date')}}</label>
                                <input type="date" name="expire_date" class="form-control" placeholder="{{__('messages.select_date')}}" id="date_to" value="{{date('Y-m-d',strtotime($coupon['expire_date']))}}"
                                        data-hs-flatpickr-options='{
                                        "dateFormat": "Y-m-d"
                                    }'>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="form-group">
                                <label class="input-label" for="discount_type">{{__('messages.discount')}} {{__('messages.type')}}</label>
                                <select name="discount_type"  required id="discount_type" class="form-control" {{$coupon['coupon_type']=='free_delivery'?'disabled':''}}>
                                    <option value="amount" {{$coupon['discount_type']=='amount'?'selected':''}}>
                                        {{ __('messages.amount').' ('.\App\CentralLogics\Helpers::currency_symbol().')'  }}

                                    </option>
                                    <option value="percent" {{$coupon['discount_type']=='percent'?'selected':''}}>
                                       {{ __('messages.percent').' (%)' }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="form-group">
                                <label class="input-label" for="discount">{{__('messages.discount')}}
                                    {{-- <span class="input-label-secondary text--title" data-toggle="tooltip"
                                        data-placement="right"
                                        data-original-title="{{ __('Currently you need to manage discount with the Restaurant.') }}">
                                        <i class="tio-info-outined"></i>
                                    </span> --}}
                                </label>
                                <input type="number" id="discount" min="1" max="999999999999.99" step="0.01" value="{{$coupon['discount']}}"
                                        name="discount" class="form-control" required {{$coupon['coupon_type']=='free_delivery'?'readonly':''}}>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{__('messages.max')}} {{__('messages.discount')}}</label>
                                <input type="number" min="0" max="999999999999.99" step="0.01"
                                        value="{{$coupon['max_discount']}}" name="max_discount" id="max_discount" class="form-control" {{$coupon['coupon_type']=='free_delivery'?'readonly':''}}>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{__('messages.min')}} {{__('messages.purchase')}}</label>
                                <input id="min_purchase" type="number" name="min_purchase" step="0.01" value="{{$coupon['min_purchase']}}"
                                        min="0" max="999999999999.99" class="form-control"
                                        placeholder="100">
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3" id="deliveryRange" style="display: {{$coupon['coupon_type']=='free_delivery'?'block':'none'}}">
                            <div class="form-group">
                                <label class="input-label" for="deliveryRange-1">Delivery Range(in Km.)</label>
                                <input id="deliveryRange-1" type="number" name="delivery_range" step="0.01" value="{{$coupon['delivery_range']}}"
                                        min="0" max="999999999999.99" class="form-control"
                                        placeholder="100">
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3" id="enbleExtraDistance" style="display: {{$coupon['coupon_type']=='free_delivery'?'block':'none'}}">
                            <div class="form-check d-flex">
                                <input id="enbleExtraDistance-1" type="checkbox" name="enble_ext_distance" onchange="this.value=this.checked?1:0"
                                {{$coupon['enble_ext_distance']?"checked" :null}}
                                 value="{{$coupon['enble_ext_distance']??0}}"
                                         class="form-check-input me-2">
                                <label class="form-check-label" for="enbleExtraDistance-1">Enable Free Delivery + Extra Distance</label>
                            </div>
                        </div>
                    </div>
                    <div class="btn--container justify-content-end">
                        <button id="reset_btn" type="button" class="btn btn-reset">{{__('messages.reset')}}</button>
                        <button type="submit" class="btn btn-primary">{{__('messages.update')}}</button>
                    </div>
                </form>
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
            $('#date_from').attr('max','{{date("Y-m-d",strtotime($coupon["expire_date"]))}}');
            $('#date_to').attr('min','{{date("Y-m-d",strtotime($coupon["start_date"]))}}');
            $('.js-data-example-ajax').select2({
            ajax: {
                url: '{{url('/')}}/admin/restaurant/get-restaurants',
                data: function (params) {
                    return {
                        q: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function (data) {
                    return {
                    results: data
                    };
                },
                __port: function (params, success, failure) {
                    var $request = $.ajax(params);

                    $request.then(success);
                    $request.fail(failure);

                    return $request;
                }
            }
        });
            // INITIALIZATION OF FLATPICKR
            // =======================================================
            // $('.js-flatpickr').each(function () {
            //     $.HSCore.components.HSFlatpickr.init($(this));
            // });
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
                $('#zone_wise').hide();
                $('#customer_wise').show();

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
                $('#max_discount').val(0);
                $('#discount_type').attr("required","false");
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
            // $('#coupon_title').val("{{$coupon['title']}}");
            // $('#coupon_code').val("{{$coupon['code']}}");
            // $('#coupon_type').val("{{$coupon['coupon_type']}}").trigger('change');
            // $('#coupon_limit').val("{{$coupon['limit']}}");
            // $('#date_from').val("{{date('Y-m-d',strtotime($coupon['start_date']))}}");
            // $('#date_to').val("{{date('Y-m-d',strtotime($coupon['expire_date']))}}");
            // $('#discount_type').val("{{$coupon['discount_type']}}").trigger('change');
            // $('#discount').val("{{$coupon['discount']}}");
            // $('#min_purchase').val("{{$coupon['min_purchase']}}");
            location.reload(true);

        })
    </script>
@endpush
