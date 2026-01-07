@extends('deliveryman.admin.layouts.main')
@section('content')


<div class="osahan-home-page">
    
    <!-- Moblile header end -->  
    <div class="main">
        <div class="container position-relative">
            <div class="row justify-content-center pt-3">
                <div class="col-12 col-md-12">
                    <div class="shadow-sm rounded bg-white mb-3 overflow-hidden">
                        <h3 class="mb-0 p-2 px-3 text-primary">
                           Choose Payment Option</h3>
                        <div class="row m-0">
                            <div class="col-md-8 px-0 border-top">
                                <h5 class="mb-0 p-2 px-3 text-primary">Order Amount : {{App\CentralLogics\Helpers::format_currency($order->order_amount)}}</h5>
                                <h5 class="mb-0 p-2 px-3 text-primary">Collectable Amount : {{App\CentralLogics\Helpers::format_currency($order->cash_to_collect)}}</h5>
                                <div class="d-flex align-items-top gap-2 p-3 border-bottom gold-members">
                                    
                                    <div class="w-75">
                                        <div class="d-flex align-items-center gap-2">
                                            
                                            <form action="{{route('deliveryman.admin.order-deliver')}}" onclick="this.submit()">
                                                <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                                                    <label for="cash" class="btn btn-sm btn-primary">Cash</label>
                                                    <input type="radio"  id="cash" hidden name="payment_type" value="cash" autocomplete="off">

                                                    <label for="online" class="btn btn-sm btn-secondary">Online</label>
                                                    <input type="radio" id="online" hidden name="payment_type" value="online" autocomplete="off">
                                                  </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- <div class="col-md-12 py-2 mx-auto ">
                                <div id="map-canvas2" style=" width:100%;height: 50vh"></div>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    

@endsection


@push('javascript')


@endpush
