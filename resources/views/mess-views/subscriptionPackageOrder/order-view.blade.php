@extends('mess-views.layouts.dashboard-main')
<style>
    .select2-container--open {
    z-index: 99999999999999;
}
</style>
@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div class="row">
        <div class="col-sm-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="header-title">
                        <h4 class="card-title text-capitalize">
                            <div class="card-header-icon d-inline-flex mr-2 img">
                                <svg xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd"
                                    image-rendering="optimizeQuality" shape-rendering="geometricPrecision"
                                    text-rendering="geometricPrecision" viewBox="0 0 2048 2048" width="30">
                                    <path fill="none" d="M0 0h2048v2048H0z"></path>
                                    <path fill="none" d="M255.999 255.999h1536v1536h-1536z"></path>
                                    <path fill="none" d="M256 255.999h1536v1536H256z"></path>
                                    <path fill="currentColor"
                                        d="M1783.77 501.991c-8.633-14.418-22.84-24.522-39.23-27.81l-870.058-209.76c-28.676-5.564-56.966 10.281-66.887 37.686L475.96 1144.612l110.708 40.342L900.95 383.877l755.976 192.9c-47.213 146.36-159.552 451.86-267.253 736.308-93.883 248.175-147.913 333.16-250.611 350.512-.114-.123-.227-.228-.227-.41-209.875 29.411-206.116-238.14-206.116-238.14l-670.28-270.759c-4.461 28.3-6.468 54.592-6.442 78.991.28 235.896 190.786 295.536 190.786 295.536l490.755 222.793c.105.07 76.722 36.564 159.98 32.804 224.59-5.276 303.51-168.35 402.405-429.618 142.872-377.593 287.875-800.656 289.295-805.143 5.11-16.012 3.093-33.286-5.45-47.659zm-423.72 406.944c5.11 1.394 10.403 2.139 15.626 2.139 25.846 0 49.492-17.24 56.739-43.374 8.615-31.42-9.99-63.864-41.35-72.418l-255.36-78.816c-31.006-8.58-63.819 9.85-72.39 41.244-8.545 31.402 9.877 63.847 41.367 72.444l255.369 78.781zM929.276 647.747c36.073 0 65.354 28.466 65.354 63.53 0 35.084-29.28 63.514-65.354 63.514-36.1 0-65.362-28.422-65.362-63.514 0-35.064 29.263-63.53 65.362-63.53zm-109.745 566.609c-25.24-10.08-54.25 2.27-64.311 27.484-10.132 25.355 2.393 54.285 27.633 64.363 25.241 10.08 54.102-2.33 64.224-27.685 10.07-25.215-2.305-54.092-27.546-64.162zm8.914-422.607l-18.37 45.995 45.993 18.36 12.955 207.517-54.215 42.34c-4.97 5.942-11 14.189-13.725 21.016-10.123 25.354 2.392 54.294 27.633 64.372l275.561 110.034 18.37-46.003-266.377-106.362c-2.357-.946-3.698-4.075-2.76-6.432l.894-2.235 36.23-30.771 170.033 67.896c18.265 7.292 35.758 3.584 48.212-7.424l142.269-116.274c6.432-2.76 7.37-5.117 9.22-9.737 5.45-13.663-.062-26.52-13.875-32.04L906.524 876.251l-2.244-54.224-75.835-30.28zm220.794 514.323c-25.24-10.078-54.241 2.278-64.311 27.493-10.124 25.354 2.393 54.285 27.633 64.364 25.24 10.078 54.092-2.332 64.223-27.686 10.061-25.215-2.313-54.093-27.545-64.171zm398.216-581.018c5.11 1.394 10.402 2.138 15.626 2.138 25.845 0 49.49-17.238 56.739-43.374 8.615-31.418-9.991-63.863-41.35-72.417l-255.36-78.816c-31.007-8.58-63.82 9.85-72.392 41.244-8.544 31.402 9.878 63.847 41.367 72.444l255.37 78.781zM1016.68 463.865c36.072 0 65.354 28.467 65.354 63.532 0 35.083-29.282 63.513-65.354 63.513-36.1 0-65.364-28.422-65.364-63.513 0-35.065 29.263-63.532 65.364-63.532z">
                                    </path>
                                </svg>
                            </div>
                            <span>
                                <span>
                                    {{ __('messages.Order Details') }}
                                </span>
                            </span>
                            <div class="d-flex  float-end">
                                <a class="btn btn-soft-info rounded-circle mr-1"
                                    href="$"
                                    data-toggle="tooltip" data-placement="top" title="Previous order">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                                <a class="btn btn-soft-info rounded-circle"
                                    href="javascript:void(0)"
                                    data-toggle="tooltip" data-placement="top" title="Next order">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </div>
                        </h4>
                    </div>
                </div>
                <div class="card-body px-0">
                    <div class="row g-1" id="printableArea">
                        <div class="col-lg-8 order-print-area-left">
                            <!-- Card -->
                            <div class="card mb-3 mb-lg-5">
                                <!-- Header -->
                                <div class="card-header d-flex justify-content-between mb-2">
                                    <div class="order-invoice-left">
                                        <h4 class="page-header-title mt-2">
                                            <span>
                                                {{ __('messages.order') }} #{{$order->id}}
                                            </span>


                                            <a class="btn btn-soft-primary m-2 print--btn d-sm-none ml-auto" href="{{ route('mess.order.generate-invoice', [$order->id]) }}">
                                                <i class="fa fa-print mr-1"></i>
                                            </a>
                                        </h4>
                                        <span class="mt-2 d-block">
                                            <i class="fas fa-date-range"></i>
                                            <span class="d-block">
                                                {{date('d M Y',strtotime($order['created_at']))}}
                                            </span>
                                            <span class="d-block text-uppercase">
                                                {{date(config('timeformat'),strtotime($order['created_at']))}}
                                            </span>
                                        </span>
                                    </div>
                                    <div class="order-invoice-right">
                                        <div class="d-none d-sm-flex flex-wrap ml-auto align-items-center justify-content-end m-n-5rem">
                                            <a class="btn btn-primary m-2 print--btn" href="{{ route('vendor.order.generate-invoice', [$order['id']]) }}">
                                                <i class="fa fa-print mr-1"></i> {{ __('messages.print') }} {{ __('messages.invoice') }}
                                            </a>
                                        </div>
                                        <div class="text-right mt-3 order-invoice-right-contents text-capitalize">

                                            <h6>
                                                <span>{{ __('Status') }} :</span>

                                                
                                                @if ($order->status == 'pending')
                                                <span class="badge bg-soft-info ml-2 ml-sm-3">
                                                    {{ __('messages.pending') }}
                                                </span>
                                                @elseif($order->status == 'confirmed')
                                                <span class="badge bg-soft-info ml-2 ml-sm-3">
                                                    {{ __('messages.confirmed') }}
                                                </span>
                                              
                                                @else
                                                <span class="badge bg-soft-danger ml-2 ml-sm-3">
                                                    {{ __(str_replace('_', ' ', $order->status)) }}
                                                </span>
                                                @endif

                                            </h6>
                                            <h6>
                                                @php($payment =  $order->paymentDetail->first())
                                                <span>
                                                    {{ __('messages.payment') }} {{ __('messages.method') }} :</span>
                                                <strong>
                                                    {{ __(str_replace('_', ' ', $payment->method)) }}</strong>
                                            </h6>
                                            <h6>
                                                <span>{{ __('Order Type') }} :</span>
                                                @if($order->meal_collection =='delivery')
                                                <span>Delivery</span>
                                                @else
                                                <span>Dine IN</span>
                                                @endif
                                            </h6>
                                            
                                            <h6 class="fw-bold">Payment Status &nbsp; 
                                                @if($payment->status == 'paid')
                                                <strong class="text-success">
                                                    {{ __('messages.paid') }}
                                                </strong>
                                                @else
                                                <strong class="text-danger">
                                                    {{ __('messages.unpaid') }}
                                                </strong>
                                                @endif
                                            </h6>
                                        
                                        </div>
                                    </div>
                                </div>
                                <!-- End Header -->

                                <!-- Body -->
                                <div class="card-body p-0">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table class="table table-responsivemb-0">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th>{{ __('Item Details') }}</th>
                                                            <th class="text-right">{{ __('Price') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($order->orderItems as  $item) 
                                                        <tr>
                                                            <td>
                                                                <div class="border-bottom gold-members">
                                                                    <div class="w-100">
                                                                        <div class="gap-2 mb-2">
                                                                            <a href="javascript:void(0)">
                                                                                <h6 class="mb-1">
                                                                                    <img  alt="#" src="{{$item->package->type == 'veg'? asset('assets/user/img/veg.png') : asset('assets/user/img/non-veg.png')}}" class="img-fluid" height="16px"  width="16px" >
                                                                                    {{Str::upper($item->package->title)}}
                                                                                </h6>
                                                                            </a> 
                                                                        </div> 
                                                                        <div class="border-top pt-2">
                                                                            @php ( $diets = json_decode($item->package->diets))
                                                                            <div class="row">
                                                                                <div class="col-lg-4">
                                                                                    
                                                                                    <p class="text-fw-bold mb-0">No. of Normal Diet - 
                                                                                        <span class=" mb-0">{{(int)$diets->breakfast + (int) $diets->lunch + (int) $diets->dinner}} </span>
                                                                                    </p>
                                                                                </div>
                                                                                <div class="col-lg-4">
                                                                                    <p class="text-fw-bold mb-0">No. of Special Diet - 
                                                                                        <span class="text-muted mb-0">{{(int)$diets->special}} </span>
                                                                                    </p>
                                                                                </div>
                                                                                <div class="col-lg-4">
                                                                                    <p class="text-fw-bold mb-0">Total Diet - 
                                                                                        <span class="text-muted mb-0">{{(int)$diets->breakfast + (int) $diets->lunch + (int) $diets->dinner + (int) $diets->special}} </span>
                                                                                    </p>
                                                                                </div>
                                                                                <div class="col-lg-4">
                                                                                    <p class="text-fw-bold mb-0">Total Breakfast - 
                                                                                        <span class="text-muted mb-0">{{(int)$diets->breakfast}} </span>
                                                                                    </p>
                                                                                </div>
                                                                                <div class="col-lg-4">
                                                                                    <p class="text-fw-bold mb-0">Total Lunch - 
                                                                                        <span class="text-muted mb-0">{{(int) $diets->lunch}} </span>
                                                                                    </p>
                                                                                </div>
                                                                                <div class="col-lg-4">
                                                                                    <p class="text-fw-bold mb-0">Total Dinner - 
                                                                                        <span class="text-muted mb-0">{{ (int) $diets->dinner}} </span>
                                                                                    </p>
                                                                                </div>
                                                                            </div>
                                                                        </div> 
                                                                    </div>
                                                                </div> 
                                                                <td>{{App\CentralLogics\Helpers::format_currency($item->price)}}</td> 
                                                            </td>
                                                        </tr>
                                                        @endforeach 
                                                    </tbody>
                                                </table>
                                            </div>


                                            <div class="col-12 px-4">
                                                @php($paymentDetail = $order->paymentDetail->first())
                                                <div class="row justify-content-md-end mb-3">
                                                    <div class="col-12">
                                                        <dl class="row text-sm-right">
                                                            <dt class="col-sm-6">Sub Total:
                                                            </dt>
                                                            <dd class="col-sm-6">
                                                               {{App\CentralLogics\Helpers::format_currency($paymentDetail->subtotal)}}
                                                            </dd>
                                                            {{-- <dt class="col-sm-6">Discount:</dt>
                                                            <dd class="col-sm-6">
                                                                - ₹ 0.00
                                                            </dd> --}}
                                                             <dt class="col-sm-6">Custome Discount:</dt>
                                                            <dd class="col-sm-6">
                                                                -{{App\CentralLogics\Helpers::format_currency($paymentDetail->custom_discount)}}</dd>
        
                                                            </dd>
                                                            <dt class="col-sm-6">Coupon
                                                                Discount:
                                                            </dt>
                                                            <dd class="col-sm-6">
                                                                -{{App\CentralLogics\Helpers::format_currency($paymentDetail->coupon_discount)}}</dd>
                                                            <dt class="col-sm-6">GST:</dt>
                                                            <dd class="col-sm-6">
                                                                +{{App\CentralLogics\Helpers::format_currency($paymentDetail->tax)}}</dd>
        
                                                                
                                                            </dd>
                                                            <dt class="col-sm-6">Delivery
                                                                Fee:
                                                            </dt>
                                                            <dd class="col-sm-6">
                                                                +{{App\CentralLogics\Helpers::format_currency($paymentDetail->delivery_charge)}}</dd>
        
                                                                <hr>
                                                            </dd>
        
                                                            <dt class="col-sm-6">Total:</dt>
                                                            <dd class="col-sm-6">
                                                                {{App\CentralLogics\Helpers::format_currency($order->total)}}
                                                            </dd>
                                                        </dl>
                                                        <!-- End Row -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                   
                                    <!-- End Row -->
                                </div>
                                <!-- End Body -->
                            </div>
                            <!-- End Card -->
                        </div>

                        <div class="col-lg-4 order-print-area-right">
                            <!-- Card -->
                            <div class="card mb-2">
                                <!-- Header -->
                                <div class="card-header border-0 py-0">
                                    <h5 class="card-header-title border-bottom py-3 m-0  w-100 text-center">Order Setup
                                    </h5>
                                </div>
                                <!-- End Header -->

                                <!-- Body -->

                                <div class="card-body">
                                    <!-- Unfold -->
                                    <div class="order-btn-wraper">
                                        @if ($order->status == 'pending')
                                        <a class="btn w-100 mb-3 btn-sm btn-soft-primary" data-order-change-status="pending" data-message="{{ __('Change status to confirmed ?') }}" href="javascript:void(0)" data-route="{{ route('mess.order.status', ['id' => $order['id'], 'order_status' => 'confirmed']) }}">
                                            {{ __('Confirm Order') }}
                                        </a>
                                        
                                        <a class="btn w-100 mb-3 btn-sm btn-soft-danger btn-danger mt-3" onclick="cancelled_status()">{{ __('Cancel Order') }}</a>
                                      
                                        @elseif ($order->status == 'confirmed' || $order->status == 'accepted')
                                        <a class="btn w-100 mb-3 btn-sm btn-soft-success"   href="javascript:void(0)">
                                            {{ __('Order Confirmed') }}
                                        </a>
                                       
                                        @endif

                                    </div>

                                    <hr>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5>Last Location</h5>
                                    </div>
                                    <span class="d-block text-lowercase qcont">
                                        @php($customerAddress = $order->customer->customerAddress->first())
                                        @if($customerAddress)
                                        <div class="text-warning mt-3">
                                            <i class="fa fa-map-marker-alt"></i>
                                            <a target="_blank" class="pl-2 text-warning"
                                            href="http://maps.google.com/maps?z=12&t=m&q=loc:{{ $customerAddress->latitude }},{{ $customerAddress->longitude }}">
                                            {{ Str::ucfirst($customerAddress->address) }}
                                            </a> 
                                        </div>
                                        @else
                                        Location Not found!
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">
                                        <span class="card-header-icon">
                                            <i class="tio-user"></i>
                                        </span>
                                        <span>
                                            Customer Information
                                        </span>
                                    </h5>
                                    <div class="media align-items-center deco-none customer-information-single"
                                        href="javascript:">
                                        <ul class="list-group">
                                            <li class="list-group-item disabled">
                                                <div class="d-flex">
                                                    <img class="theme-color-default-img img-fluid avatar avatar-50 avatar-rounded"
                                                        src="{{asset("customers/".$order->customer->image)}}"
                                                        alt="Image Description">

                                                    <h5 class=" text-title font-semibold text-hover-primary mx-2">
                                                        {{Str::ucfirst($order->customer->f_name)}} {{Str::ucfirst($order->customer->l_name)}}
                                                    </h5>
                                                </div>
                                                <span class="d-block">
                                                    <strong class="text--title font-semibold">
                                                        1
                                                    </strong>
                                                    Orders
                                                </span>
                                                <span class="d-block">
                                                    <strong>
                                                        {{$order->customer->email}}
                                                    </strong>
                                                </a>
                                            </span>
                                            <span class="d-block">
                                                    <a class="text-title font-semibold" href="tel:{{$order->customer->phone}}">
                                                    <strong class="text--title font-semibold">
                                                    </strong>
                                                    {{$order->customer->phone}}
                                                </span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            @if($order->address)
                            <div class="card mt-2">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="card-title">
                                            <span class="card-header-icon">
                                                <i class="fa fa-user"></i>
                                            </span>
                                            <span>
                                                Delivery Information
                                            </span>
                                        </h5>
                                    </div>
                                    <span class="fa fa-map-marker-alt delivery-information-single mt-3">
                                        <a target="_blank" class="pl-2"
                                        @php($cord = json_decode($order->coordinates))
                                        href="http://maps.google.com/maps?z=12&t=m&q=loc:{{ $cord->latitude }},{{ $cord->longitude }}">
                                        {{ Str::ucfirst($order->delivery_address) }}
                                        </a> 
                                    </span>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>  
   

@endsection
@push('javascript')
<script type = "module" >
    import {timeStringToMinutes} from "{{asset('assets/js/Helpers/helper.js')}}";

    document.querySelectorAll('[data-order-change-status]').forEach(element => {
    element.addEventListener('click', (e) => {
        e.preventDefault();
        let verification = element.dataset.verification ? element.dataset.verification : false;
        let processing = element.dataset.processing ? element.dataset.processing : false;
        order_status_change_alert(element.dataset.route, element.dataset.message, verification, processing);
    });
});

    function order_status_change_alert(route, message, verification = false, processing = false) {
        if (verification) {
            Swal.fire({
                title: '{{ __('Enter order verification code') }}',
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#FC6A57',
                confirmButtonText: '{{ __('messages.submit') }}',
                showLoaderOnConfirm: true,
                preConfirm: (otp) => {
                    location.href = route + '&otp=' + otp;
                },
                allowOutsideClick: () => !Swal.isLoading()
            })
            
        } else if (processing) {
            Swal.fire({
                //text: message,
                title: '{{ __('messages.Are you sure ?') }}',
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#FC6A57',
                cancelButtonText: '{{ __('messages.Cancel') }}',
                confirmButtonText: '{{ __('messages.submit') }}',
                inputPlaceholder: "{{ __('Enter processing time') }}",
                input: 'text',
                html: message + '<br/>'+'<label>{{ __('Enter Processing time in minutes') }}</label>',
                inputValue: timeStringToMinutes(processing),
                preConfirm: (processing_time) => {
                    location.href = route + '&processing_time=' + processing_time;
                },
                allowOutsideClick: () => !Swal.isLoading()
            })
        } else {
            Swal.fire({
                title: '{{ __('messages.Are you sure ?') }}',
                text: message,
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#FC6A57',
                cancelButtonText: '{{ __('messages.No') }}',
                confirmButtonText: '{{ __('messages.Yes') }}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    location.href = route;
                }
            })
        }
    } 

    


document.querySelectorAll('[data-dm-assign-manually]').forEach(item => {
    item.addEventListener('click', async () => {
        try {
            const resp = await fetch(`{{ route("vendor.order.dm_assign_manually") }}?delivery_man_id=${item.dataset.dmAssignManually}&order_id=${item.dataset.orderId}`);
            const result = await resp.json();
            if (resp.ok && result !== null) {
                console.log(result);
                // console.log(res)
            }
        } catch (error) {
            console.error('Error fetching data:', error);
        }
    });
});
</script>
<script>
    function cancelled_status() {
        Swal.fire({
            title: '{{ __("messages.are_you_sure") }}',
            text: '{{ __("messages.You_want_to_cancel_this_order_?") }}',
            type: 'warning',
            html:
                `<div class="form-group mx-1">
                    <label for="reason">Enter Canceletion Reasion </label>
                    <input class="form-control" type="text" id="reason" />
                </div>`,
            showCancelButton: true,
            cancelButtonColor: 'default',
            confirmButtonColor: '#FC6A57',
            cancelButtonText: '{{ __("messages.no") }}',
            confirmButtonText: '{{ __("messages.yes") }}',
            reverseButtons: true,
            
        }).then((result) => {
            if (result.value) {
                // console.log(result);
                var reason = document.getElementById('reason').value;
                location.href = '{!! route("mess.order.status", ["id" => $order["id"],"order_status" => "canceled"]) !!}&reason='+reason,'{{ __("Change status to canceled ?") }}';
            }
        })
    }
</script>
@endpush
