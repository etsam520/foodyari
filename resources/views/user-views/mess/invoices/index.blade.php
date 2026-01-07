@extends('user-views.layouts.main')
@php
      $payment = $order->paymentDetail->first();
        $total = (int) $payment->subtotal - (int) $payment->coupon_discount- (int) $payment->custom_discount + (int) $payment->tax  + (int) $payment->delivery_charge
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

@section('content')
    
<div class="container position-relative">
    <div class="row d-flex justify-content-center">
        <div class="col-md-5 pt-3">
            <div class="shadow-sm rounded bg-white mb-3 overflow-hidden">
                <div class="d-flex justify-content-between bg-light border-bottom d-print-none">
                    <h6 class="p-3 m-0 ">Order Invoice</h6>
                    <div class="d-flex align-self-center px-3">
                        <button type="button" class="btn btn-primary non-printable me-2"
                        onclick="javascript:window.print();">
                            <i class="feather-printer"></i>
                        </button>
                        <a href="https://admin.foodyari.com/admin/order/list/all"
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
                    <h5 class="mb-1 fw-bolder text-center">{{Str::upper($order->mess->name)}}</h5>
                    <p class="mb-1 text-center"><span class="text-primary me-1">
                        @php($address = json_decode($order->mess->address))
                        <i
                                class="feather-map-pin"></i></span>{{Str::ucfirst($address->street)}} {{Str::ucfirst($address->city)}}, {{Str::ucfirst($address->pincode)}}</p>
                    <p class="mb-1 text-center"><span class="text-primary me-1"><i class="feather-phone"></i></span>+91
                        {{$order->mess->phone??null}}</p>
                    <hr style="border-top: dashed;">
                    <p class="mb-0"><b>Order ID :</b>
                        <span class="font-light"> #{{$order->id}} </span>
                    </p>
                    <div>
                        <b>Customer Name :</b>
                        <span class="font-light">
                           {{Str::ucfirst($order->customer->f_name)}} {{Str::ucfirst($order->customer->l_name)}}
                        </span>
                    </div>
                    <div>
                        <b>Phone :</b>
                        <span class="font-light">
                            +91{{$order->customer->phone}}
                        </span>
                    </div>
                    <div>
                        <b>Address :</b>
                        @php($c_address = json_decode($order->customer->address))

                        <span class="font-light">
                            {{Str::ucfirst($c_address->street)}} {{Str::ucfirst($c_address->city)}}, {{Str::ucfirst($c_address->pincode)}}
                        </span>
                    </div>
                    <hr style="border-top: dashed;">
                    <table class="table table-bordered mt-1 mb-1">
                        <thead>
                            <tr>
                                <th class="initial-38-7">Sl No.</th>
                                <th class="initial-38-7">Description</th>
                                <th class="initial-38-6">Qty</th>
                                <th class="initial-38-7">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            @foreach ($order->orderItems as $item )
                            {{-- @dd($item) --}}
                                
                            <tr>
                                <td class="">
                                    {{$loop->index + 1}}
                                </td>
                                <td class="text-break">
                                   {{Str::ucfirst($item->package->title)}} <br>

                                    <div class="font-size-sm text-body">
                                       
                                        <span class="font-weight-bold">{{Str::ucfirst($item->package->details)}} </span>
                                    </div>
                                </td>
                                <td class="">
                                    {{$item->quantity}}
                                </td>
                                <td class="w-28p">
                                   {{ App\CentralLogics\Helpers::format_currency($item->price)}}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{-- <hr style="border-top: dashed;">
                    <p class="mb-1">Items Price <span class="float-end">{{ App\CentralLogics\Helpers::format_currency($item->price)}}</span></p>
                    <p class="mb-1">Addon Cost <span class="float-end">{{ App\CentralLogics\Helpers::format_currency($item->price)}}</span></p>
                    <hr> --}}
        
                    <p class="mb-1">Sub Total <span class="float-end">{{ App\CentralLogics\Helpers::format_currency($payment->subtotal)}}</span></p>
                    <p class="mb-1">Discount <span class="float-end">{{ App\CentralLogics\Helpers::format_currency($payment->custom_discount)}}</span></p>
                    <p class="mb-1">Coupon Discount <span class="float-end">{{ App\CentralLogics\Helpers::format_currency($payment->coupon_discount)}}</span></p>
                    <p class="mb-1">GST <span class="float-end">{{ App\CentralLogics\Helpers::format_currency($payment->tax)}}</span></p>
                    <p class="mb-1">Deliveryman Tip <span class="float-end">{{ App\CentralLogics\Helpers::format_currency($payment->price)}}</span></p>
                    <p class="mb-1">Delivery Fee <span class="text-info ms-1"><span
                                class="float-end text-dark">{{ App\CentralLogics\Helpers::format_currency($payment->delivery_charge)}}</span></p>

                    <hr>
                    <h6 class="mb-0">Total <span class="float-end">{{ App\CentralLogics\Helpers::format_currency($total)}}</span></h6>
                    <hr>
                    <div>
                        <b>Paid By :</b>
                        <span class="font-light">
                            {{Str::ucfirst($payment->method)}} <span class="badge bg-success">{{$payment->status}}</span>
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
            </div>
        </div>
    </div>
</div>
@endsection
