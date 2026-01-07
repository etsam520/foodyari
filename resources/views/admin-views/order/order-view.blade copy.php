
<?php
    $max_processing_time = explode('-', $order->restaurant->max_delivery_time)[0];
    // orderCalculationStmt
    $customerOrderData = json_decode($order->orderCalculationStmt?->customerData);
    // $billing = new App\Http\Controllers\User\Restaurant\BillingController(3972);
    // $billing->process();
    // $billmakerData = $billing->billMaker();
    // $customerOrderData = $billmakerData->customerBillData();
    // dd($customerOrderData);

?>
@extends('layouts.dashboard-main')


<style>
    .select2-container--open {
    z-index: 99999999999999;
    }
    .fullscreen {
        width: 100vw !important;
        height: 100vh !important;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
    }
    .js--design-1 {
        width: 45px;
    }
    .p--10px {
        padding: 10px;
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
                                    <svg xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd" image-rendering="optimizeQuality" shape-rendering="geometricPrecision" text-rendering="geometricPrecision" viewBox="0 0 2048 2048" width="30">
                                        <path fill="none" d="M0 0h2048v2048H0z"></path>
                                        <path fill="none" d="M255.999 255.999h1536v1536h-1536z"></path>
                                        <path fill="none" d="M256 255.999h1536v1536H256z"></path>
                                        <path fill="currentColor" d="M1783.77 501.991c-8.633-14.418-22.84-24.522-39.23-27.81l-870.058-209.76c-28.676-5.564-56.966 10.281-66.887 37.686L475.96 1144.612l110.708 40.342L900.95 383.877l755.976 192.9c-47.213 146.36-159.552 451.86-267.253 736.308-93.883 248.175-147.913 333.16-250.611 350.512-.114-.123-.227-.228-.227-.41-209.875 29.411-206.116-238.14-206.116-238.14l-670.28-270.759c-4.461 28.3-6.468 54.592-6.442 78.991.28 235.896 190.786 295.536 190.786 295.536l490.755 222.793c.105.07 76.722 36.564 159.98 32.804 224.59-5.276 303.51-168.35 402.405-429.618 142.872-377.593 287.875-800.656 289.295-805.143 5.11-16.012 3.093-33.286-5.45-47.659zm-423.72 406.944c5.11 1.394 10.403 2.139 15.626 2.139 25.846 0 49.492-17.24 56.739-43.374 8.615-31.42-9.99-63.864-41.35-72.418l-255.36-78.816c-31.006-8.58-63.819 9.85-72.39 41.244-8.545 31.402 9.877 63.847 41.367 72.444l255.369 78.781zM929.276 647.747c36.073 0 65.354 28.466 65.354 63.53 0 35.084-29.28 63.514-65.354 63.514-36.1 0-65.362-28.422-65.362-63.514 0-35.064 29.263-63.53 65.362-63.53zm-109.745 566.609c-25.24-10.08-54.25 2.27-64.311 27.484-10.132 25.355 2.393 54.285 27.633 64.363 25.241 10.08 54.102-2.33 64.224-27.685 10.07-25.215-2.305-54.092-27.546-64.162zm8.914-422.607l-18.37 45.995 45.993 18.36 12.955 207.517-54.215 42.34c-4.97 5.942-11 14.189-13.725 21.016-10.123 25.354 2.392 54.294 27.633 64.372l275.561 110.034 18.37-46.003-266.377-106.362c-2.357-.946-3.698-4.075-2.76-6.432l.894-2.235 36.23-30.771 170.033 67.896c18.265 7.292 35.758 3.584 48.212-7.424l142.269-116.274c6.432-2.76 7.37-5.117 9.22-9.737 5.45-13.663-.062-26.52-13.875-32.04L906.524 876.251l-2.244-54.224-75.835-30.28zm220.794 514.323c-25.24-10.078-54.241 2.278-64.311 27.493-10.124 25.354 2.393 54.285 27.633 64.364 25.24 10.078 54.092-2.332 64.223-27.686 10.061-25.215-2.313-54.093-27.545-64.171zm398.216-581.018c5.11 1.394 10.402 2.138 15.626 2.138 25.845 0 49.49-17.238 56.739-43.374 8.615-31.418-9.991-63.863-41.35-72.417l-255.36-78.816c-31.007-8.58-63.82 9.85-72.392 41.244-8.544 31.402 9.878 63.847 41.367 72.444l255.37 78.781zM1016.68 463.865c36.072 0 65.354 28.467 65.354 63.532 0 35.083-29.282 63.513-65.354 63.513-36.1 0-65.364-28.422-65.364-63.513 0-35.065 29.263-63.532 65.364-63.532z"></path>
                                    </svg>
                                </div>
                                <span>
                                    {{ __('messages.Order Details') }}
                                </span>
                                <div class="d-flex  float-end">
                                    <a class="btn btn-soft-info rounded-circle mr-1" href="{{ route('admin.order.details', [$order['id'] - 1]) }}" data-toggle="tooltip" data-placement="top" title="{{ __('Previous order') }}">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                    <a class="btn btn-soft-info rounded-circle" href="{{ route('admin.order.details', [$order['id'] + 1]) }}" data-toggle="tooltip" data-placement="top" title="{{ __('Next order') }}">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </div>
                            </h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-1" id="printableArea">
                            <div class="col-lg-8 order-print-area-left">
                                <!-- Card -->
                                <div class="card mb-3 mb-lg-5">
                                    <!-- Header -->
                                    <div class="card-header d-flex justify-content-between mb-2">
                                        <div class="order-invoice-left">
                                            <h4 class="page-header-title mt-2">
                                                <span>
                                                    {{ __('messages.order') }} #{{ $order['id'] }}
                                                </span>
                                                @if ($order->edited)
                                                <span class="badge bg-danger text-capitalize px-2 ml-2">
                                                    {{ __('messages.edited') }}
                                                </span>
                                                @endif
                                                <a class="btn btn-soft-primary m-2 print--btn d-sm-none ml-auto" href="{{ route('admin.order.generate-invoice', [$order['id']]) }}">
                                                    <i class="fa fa-print mr-1"></i>
                                                </a>
                                            </h4>
                                            <span class="mt-2 d-block">
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
                                            <span class="mt-2 d-block">
                                                <i class="">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" width="25">
                                                        <path fill="#222"
                                                            d="M32 20.1H7.162a1.5 1.5 0 0 1-1.3-2.245L11.833 7.4a1.5 1.5 0 0 1 1.3-.755H32a1.5 1.5 0 0 1 0 3H14.006L9.747 17.1H32a1.5 1.5 0 0 1 0 3Z" />
                                                        <path fill="#222"
                                                            d="M56.838 20.1H32a1.5 1.5 0 0 1 0-3h22.253l-4.259-7.455H32a1.5 1.5 0 0 1 0-3h18.864a1.5 1.5 0 0 1 1.3.755l5.976 10.454a1.5 1.5 0 0 1-1.3 2.245zM13.372 31.267a7.719 7.719 0 0 1-7.71-7.71V18.6a1.5 1.5 0 0 1 3 0v4.958a4.71 4.71 0 1 0 9.419 0V18.6a1.5 1.5 0 0 1 3 0v4.958a7.718 7.718 0 0 1-7.709 7.709z" />
                                                        <path fill="#222"
                                                            d="M25.791 31.267a7.719 7.719 0 0 1-7.71-7.71V18.6a1.5 1.5 0 0 1 3 0v4.958a4.71 4.71 0 1 0 9.419 0V18.6a1.5 1.5 0 0 1 3 0v4.958a7.718 7.718 0 0 1-7.709 7.709Z" />
                                                        <path fill="#222"
                                                            d="M38.209 31.267a7.718 7.718 0 0 1-7.709-7.71V18.6a1.5 1.5 0 0 1 3 0v4.958a4.71 4.71 0 1 0 9.419 0V18.6a1.5 1.5 0 0 1 3 0v4.958a7.719 7.719 0 0 1-7.71 7.709Z" />
                                                        <path fill="#222"
                                                            d="M50.628 31.267a7.718 7.718 0 0 1-7.709-7.71V18.6a1.5 1.5 0 0 1 3 0v4.958a4.71 4.71 0 1 0 9.419 0V18.6a1.5 1.5 0 1 1 3 0v4.958a7.719 7.719 0 0 1-7.71 7.709Z" />
                                                        <path fill="#222"
                                                            d="M44.418 20.1a1.5 1.5 0 0 1-1.436-1.068L39.838 8.577a1.5 1.5 0 0 1 2.873-.865l3.144 10.455a1.5 1.5 0 0 1-1 1.868 1.475 1.475 0 0 1-.437.065zm-24.836 0a1.475 1.475 0 0 1-.433-.064 1.5 1.5 0 0 1-1-1.868l3.14-10.456a1.5 1.5 0 0 1 2.873.865l-3.144 10.454a1.5 1.5 0 0 1-1.436 1.069zM32 20.1a1.5 1.5 0 0 1-1.5-1.5V8.145a1.5 1.5 0 1 1 3 0V18.6a1.5 1.5 0 0 1-1.5 1.5zm0 37.255H9.684a1.5 1.5 0 0 1-1.5-1.5v-27.31a1.5 1.5 0 0 1 3 0v25.81H32a1.5 1.5 0 0 1 0 3z" />
                                                        <path fill="#222"
                                                            d="M54.316 57.355H32a1.5 1.5 0 1 1 0-3h20.816v-25.81a1.5 1.5 0 0 1 3 0v27.31a1.5 1.5 0 0 1-1.5 1.5Z" />
                                                        <path fill="#222"
                                                            d="M43.881 56.98a1.5 1.5 0 0 1-1.5-1.5V39.615H21.619v15.757a1.5 1.5 0 0 1-3 0V38.115a1.5 1.5 0 0 1 1.5-1.5h23.762a1.5 1.5 0 0 1 1.5 1.5V55.48a1.5 1.5 0 0 1-1.5 1.5Z" />
                                                    </svg>
                                                </i> Restaurant :
                                                <span class="badge bg-soft-warning">
                                                    {{Str::ucfirst($order->restaurant->name)}}
                                                </span>

                                            </span>
                                            <span class="mt-2 d-block">
                                                <?php $restaurantAddress = json_decode($order->restaurant->address) ; ?>
                                                <hr class="hr-horizontal">
                                                {{Str::ucfirst($restaurantAddress->street)}}, {{Str::ucfirst($restaurantAddress->city)}} - {{Str::ucfirst($restaurantAddress->pincode)}}
                                            </span>
                                            @if($order->cooking_instruction != null )
                                            <hr class="hr-horizontal">
                                            <span>
                                                Cooking Instruction : <span class="badge bg-warning text-capitalize px-2 ml-2 me-2" style="white-space: normal; word-wrap: break-word;font-size:18px">
                                                    {{ $order->cooking_instruction }}
                                                </span>
                                            </span>
                                            @endif

                                        </div>
                                        <div class="order-invoice-right">
                                            <div class="d-none d-sm-flex flex-wrap ml-auto align-items-center justify-content-end m-n-5rem">
                                                <a class="btn btn-primary m-2 print--btn" href="{{ route('admin.order.generate-invoice', [$order['id']]) }}">
                                                    <i class="fa fa-print mr-1"></i> {{ __('messages.print') }} {{ __('messages.invoice') }}
                                                </a>
                                            </div>
                                            <div class="text-right mt-3 order-invoice-right-contents text-capitalize">
                                                @if (isset($order->subscription))
                                                <h6>
                                                    <span>{{ __('messages.Subscription_status') }} :</span>
                                                    @if ($order->subscription->status == 'active')
                                                    <span class="badge badge-soft-success ">
                                                        <span class="legend-indicator bg-success"></span>{{__('messages.'.$order->subscription->status)}}
                                                    </span>
                                                    @elseif ($order->subscription->status == 'paused')
                                                    <span class="badge badge-soft-primary">
                                                        <span class="legend-indicator bg-danger"></span>{{__('messages.'.$order->subscription->status)}}
                                                    </span>
                                                    @else
                                                    <span class="badge badge-soft-primary ">
                                                        <span class="legend-indicator bg-info"></span>{{__('messages.'.$order->subscription->status)}}
                                                    </span>
                                                    @endif
                                                </h6>
                                                @endif

                                                <h6>
                                                    <span>{{ __('Status') }} :</span>

                                                    @if (isset($order->subscription) && $order->subscription->status != 'canceled' )
                                                    @php
                                                    $order->order_status = $order->subscription_log ? $order->subscription_log->order_status : $order->order_status;
                                                    @endphp
                                                    @endif
                                                    @if ($order['order_status'] == 'pending')
                                                    <span class="badge bg-soft-info ml-2 ml-sm-3">
                                                        {{ __('messages.pending') }}
                                                    </span>
                                                    @elseif($order['order_status'] == 'confirmed')
                                                    <span class="badge bg-soft-info ml-2 ml-sm-3">
                                                        {{ __('messages.confirmed') }}
                                                    </span>
                                                    @elseif($order['order_status'] == 'processing')
                                                    <span class="badge bg-soft-warning ml-2 ml-sm-3">
                                                        {{ __('messages.cooking') }}
                                                    </span>
                                                    @elseif($order['order_status'] == 'picked_up')
                                                    <span class="badge bg-soft-warning ml-2 ml-sm-3">
                                                        {{ __('messages.out_for_delivery') }}
                                                    </span>
                                                    @elseif($order['order_status'] == 'delivered')
                                                    <span class="badge bg-soft-success ml-2 ml-sm-3">
                                                        {{ __('messages.delivered') }}
                                                    </span>
                                                    @else
                                                    <span class="badge bg-soft-info ml-2 ml-sm-3">
                                                        {{ __(str_replace('_', ' ', $order['order_status'])) }}
                                                    </span>
                                                    @endif

                                                </h6>
                                                <h6>
                                                    <span>
                                                        {{ __('messages.payment') }} {{ __('messages.method') }} :</span>
                                                    <strong>
                                                        {{ __(str_replace('_', ' ', $order['payment_method'])) }}</strong>
                                                </h6>
                                                <h6>
                                                    <span>{{ __('Order Type') }} :</span>
                                                    <strong class="text--title">{{ __(str_replace('_', ' ', $order['order_type'])) }}</strong>
                                                </h6>
                                                <h6>
                                                    <span>{{ __('Payment Status') }} :</span>
                                                    @if ($order['payment_status'] == 'paid')
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
                                        <div class="p-3 border-bottom bg-white">
                                            <div class="order-body">
                                                <div class="pb-3">
                                                   <div class="w-100">
                                                        <div class="d-flex gap-2 mb-2">
                                                            <a href="javascript:void(0)">
                                                                <h6 class="mb-1">Items</h6>
                                                            </a>
                                                        </div>
                                                        <div class="border-top pt-2">
                                                            <div class="row">
                                                                <div class="col-2">
                                                                    <p class="text-fw-bold mb-0">SI. </p>
                                                                </div>
                                                                <div class="col-4">
                                                                    <p class="text-fw-bold mb-0">Name</p>
                                                                </div>
                                                                <div class="col-2">
                                                                    <p class="text-fw-bold mb-0">Quantity </p>
                                                                </div>
                                                                <div class="col-4">
                                                                    <p class="text-fw-bold mb-0">Amount</p>
                                                                </div>
                                                                @if($customerOrderData?->foodItemList != null)
                                                                @foreach ($customerOrderData->foodItemList as $key => $listItem)
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
                                                                    <p class="text-fw-bold mb-0">{{Helpers::format_currency($listItem->foodPrice)}}
                                                                        <span class="text-muted mb-0"> </span>
                                                                    </p>
                                                                </div>
                                                                @endforeach
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr class="hr-horizontal">

                                            <div class="p-3 border-bottom">
                                                <h6 class="fw-bold">Payment Details &nbsp;
                                                    @if($order->payment_status == 'paid')
                                                    <span class="badge bg-success">{{Str::ucfirst($order->payment_status)}}</span>
                                                    @elseif ($order->payment_status=='unpaid')
                                                    <span class="badge bg-primary">{{Str::ucfirst($order->payment_status)}}</span>
                                                    @endif
                                                </h6>
                                                <p class="mb-1">Sub Total <span class="text-info ms-1"></span><span
                                                    class="float-end text-dark">{{Helpers::format_currency($customerOrderData?->sumOfFoodPriceBeforDiscount)}}</span>
                                                </p>

                                                @if ($customerOrderData?->sumOfDiscount > 0)
                                                <p class="mb-1 text-success">Discount <span class="float-end text-success">{{Helpers::format_currency($customerOrderData?->sumOfDiscount)}}</span>
                                                </p>
                                                @endif
                                                @if ($customerOrderData?->couponDiscountAmount > 0)
                                                <p class="mb-1 text-success">Coupon Discount <span class="float-end text-success">{{Helpers::format_currency($customerOrderData?->couponDiscountAmount)}}</span>
                                                </p>
                                                @endif
                                                @if ($customerOrderData?->platformCharge > 0)
                                                <div class="mb-1">Platform Fee
                                                <button type="button" class="btn text-info ms-1 p-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                                                    data-bs-title="Platform Fee Explanation"><i class="feather-info"></i></button>
                                                    <div class="float-end">
                                                        <span class="text-danger "><strike class="me-2">{{Helpers::format_currency($customerOrderData?->zone->platform_charge)}}</strike></span>
                                                        <span class="text-dark ">{{Helpers::format_currency($customerOrderData?->platformCharge)}}</span>
                                                    </div>
                                                </div>
                                                @endif
                                                <p class="mb-1">Packing Charge <span class="text-info ms-1"></span><span
                                                    class="float-end text-dark">{{Helpers::format_currency($customerOrderData?->sumofPackingCharge)}}</span>
                                                </p>
                                                <p class="mb-1">Delivery Charge<button type="button" class="btn text-info ms-1 p-0"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                                                    data-bs-title="Delivery Charge Explanation"><i class="feather-info"></i></button><span
                                                    class="float-end text-dark">
                                                    @if(isset($customerOrderData->freeDelivery) && $customerOrderData?->freeDelivery > 0)
                                                    <span class="text-danger "><strike class="me-2">{{Helpers::format_currency($customerOrderData?->deliveryChargeFaceVal)}}</strike></span>
                                                    @endif
                                                    {{ Helpers::format_currency($customerOrderData?->deliveryCharge)}}
                                                    </span>
                                                </p>
                                                   <hr>
                                                 <p class="mb-1  text-success">Gross Total<span class="float-end text-success">{{Helpers::format_currency($customerOrderData?->grossTotal)}}</span>
                                                <p class="mb-1">GST {{$customerOrderData?->gstPercent}}%<button type="button" class="btn text-info ms-1 p-0"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                                                    data-bs-title="GST and Restaurant Charge's Explanation"><i
                                                        class="feather-info"></i></button><span class="float-end text-dark">{{Helpers::format_currency($customerOrderData?->gstAmount)}}</span></p>
                                                <p class="mb-1  text-warning">Delivery Boy Tips<span class="float-end text-warning">{{Helpers::format_currency($customerOrderData?->dm_tips)}}</span>
                                                </p>

                                                <hr class="hr-horizontal">
                                                <h6 class="fw-bold mb-0">TOTAL <span class="float-end">{{ Helpers::format_currency(number_format($customerOrderData?->billingTotal, 2, '.', ''))}}</span></h6>
                                            </div>
                                        </div>
                                        @if($order->payment_method == "cash&wallet" ||$order->payment_method == "online&wallet" )
                                        <hr class="hr-horizontal">

                                        <p class="mb-1 text-info mx-3">Wallet<span class="float-end text-success">{{Helpers::format_currency($order->order_amount - $order->cash_to_collect)}}</span>
                                        </p>
                                        <p class="mb-1 text-info mx-3">Cash|Online<span class="float-end text-success">
                                            @if($order->payment_method == "cash")
                                            {{Helpers::format_currency($order->order_amount)}}
                                            @elseif ($order->payment_method == "cash&wallet")
                                            {{Helpers::format_currency($order->cash_to_collect)}}
                                            @elseif ($order->payment_method == "online")
                                            {{Helpers::format_currency($order->order_amount)}}
                                            @elseif ($order->payment_method == "online&wallet")
                                            {{Helpers::format_currency($order->cash_to_collect)}}
                                            @endif
                                        </span>
                                        </p>
                                        @endif


                                    </div>
                                    <!-- End Body -->
                                </div>
                                <!-- End Card -->
                            </div>

                        <div class="col-lg-4 order-print-area-right">
                            <!-- Card -->
                            @if ($order['order_status'] != 'delivered')
                            <div class="card mb-2">
                                <!-- Header -->
                                <div class="card-header border-0 py-0">
                                    <h5 class="card-header-title border-bottom py-3 m-0  w-100 text-center">{{ __('Order Setup') }}</h5>
                                </div>
                                <!-- End Header -->

                                <!-- Body -->

                                <div class="card-body">
                                    <!-- Unfold -->
                                    <div class="form-group">
                                        <select  id="statusChanger" type="button" class="form-control  bg-soft-primary">
                                            <option value="confirmed" {{$order->order_status == 'confirmed'? 'selected' : null}} data-route="{{ route('admin.order.order-status-update', ['id' => $order['id'], 'order_status' => 'confirmed']) }}" data-message="{{ __('Change status to confirmed ?') }}">
                                                {{ __('Confirm Order') }}
                                            </option>
                                            <option value="pending" {{$order->order_status == 'pending'? 'selected' : null}}  data-route="{{ route('admin.order.order-status-update', ['id' => $order['id'], 'order_status' => 'pending']) }}" data-message="{{ __('Are You sure to make Order Pending ?') }}">
                                                {{ __('Pending Order') }}
                                            </option>
                                            <option value="processing" {{$order->order_status == 'processing'? 'selected' : null}}  data-message="{{ __('Change status to cooking ?') }}" data-processing="{{ $max_processing_time }}"
                                                data-route="{{ route('admin.order.order-status-update', ['id' => $order['id'], 'order_status' => 'processing']) }}">
                                                {{__('messages.Proceed_for_cooking')}}
                                            </option>
                                            <option value="dm_at_restaurant" {{$order->order_status == 'dm_at_restaurant'? 'selected' : null}}  data-route="{{ route('admin.order.order-status-update', ['id' => $order['id'], 'order_status' => 'dm_at_restaurant']) }}" data-message="{{ __('Change status to Delivery Boy at restaurant')}}">
                                                {{ __('Delivery Boy at restaurant')}}
                                            </option>
                                            <option value="handover" {{$order->order_status == 'handover'? 'selected' : null}}  data-route="{{ route('admin.order.order-status-update', ['id' => $order['id'], 'order_status' => 'handover']) }}" data-message="{{ __('Change status to ready for handover ?')}}">
                                                {{ $order->order_status == 'handover'? __('Handover'): __('messages.make_ready_for_handover')  }}
                                            </option>
                                            <option value="order_on_way" {{$order->order_status == 'order_on_way'? 'selected' : null}}  data-route="{{ route('admin.order.order-status-update', ['id' => $order['id'], 'order_status' => 'order_on_way']) }}" data-message="{{ __('Change status to Order on the Way to costumer ?')}}">
                                                {{  __('Order on the Way to costumer') }}
                                            </option>
                                            <option value="arrived_at_door" {{$order->order_status == 'arrived_at_door'? 'selected' : null}}  data-route="{{ route('admin.order.order-status-update', ['id' => $order['id'], 'order_status' => 'arrived_at_door']) }}" data-message="{{ __('Change status to Order ⁠Arived at costumer doors ?')}}">
                                                {{  __('⁠Arrived at costumer doors') }}
                                            </option>
                                            <option value="delivered" {{$order->order_status == 'delivered'? 'selected' : null}}  data-route="{{ route('admin.order.order-status-update', ['id' => $order['id'], 'order_status' => 'delivered']) }}" data-message="{{ __('Change status to Order Delivered?')}}">
                                                {{  __('Delivered') }}
                                            </option>
                                            <option value="canceled" {{$order->order_status == 'canceled'? 'selected' : null}}  data-route="{{ route('admin.order.order-status-update', ['id' => $order['id'], 'order_status' => 'canceled']) }}" data-message="{{ __('Are You sure want to Cancel Order ?')}}">
                                                {{  __('Cancel Order') }}
                                            </option>
                                        </select>
                                    </div>
                                    <!-- End Unfold -->

                                </div>
                            </div>
                            @if ($order['order_type'] != 'take_away')
                            @if ($order->delivery_man)
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">{{ __('Delivery Man Information') }}</h5>
                                </div>
                                @php($deliveryAddress = json_decode($order->delivery_address))
                                <div class="card-body">
                                    <div class="row">
                                        <!-- Customer Info -->
                                        <div class="col-md-8">
                                            <h6 class="text-muted"><i class="fas fa-user"></i>
                                                {{ Str::ucfirst($order->delivery_man['f_name']) . ' ' . Str::ucFirst($order->delivery_man['l_name']) }}
                                            </h6>

                                            {{-- <p><i class="fas fa-phone"></i> <a href="tel:+1234567890">+1234567890</a></p> --}}
                                            {{-- <p class="mt-2"><i class="fas fa-map-marker-alt"></i>  {{ isset($deliveryAddress->stringAddress) ? $deliveryAddress->stringAddress : '' }}</p> --}}
                                            <p class="mt-2"><i class="fas fa-phone"></i>  {{ $order->delivery_man['phone'] }}</p>
                                            {{-- <p>Total Orders: <strong>{{\App\Models\Order::where('customer_id', $order->customer->id)
                                                ->where('order_status', 'delivered')
                                                ->count();}}</strong></p> --}}
                                        </div>
                                        <!-- Actions -->
                                        <div class="col-md-4 text-md-end">
                                            {{-- href="https://maps.google.com/?q={{$deliveryAddress->position->lat.','.$deliveryAddress->position->lon}}" --}}
                                            @if(isset($deliveryAddress->position))
                                            <button type="button" data-bs-toggle="offcanvas" data-bs-target="#dmLocation"  class="btn btn-outline-primary">
                                                <i class="fas fa-map-marked-alt"></i> View on Map
                                            </button>
                                            @endif
                                            <a  href="tel:{{ $order->delivery_man['phone'] }}" class="btn mt-2 btn-sm btn-outline-success">
                                                <i class="fas fa-phone"></i> Call
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                                @if ($order['order_status'] != 'delivered' && $order['order_status'] != 'canceled')
                                <div class="w-100 text-center mt-4">
                                    <button type="button" class="btn w-100 btn-primary font-regular" data-bs-toggle="offcanvas" data-bs-target="#dmManualSelect" data-lat='21.03' data-lng='105.85'>
                                        <i class="tio-bike"></i> {{ __('messages.assign_delivery_mam_manually') }}
                                    </button>
                                </div>
                                @endif
                            @endif
                            @endif
                            @if ($order->customer)
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Customer Details</h5>
                                </div>
                                @php($deliveryAddress = json_decode($order->delivery_address))
                                <div class="card-body">
                                    <div class="row">
                                        <!-- Customer Info -->
                                        <div class="col-md-8" onclick="location.href = '{{route('admin.customer.view',$order->customer_id)}}'">
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
                                            <button data-bs-toggle="offcanvas" data-bs-target="#customerLocation" class="btn btn-outline-primary">
                                                <i class="fas fa-map-marked-alt"></i> View on Map
                                            </button>
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
                                        <h3 class="mb-2">#{{$order->id}}</h3>
                                    </div>
                                    <hr class="hr-vertial">
                                    <div>
                                        {{-- <h2 class="mb-2">7,500</h2> --}}
                                        <p class="mb-0 ">
                                        <a class="badge py-1 px-2 bg-soft-success"  href="{{route('admin.order.generate-KOT',$order->id)}}"  type="button">Print KOT</a>
                                        <a class="badge py-1 px-2 bg-soft-warning" href="{{route('admin.order.generate-invoice',$order->id)}}" type="button"><i class="fa fa-print"></i>Print Bill</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

 {{-- dm location --}}
 @if($order->delivery_man && $order->created_at->isToday())
    <?php
      $dmData = new \App\Services\JsonDataService($order->delivery_man->id);
      $dmData = $dmData->readData();
      $dmLoation = $dmData?->last_location;
     ?>
    <div class="offcanvas offcanvas-bottom fullscreen " tabindex="-1" id="dmLocation" aria-labelledby="offcanvasBottomLabel">
        <i class="position-absolute top-0 end-0 text-primary me-2 mt-2 fa fa-close fa-2x" style="z-index: 1;" data-bs-dismiss="offcanvas"></i>
        <div class="offcanvas-body m-1 p-1 ">
            <div class="mapouter m-0 p-0">
                <iframe
                    src="https://www.google.com/maps?q={{$dmLoation['lat']}},{{$dmLoation['lng']}}&output=embed"
                    style="border:0;width:100%;height:100%;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
      </div>
    @endif
{{-- customer locaton --}}
@if ($order->customer)
      <div class="offcanvas offcanvas-bottom fullscreen " tabindex="-1" id="customerLocation" aria-labelledby="offcanvasBottomLabel">
        <i class="position-absolute top-0 end-0 text-primary me-2 mt-2 fa fa-close fa-2x" style="z-index: 1;" data-bs-dismiss="offcanvas"></i>
        <div class="offcanvas-body m-1 p-1 ">
            <div class="mapouter m-0 p-0">
                <iframe
                    src="https://www.google.com/maps?q={{$deliveryAddress->position->lat}},{{$deliveryAddress->position->lon}}&output=embed"
                    style="border:0;width:100%;height:100%;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
      </div>
@endif

{{--  manual assign dm--}}
@if($delivery_man != null)
<div class="offcanvas offcanvas-bottom fullscreen " tabindex="-1" id="dmManualSelect" aria-labelledby="offcanvasBottomLabel">
    <i class="position-absolute top-0 end-0 text-primary me-2 mt-2 fa fa-close fa-2x" style="z-index: 1;" data-bs-dismiss="offcanvas"></i>
    <div class="offcanvas-body m-1 p-1 ">
        <div class="mapouter m-0 p-0">
            <div class="row">
                <div class="col-md-5 my-2">
                    <ul class="list-group overflow-auto max-height-400">
                        @foreach ($delivery_man??[] as $dm)
                            <li class="list-group-item">
                                <span class="dm_list w-100" role='button' data-id="{{ $dm['id'] }}">
                                    <img class="avatar avatar-sm w-25 avatar-circle mr-1" onerror="this.src='{{ asset('assets/images/icons/300x100/profile-deliveryman.png') }}'" src="{{ asset('delivery-man/'. $dm['image']) }}LL" alt="{{ $dm['name'] }}">
                                    {{ $dm['name'] }}
                                </span>

                                <a class="btn btn-primary  btn-xs float-right" onclick="addDeliveryMan({{ $dm['id'] }})">{{$order->delivery_man_id != null &&  $order->delivery_man_id == $dm['id']?__("Assigned") : __('messages.assign') }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-md-7 modal_body_map">

                    <div id="map_canvas" style=" width:100%;height: 100vh"></div>
                </div>
            </div>
        </div>
    </div>
  </div>

@endif


@endsection
@push('javascript')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBPVVxuVkmdE1GzSgIqm_9dx64tea7Wltk&libraries=geometry,places"></script>
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

function initializeGMap() {
    // Correctly parse the JSON object
    const deliveryman = JSON.parse(`{!! json_encode($delivery_man) !!}`);

    // Ensure map options are defined correctly (example options, you may customize)
    const myOptions = {
        zoom: 18,
        center: new google.maps.LatLng(0, 0),
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        disableDefaultUI: true,
    };

    // Initialize the map
    var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

    // Define bounds for fitting all markers on the map
    var dmbounds = new google.maps.LatLngBounds();
    var dmMarkers = {};

    // Info window to display information when a marker is clicked
    var infowindow = new google.maps.InfoWindow();

    // Loop through each deliveryman and create a marker
    for (var i = 0; i < deliveryman.length; i++) {
        if (deliveryman[i].lat && deliveryman[i].lng) {  // Ensure lat/lng exists
            // Create a LatLng point
            var point = new google.maps.LatLng(deliveryman[i].lat, deliveryman[i].lng);
            dmbounds.extend(point); // Extend bounds to include this point

            // Create marker for deliveryman
            var marker = new google.maps.Marker({
                position: point,
                map: map,
                title: deliveryman[i].location,
                icon: {
                url: "{{ asset('assets/user/img/icons/deliveryman-map-icon.png') }}",
                scaledSize: new google.maps.Size(50, 50),
                }
            });

            // Store the marker by deliveryman ID
            dmMarkers[deliveryman[i].id] = marker;

            // Add a click listener to show infowindow when marker is clicked
            google.maps.event.addListener(marker, 'click', (function(marker, i) {
                return function() {
                    infowindow.setContent(
                        "<div class='float-start'><img class='img-fluid rounded-circle' style='width: 25px' src='{{ asset('delivery-man/') }}/" +
                        deliveryman[i].image +
                        "'></div><div class='float-end p--10px'><b>" + deliveryman[i].name +
                        "</b><br/>  </div>");
                    infowindow.open(map, marker);
                };
            })(marker, i));
        }
    }

    // Fit the map to include all deliverymen markers
    map.fitBounds(dmbounds);
}

// Initialize the map on page loadw
initializeGMap();
</script>
<script>
    function addDeliveryMan(id) {
        $.ajax({
            type: "GET",
            url: '{!! route('admin.order.dm_assign_manually')."?order_id={$order->id}&dm_id=" !!}' + id,
            success: function(data) {
                location.reload();
                console.log(data)
                toastr.success('Successfully added', {
                    CloseButton: true,
                    ProgressBar: true
                });
            },
            error: function(response) {
                console.log(response);
                toastr.error(response.responseJSON.message, {
                    CloseButton: true,
                    ProgressBar: true
                });
            }
        });
    }
</script>
@endpush
