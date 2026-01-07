@extends('layouts.dashboard-main')
@php
 $customerOrderData = json_decode($order->orderCalculationStmt?->customerData);
@endphp

@push('css')

<style>
    @media print{
        body * {
            visibility: none;
        }
        .fixed-bottom {
            display: none!important;
        }
        body{
            visibility: hidden;
            /* display: none; */
            margin: 0;
            padding: 0;
        }
        #order-invoice{
            display: block !important;
            margin: 0 !important;
            padding: 0 !important;
            font-size: 12px;
            visibility: visible;
            position: absolute;
            z-index: 99999999;
            left: 0 !important;
            top: 0 !important;
        }
        .non-printable{
            display: none;
        }
        @page {
            size:  80mm auto !important;
            margin: 0;
            margin-left: 5px;
        }
    }
</style>
@endpush

@section('content')

<div class="container position-relative">
    <div class="row d-flex justify-content-center">
        <div class="col-md-12 pt-3">
            <div class="shadow-sm rounded bg-white mb-3 overflow-hidden">
                <div class="d-flex justify-content-between bg-light border-bottom d-print-none">
                    <h6 class="p-3 m-0 ">Order Invoice</h6>
                    <div class="d-flex align-self-center px-3">
                        <button id="downloadBtn" class="btn btn-sm btn-primary non-printable me-2">Download PNG <i class="feather-download"></i></button>
                        <button type="button" class="btn btn-sm btn-primary non-printable me-2"
                        onclick="javascript:window.print();">
                            <i class="feather-printer"></i>
                        </button>
                        <a onclick="window.history.back()"
                            class="btn btn-sm btn-danger non-printable">Back</a>
                    </div>
                </div>
                <div class="bg-white p-3 clearfix border-bottom" id="order-invoice">
                    <div class="d-flex justify-content-between">
                        <p class="mb-0"><b>Date :</b>
                            <span class="font-light"> {{App\CentralLogics\Helpers::format_date($order->created_at)}} </span>
                        </p>
                        <p class="mb-0">
                            <b>Time :</b>
                            <span class="font-light">
                                {{ \Carbon\Carbon::parse($order->created_at)->format('h:i A') }}

                            </span>
                        </p>
                    </div>
                    <div class="pt-3 text-center mb-2">
                        <img src="img/qr-code.png" class="initial-38-2" alt="" style="height:100px;">
                    </div>
                    @php($deliveryAddress = json_decode($order->delivery_address))
                    @if($order->customer)
                    <h5 class="mb-1 fw-bolder text-center">{{Str::upper($order->restaurant->name)}}</h5>
                    <p class="mb-1 text-center"><span class="text-primary me-1">
                        @php($restaurantAddress =  json_decode($order->restaurant->address))
                        <i class="feather-map-pin"></i></span>
                        {{Str::ucfirst($restaurantAddress->street) }} {{Str::ucfirst($restaurantAddress->city) }} - {{Str::ucfirst($restaurantAddress->pincode) }}
                    </p>
                    <p class="mb-1 text-center"><span class="text-primary me-1"><i class="feather-phone"></i></span>
                        {{$order->restaurant->vendor->phone }}
                    </p>
                    <hr style="border-top: dashed;">
                    <p class="mb-0"><b>Order ID :</b>
                        <span class="font-light"> <a href="{{route('admin.order.details', $order->id)}}">#{{$order->id}}</a> </span>
                    </p>
                    <div>
                        <b>Customer Name :</b>
                        <span class="font-light">
                        @if (isset($deliveryAddress->contact_person_name))
                            {{ $deliveryAddress->contact_person_name }}
                        @elseif ($order->lovedOne)
                            {{ $order->lovedOne->name }} ❤️
                        @else
                            {{ Str::ucfirst($order->customer->f_name).' '.Str::ucfirst($order->customer->l_name) }}
                        @endif
                        </span>
                    </div>
                    <div>
                        <b>Phone :</b>
                        <span class="font-light">
                        @if (isset($deliveryAddress->contact_person_number))
                            {{ $deliveryAddress->contact_person_number }}
                        @elseif ($order->lovedOne)
                            {{ $order->lovedOne->phone }}
                        @else
                            {{ $order->customer->phone }}
                        @endif
                        </span>
                    </div>
                    <div>
                        <b>Address :</b>
                        <span class="font-light">
                        {{ isset($deliveryAddress->stringAddress) ? $deliveryAddress->stringAddress : '' }}
                        </span>
                    </div>
                    @endif
                    <hr style="border-top: dashed;">
                    <div class="w-100">

                        <table class="table table-responsive table-bordered mt-1 mb-1">
                            <thead>
                                <tr>
                                    <th class="initial-38-7">Sl No.</th>
                                    <th class="initial-38-7">Description</th>
                                    <th class="initial-38-6">Qty</th>
                                    <th class="initial-38-7">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($customerOrderData?->foodItemList != null)
                                @foreach ($customerOrderData->foodItemList as $key => $listItem)
                                <tr>
                                    <td class="">
                                        {{$key + 1}}
                                    </td>
                                    <td class="text-break">
                                        <div class="font-size-sm text-body">
                                            <span class="font-weight-bold">{{Str::ucfirst($listItem->foodName)}}</span>
                                        </div>
                                    </td>
                                    <td class="">
                                        {{$listItem->quantity}}
                                    </td>
                                    <td class="w-28p">
                                        {{App\CentralLogics\Helpers::format_currency($listItem->foodPrice)}}
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <hr style="border-top: dashed;">

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

                        <p class="mb-1 text-success">Discount <span class="float-end text-success">{{Helpers::format_currency($customerOrderData?->sumOfDiscount)}}</span>
                        </p>
                        <p class="mb-1 text-success">Coupon Discount <span class="float-end text-success">{{Helpers::format_currency($customerOrderData?->couponDiscountAmount)}}</span>
                        </p>
                        <div class="mb-1">Platform Fee
                        <button type="button" class="btn text-info ms-1 p-0" data-bs-toggle="tooltip"
                            data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                            data-bs-title="Platform Fee Explanation"><i class="feather-info"></i></button>
                            <div class="float-end">
                                <span class="text-danger "><strike class="me-2">{{Helpers::format_currency($customerOrderData?->zone->platform_charge)}}</strike></span>
                                <span class="text-dark ">{{Helpers::format_currency($customerOrderData?->platformCharge)}}</span>
                            </div>
                        </div>
                        <p class="mb-1">Packing Charge <span class="text-info ms-1"></span><span
                            class="float-end text-dark">{{Helpers::format_currency($customerOrderData?->sumofPackingCharge)}}</span>
                        </p>
                        <p class="mb-1">Delivery Charge<button type="button" class="btn text-info ms-1 p-0"
                            data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                            data-bs-title="Delivery Charge Explanation"><i class="feather-info"></i></button><span
                            class="float-end text-dark">
                            @if($customerOrderData?->freeDelivery > 0)
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
                    <hr>
                    <h2 class="text-center fw-bolder">* THANK YOU **</h2>
                    <hr style="border-top: dashed;">
                    <p class="text-center">© 2024 FoodYari. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('javascript')
{{-- <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"
integrity="sha512-BNaRQnYJYiPSqHHDb58B0yaPfCu+Wgds8Gp/gU33kqBtgNS4tSPHuGibyoeqMV/TJlSKda6FXzoEyYGjTe+vXA=="
crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    function printDiv(divName) {

        if ($('html').attr('dir') === 'rtl') {
            $('html').attr('dir', 'ltr')
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            $('.initial-38-1').attr('dir', 'rtl')
            window.print();
            document.body.innerHTML = originalContents;
            $('html').attr('dir', 'rtl')
            location.reload();
        } else {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
        }

    }

    document.getElementById('downloadBtn').onclick = function() {
    html2canvas(document.getElementById('order-invoice')).then(function(canvas) {
        var link = document.createElement('a');
        link.download = '{{$order->id.__('messages.Invoice')}}';
        link.href = canvas.toDataURL('image/png');
        link.click();
    });
    };
</script>
@endpush
