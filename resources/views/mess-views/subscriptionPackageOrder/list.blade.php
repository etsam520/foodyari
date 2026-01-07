@extends('mess-views.layouts.dashboard-main')



@section('content')

<div class="conatiner-fluid content-inner mt-5 py-0">
    <div class="row">
        <div class="col-sm-12 col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title"> {{str_replace('_',' ',$status)}} {{__('messages.orders')}} <span class="badge bg-soft-primary ml-2">{{$orders->total()}}</span></h4>
                    </div>
                </div>
                <div class="card-body">

                    <!-- Table -->
                    <div class="table-responsive datatable-custom">
                        <table id="datatable" class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                            <thead class="thead-light">
                                <tr>
                                    <th class="w-60px">
                                        {{ __('messages.sl') }}
                                    </th>
                                    <th class="w-90px table-column-pl-0">{{__('messages.Order ID')}}</th>
                                    <th class="w-140px">{{__('messages.order')}} {{__('messages.date')}}</th>
                                    <th class="w-140px">{{__('messages.customer_information')}}</th>
                                    <th class="w-100px">{{__('messages.total')}} {{__('messages.amount')}}</th>
                                    <th class="w-100px text-center">{{__('messages.order')}} {{__('messages.status')}}</th>
                                    <th class="w-100px text-center">{{__('messages.actions')}}</th>
                                </tr>
                            </thead>

                            <tbody id="set-rows">
                                @foreach($orders as $key=>$order)
                                <tr class="status-{{$order['order_status']}} class-all">
                                    <td class="">
                                        {{$key+$orders->firstItem()}}
                                    </td>
                                    <td class="table-column-pl-0">
                                        <a href="{{route('vendor.order.details',['id'=>$order['id']])}}" class="text-hover">{{$order['id']}}</a>
                                    </td>
                                    <td>
                                        <span class="d-block">
                                            {{date('d M Y',strtotime($order['created_at']))}}
                                        </span>
                                        <span class="d-block text-uppercase">
                                            {{date(config('timeformat'),strtotime($order['created_at']))}}
                                        </span>
                                    </td>
                                    <td>
                                        @if($order->customer)
                                        <a class="text-body text-capitalize" href="{{route('mess.order.details',['id'=>$order['id']])}}">
                                            <span class="d-block font-semibold">
                                                {{$order->customer['f_name'].' '.$order->customer['l_name']}}
                                            </span>
                                            <span class="d-block">
                                                {{$order->customer['phone']}}
                                            </span>
                                        </a>
                                        @else
                                        <label class="badge rounded-pill bg-danger">{{__('messages.invalid')}} {{__('messages.customer')}} {{__('messages.data')}}</label>
                                        @endif
                                    </td>
                                    <td>


                                        <div class="text-right mw-85px">
                                            <div>
                                                {{\App\CentralLogics\Helpers::format_currency($order['total'])}}
                                            </div>
                                          
                                            @if($order->status =='confirmed')
                                            <strong class="text-success">
                                                {{__('messages.paid')}}
                                            </strong>
                                            @else
                                            <strong class="text-danger">
                                                {{__('messages.unpaid')}}
                                            </strong>
                                            @endif
                                        </div>

                                    </td>
                                    <td class="text-capitalize text-center">
                                        @if($order->status=='pending')
                                        <span class="badge rounded-pill bg-soft-info mb-1">
                                            {{__('messages.pending')}}
                                        </span>
                                        @elseif($order->status=='confirmed')
                                        <span class="badge rounded-pill bg-soft-info mb-1">
                                            {{__('messages.confirmed')}}
                                        </span>
                                        @elseif($order->status=='canceled')
                                        <span class="badge rounded-pill bg-soft-danger mb-1">
                                            {{__('messages.processing')}}
                                        </span>
                                        @else
                                        <span class="badge rounded-pill bg-danger mb-1">
                                            {{__(str_replace('_',' ',$order->status))}}
                                        </span>
                                        @endif


                                        <div class="text-capitalze opacity-7">
                                            @if($order->meal_collection =='delivery')
                                            <span>Delivery</span>
                                            @else
                                            <span>Dine IN</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn--container justify-content-center">
                                            <a class="text-warning mx-2" href="{{route('mess.order.details',['id'=>$order['id']])}}"><i class="fa fa-eye"></i></a>
                                            <a class="text-primary" target="_blank" href="{{route('vendor.order.generate-invoice',[$order['id']])}}"><i class="fa fa-print"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if(count($orders) === 0)
                    <div class="text-center">
                        <img src="{{asset('assets/images/icons/nodata.png')}}" alt="public">
                    </div>
                    @endif
                    <!-- End Table -->
                </div>
                <!-- End Card -->
            </div>
        </div>
    </div>
</div>
@endsection

