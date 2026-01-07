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
                <div class="col-md-3 mb-3">
                    <ul class="nav nav-tabsa custom-tabsa border-0 flex-column bg-white rounded overflow-hidden shadow-sm p-2 c-t-order" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link border-0 text-dark py-3 {{$status == "Confirmed"?'active' :null}} " id="completed-tab"  href="{{route('user.mess.order-list',['status' => 'confirmed'])}}" role="tab" aria-controls="completed" aria-selected="true">
                                <i class="feather-check me-2 text-success mb-0"></i> Completed</a>
                        </li>
                        <li class="nav-item border-top" role="presentation">
                            <a class="nav-link border-0 text-dark py-3 {{$status== "Pending"?'active' :null}} " id="pending-tab"  href="{{route('user.mess.order-list',['status' => 'pending'])}}" role="tab" aria-controls="progress" aria-selected="false" tabindex="-1">
                                <i class="feather-clock me-2 text-warning mb-0"></i> On Peding</a>
                        </li>
                        <li class="nav-item border-top" role="presentation">
                            <a class="nav-link border-0 text-dark py-3{{$status == "Canceled"?'active' :null}} " id="canceled-tab"  href="{{route('user.mess.order-list',['status' => 'canceled'])}}" role="tab" aria-controls="canceled" aria-selected="false" tabindex="-1">
                                <i class="feather-x-circle me-2 text-danger mb-0"></i> Canceled</a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-9">
                @foreach ($orders as $order)
                @if ($order->orderItems->count() > 0)

                @php($firstProductId =  $order->orderItems->first()->product_id)
                {{-- @dd($firstProductId) --}}
                @php ($product = App\Models\Subscription::findMess()->find($firstProductId))
                <div class="tab-contentd" id="myTabContent">
                    <div class="tab-pane fade show active" id="{{$order->status}}" role="tabpanel" aria-labelledby="{{$order->status}}-tab">
                        <div class="order-body">
                            <div class="pb-3">
                                <div class="p-3 rounded shadow-sm bg-white">
                                    <div class="d-flex border-bottom pb-3">
                                        <div class="text-muted me-3">
                                            <img alt="#" src="{{asset('vendorMess/'.$product->mess->logo)}}" class="img-fluid order_img rounded">
                                        </div>
                                        <div>
                                            <p class="mb-0 fw-bold"><a href="{{route('user.mess.view',$product->mess->id)}}" class="text-dark">{{Str::upper($product->mess->name)}}</a></p>
                                            <p>ORDER #{{$order->id}}</p>
                                            {{-- <p class="mb-0">Review</p> --}}
                                            <p class="mb-0 small"><a href="{{route('user.mess.my-order', ['order_id'=> $order->id])}}">View Details</a></p>
                                            <p class="mb-0 small"><a class="feather-printer" href="{{route('user.mess.invoice')."?order_id=$order->id"}}">Invoice</a></p>
                                        </div>
                                        <div class="ms-auto">
                                            @if($order->status == 'confirmed')
                                            <p class="bg-success text-white py-1 px-2 rounded small text-center mb-1">Confirmed</p>
                                            @elseif($order->status == 'pending')
                                            <p class="bg-warning text-white py-1 px-2 rounded small text-center mb-1">On Process</p>
                                            @elseif ($order->status == 'canceled')
                                            <p class="bg-danger text-white py-1 px-2 rounded small text-center mb-1">{!! Str::ucfirst($order->cancel_reason)!!}</p>
                                            @endif
                                            <p class="small fw-bold text-center">
                                                <i class="feather-clock"></i>
                                                {{-- @dd() --}}
                                                {{App\CentralLogics\Helpers::format_date($order->updated_at).' - '.App\CentralLogics\Helpers::format_time($order->updated_at->toTimeString())}}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="d-flex pt-3">
                                       <div class="text-muted m-0 ms-auto me-3 small">Total Payment<br>
                                            <span class="text-dark fw-bold"> {{App\CentralLogics\Helpers::format_currency($order->total)}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                @endforeach
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

