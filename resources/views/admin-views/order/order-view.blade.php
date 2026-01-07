<?php
$max_processing_time = explode('-', $order->restaurant->max_delivery_time)[0];
// orderCalculationStmt
$customerOrderData = json_decode($order->orderCalculationStmt?->customerData);
$deliveryAddress = json_decode($order->delivery_address);
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

    .iq-timeline0 li .timeline-dots {
        top: 0px !important;
    }
</style>
@section('content')

    <div class="conatiner-fluid content-inner mt-1 py-0">

        <div class="card">
            <div class="card-body d-flex justify-content-between">
                <h4 class="mb-0 d-flex align-items-center">
                    <div class="">
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
                        {{ __('messages.Order Details') }}
                    </span>
                </h4>
                <div class="d-flex  float-end">
                    <a class="btn btn-soft-info rounded-circle me-2 p-3"
                        href="{{ route('admin.order.details', [$order['id'] - 1]) }}" data-toggle="tooltip"
                        data-placement="top" title="{{ __('Previous order') }}">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                    <a class="btn btn-soft-info rounded-circle p-3"
                        href="{{ route('admin.order.details', [$order['id'] + 1]) }}" data-toggle="tooltip"
                        data-placement="top" title="{{ __('Next order') }}">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>

            </div>
        </div>
        {{-- <div class="card"> --}}
        <div class="row" id="printableArea">
            <div class="col-lg-8 order-print-area-left ps-0">
                <!-- Card -->
                <div class="card mb-3 mb-lg-5">
                    <!-- Header -->
                    <div class="card-body">
                        <div class="d-flex justify-content-between border-bottom pb-3">
                            <div>
                                <h5 class="page-header-title mt-2">
                                    <span class="text-info">
                                        {{ __('messages.order') }} #{{ $order['id'] }}
                                    </span>
                                </h5>
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
                                    <div class="badge bg-soft-info">
                                        <i class="fs-5">{{ Str::ucfirst($order->restaurant->name) }}</i>
                                    </div>

                                </span>
                                <span class="mt-2 d-block">
                                    <i class="">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="25">
                                            <circle cx="15" cy="12" r="8.5" fill="none" stroke="#303c42"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path fill="none" stroke="#303c42" stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M2.5 6.5c-2.7 3.2-2.7 7.8 0 11m3-11c-2.7 3.2-2.7 7.8 0 11" />
                                            <circle cx="15" cy="12" r=".5" fill="none" stroke="#303c42"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path fill="none" stroke="#303c42" stroke-linecap="round"
                                                stroke-linejoin="round" d="M15 6.5V12l3 3" />
                                        </svg>
                                    </i>
                                    {{ date('d M Y ' . config('timeformat'), strtotime($order['created_at'])) }}
                                    {{ \Carbon\Carbon::parse($order->created_at)->format('h:i A') }}
                                </span>
                                @if($order->order_status === 'scheduled' && $order->schedule_at)
                                    <span class="d-block text-warning mt-2">
                                        <i class="fas fa-calendar-clock me-2"></i>
                                        <strong>Scheduled for:</strong>
                                        {{ \Carbon\Carbon::parse($order->schedule_at)->format('d M Y, h:i A') }}
                                    </span>
                                @endif
                            </div>
                            <div>
                                <div class="d-sm-flex flex-wrap ml-auto align-items-center justify-content-end m-n-5rem">
                                    <a class="btn btn-primary m-2 print--btn text-nowrap py-lg-2 px-2 px-lg-4"
                                        href="{{ route('admin.order.generate-invoice', [$order['id']]) }}">
                                        <i class="fa fa-print mr-1"></i> {{ __('messages.print') }}
                                        {{ __('messages.invoice') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-lg-6">
                                <p>
                                    <span class="text-dark"> <b> Name :</b>
                                        @if (isset($deliveryAddress->contact_person_name))
                                            {{ $deliveryAddress->contact_person_name }}
                                        @elseif ($order->lovedOne)
                                            {{ $order->lovedOne->name }}
                                            <small class="badge bg-warning text-dark ms-1">❤️ Loved One</small>
                                        @else
                                            {{ Str::ucfirst($order->customer->f_name) . ' ' . Str::ucfirst($order->customer->l_name) }}
                                        @endif
                                    </span>
                                </p>
                                <p>
                                    {{-- <span class="text-dark"> <b> Email :</b></span> --}}
                                </p>
                                <p>
                                    <span class="text-dark"> <b> Contact Number :</b>
                                        @if (isset($deliveryAddress->contact_person_number))
                                            {{ $deliveryAddress->contact_person_number }}
                                        @elseif ($order->lovedOne)
                                            {{ $order->lovedOne->phone }}
                                        @else
                                            {{ $order->customer->phone }}
                                        @endif
                                    </span>
                                </p>
                                <p class="mb-0">
                                    <span class="text-dark"> <b> Delivery Address :</b>
                                        {{ isset($deliveryAddress->stringAddress) ? $deliveryAddress->stringAddress : '' }}</span>
                                </p>

                                <?php // $restaurantAddress = json_decode($order->restaurant->address);
                                ?>

                                {{-- Str::ucfirst($restaurantAddress->street) }}, {{ Str::ucfirst($restaurantAddress->city) }} - {{ Str::ucfirst($restaurantAddress->pincode) --}}
                            </div>
                            <div class="col-lg-6">

                                <div class="row g-1">
                                    <div class="col-6 px-0">
                                        <div class="border text-center py-2  bg-soft-light">
                                            <span class="text-dark text-uppercase fw-bold">{{ __('Status') }}</span>

                                        </div>
                                        <div class="border text-center py-2">
                                            @if (isset($order->subscription) && $order->subscription->status != 'canceled')
                                                @php
                                                    $order->order_status = $order->subscription_log
                                                        ? $order->subscription_log->order_status
                                                        : $order->order_status;
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
                                            @elseif($order['order_status'] == 'scheduled')
                                                <span class="text-capitalize text-warning ml-2 ml-sm-3">
                                                    <i class="fas fa-clock me-1"></i>{{ __('messages.scheduled') }}
                                                </span>
                                            @elseif($order['order_status'] == 'canceled')
                                                <span class="text-capitalize text-danger ml-2 ml-sm-3">
                                                    {{ __('messages.canceled') }}
                                                    @if(!empty($order['cancellation_reason']))
                                                        <br><small class="text-muted">{{ $order['cancellation_reason'] }}</small>
                                                    @endif
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
                                            <span class="text-dark text-uppercase fw-bold">{{ __('messages.payment') }}
                                                {{ __('messages.method') }}</span>
                                        </div>
                                        <div class="border text-center py-2">
                                            <span class="text-capitalize">
                                                {{ __(str_replace('_', ' ', $order['payment_method'])) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-1">
                                    <div class="col-6 px-0">
                                        <div class="border text-center py-2 bg-soft-light">
                                            <span class="text-dark text-uppercase fw-bold">{{ __('Order Type') }}</span>
                                        </div>
                                        <div class="border text-center py-2">
                                            <span
                                                class="text-capitalize">{{ __(str_replace('_', ' ', $order['order_type'])) }}</span>
                                        </div>
                                    </div>
                                    <div class="col-6 px-0">
                                        <div class="border text-center py-2 bg-soft-light">
                                            <span class="text-dark text-uppercase fw-bold">{{ __('Payment Status') }}
                                            </span>
                                        </div>
                                        <div class="border text-center py-2">
                                            @if ($order['payment_status'] == 'paid')
                                                <span class="text-success text-capitalize">
                                                    {{ __('messages.paid') }}
                                                </span>
                                            @else
                                                <span class="text-danger text-capitalize">
                                                    {{ __('messages.unpaid') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-1">
                                    <div class="col-6 px-0">
                                        <div class="border text-center py-2 bg-soft-light">
                                            <span class="text-dark text-uppercase fw-bold">{{ __('Distance') }}</span>
                                        </div>
                                        <div class="border text-center py-2">
                                            <span
                                                class="text-capitalize">{{ number_format(
                                                    Helpers::haversineDistance(
                                                        [
                                                            'lat' => $order->restaurant->latitude,
                                                            'lon' => $order->restaurant->longitude,
                                                        ],
                                                        [
                                                            'lat' => $deliveryAddress->position->lat,
                                                            'lon' => $deliveryAddress->position->lon,
                                                        ],
                                                    ),
                                                    2,
                                                ) }}
                                                Km.</span>
                                        </div>
                                    </div>
                                    <div class="col-6 px-0">
                                        <div class="border text-center py-2 bg-soft-light">
                                            <span class="text-dark text-uppercase fw-bold">{{ __('Delivery Pin') }}
                                            </span>
                                        </div>
                                        <div class="border text-center py-2">
                                            <span class="text-success text-capitalize">
                                                {{ $order->otp }}
                                            </span>

                                        </div>
                                    </div>
                                </div>

                            </div>

                            {{-- <div class="d-none d-sm-flex flex-wrap ml-auto align-items-center justify-content-end m-n-5rem">
                                    <a class="btn btn-primary m-2 print--btn" href="{{ route('admin.order.generate-invoice', [$order['id']]) }}">
                                        <i class="fa fa-print mr-1"></i> {{ __('messages.print') }} {{ __('messages.invoice') }}
                                    </a>
                                </div> --}}
                        </div>
                        @if ($order->cooking_instruction != null)
                            <div class="bd-example mt-1">
                                <div class="alert alert-success bg-white mb-0 rounded-2" role="alert">
                                    <h5 class="alert-heading mb-2 fw-bolder">Cooking Instruction</h5>
                                    <p>{{ $order->cooking_instruction }}</p>
                                </div>
                            </div>
                        @endif
                        {{-- @dd($order->delivery_instruction) --}}
                        @if ($order->delivery_instruction != null)
                            <div class="bd-example mt-1">
                                <div class="alert alert-success bg-white mb-0 rounded-2" role="alert">
                                    <h5 class="alert-heading mb-2 fw-bolder">Delivery Instruction</h5>
                                    <p>
                                        <?php
                                        $instructions = json_decode($order->delivery_instruction);
                                        foreach ($instructions??[] as $key => $ins):?>
                                        @if ($ins != null)
                                            <span>✦︎{{ Str::ucfirst($ins) }}</span>
                                        @endif
                                        <?php endforeach;  ?>
                                    </p>
                                </div>
                            </div>
                        @endif
                        <div class="">
                            <div class="my-3 border-top pt-3">
                                <h5 class="text-muted text-center">Items</h5>
                            </div>
                            <div class="pt-2">
                                <div class="row g-1 table-responsive">
                                    <table class="table table-bordered mt-1 mb-1">
                                        <thead>
                                            <tr class="text-center">
                                                <th class="initial-38-7">Sl No.</th>
                                                <th class="initial-38-7">Name</th>
                                                <th class="initial-38-6">Quantity</th>
                                                <th class="initial-38-7">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($customerOrderData?->foodItemList != null)
                                                @foreach ($customerOrderData->foodItemList as $key => $listItem)
                                                    <tr>
                                                        <td class="text-muted">
                                                            {{ $key + 1 }}.
                                                        </td>
                                                        <td class="text-break">
                                                            <div class="font-size-sm text-body">
                                                                <span
                                                                    class="font-weight-bold">{{ Str::ucfirst($listItem->foodName) }}</span>
                                                            </div>
                                                        </td>
                                                        <td class="">
                                                            {{ $listItem->quantity }}
                                                        </td>
                                                        <td class="w-28p">
                                                            {{ App\CentralLogics\Helpers::format_currency($listItem->foodPrice) }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="py-4 px-0">
                            <h5 class="fw-bold border-bottom border-top py-3 my-3 text-center">Payment Details &nbsp;
                                @if ($order->payment_status == 'paid')
                                    <span class="badge bg-success">{{ Str::ucfirst($order->payment_status) }}</span>
                                @elseif ($order->payment_status == 'unpaid')
                                    <span class="badge bg-primary">{{ Str::ucfirst($order->payment_status) }}</span>
                                @endif
                            </h5>
                            <p class="mb-1">Sub Total <span class="text-info ms-1"></span><span
                                    class="float-end text-dark">{{ Helpers::format_currency($customerOrderData?->sumOfFoodPriceBeforDiscount) }}</span>
                            </p>

                            @if ($customerOrderData?->sumOfDiscount > 0)
                                <p class="mb-1 text-success">Discount <span
                                        class="float-end text-success">{{ Helpers::format_currency($customerOrderData?->sumOfDiscount) }}</span>
                                </p>
                            @endif
                            @if ($customerOrderData?->couponDiscountAmount > 0)
                                <p class="mb-1 text-success">Coupon Discount <span
                                        class="float-end text-success">{{ Helpers::format_currency($customerOrderData?->couponDiscountAmount) }}</span>
                                </p>
                            @endif
                            @if ($customerOrderData?->platformCharge > 0)
                                <div class="mb-1">Platform Fee
                                    <button type="button" class="btn text-info ms-1 p-0" data-bs-toggle="tooltip"
                                        data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                                        data-bs-title="Platform Fee Explanation"><i class="feather-info"></i></button>
                                    <div class="float-end">
                                        <span class="text-danger "><strike
                                                class="me-2">{{ Helpers::format_currency($customerOrderData?->zone->platform_charge) }}</strike></span>
                                        <span
                                            class="text-dark ">{{ Helpers::format_currency($customerOrderData?->platformCharge) }}</span>
                                    </div>
                                </div>
                            @endif
                            <p class="mb-1">Packing Charge <span class="text-info ms-1"></span><span
                                    class="float-end text-dark">{{ Helpers::format_currency($customerOrderData?->sumofPackingCharge) }}</span>
                            </p>
                            <p class="mb-1">Delivery Charge<button type="button" class="btn text-info ms-1 p-0"
                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                    data-bs-custom-class="custom-tooltip" data-bs-title="Delivery Charge Explanation"><i
                                        class="feather-info"></i></button><span class="float-end text-dark">
                                    @if (isset($customerOrderData->freeDelivery) && $customerOrderData?->freeDelivery > 0)
                                        <span class="text-danger "><strike
                                                class="me-2">{{ Helpers::format_currency($customerOrderData?->deliveryChargeFaceVal) }}</strike></span>
                                    @endif
                                    {{ Helpers::format_currency($customerOrderData?->deliveryCharge) }}
                                </span>
                            </p>
                            <hr class="hr-horizontal">
                            <p class="mb-1  text-success">Gross Total<span
                                    class="float-end text-success">{{ Helpers::format_currency($customerOrderData?->grossTotal) }}</span>
                            <p class="mb-1">GST {{ $customerOrderData?->gstPercent }}%<button type="button"
                                    class="btn text-info ms-1 p-0" data-bs-toggle="tooltip" data-bs-placement="top"
                                    data-bs-custom-class="custom-tooltip"
                                    data-bs-title="GST and Restaurant Charge's Explanation"><i
                                        class="feather-info"></i></button><span
                                    class="float-end text-dark">{{ Helpers::format_currency($customerOrderData?->gstAmount) }}</span>
                            </p>
                            <p class="mb-1  text-warning">Delivery Boy Tips<span
                                    class="float-end text-warning">{{ Helpers::format_currency($customerOrderData?->dm_tips) }}</span>
                            </p>

                            <div class="border-top pt-2 mt-2"></div>
                            <h6 class="fw-bold mb-0">TOTAL <span
                                    class="float-end">{{ Helpers::format_currency(number_format($customerOrderData?->billingTotal, 2, '.', '')) }}</span>
                            </h6>
                        </div>
                    </div>
                    {{-- @if (!empty($customerOrderData->couponDetails)) --}}
                    <hr class="hr-horizontal">
                    <h5 class="mx-3 mb-2 text-muted"> Used Coupons</h5>
                    @foreach ($customerOrderData->couponDetails as $couponDet)
                        <p class="mb-1 text-info mx-3">Code -{{ $couponDet->code }}<span
                                class="float-end text-success">Discount :
                                {{ Helpers::format_currency($couponDet->couponDiscount) }}</span>
                        </p>
                    @endforeach
                    {{-- @endif --}}


                    {{-- </div> --}}
                    @if ($order->payment_method == 'cash&wallet' || $order->payment_method == 'online&wallet')
                        <hr class="hr-horizontal">

                        <p class="mb-1 text-info mx-3">Wallet<span
                                class="float-end text-success">{{ Helpers::format_currency($order->order_amount - $order->cash_to_collect) }}</span>
                        </p>
                        @if ($order->payment_method == 'cash')
                            <p class="mb-1 text-info mx-3">Cash<span class="float-end text-success">
                                    {{ Helpers::format_currency($order->order_amount) }}
                                </span></p>
                        @elseif ($order->payment_method == 'cash&wallet')
                            <p class="mb-1 text-info mx-3">Cash<span class="float-end text-success">
                                    {{ Helpers::format_currency($order->cash_to_collect) }}
                                </span></p>
                        @elseif ($order->payment_method == 'online')
                            <p class="mb-1 text-info mx-3">Online<span class="float-end text-success">
                                    {{ Helpers::format_currency($order->order_amount) }}
                                </span></p>
                        @elseif ($order->payment_method == 'online&wallet')
                            <p class="mb-1 text-info mx-3">Online<span class="float-end text-success">
                                    {{ Helpers::format_currency($order->cash_to_collect) }}
                                </span></p>
                        @endif
                    @endif


                    {{-- </div> --}}
                    <!-- End Body -->
                </div>
                <!-- End Card -->
            </div>

            <div class="col-lg-4 order-print-area-right p-0">
                <!-- Card -->
                @if ($order['order_status'] != 'delivered')
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-header-title py-3 m-0 fw-bolder w-100 text-center text-primary">
                                {{ __('Order Setup') }}</h5>
                            <hr class="hr-horizontal mb-4">
                            <div class="form-group">
                                <select id="statusChanger" type="button" class="form-control  bg-soft-primary">
                                    <option value="confirmed"
                                        {{ $order->order_status == 'confirmed' ? 'selected' : null }}
                                        data-route="{{ route('admin.order.order-status-update', ['id' => $order['id'], 'order_status' => 'confirmed']) }}"
                                        data-message="{{ __('Change status to confirmed ?') }}">
                                        {{ __('Confirm Order') }}
                                    </option>
                                    <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : null }}
                                        data-route="{{ route('admin.order.order-status-update', ['id' => $order['id'], 'order_status' => 'pending']) }}"
                                        data-message="{{ __('Are You sure to make Order Pending ?') }}">
                                        {{ __('Pending Order') }}
                                    </option>
                                    <option value="processing"
                                        {{ $order->order_status == 'processing' ? 'selected' : null }}
                                        data-message="{{ __('Change status to cooking ?') }}"
                                        data-processing="{{ $max_processing_time }}"
                                        data-route="{{ route('admin.order.order-status-update', ['id' => $order['id'], 'order_status' => 'processing']) }}">
                                        {{ __('messages.Proceed_for_cooking') }}
                                    </option>
                                    <option value="dm_at_restaurant"
                                        {{ $order->order_status == 'dm_at_restaurant' ? 'selected' : null }}
                                        data-route="{{ route('admin.order.order-status-update', ['id' => $order['id'], 'order_status' => 'dm_at_restaurant']) }}"
                                        data-message="{{ __('Change status to Delivery Boy at restaurant') }}">
                                        {{ __('Delivery Boy at restaurant') }}
                                    </option>
                                    <option value="handover" {{ $order->order_status == 'handover' ? 'selected' : null }}
                                        data-route="{{ route('admin.order.order-status-update', ['id' => $order['id'], 'order_status' => 'handover']) }}"
                                        data-message="{{ __('Change status to ready for handover ?') }}">
                                        {{ $order->order_status == 'handover' ? __('Handover') : __('messages.make_ready_for_handover') }}
                                    </option>
                                    <option value="order_on_way"
                                        {{ $order->order_status == 'order_on_way' ? 'selected' : null }}
                                        data-route="{{ route('admin.order.order-status-update', ['id' => $order['id'], 'order_status' => 'order_on_way']) }}"
                                        data-message="{{ __('Change status to Order on the Way to costumer ?') }}">
                                        {{ __('Order on the Way to costumer') }}
                                    </option>
                                    <option value="arrived_at_door"
                                        {{ $order->order_status == 'arrived_at_door' ? 'selected' : null }}
                                        data-route="{{ route('admin.order.order-status-update', ['id' => $order['id'], 'order_status' => 'arrived_at_door']) }}"
                                        data-message="{{ __('Change status to Order ⁠Arived at costumer doors ?') }}">
                                        {{ __('⁠Arrived at costumer doors') }}
                                    </option>
                                    <option value="delivered"
                                        {{ $order->order_status == 'delivered' ? 'selected' : null }}
                                        data-route="{{ route('admin.order.order-status-update', ['id' => $order['id'], 'order_status' => 'delivered']) }}"
                                        data-message="{{ __('Change status to Order Delivered?') }}">
                                        {{ __('Delivered') }}
                                    </option>
                                    <option value="canceled" {{ $order->order_status == 'canceled' ? 'selected' : null }}
                                        data-route="{{ route('admin.order.order-status-update', ['id' => $order['id'], 'order_status' => 'canceled']) }}"
                                        data-message="{{ __('Are You sure want to Cancel Order ?') }}">
                                        {{ __('Cancel Order') }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                @endif
                    
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
                                        <h6 class="text-muted">
                                           <a href="{{route('admin.delivery-man.show',['id' => $order->delivery_man->id])}}"><i class="fas fa-user"></i>{{ Str::ucfirst($order->delivery_man['f_name']) . ' ' . Str::ucFirst($order->delivery_man['l_name']) }}</a>
                                        </h6>

                                        <p class="mt-2"><i class="fas fa-phone"></i>
                                            {{ $order->delivery_man['phone'] }}</p>
                                        {{-- <p>Total Orders: <strong>{{\App\Models\Order::where('customer_id', $order->customer->id)
                                            ->where('order_status', 'delivered')
                                            ->count();}}</strong></p> --}}
                                    </div>
                                    <!-- Actions -->
                                    <div class="col-md-4 text-md-end">
                                        @if (isset($deliveryAddress->position))
                                            <button type="button" data-bs-toggle="offcanvas"
                                                data-bs-target="#dmLocation" class="btn btn-outline-primary">
                                                <i class="fas fa-map-marked-alt"></i> View on Map
                                            </button>
                                        @endif
                                        <a href="tel:{{ $order->delivery_man['phone'] }}"
                                            class="btn mt-2 btn-sm btn-outline-success">
                                            <i class="fas fa-phone"></i> Call
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($order['order_status'] != 'delivered' && $order['order_status'] != 'canceled')
                        <div class="w-100 text-center mt-4">
                            <button type="button" class="btn w-100 btn-primary font-regular"
                                data-bs-toggle="offcanvas" data-bs-target="#dmManualSelect" data-lat='21.03'
                                data-lng='105.85'>
                                <i class="tio-bike"></i> {{ __('messages.assign_delivery_mam_manually') }}
                            </button>
                        </div>
                    @endif
                @endif
                @if ($order->customer)
                    <div class="card">
                        <div class="card-header mb-3 d-flex justify-content-between">
                            <h5 class="card-title mb-0 text-primary fw-bolder">Customer Details</h5>
                            <div><span
                                    class="badge bg-info mb-0">{{ \App\Models\Order::where('customer_id', $order->customer->id)->where('order_status', 'delivered')->count() }}
                                    Orders </span>
                            </div>
                        </div>

                        <div class="card-body border-top">
                            <div class="row">
                                <!-- Customer Info -->
                                <div onclick="location.href = '{{ route('admin.customer.view', $order->customer_id) }}'" type="button">
                                    <p><i class="fas fa-user me-2 text-primary "></i>
                                        <b class="text-uppercase">
                                                {{ Str::ucfirst($order->customer->f_name) . ' ' . Str::ucfirst($order->customer->l_name) }} ({{$order->customer->phone}})
                                        </span>
                                    @if ($order->lovedOne)
                                        <p>❤️<b class="ms-2 text-muted text-uppercase "> {{ $order->lovedOne ? $order->lovedOne->name : '' }} ({{$order->lovedOne ? $order->lovedOne->phone : ''}})</b></p>
                                    @endif

                                    {{-- <p><i class="fas fa-phone"></i> <a href="tel:+1234567890">+1234567890</a></p> --}}
                                    <p class="d-flex"><i class="fas fa-map-marker-alt me-3 mt-1 text-primary"></i>
                                        {{ isset($deliveryAddress->stringAddress) ? $deliveryAddress->stringAddress : '' }}
                                    </p>
                                    <p class=""><i class="fas fa-phone me-2 text-primary"></i>
                                        @if (isset($deliveryAddress->contact_person_number))
                                            {{ $deliveryAddress->contact_person_number }}
                                        @elseif ($order->lovedOne)
                                            {{ $order->lovedOne->phone }}
                                        @else
                                            {{ $order->customer->phone }}
                                        @endif
                                    </p>
                                    
                                    {{-- <p>Total Orders:
                                        <strong>{{ \App\Models\Order::where('customer_id', $order->customer->id)->where('order_status', 'delivered')->count() }}</strong>
                                    </p> --}}
                                </div>
                                <!-- Actions -->
                                <div class="d-flex">
                                    @if (isset($deliveryAddress->position))
                                        <button data-bs-toggle="offcanvas" data-bs-target="#customerLocation"
                                            class="btn btn-outline-primary w-50 me-1 px-1">
                                            <i class="fas fa-map-marked-alt me-1"></i> View Map
                                        </button>
                                    @endif
                                    <a href="tel:{{ isset($deliveryAddress->contact_person_number) ? $deliveryAddress->contact_person_number : $order->customer->phone }}"
                                        class="btn btn-outline-success w-50 ms-1 px-1">
                                        <i class="fas fa-phone me-1"></i> Call
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="card">
                    {{-- <div class="text-center card-body d-flex justify-content-around"> --}}
                    <div class="card-header mb-3 d-flex justify-content-between">
                        <h5 class="card-title mb-0 text-primary fw-bolder">#{{ $order->id }}</h5>
                    </div>
                    <div class="card-body border-top d-flex">
                        <a class="btn py-1 px-2 bg-soft-success w-50 me-1"
                            href="{{ route('admin.order.generate-KOT', $order->id) }}" type="button"><i
                                class="fa fa-print me-2"></i>Print KOT</a>
                        <a class="btn py-1 px-2 bg-soft-warning w-50 ms-1"
                            href="{{ route('admin.order.generate-invoice', $order->id) }}" type="button"><i
                                class="fa fa-print me-2"></i>Print Bill</a>
                    </div>
                </div>
                {{-- Refund Management Card --}}
                
                @if (in_array($order->order_status, ['delivered', 'canceled']) && $order->order_amount > 0)
                    <div class="card">
                        <div class="card-header mb-3">
                            <h5 class="card-title mb-0 text-primary fw-bolder">{{ __('Refund Management') }}</h5>
                        </div>
                        <div class="card-body border-top">
                            <?php
                                $hasRefunds = $order->refunds()->exists();
                                $pendingRefunds = $order->refunds()->where('refund_status', 'pending')->count();
                                $totalRefunded = $order->refunds()->where('refund_status', 'processed')->sum('refund_amount');
                            ?>
                            <div class="row mb-3">
                                <div class="col-12">
                                    <small class="text-muted">Order Amount: <strong>{{ \App\CentralLogics\Helpers::currency_symbol() }}{{ $order->order_amount }}</strong></small><br>
                                    @if($totalRefunded > 0)
                                        <small class="text-muted">Refunded: <strong class="text-success">{{ \App\CentralLogics\Helpers::currency_symbol() }}{{ $totalRefunded }}</strong></small><br>
                                    @endif
                                    @if($pendingRefunds > 0)
                                        <small class="text-warning"><i class="fas fa-clock"></i> {{ $pendingRefunds }} pending refund(s)</small>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="d-flex gap-2">
                                @if(!$hasRefunds)
                                    <button type="button" class="btn btn-outline-danger btn-sm w-50" 
                                            data-bs-toggle="modal" data-bs-target="#createRefundModal">
                                        <i class="fas fa-undo me-1"></i>Create Refund
                                    </button>
                                @endif
                                
                                @if($hasRefunds)
                                    <a href="{{ route('admin.refund.show', $order->refunds()->first()->id ?? 0) }}" 
                                        class="btn btn-outline-info btn-sm w-50">
                                        <i class="fas fa-eye me-1"></i>View Refunds
                                    </a>
                                @endif
                                
                                @if($pendingRefunds > 0)
                                    <a href="{{ route('admin.refund.index', ['status' => 'pending', 'order' => $order->id]) }}" 
                                        class="btn btn-warning btn-sm w-50">
                                        <i class="fas fa-clock me-1"></i>Process Pending
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
                {{-- <div class="col-lg-6"> --}}
                <div class="card">
                    <div class="card-header d-flex justify-content-between border-bottom pb-3 bg-soft-primary">
                        <div class="header-title">
                            <h4 class="card-title text-primary fw-bolder">Timeline</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="iq-timeline0 m-0 d-flex align-items-center justify-content-between position-relative">

                            <ul class="list-inline p-0 m-0">
                                @if ($order->pending != null)
                                    <li>
                                        <div class="timeline-dots timeline-dot1 border-primary text-primary"></div>
                                        <h6 class="float-left mb-1">
                                            {{ \Carbon\Carbon::parse($order->pending)->format('h:i A') }}</h6>
                                        <div class="d-inline-block w-100">
                                            <p>{{ __('Order Placed') }}</p>
                                        </div>
                                    </li>
                                @endif
                                @if ($order->confirmed != null)
                                    <li>
                                        <div class="timeline-dots timeline-dot1 border-primary text-primary"></div>
                                        <h6 class="float-left mb-1">
                                            {{ \Carbon\Carbon::parse($order->confirmed)->format('h:i A') }}</h6>
                                        <div class="d-inline-block w-100">
                                            <p>{{ __('Confirmed') }}</p>
                                        </div>
                                    </li>
                                @endif
                                @if ($order->processing != null)
                                    <li>
                                        <div class="timeline-dots timeline-dot1 border-primary text-primary"></div>
                                        <h6 class="float-left mb-1">
                                            {{ \Carbon\Carbon::parse($order->processing)->format('h:i A') }}</h6>
                                        <div class="d-inline-block w-100">
                                            <p>{{ __('Processing') }}</p>
                                        </div>
                                    </li>
                                @endif
                                @if ($order->dm_at_restaurant != null)
                                    <li>
                                        <div class="timeline-dots timeline-dot1 border-primary text-primary"></div>
                                        <h6 class="float-left mb-1">
                                            {{ \Carbon\Carbon::parse($order->dm_at_restaurant)->format('h:i A') }}</h6>
                                        <div class="d-inline-block w-100">
                                            <p>{{ __('Delivery Boy at restaurant') }}</p>
                                        </div>
                                    </li>
                                @endif
                                @if ($order->handover != null)
                                    <li>
                                        <div class="timeline-dots timeline-dot1 border-primary text-primary"></div>
                                        <h6 class="float-left mb-1">
                                            {{ \Carbon\Carbon::parse($order->handover)->format('h:i A') }}</h6>
                                        <div class="d-inline-block w-100">
                                            <p>{{ __('Handovered') }}</p>
                                        </div>
                                    </li>
                                @endif
                                @if($order->picked_up != null)
                                    <li>
                                        <div class="timeline-dots timeline-dot1 border-primary text-primary"></div>
                                        <h6 class="float-left mb-1">
                                            {{ \Carbon\Carbon::parse($order->picked_up)->format('h:i A') }}</h6>
                                        <div class="d-inline-block w-100">
                                            <p>{{ __('Picked up by deliveryman') }}</p>
                                        </div>
                                    </li>
                                @endif
                                @if ($order->order_on_way != null)
                                    <li>
                                        <div class="timeline-dots timeline-dot1 border-primary text-primary"></div>
                                        <h6 class="float-left mb-1">
                                            {{ \Carbon\Carbon::parse($order->order_on_way)->format('h:i A') }}</h6>
                                        <div class="d-inline-block w-100">
                                            <p>{{ __('Order on the Way to costumer') }}</p>
                                        </div>
                                    </li>
                                @endif
                                @if ($order->arrived_at_door != null)
                                    <li>
                                        <div class="timeline-dots timeline-dot1 border-primary text-primary"></div>
                                        <h6 class="float-left mb-1">
                                            {{ \Carbon\Carbon::parse($order->arrived_at_door)->format('h:i A') }}</h6>
                                        <div class="d-inline-block w-100">
                                            <p>{{ __('Arrived at costumer doors') }}</p>
                                        </div>
                                    </li>
                                @endif
                                @if ($order->delivered != null)
                                    <li>
                                        <div class="timeline-dots timeline-dot1 border-primary text-primary"></div>
                                        <h6 class="float-left mb-1">
                                            {{ \Carbon\Carbon::parse($order->delivered)->format('h:i A') }}</h6>
                                        <div class="d-inline-block w-100">
                                            <p>{{ __('Delivered') }}</p>
                                        </div>
                                    </li>
                                @endif
                                @if ($order->canceled != null)
                                    <li>
                                        <div class="timeline-dots timeline-dot1 border-primary text-primary"></div>
                                        <h6 class="float-left mb-1">
                                            {{ \Carbon\Carbon::parse($order->canceled)->format('h:i A') }}</h6>
                                        <div class="d-inline-block w-100">
                                            <p>{{ __('Canceled') }}</p>
                                            @if(!empty($order['cancellation_reason']))
                                                <small class="text-muted d-block">Reason: {{ $order['cancellation_reason'] }}</small>
                                            @endif
                                        </div>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- </div> --}}
    {{-- </div> --}}
    {{-- </div> --}}
    {{-- </div> --}}

    {{-- dm location --}}
    @if ($order->delivery_man && $order->created_at->isToday())
        <?php
        $dmData = new \App\Services\JsonDataService($order->delivery_man->id);
        $dmData = $dmData->readData();
        $dmLoation = $dmData?->last_location;
        ?>
        <div class="offcanvas offcanvas-bottom fullscreen " tabindex="-1" id="dmLocation"
            aria-labelledby="offcanvasBottomLabel">
            <i class="position-absolute top-0 end-0 text-primary me-2 mt-2 fa fa-close fa-2x" style="z-index: 1;"
                data-bs-dismiss="offcanvas"></i>
            <div class="offcanvas-body m-1 p-1 ">
                @if (isset($dmLoation['lat']) && isset($dmLoation['lng']))
                    <div class="mapouter m-0 p-0">
                        <iframe
                            src="https://www.google.com/maps?q={{ $dmLoation['lat'] }},{{ $dmLoation['lng'] }}&output=embed"
                            style="border:0;width:100%;height:100%;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                @endif
            </div>
        </div>
    @endif
    {{-- customer locaton --}}
    @if ($order->customer)
        <div class="offcanvas offcanvas-bottom fullscreen " tabindex="-1" id="customerLocation"
            aria-labelledby="offcanvasBottomLabel">
            <i class="position-absolute top-0 end-0 text-primary me-2 mt-2 fa fa-close fa-2x" style="z-index: 1;"
                data-bs-dismiss="offcanvas"></i>
            <div class="offcanvas-body m-1 p-1 ">
                <div class="mapouter m-0 p-0">
                    <iframe
                        src="https://www.google.com/maps?q={{ $deliveryAddress->position->lat }},{{ $deliveryAddress->position->lon }}&output=embed"
                        style="border:0;width:100%;height:100%;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>
    @endif

    {{--  manual assign dm --}}
    @if ($delivery_man != null)
        <div class="offcanvas offcanvas-bottom fullscreen " tabindex="-1" id="dmManualSelect"
            aria-labelledby="offcanvasBottomLabel">
            <i class="position-absolute top-0 end-0 text-primary me-2 mt-2 fa fa-close fa-2x" style="z-index: 1;"
                data-bs-dismiss="offcanvas"></i>
            <div class="offcanvas-body m-1 p-1 ">
                <div class="mapouter m-0 p-0">
                    <div class="row">
                        <div class="col-md-5 my-2">
                            <ul class="list-group overflow-auto max-height-400">
                                @foreach ($delivery_man ?? [] as $dm)
                                    <li class="list-group-item">
                                        <span class="dm_list w-100" role='button' data-id="{{ $dm['id'] }}">
                                            <img class="avatar avatar-sm w-25 avatar-circle mr-1"
                                                onerror="this.src='{{ asset('assets/images/icons/300x100/profile-deliveryman.png') }}'"
                                                src="{{ asset('delivery-man/' . $dm['image']) }}LL"
                                                alt="{{ $dm['name'] }}">
                                            {{ $dm['name'] }}
                                        </span>

                                        <a class="btn btn-primary  btn-xs float-right"
                                            onclick="addDeliveryMan({{ $dm['id'] }})">{{ $order->delivery_man_id != null && $order->delivery_man_id == $dm['id'] ? __('Assigned') : __('messages.assign') }}</a>
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


    <!-- Create Refund Modal -->
    <div class="modal fade" id="createRefundModal" tabindex="-1" aria-labelledby="createRefundModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createRefundModalLabel">Create Refund for Order #{{ $order->id }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="createRefundForm">
                    @csrf
                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                    
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="refund_type" class="form-label">Refund Type <span class="text-danger">*</span></label>
                            <select class="form-control" id="refund_type" name="refund_type" required>
                                <option value="">Select refund type</option>
                                <option value="full">Full Refund</option>
                                <option value="partial">Partial Refund</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="refund_amount" class="form-label">Refund Amount <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">{{ \App\CentralLogics\Helpers::currency_symbol() }}</span>
                                <input type="number" class="form-control" id="refund_amount" name="refund_amount" 
                                    step="0.01" min="0.01" max="{{ $order->order_amount }}" required>
                            </div>
                            <small class="text-muted">Maximum refundable: {{ \App\CentralLogics\Helpers::currency_symbol() }}{{ $order->order_amount }}</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="restaurant_deduction_amount" class="form-label">Restaurant Deduction Amount <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">{{ \App\CentralLogics\Helpers::currency_symbol() }}</span>
                                <input type="number" class="form-control" id="restaurant_deduction_amount" name="restaurant_deduction_amount" 
                                    step="0.01" min="0" max="{{ $order->order_amount }}" required>
                            </div>
                            <small class="text-muted">Amount to be deducted from restaurant earnings</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="restaurant_deduction_reason" class="form-label">Restaurant Deduction Reason</label>
                            <textarea class="form-control" id="restaurant_deduction_reason" name="restaurant_deduction_reason" rows="2" 
                                    placeholder="Optional reason for restaurant deduction..."></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="refund_reason" class="form-label">Reason <span class="text-danger">*</span></label>
                            <select class="form-control" id="refund_reason" name="refund_reason" required>
                                <option value="">Select reason</option>
                                @foreach(\App\Models\RefundReason::active()->forUserType('admin')->get() as $reason)
                                    <option value="{{ $reason->reason }}">{{ $reason->reason }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="admin_note" class="form-label">Admin Note</label>
                            <textarea class="form-control" id="admin_note" name="admin_note" rows="3" 
                                    placeholder="Optional note about this refund..."></textarea>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Note:</strong> This refund will be automatically approved and processed since you are creating it as an admin.
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Refund</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection
@push('javascript')
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBPVVxuVkmdE1GzSgIqm_9dx64tea7Wltk&libraries=geometry,places">
    </script>
    <script type="module">
        import {
            timeStringToMinutes
        } from "{{ asset('assets/js/Helpers/helper.js') }}";

        document.querySelector('#statusChanger').addEventListener('change', (e) => {
            const element = e.target;
            const selectedOption = document.querySelector(`option[value="${element.value}"]`);
            const option = {
                verification: selectedOption.dataset.verification ? selectedOption.dataset.verification : false,
                processing: element.value,
            }
            if (selectedOption.dataset.processing) {
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
                    html: message + '<br/>' + '<label>{{ __('Enter Processing time in minutes') }}</label>',
                    inputValue: timeStringToMinutes(option.processingTime),
                    preConfirm: (processing_time) => {

                        location.href = route + '&processing_time=' + processing_time;
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                })
            } else if (option.processing == "canceled") {
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
                    html: '<br/>' + '<label>{{ __('Enter Cancle reason') }}</label>',
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
                if (deliveryman[i].lat && deliveryman[i].lng) { // Ensure lat/lng exists
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
                url: '{!! route('admin.order.dm_assign_manually') . "?order_id={$order->id}&dm_id=" !!}' + id,
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

        // Refund creation form submission
        $('#createRefundForm').on('submit', function(e) {
            e.preventDefault();
            
            let formData = new FormData(this);
            
            $.ajax({
                url: "{{ route('admin.refund.create', $order->id) }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    toastr.success(response.message || 'Refund created successfully', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                    $('#createRefundModal').modal('hide');
                    location.reload();
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON?.errors || {};
                    let message = xhr.responseJSON?.message || 'Something went wrong!';
                    
                    if (Object.keys(errors).length > 0) {
                        Object.keys(errors).forEach(function(key) {
                            toastr.error(errors[key][0], {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        });
                    } else {
                        toastr.error(message, {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }
                }
            });
        });

        // Update refund amount when type changes
        $('#refund_type').on('change', function() {
            let type = $(this).val();
            let orderAmount = {{ $order->order_amount }};
            
            if (type === 'full') {
                $('#refund_amount').val(orderAmount.toFixed(2));
                $('#restaurant_deduction_amount').val(orderAmount.toFixed(2));
            } else {
                $('#refund_amount').val('');
                $('#restaurant_deduction_amount').val('');
            }
        });

        // Validate restaurant deduction amount doesn't exceed refund amount
        $('#restaurant_deduction_amount').on('input', function() {
            let deductionAmount = parseFloat($(this).val()) || 0;
            let refundAmount = parseFloat($('#refund_amount').val()) || 0;
            
            if (deductionAmount > refundAmount) {
                $(this).addClass('is-invalid');
                if (!$(this).next('.invalid-feedback').length) {
                    $(this).after('<div class="invalid-feedback">Restaurant deduction cannot exceed refund amount</div>');
                }
            } else {
                $(this).removeClass('is-invalid');
                $(this).next('.invalid-feedback').remove();
            }
        });

        
    </script>
@endpush
