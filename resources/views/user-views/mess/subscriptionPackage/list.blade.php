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
                        @foreach ($orders as $ord)
                        <li class="nav-item border-top {{$loop->index == 0 ? 'first':null}}" type="button" role="presentation" data-order-id="{{$ord->id}}">
                            <div class="position-relative border border-primary rounded  p-0">
                                {{-- <label class="form-check-label w-100 border rounded" for="customRadioInline1"></label> --}}
                                <div>
                                    <div class="p-3 bg-white rounded rounded-bottom-0 shadow-sm w-100">
                                        <h5 class="mn-0 text-muted">Order No : #{{$ord->id}}</h5>
                                        <div class="d-flex align-items-center mb-2">
                                            <h6 class="mb-0">Collection : {{Str::ucfirst($ord->meal_collection)}}</h6>
                                            <p class="mb-0 badge text-bg-success ms-auto"><i class="icofont-check-circled"></i>{{App\CentralLogics\Helpers::format_date($ord->created_at)}}</p>
                                        </div>
                                        @if(!empty($ord->special_note))
                                        <p class="small text-muted m-0"><i>Special Note :</i>{{$ord->special_note}}</p>
                                        @endif
                                        {{-- <p class="small text-muted m-0">Redwood City, CA 94063</p> --}}
                                    </div>
                                    <span  class="badge bg-primary w-100 ">{{App\CentralLogics\Helpers::format_currency($ord->total)}}</span>
                                </div>
                            </div>
                        </li>    
                        @endforeach
                    </ul>
                    {{ $orders->links() }}
                </div>
                <div class="col-md-9" data-order-items="all">

                </div>
                 
            </div>
        </div> 
    </div>
</div>


@endsection

@push('javascript')
<script>
    document.querySelectorAll('[data-order-id]').forEach(item => {
        if (item.classList.contains('first')) {
            showOrderItems(item.dataset.orderId);
        }
        item.addEventListener('click', () => {
            showOrderItems(item.dataset.orderId);
        });
    });

    async function showOrderItems(orderId) {
        try {
            const res = await fetch("{{route('user.mess.mess-package-history-items')}}?order_id="+orderId);
            const result = await res.json();
            console.log(result);
            if (result.success) {
                document.querySelector('[data-order-items="all"]').innerHTML = result.view;
                document.querySelectorAll('[data-activate=package]').forEach(item => {
                    item.addEventListener('click',()=> {
                        activatePackage(item.dataset.orderId, item.dataset.packageId)
                    })
                })
            } else if (result.error) {
                throw new Error(result.error);
            } else {
                
            }
        } catch (error) {
            toastr.error(error.message);
        }
    }

    async function activatePackage(orderId ,packageId) {
        try {
            const res = await fetch(`{{route('user.mess.activate-package')}}?order_id=${orderId}&product_id=${packageId}`);
            const result = await res.json();
            console.log(result);
            if (result.success) {
                showOrderItems(orderId);
               toastr.success(result.success)
            } else if (result.error) {
                throw new Error(result.error);
            } else {
                console.log(result)
            }
        } catch (error) {
            toastr.error(error.message);
        }
    }
</script>

@endpush

