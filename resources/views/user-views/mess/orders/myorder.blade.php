@extends('user-views.layouts.main')

@section('content')
    
<div class="osahan-home-page">
    <!-- Moblile header -->  
    @include('user-views.layouts.m-header')      
    <!-- Moblile header end -->  
    <div class="main ">
        <div class="container position-relative">
            @include('user-views.layouts.slider')  
        </div>
      
        <div class="container  position-relative" >
            <div class="row mt-3">
                <div class="col-md-8">
                    <!-- body -->
                    <section class="bg-white osahan-main-body rounded shadow-sm overflow-hidden">
                        <div class="container p-0">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="osahan-status">
                                        <!-- status complete -->
                                        <div class="p-3 status-order bg-white border-bottom d-flex align-items-center">
                                            <p class="m-0">
                                                <i class="feather-calendar text-primary"></i> 
                                                {{App\CentralLogics\Helpers::format_date($order->updated_at).' - '.App\CentralLogics\Helpers::format_time($order->updated_at->toTimeString())}}
                                            </p>
                                            <a href="{{route('user.mess.invoice')."?order_id=$order->id"}}" class="feather-printer text-primary ms-2 text-decoration-none "> Invoice</a>
                                            <a href="review.html" class="text-primary ms-auto text-decoration-none">Review</a>
                                        </div>
                                        <div class="p-3 border-bottom">
                                            <h6 class="fw-bold">Order Status</h6>
                                            <div class="tracking-wrap">
                                                @if($order->status == 'pending')
                                                <div class="my-1 step active">
                                                    <span class="icon text-warning"><i class="feather-alert-circle"></i></span>
                                                    <span class="text small">Pending</span>
                                                </div>
                                                @elseif ($order->status == 'confirmed')
                                                <!-- step.// -->
                                                <div class="my-1 step active">
                                                    <span class="icon text-success"><i class="feather-check"></i></span>
                                                    <span class="text small">Confirmed</span>
                                                </div>
                                                <!-- step.// -->
                                                @elseif($order->status == 'canceled')
                                                <div class="my-1 step">
                                                    <span class="icon text-danger"><i class="feather-alert-triangle"></i></span>
                                                    <span class="text small">Reject/Cancelled</span>
                                                </div>
                                                @endif
                                               
                                            </div>
                                        </div>
                                        <div class="p-3 border-bottom bg-white">
                                            {{-- @dd() --}}
                                            @foreach ($order->orderItems as  $item) 
                                           
                                            <div class="d-flex gap-2 p-3 border-bottom gold-members">
                                                <img alt="#" src="{{$item->package->type == 'veg'? asset('assets/user/img/veg.png') : asset('assets/user/img/non-veg.png')}}" class="img-fluid package-img">
                                                <div class="w-100">
                                                    <div class="d-flex gap-2 mb-2">
                                                        <a href="javascript:void(0)">
                                                            <h6 class="mb-1">{{Str::upper($item->package->title)}}</h6>
                                                        </a> 
                                                    </div> 
                                                    <div class="border-top pt-2">
                                                        @php ( $diets = json_decode($item->package->diets))
                                                        {{-- @dd($diets) --}}
                                                        <div class="row">
                                                            <div class="col-lg-4">
                                                                <p class="text-fw-bold mb-0">No. of Normal Diet - 
                                                                    <span class="text-muted mb-0">{{(int)$diets->breakfast + (int) $diets->lunch + (int) $diets->dinner}} </span>
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
                                            @endforeach
                                        </div>
                                        <!-- Destination -->
                                        <div class="p-3 border-bottom bg-white">
                                            <h6 class="fw-bold">Destination</h6>
                                            <p class="m-0 small">554 West 142nd Street, New York, NY 10031</p>
                                        </div>
                                        <!-- Destination -->
                                        <div class="p-3 bg-white">
                                            <div class="d-flex align-items-center mb-2">
                                                <h6 class="fw-bold mb-1">Total Cost</h6>
                                                <h6 class="fw-bold ms-auto mb-1"> {{App\CentralLogics\Helpers::format_currency($order->total)}}</h6>
                                            </div>
                                            <p class="m-0 small text-muted">You can check your order detail here,<br>Thank you for order.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
                <div class="col-md-4">
                    <!-- body -->
                    @php($payment =  $order->paymentDetail->first())
                    {{-- @dd($payment) --}}
                    <section class="bg-white osahan-main-body rounded shadow-sm overflow-hidden">
                        <div class="container p-0">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="osahan-status">
                                        <!-- status complete -->
                                        <div class="p-3 status-order bg-white border-bottom pb-4 ">
                                            <a href="review.html" class="text-primary float-end ms-auto text-decoration-none">Invoice</a>
                                        </div>
                                        <div class="p-3 border-bottom">
                                            <h6 class="fw-bold">Payment Details &nbsp; 
                                                @if($payment->status == 'paid')
                                                <span class="badge bg-success">Paid</span>
                                                @elseif ($payment->status == 'unpaid')
                                                <span class="badge bg-primary">Unpaid</span>
                                                @endif
                                            </h6>
                                            <div class="bg-white p-3 clearfix border-bottom">
                                                <p class="mb-1">Subtatol Total <span class="float-end text-dark"></span></p>
                                                <p class="mb-1">Mess Charges <span class="float-end text-dark">
                                                    {{App\CentralLogics\Helpers::format_currency($payment->subtotal )}}</span>
                                                </p>
                                                <p class="mb-1">Delivery Fee<span class="text-info ms-1"><i class="feather-info"></i></span>
                                                    <span class="float-end text-dark">{{App\CentralLogics\Helpers::format_currency($payment->delivery_charge)}}</span>
                                                </p>
                                                <p class="mb-1">Custome Discount<span class="text-info ms-1"><i class="feather-info"></i></span>
                                                    <span class="float-end text-dark">{{App\CentralLogics\Helpers::format_currency($payment->custom_discount)}}</span>
                                                </p>
                                                <p class="mb-1 text-success">Coupon Discount<span class="float-end text-success">
                                                    {{App\CentralLogics\Helpers::format_currency($payment->coupon_discount )}}</span>
                                                </p>
                                                <hr>
                                                <h6 class="fw-bold mb-0">Total <span class="float-end">
                                                    {{App\CentralLogics\Helpers::format_currency($payment->total)}}</span>
                                                </h6>
                                                <hr>
                                                <h6 class="fw-bold mb-0">Payment Method <small class="float-end badge bg-primary">{{Str::upper($payment->method)}}</small></h6>
                                            </div>
                                          
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
    
                
            </div>
        </div>  
    </div>
</div>
@endsection

