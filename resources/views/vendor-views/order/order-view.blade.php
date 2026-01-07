@php
    $restaurantOrderData = json_decode($order->orderCalculationStmt?->restaurantData);
    // $billing = new App\Http\Controllers\User\Restaurant\BillingController(3972);
    //         $billing->process();
    //         $billmakerData = $billing->billMaker();
    //         $restaurantOrderData = $billmakerData->restaurantBillData();
    // dd($restaurantOrderData);
    $deliveryAddress = json_decode($order->delivery_address);
    $max_processing_time = explode('-', $order->restaurant->max_delivery_time)[0];
@endphp
@extends('vendor-views.layouts.dashboard-main')


<style>
    .select2-container--open {
    z-index: 99999999999999;
}
.order_id strong{
    font-size: larger;
}
</style>
@section('content')

    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div class="row">
            <div class="d-flex justify-content-between">
                <h4 class="mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd" image-rendering="optimizeQuality" shape-rendering="geometricPrecision" text-rendering="geometricPrecision" viewBox="0 0 2048 2048" width="30">
                        <path fill="none" d="M0 0h2048v2048H0z"></path>
                        <path fill="none" d="M255.999 255.999h1536v1536h-1536z"></path>
                        <path fill="none" d="M256 255.999h1536v1536H256z"></path>
                        <path fill="currentColor" d="M1783.77 501.991c-8.633-14.418-22.84-24.522-39.23-27.81l-870.058-209.76c-28.676-5.564-56.966 10.281-66.887 37.686L475.96 1144.612l110.708 40.342L900.95 383.877l755.976 192.9c-47.213 146.36-159.552 451.86-267.253 736.308-93.883 248.175-147.913 333.16-250.611 350.512-.114-.123-.227-.228-.227-.41-209.875 29.411-206.116-238.14-206.116-238.14l-670.28-270.759c-4.461 28.3-6.468 54.592-6.442 78.991.28 235.896 190.786 295.536 190.786 295.536l490.755 222.793c.105.07 76.722 36.564 159.98 32.804 224.59-5.276 303.51-168.35 402.405-429.618 142.872-377.593 287.875-800.656 289.295-805.143 5.11-16.012 3.093-33.286-5.45-47.659zm-423.72 406.944c5.11 1.394 10.403 2.139 15.626 2.139 25.846 0 49.492-17.24 56.739-43.374 8.615-31.42-9.99-63.864-41.35-72.418l-255.36-78.816c-31.006-8.58-63.819 9.85-72.39 41.244-8.545 31.402 9.877 63.847 41.367 72.444l255.369 78.781zM929.276 647.747c36.073 0 65.354 28.466 65.354 63.53 0 35.084-29.28 63.514-65.354 63.514-36.1 0-65.362-28.422-65.362-63.514 0-35.064 29.263-63.53 65.362-63.53zm-109.745 566.609c-25.24-10.08-54.25 2.27-64.311 27.484-10.132 25.355 2.393 54.285 27.633 64.363 25.241 10.08 54.102-2.33 64.224-27.685 10.07-25.215-2.305-54.092-27.546-64.162zm8.914-422.607l-18.37 45.995 45.993 18.36 12.955 207.517-54.215 42.34c-4.97 5.942-11 14.189-13.725 21.016-10.123 25.354 2.392 54.294 27.633 64.372l275.561 110.034 18.37-46.003-266.377-106.362c-2.357-.946-3.698-4.075-2.76-6.432l.894-2.235 36.23-30.771 170.033 67.896c18.265 7.292 35.758 3.584 48.212-7.424l142.269-116.274c6.432-2.76 7.37-5.117 9.22-9.737 5.45-13.663-.062-26.52-13.875-32.04L906.524 876.251l-2.244-54.224-75.835-30.28zm220.794 514.323c-25.24-10.078-54.241 2.278-64.311 27.493-10.124 25.354 2.393 54.285 27.633 64.364 25.24 10.078 54.092-2.332 64.223-27.686 10.061-25.215-2.313-54.093-27.545-64.171zm398.216-581.018c5.11 1.394 10.402 2.138 15.626 2.138 25.845 0 49.49-17.238 56.739-43.374 8.615-31.418-9.991-63.863-41.35-72.417l-255.36-78.816c-31.007-8.58-63.82 9.85-72.392 41.244-8.544 31.402 9.878 63.847 41.367 72.444l255.37 78.781zM1016.68 463.865c36.072 0 65.354 28.467 65.354 63.532 0 35.083-29.282 63.513-65.354 63.513-36.1 0-65.364-28.422-65.364-63.513 0-35.065 29.263-63.532 65.364-63.532z"></path>
                    </svg>
                    <span>
                        {{ __('messages.Order Details') }}
                    </span>
                </h4>
                <div>
                    <a class="btn btn-primary btn-sm print--btn fw-bolder" href="{{ route('vendor.order.generate-invoice', [$order['id']]) }}">
                        <i class="fa fa-print mr-1"></i> {{ __('messages.print') }} {{ __('messages.invoice') }}
                    </a>
                    <a class="btn badge btn-sm bg-soft-success fw-bolder"  href="{{route('vendor.order.generate-KOT',$order->id)}}"  type="button">Print KOT</a>
                </div>
            </div>

            <div class="col-sm-12 col-lg-12">
                <div class="row">
                    <div class="col-lg-8 order-print-area-left px-2">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <div class="order-invoice-left">
                                    <h5 class="page-header-title">
                                        <span class="order_id">
                                            {{ __('messages.order') }} #{!! substr($order['id'], 0, -4) . '<strong >' . substr($order['id'], -4) . '</strong>' !!}
                                        </span>
                                        @if ($order->edited)
                                        <span class="badge bg-danger text-capitalize px-2 ml-2">
                                            {{ __('messages.edited') }}
                                        </span>
                                        @endif
                                    </h5>


                                    @if ($order->cooking_instruction != null)
                                        <div class="bd-example mt-1">
                                            <div class="alert alert-success bg-white mb-0 rounded-2" role="alert">
                                                <h5 class="alert-heading mb-2 fw-bolder">Cooking Instruction</h5>
                                                <p>{{ $order->cooking_instruction }}</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="order-invoice-right">
                                    <span class="">
                                        <i class="">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="25">
                                                <circle cx="15" cy="12" r="8.5" fill="none" stroke="#303c42" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path fill="none" stroke="#303c42" stroke-linecap="round" stroke-linejoin="round"
                                                    d="M2.5 6.5c-2.7 3.2-2.7 7.8 0 11m3-11c-2.7 3.2-2.7 7.8 0 11" />
                                                <circle cx="15" cy="12" r=".5" fill="none" stroke="#303c42" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path fill="none" stroke="#303c42" stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15 6.5V12l3 3" />
                                            </svg>
                                        </i>
                                        {{ date('d M Y ' . config('timeformat'), strtotime($order['created_at'])) }}
                                        {{ \Carbon\Carbon::parse($order->created_at)->format('h:i A') }}
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <span class="text-dark"> <b> Name :</b> {{ isset($deliveryAddress->contact_person_name)? $deliveryAddress->contact_person_name : Str::ucfirst($order->customer->f_name).' '.Str::ucfirst($order->customer->l_name) }} </span>
                                        </div>
                                        <div>
                                            <span class="text-dark"> <b> Orders :</b> {{ \App\Models\Order::where('customer_id', $order->customer->id)->where('order_status', 'delivered')->where('restaurant_id',$order->restaurant_id)->count() }}</span>
                                        </div>
                                        {{-- <p>
                                            <span class="text-dark"> <b> Contact Number :</b> {{ isset($deliveryAddress->contact_person_number) ?  $deliveryAddress->contact_person_number: $order->customer->phone }}</span>
                                        </p>
                                        <p class="mb-0">
                                            <span class="text-dark"> <b> Delivery Address :</b> {{ isset($deliveryAddress->stringAddress) ? $deliveryAddress->stringAddress : '' }}</span>
                                        </p> --}}

                                        <?php // $restaurantAddress = json_decode($order->restaurant->address); ?>

                                        {{-- Str::ucfirst($restaurantAddress->street) }}, {{ Str::ucfirst($restaurantAddress->city) }} - {{ Str::ucfirst($restaurantAddress->pincode) --}}
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="row g-1">
                                            <div class="text-end">Change Status</div>
                                            <div class="form-group">
                                                <select  id="statusChanger" type="button" class="form-select  bg-soft-primary">
                                                    <option value="confirmed" {{$order->order_status == 'confirmed'? 'selected' : null}} data-route="{{ route('vendor.order.order-status-update', ['id' => $order['id'], 'order_status' => 'confirmed']) }}" data-message="{{ __('Change status to confirmed ?') }}">
                                                        {{ __('Confirm Order') }}
                                                    </option>
                                                    <option value="pending" {{$order->order_status == 'pending'? 'selected' : null}}  data-route="{{ route('vendor.order.order-status-update', ['id' => $order['id'], 'order_status' => 'pending']) }}" data-message="{{ __('Are You sure to make Order Pending ?') }}">
                                                        {{ __('Pending Order') }}
                                                    </option>
                                                    <option value="processing" {{$order->order_status == 'processing'? 'selected' : null}}  data-message="{{ __('Change status to cooking ?') }}" data-processing="{{ $max_processing_time }}"
                                                        data-route="{{ route('vendor.order.order-status-update', ['id' => $order['id'], 'order_status' => 'processing']) }}">
                                                        {{__('messages.Proceed_for_cooking')}}
                                                    </option>

                                                    {{-- @if($restaurant?->ready_to_handover)
                                                    <option value="handover" {{$order->order_status == 'handover'? 'selected' : null}}  data-route="{{ route('vendor.order.order-status-update', ['id' => $order['id'], 'order_status' => 'handover']) }}" data-message="{{ __('Change status to ready for handover ?')}}">
                                                        {{ $order->order_status == 'handover'? __('Handover'): __('messages.make_ready_for_handover')  }}
                                                    </option>
                                                    @endif --}}
                                                    <option value="canceled" {{$order->order_status == 'canceled'? 'selected' : null}}  data-route="{{ route('vendor.order.order-status-update', ['id' => $order['id'], 'order_status' => 'canceled']) }}" data-message="{{ __('Are You sure want to Cancel Order ?')}}">
                                                        {{  __('Cancel Order') }}
                                                    </option>
                                                </select>
                                            </div>
                                            {{-- <div class="col-6 px-0">
                                                <div class="border text-center py-2  bg-soft-light">
                                                    <span class="text-dark text-uppercase fw-bold">{{ __('Status') }}</span>

                                                </div>
                                                <div class="border text-center py-2">
                                                    @if (isset($order->subscription) && $order->subscription->status != 'canceled')
                                                        @php
                                                            $order->order_status = $order->subscription_log ? $order->subscription_log->order_status : $order->order_status;
                                                        @endphp
                                                    @endif
                                                    @if ($order['order_status'] == 'pending')
                                                        <span class="text-capitalize text-info ml-2 ml-sm-3">
                                                            {{ __('messages.pending') }}
                                                        </span>
                                                    @elseif($order['order_status'] == 'confirmed')
                                                        <span class="text-capitalize text-info ml-2 ml-sm-3">
                                                            {{ __('messages.confirmed') }}
                                                        </span>
                                                    @elseif($order['order_status'] == 'processing')
                                                        <span class="text-capitalize text-warning ml-2 ml-sm-3">
                                                            {{ __('messages.cooking') }}
                                                        </span>
                                                    @elseif($order['order_status'] == 'picked_up')
                                                        <span class="text-capitalize text-warning ml-2 ml-sm-3">
                                                            {{ __('messages.out_for_delivery') }}
                                                        </span>
                                                    @elseif($order['order_status'] == 'delivered')
                                                        <span class="text-capitalize text-success ml-2 ml-sm-3">
                                                            {{ __('messages.delivered') }}
                                                        </span>
                                                    @else
                                                        <span class="text-capitalize text-info ml-2 ml-sm-3">
                                                            {{ __(str_replace('_', ' ', $order['order_status'])) }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-6 px-0">
                                                <div class="border text-center py-2 bg-soft-light">
                                                    <span class="text-dark text-uppercase fw-bold">{{ __('Distance') }}</span>
                                                </div>
                                                <div class="border text-center py-2">
                                                    <span class="text-capitalize">{{number_format(Helpers::haversineDistance(
                                                        [
                                                            'lat' => $order->restaurant->latitude,
                                                            'lon' => $order->restaurant->longitude
                                                        ],
                                                        [
                                                            'lat' =>   $deliveryAddress->position->lat,
                                                            'lon' =>  $deliveryAddress->position->lon
                                                        ]), 2)}} Km.</span>
                                                </div>
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="w-100">
                                        <hr style="border: 1px solid #cecbcb;">
                                            <div class="d-flex gap-2 mb-2">
                                                <a href="javascript:void(0)">
                                                    <h6 class="mb-1 text-primary fw-bolder">Food Items</h6>
                                                </a>
                                            </div>
                                            <hr style="border: 1px solid #cecbcb;">
                                            <div class="pt-2">
                                                <div class="row">
                                                    <div class="col-2">
                                                        <p class="text-fw-bold mb-0 text-dark">SI. </p>
                                                    </div>
                                                    <div class="col-4">
                                                        <p class="text-fw-bold mb-0 text-dark">Name</p>
                                                    </div>
                                                    <div class="col-2">
                                                        <p class="text-fw-bold mb-0 text-dark">Quantity </p>
                                                    </div>
                                                    <div class="col-4">
                                                        <p class="text-fw-bold mb-0 text-dark">Amount</p>
                                                    </div>
                                                    @if($restaurantOrderData?->foodItemList != null)
                                                    @foreach ($restaurantOrderData->foodItemList as $key => $listItem)
                                                    <div class="col-2">
                                                        <p class="text-fw-bold mb-0">{{$key+1}}
                                                        </p>
                                                    </div>
                                                    <div class="col-4">
                                                        <p class="text-fw-bold mb-0">{{Str::ucfirst($listItem->foodName)}}
                                                        </p>
                                                    </div>
                                                    <div class="col-2">
                                                        <p class="text-fw-bold mb-0"> {{$listItem->quantity}}
                                                            <span class="text-muted mb-0"> </span>
                                                        </p>
                                                    </div>
                                                    <div class="col-4">
                                                        <p class="text-fw-bold mb-0">{{Helpers::format_currency($listItem->restaurantPrice)}}
                                                            <span class="text-muted mb-0"> </span>
                                                        </p>
                                                    </div>
                                                    @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr style="border: 1px solid #cecbcb;">
                                    <div>
                                        <div class="mt-4">
                                            <h6 class="fw-bold text-primary mb-3">Payment Details &nbsp;
                                                @if($order->payment_status == 'paid')
                                                <span class="badge bg-success">{{Str::ucfirst($order->payment_status)}}</span>
                                                @elseif ($order->payment_status=='unpaid')
                                                <span class="badge bg-primary">{{Str::ucfirst($order->payment_status)}}</span>
                                                @endif
                                            </h6>


                                            <div class="">

                                                <p class="mb-1">Sub Total <span class="float-end">{{ Helpers::format_currency($restaurantOrderData?->foodPriceCollectionPreDiscount)}}</span></p>
                                                @if ($restaurantOrderData?->sumOfDiscount > 0)
                                                <p class="mb-1">Discount <span class="float-end">{{ Helpers::format_currency($restaurantOrderData?->sumOfDiscount)}}</span></p>
                                                @endif
                                                @if($restaurantOrderData?->couponDiscountAmount > 0)
                                                <p class="mb-1">Coupon Discount <span class="float-end">{{ Helpers::format_currency($restaurantOrderData?->couponDiscountAmount)}}</span></p>
                                                @endif
                                                @if ($restaurantOrderData?->sumofPackingCharge > 0)
                                                <p class="mb-1">Packing Charge <span class=" ms-1"><span
                                                            class="float-end ">{{ Helpers::format_currency($restaurantOrderData?->sumofPackingCharge)}}</span></p>
                                                @endif
                                                @if ($restaurantOrderData?->commissionChargedByAdmin > 0)
                                                <p class="mb-1">Commission Charged By Admin  <span class="float-end">{{__("- ").Helpers::format_currency($restaurantOrderData?->commissionChargedByAdmin)}}</span></p>
                                                @endif
                                                <p class="mb-1">Grosss Total <span class="float-end">{{ Helpers::format_currency($restaurantOrderData?->grossTotal)}}</span></p>
                                                <p class="mb-1">GST {{$restaurantOrderData?->gstPercent .__(' %')}} <span class="float-end">{{ Helpers::format_currency($restaurantOrderData?->gstAmount)}}</span></p>

                                                <hr style="border: 1px solid #cecbcb;">
                                                <h6 class="mb-0">Total <span class="float-end">{{ Helpers::format_currency($restaurantOrderData?->receivableAmount)}}</span></h6>

                                                <hr style="border: 1px solid #cecbcb;">
                                                <h6 class="fw-bold mb-0">Earning <small class="float-end badge bg-success px-3 py-2">{{Helpers::format_currency($restaurantOrderData?->earning)}}</small></h6>
                                            </div>

                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 order-print-area-right px-2">
                        <!-- Card -->
                        @if ($order['order_status'] != 'delivered')
                        <div class="card mb-2">
                            <!-- Header -->
                            <div class="card-header">
                                <h5 class="page-header-title border-bottom text-center">{{ __('Delivery Man Information') }}</h5>
                            </div>
                            <!-- End Header -->

                            <!-- Body -->

                            <div class="card-body">
                                <!-- Unfold -->
                                @php($order_delivery_verification = (bool) \App\Models\ZoneBusinessSetting::getSettingValue('order_delivery_verification', $order->getZoneId()))
                                @php($restaurant = $order->restaurant)
                                {{-- <div class="form-group">
                                    <select  id="statusChanger" type="button" class="form-control  bg-soft-primary">
                                        <option value="confirmed" {{$order->order_status == 'confirmed'? 'selected' : null}} data-route="{{ route('vendor.order.order-status-update', ['id' => $order['id'], 'order_status' => 'confirmed']) }}" data-message="{{ __('Change status to confirmed ?') }}">
                                            {{ __('Confirm Order') }}
                                        </option>
                                        <option value="pending" {{$order->order_status == 'pending'? 'selected' : null}}  data-route="{{ route('vendor.order.order-status-update', ['id' => $order['id'], 'order_status' => 'pending']) }}" data-message="{{ __('Are You sure to make Order Pending ?') }}">
                                            {{ __('Pending Order') }}
                                        </option>
                                        <option value="processing" {{$order->order_status == 'processing'? 'selected' : null}}  data-message="{{ __('Change status to cooking ?') }}" data-processing="{{ $max_processing_time }}"
                                            data-route="{{ route('vendor.order.order-status-update', ['id' => $order['id'], 'order_status' => 'processing']) }}">
                                            {{__('messages.Proceed_for_cooking')}}
                                        </option>

                                        @if($restaurant?->ready_to_handover)
                                        <option value="handover" {{$order->order_status == 'handover'? 'selected' : null}}  data-route="{{ route('vendor.order.order-status-update', ['id' => $order['id'], 'order_status' => 'handover']) }}" data-message="{{ __('Change status to ready for handover ?')}}">
                                            {{ $order->order_status == 'handover'? __('Handover'): __('messages.make_ready_for_handover')  }}
                                        </option>
                                        @endif
                                        <option value="canceled" {{$order->order_status == 'canceled'? 'selected' : null}}  data-route="{{ route('vendor.order.order-status-update', ['id' => $order['id'], 'order_status' => 'canceled']) }}" data-message="{{ __('Are You sure want to Cancel Order ?')}}">
                                            {{  __('Cancel Order') }}
                                        </option>
                                    </select>
                                </div> --}}
                                <!-- End Unfold -->
                                @if ($order['order_type'] != 'take_away')
                                @if ($order->delivery_man)
                                {{-- <div class="card-title mb-3">
                                    <span>
                                        {{ __('Delivery Man Information') }}
                                    </span>
                                </div> --}}
                                <div >
                                    {{-- <ul class="list-group">
                                        <li class="list-group-item"> --}}
                                            <div class="d-flex mb-2">
                                                <div class="text-title"> <i class="fa-solid fa-user"></i>
                                                    {{ $order->delivery_man['f_name'] . ' ' . $order->delivery_man['l_name'] }}
                                                </div>
                                            </div>
                                            {{-- <a class="text-title text-muted" href="tel:{{ $order->delivery_man['phone'] }}"> --}}
                                            <div class="d-flex">
                                                <div class="text-title">
                                                    <i class="fa-solid fa-phone"></i>
                                                        {{ $order->delivery_man['phone'] }}
                                                </div>
                                            </div>
                                            <hr style="border: 1px solid #cecbcb;">
                                            <div>
                                                <a href="javascript:void(0)" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-envelope"></i> Message
                                                </a>
                                                <a href="tel:{{ $order->delivery_man['phone'] }}" class="btn btn-sm btn-outline-success">
                                                    <i class="fas fa-phone"></i> Call
                                                </a>
                                            </div>
                                            {{-- </a> --}}
                                        {{-- </li>
                                    </ul> --}}
                                </div>


                                @endif
                                @endif
                            </div>
                        </div>
                        @endif
                        @if ($order->customer && false)
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Customer Details</h5>
                            </div>
                            @php($deliveryAddress = json_decode($order->delivery_address))
                            <div class="card-body">
                                <div class="row">
                                    <!-- Customer Info -->
                                    <div class="col-md-8">
                                        <h6 class="text-muted"><i class="fas fa-user"></i>
                                            {{ isset($deliveryAddress->contact_person_name)? $deliveryAddress->contact_person_name : Str::ucfirst($order->customer->f_name).' '.Str::ucfirst($order->customer->l_name) }}
                                        </h6>

                                        {{-- <p><i class="fas fa-phone"></i> <a href="tel:+1234567890">+1234567890</a></p> --}}
                                        <p class="mt-2"><i class="fas fa-map-marker-alt"></i>  {{ isset($deliveryAddress->stringAddress) ? $deliveryAddress->stringAddress : '' }}</p>
                                        <p class="mt-2"><i class="fas fa-phone"></i>  {{ isset($deliveryAddress->contact_person_number) ?  $deliveryAddress->contact_person_number: $order->customer->phone }}</p>
                                        <p>Total Orders: <strong>{{\App\Models\Order::where('customer_id', $order->customer->id)
                                            ->where('order_status', 'delivered')
                                            ->count();}}</strong></p>
                                    </div>
                                    <!-- Actions -->
                                    <div class="col-md-4 text-md-end">
                                        @if(isset($deliveryAddress->position))
                                        <a href="https://maps.google.com/?q={{$deliveryAddress->position->lat.','.$deliveryAddress->position->lon}}"  class="btn btn-outline-primary">
                                            <i class="fas fa-map-marked-alt"></i> View on Map
                                        </a>
                                        @endif
                                        <a  href="tel:{{ isset($deliveryAddress->contact_person_number) ?  $deliveryAddress->contact_person_number: $order->customer->phone }}" class="btn mt-2 btn-sm btn-outline-success">
                                            <i class="fas fa-phone"></i> Call
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="card aos-init aos-animate" data-aos="fade-up" data-aos-delay="500">
                            <div class="text-center card-body d-flex justify-content-around">
                                <div>
                                    <h3 class="mb-2 order_id">#{!! substr($order['id'], 0, -4) . '<strong >' . substr($order['id'], -4) . '</strong>' !!}</h3>
                                </div>
                                <hr class="hr-vertial">
                                <div>
                                    {{-- <h2 class="mb-2">7,500</h2> --}}
                                    <p class="mb-0 ">
                                    <a class="badge py-1 px-2 bg-soft-success"  href="{{route('vendor.order.generate-KOT',$order->id)}}"  type="button">Print KOT</a>
                                    <a class="badge py-1 px-2 bg-soft-warning" href="{{route('vendor.order.generate-invoice',$order->id)}}" type="button"><i class="fa fa-print"></i>Print Bill</a>
                                    </p>
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
<script type = "module" >
    import {timeStringToMinutes} from "{{asset('assets/js/Helpers/helper.js')}}";

    document.querySelector('#statusChanger').addEventListener('change', (e) => {
        const element = e.target;
        const selectedOption = document.querySelector(`option[value="${element.value}"]`) ;
        const option = {
             verification : selectedOption.dataset.verification ? selectedOption.dataset.verification : false,
             processing :element.value,
        }
        if(selectedOption.dataset.processing){
            option.processingTime = selectedOption.dataset.processing;
        }
        order_status_change_alert(selectedOption.dataset.route, selectedOption.dataset.message, option);
    });

    function order_status_change_alert(route, message, option = {}) {
        // console.log(option);
        // console.log(route);
        // return 0;
        if (option.verification) {
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

        } else if (option.processing == "processing") {
            // console.log(option);return 0;
            Swal.fire({
                //text: message,
                title: message,
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#FC6A57',
                cancelButtonText: '{{ __('messages.Cancel') }}',
                confirmButtonText: '{{ __('messages.submit') }}',
                inputPlaceholder: "{{ __('Enter processing time') }}",
                input: 'text',
                html: message + '<br/>'+'<label>{{ __('Enter Processing time in minutes') }}</label>',
                inputValue: timeStringToMinutes(option.processingTime),
                preConfirm: (processing_time) => {

                    location.href = route + '&processing_time=' + processing_time;
                },
                allowOutsideClick: () => !Swal.isLoading()
            })
        }else if (option.processing == "canceled") {
            Swal.fire({
                //text: message,
                title: message,
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#FC6A57',
                cancelButtonText: '{{ __('messages.Cancel') }}',
                confirmButtonText: '{{ __('messages.submit') }}',
                // inputPlaceholder: "{{ __('Enter processing time') }}",
                input: 'text',
                html:'<br/>'+'<label>{{ __('Enter Cancle reason') }}</label>',
                // inputValue: ,
                preConfirm: (cancelReason) => {

                    location.href = route + '&cancel_reason=' + cancelReason;
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
//     function timeStringToMinutes(timeString) {
//     const [hours, minutes, seconds] = timeString.split(':').map(Number);
//     return hours * 60 + minutes + Math.round(seconds / 60);
// }

</script>
@endpush
