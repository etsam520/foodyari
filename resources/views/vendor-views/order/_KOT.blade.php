@extends('vendor-views.layouts.dashboard-main')
<?php
$invoiceItemsList = [];
foreach ($order->details as $orderItem){
    $itemDetails = json_decode($orderItem->food_details);
    if($itemDetails->isCustomize != 1){
        $invoiceItemsList[] =[
            'name' => $itemDetails->name,
            'quantity' => $orderItem->quantity,
            'price' => $orderItem->quantity * $orderItem->price ,
        ];
    }else{
        foreach(json_decode($orderItem->variation) as $variation){
            // $variation->option;
            foreach ($variation->values as $value){
                $invoiceItemsList[] = [
                'name' =>$itemDetails->name." ($value->label)",
                'quantity' =>$value->qty,
                'price' => $value->price * $value->qty ,
            ];
            }
        }
    }

    foreach (json_decode($orderItem->add_ons) as $addon){
        $invoiceItemsList[] = [
            'name' =>$addon->name,
            'quantity' => $addon->qty,
            'price' => $addon->price * $addon->qty ,
        ];
    }
}
?>

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
                    <h6 class="p-3 m-0 ">Order KOT</h6>
                    <div class="d-flex align-self-center px-3">
                        <button type="button" class="btn btn-primary non-printable me-2"
                        onclick="javascript:window.print();">
                            <i class="feather-printer"></i>
                        </button>
                        <a onclick="window.history.back()"
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
                    @php($deliveryAddress = json_decode($order->delivery_address))
                    @if($order->customer)
                    <h5 class="mb-1 fw-bolder text-center">{{Str::upper($order->restaurant->name)}}</h5>
                    <p class="mb-1 text-center"><span class="text-primary me-1">
                        @php($restaurantAddress =  json_decode($order->restaurant->address))
                        <i class="feather-map-pin"></i></span>
                        {{Str::ucfirst($restaurantAddress->street) }} {{Str::ucfirst($restaurantAddress->city) }} - {{Str::ucfirst($restaurantAddress->pincode) }}
                    </p>
                    <p class="mb-1 text-center"><span class="text-primary me-1"><i class="feather-phone"></i></span>+91
                        {{$order->restaurant->vendor->phone }}
                    </p>
                    <hr style="border-top: dashed;">
                    <p class="mb-0"><b>Order ID :</b>
                        <span class="font-light"> #{{$order->id}} </span>
                    </p>
                    {{-- <div>
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
                    </div> --}}
                    {{-- <div>
                        <b>Address :</b>
                        <span class="font-light">
                        {{ isset($deliveryAddress->stringAddress) ? $deliveryAddress->stringAddress : '' }}
                        </span>
                    </div> --}}
                    @endif
                    <hr style="border-top: dashed;">
                    <div class="w-100">

                        <table class="table table-responsive table-bordered mt-1 mb-1">
                            <thead>
                                <tr>
                                    <th class="initial-38-7">Sl No.</th>
                                    <th class="initial-38-7">Description</th>
                                    <th class="initial-38-6">Qty</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoiceItemsList as $key => $listItem)
                                <tr>
                                    <td class="">
                                        {{$key+1}}
                                    </td>
                                    <td class="text-break">
                                        <div class="font-size-sm text-body">
                                            <span class="font-weight-bold">{{Str::ucfirst($listItem['name'])}}</span>
                                        </div>
                                    </td>
                                    <td class="">
                                        {{$listItem['quantity']}}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <hr style="border-top: dashed;">


                    <hr style="border-top: dashed;">
                    <div class="text-center">
                        <img src="img/qr-code.png" class="initial-38-2" alt="" style="height:100px;">
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
</script>
@endpush
