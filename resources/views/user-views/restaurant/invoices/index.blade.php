@extends('user-views.restaurant.layouts.main')
@php
$deliveryAddress = json_decode($order->delivery_address);
$customerOrderData = json_decode($order->orderCalculationStmt?->customerData);


@endphp
@push('css')
<style>

.watermark {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) rotate(-20deg);
    width: 280px;
    height: 280px;
    border: 6px solid #e74c3c;
    border-radius: 50%;
    text-align: center;
    line-height: 280px;
    font-size: 32px;
    font-weight: 900;
    color: #e74c3c;
    background: rgba(255, 255, 255, 0.3);
    opacity: 0.4;
    z-index: 50;
    text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.2);
    font-family: 'Arial Black', sans-serif;
    pointer-events: none;
    box-shadow: 0 0 20px rgba(231, 76, 60, 0.5);
}
    @media print{
        body * {
            visibility: none;
        }
        .fixed-bottom {
            display: none!important;
        }
        #order-invoice{
            font-size: 12px;
            visibility: visible;
            position: absolute;
            left: 0;
            top: 0;
        }
        @page {
            size:  80mm auto;
            margin: 0;
        }
    }


</style>
@endpush

@section('containt')

<div class="container position-relative">

    <div class="row d-flex justify-content-center">
        <div class="col-md-5 pt-3">
            <div class="shadow-sm rounded bg-white mb-3 overflow-hidden">
                <div class="d-flex justify-content-between bg-light border-bottom d-print-none">
                    <h6 class="p-3 m-0 ">Order Invoice</h6>
                    <div class="d-flex align-self-center px-3">
                        <button id="downloadBtn" class="btn btn-sm btn-primary non-printable me-2"><i class="feather-download"></i></button>
                        <button type="button" class="btn btn-primary non-printable me-2"
                        onclick="javascript:window.print();">
                            <i class="feather-printer"></i>
                        </button>
                        <a  onclick="window.history.back()"
                            class="btn btn-danger non-printable">Back</a>
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
                    <h5 class="mb-1 fw-bolder text-center">{{Str::upper($order->restaurant->name)}}</h5>
                    <p class="mb-1 text-center"><span class="text-primary me-1">
                        @php($address = json_decode($order->restaurant->address))
                        <i class="feather-map-pin"></i></span>{{Str::ucfirst($address->street)}} {{Str::ucfirst($address->city)}}, {{Str::ucfirst($address->pincode)}}</p>
                    @if($order->restaurant->phone != null)
                    <p class="mb-1 text-center"><span class="text-primary me-1"><i class="feather-phone"></i></span>+91
                        {{$order->restaurant->phone}}</p>
                    @endif
                    <hr style="border-top: dashed;">
                    <p class="mb-0"><b>Order ID :</b>
                        <span class="font-light"> #{{$order->id}} </span>
                    </p>
                    <div>
                        <b>Customer Name :</b>
                        <span class="font-light">
                            {{ isset($deliveryAddress->contact_person_name)? $deliveryAddress->contact_person_name : Str::ucfirst($order->customer->f_name).' '.Str::ucfirst($order->customer->l_name) }}
                        </span>
                    </div>
                    <div>
                        <b>Phone :</b>
                        <span class="font-light">
                            {{ isset($deliveryAddress->contact_person_number) ?  $deliveryAddress->contact_person_number: $order->customer->phone }}
                        </span>
                    </div>
                    <div>
                        <b>Address :</b>
                        <span class="font-light">
                            {{ isset($deliveryAddress->stringAddress) ? $deliveryAddress->stringAddress : '' }}
                        </span>
                    </div>
                    <hr style="border-top: dashed;">

                    <table class="table table-bordered mt-1 mb-1">
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
                                    {{$key+1}}
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
                                    {{Helpers::format_currency($listItem->foodPrice)}}
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>

                    <hr style="border-top: dashed;">
                    <p class="mb-1">Sub Total <span class="float-end">{{Helpers::format_currency($customerOrderData?->sumOfFoodPriceBeforDiscount)}}</span></p>
                    @if($customerOrderData?->sumOfDiscount > 0)
                    <p class="mb-1">Discount <span class="float-end">{{Helpers::format_currency($customerOrderData?->sumOfDiscount)}}</span></p>
                    @endif

                    @if($customerOrderData?->couponDiscountAmount > 0)
                    <p class="mb-1">Coupon Discount <span class="float-end">{{Helpers::format_currency($customerOrderData?->couponDiscountAmount)}}</span></p>
                    @endif
                    @if ($customerOrderData?->platformCharge > 0)
                    <p class="mb-1">Platform Fee <span class="text-info ms-1"><span
                        class="float-end text-dark">{{Helpers::format_currency($customerOrderData?->platformCharge)}}</span></p>
                    @endif
                    @if ($customerOrderData?->sumofPackingCharge > 0)
                    <p class="mb-1">Packing Charge <span class="float-end">{{Helpers::format_currency($customerOrderData?->sumofPackingCharge)}}</span></p>
                    @endif

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
                    <h6 class="mb-0">Gross Total <span class="float-end">{{Helpers::format_currency($customerOrderData?->grossTotal)}}</span></h6>
                    <p class="mb-1">GST {{$customerOrderData?->gstPercent}}%<span class="float-end">{{Helpers::format_currency($customerOrderData?->gstAmount)}}</span></p>
                    @if ($customerOrderData?->dm_tips > 0)
                    <p class="mb-1">Deliveryman Tip <span class="float-end">{{Helpers::format_currency($customerOrderData?->dm_tips)}}</span></p>
                    @endif
                    <hr>
                    <h4 class="mb-0"> Total <span class="float-end">{{ Helpers::format_currency(ceil($customerOrderData?->billingTotal))}}</span></h4>

                    <hr>
                    <div>
                        <b>Paid By :</b>
                        <span class="font-light">
                            {{Str::ucfirst($order->payment_method)}} <span class="badge bg-success">{{$order->payment_status}}</span>
                        </span>
                    </div>
                    <hr style="border-top: dashed;">
                    <div class="text-center">
                        <img src="img/qr-code.png" class="initial-38-2" alt="" style="height:100px;">
                    </div>
                    <hr>
                    <h2 class="text-center fw-bolder">* THANK YOU **</h2>
                    <hr style="border-top: dashed;">
                    <p class="text-center">© 2024 FoodYari. All rights reserved.</p>

                </div>
                @if($order->order_status == 'canceled')
                <div class="watermark">CANCELLED</div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection
@push('javascript')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
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
