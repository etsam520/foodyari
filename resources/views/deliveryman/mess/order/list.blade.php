@extends('deliveryman.layouts.main')
@section('content')

@include('deliveryman.layouts.m-header')      
<div class="osahan-home-page">
    
    <!-- Moblile header end -->  
    <div class="main">
        <div class="container">
            @include('deliveryman.layouts.activate')     
        </div>
        <div class="container position-relative">
            <div class="row justify-content-center pt-3">
                <div class="col-12 col-md-12">
                    <div class="shadow-sm rounded bg-white mb-3 overflow-hidden">
                        <h3 class="mb-0 p-2 px-3 text-primary">
                            {{__('messages.orders')}} {{__("messages.".$state)}}</h3>
                        <div class="row m-0">
                            <div class="col-md-8 px-0 border-top">
                                @foreach ($orders as $order)
                                    <div class="d-flex align-items-top gap-2 p-3 border-bottom gold-members"
                                    @if ($state === 'pickedUp')
                                     onclick="location.href='{{route('deliveryman.mess.order-track',['dmOrderAcceptId'=> $order->id])}}'"
                                    @endif
                                     >
                                        <div class="w-25">
                                            <img alt="#" src="{{asset('customers/'.$order->customer->image)}}" class="img-fluid">
                                        </div>
                                        <div class="w-75">
                                            <div class="d-lg-flex align-items-center gap-2">
                                                <div>
                                                    <h4 class="mb-1">
                                                        {{Str::ucfirst($order->customer->f_name)}}  {{Str::ucfirst($order->customer->l_name)}}
                                                    </h4>
                                                    {{-- <small class="text-black-50">Quantity - 1 (Full)</small> --}}
                                                    <p class="mb-0 mt-1 text-black-50 feather-phone">
                                                        {{$order->customer->phone}}
                                                    </p>
                                                    <p class="mb-0 mt-1 text-black-50 feather-calendar">
                                                        {{-- {{Carbon/Carbon::parse$order->}} --}}
                                                        {{App\CentralLogics\Helpers::format_date($order->updated_at) }} {{App\CentralLogics\Helpers::format_time($order->updated_at) }}
                                                    </p>
                                                    <p class="mb-0 mt-1 text-black-50 feather-map-pin">
                                                        {{Str::ucfirst($order->checklist->coupon->customerSubscriptionTxns->delivery_address)}}
                                                    </p>
                                                </div>
                                                <div class="ms-auto mt-1 d-flex flex-column justify-content-evenly">
                                                    <span class="badge bg-primary fs-5 mb-2">Tiffin NO - ****</span>
                                                    @if ($state === 'accepted')
                                                    <span class="btn btn-sm btn-warning" data-coupon-id="{{$order->checklist->coupon->id}}" data-acceptance="pickedUp"  type="button">Out For Delivery</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                @if($orders->count() == 0)
                                <div class="d-flex align-items-top gap-2 p-3 border-bottom gold-members">
                                    <img alt="#" src="{{asset('assets/images/icons/nodata.png')}}" class="img-fluid w-50">
                                    <div class="w-100">
                                        <div class="align-items-center gap-2">
                                            <div>
                                                <h4 class="mb-1">
                                                    Empty Data
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    
<div class="modal fade" id="delivery_boy_order_update" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                
            </div>
        </div>
    </div>
</div>
@endsection


@push('javascript')
<script>     
    //dm order status acceptance  
    document.querySelectorAll('[data-acceptance]').forEach(element => {
        element.addEventListener('click',async ()=> {
            Swal.fire({
                title: `Do you want to ${element.dataset.acceptance} the Order?`,
                showDenyButton: false,
                showCancelButton: true,
                confirmButtonText: element.dataset.acceptance.toUpperCase(),
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const resp = await fetch('{{route('deliveryman.mess.order-confirmation')}}?coupon_id=' + element.dataset.couponId+"&status="+element.dataset.acceptance);
                        if (!resp.ok){
                            const error = await resp.json();
                            throw new Error(error.message)
                        }else{
                            const data = await resp.json();
                            Swal.fire('Saved!', data.message, 'success');
                        }
                    } catch (error) {
                        console.error('Error fetching data:', error);
                        Swal.fire('Error', error.message, 'error');
                    }
                    
                } else if (result.isDenied) {
                    Swal.fire('Changes are not saved', '', 'info');
                }
            });
        })
    });

</script>

@endpush
