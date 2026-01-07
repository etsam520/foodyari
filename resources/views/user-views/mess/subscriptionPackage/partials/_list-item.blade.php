
@foreach ($order->orderItems as  $item)
    
@php ($product = App\Models\Subscription::findMess()->find($item->product_id))
<div class="tab-contentee" id="myTabContent">
    <div class="tab-pane fade show active" id="{{$order->status}}" role="tabpanel" aria-labelledby="{{$order->status}}-tab">
        <div class="order-body">
            <div class="pb-3">
                <div class="p-3 rounded shadow-sm bg-white">
                    <div class="d-flex border-bottom pb-3">
                        <div class="text-muted me-3">
                            <img alt="#" src="{{asset('assets/images/icons/food.svg')}}" class="img-fluid order_img rounded">
                        </div>
                        <div>
                            <p class="mb-0 fw-bold"><a href="{{route('user.mess.view',$product->mess->id)}}" class="text-dark">{{Str::upper($product->title)}}</a></p>
                            {{-- <p class="mb-0">address</p> --}}
                            <p>Validity : {{$product->validity}}&nbspDays</p>
                            <p class="mb-0 small"><a href="{{route('user.mess.my-order', ['order_id'=> $order->id])}}">View Details</a></p>
                        </div>
                        <div class="ms-auto">
                            
                            @if(!$item->status || $item->status== 'inactive' )
                            <p type="button" data-activate="package" data-order-id="{{$order->id}}" data-package-id="{{$product->id}}" class="bg-primary text-white py-1 px-2 rounded small text-center mb-1">Activate</p>
                            @else
                                 @if ($item->status == "active" && Carbon\Carbon::parse($item->expiring)->isPast()) 

                                    @php($item = App\Models\SubscriptionOrderItems::find($item->id))
                                    @php($item->status = 'expired')
                                    @php($item->save())
                                @endif

                                @if ($item->status == "active")
                                <p class="bg-success text-white py-1 px-2 rounded small text-center mb-1"><i class="feather-check">Activated</i></p>
                                @endif

                                @if ($item->status == "expired")
                                <p class="bg-warning text-white py-1 px-2 rounded small text-center mb-1">Expired</p>
                                    
                                @endif
                            @endif
       
                            <p class="small fw-bold text-center">
                                <i class="feather-clock"></i> 
                                {{App\CentralLogics\Helpers::format_date($order->updated_at).' - '.App\CentralLogics\Helpers::format_time($order->updated_at->toTimeString())}}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach


