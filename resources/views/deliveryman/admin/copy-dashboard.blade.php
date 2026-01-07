@extends('deliveryman.admin.layouts.main')
@section('content')

@include('deliveryman.admin.layouts.m-header')      
<div class="osahan-home-page">
    
    <!-- Moblile header end -->  
    <div class="main">
        <div class="container">
            @include('deliveryman.admin.layouts.activate')     
        </div>
        <div class="container position-relative">
            <div class="row justify-content-center pt-3">
                <div class="col-12 col-lg-8">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <a data-bs-toggle="modal" data-bs-target="#delivery_boy_order_update"
                            href="javascript:void(0)">
                                <div class="bg-white shadow-sm rounded p-4 text-center">
                                    <p class="mb-3 text-primary order-count text-center" data-count="currentOrders">0</p>
                                    <p class="mb-2 order-text">Today's Order</p>
                                </div>
                            </a>
                        </div>
                        {{-- <div class="col-6 mb-3">
                            <div class="bg-white shadow-sm rounded p-4 text-center">
                                <p class="mb-3 text-primary order-count text-center">5</p>
                                <p class="mb-2 order-text">This Week Order</p>
                            </div>
                        </div> --}}
                        <div class="col-6  mb-3">
                            <div class="bg-white shadow-sm rounded p-4 text-center">
                                <p class="mb-3 text-primary order-count text-center">6</p>
                                <p class="mb-2 order-text">Total Order</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-12 mb-3">
                            <div class="bg-white shadow-sm rounded p-4 text-center">
                                <p class="mb-3 text-primary order-count text-center">0</p>
                                <p class="mb-2 order-text">Cash in Your Hand</p>
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
async function loadOrders(params) {
    try {
        const resp = await fetch('{{route('deliveryman.admin.get_latest_orders')}}');
        const result = await resp.json();
        if (resp.ok && result !== null) {
            console.log(result);
            document.querySelector('#delivery_boy_order_update .modal-body').innerHTML = result.view;
            document.querySelector('[data-count=currentOrders]').textContent = result.currentOrders
            OrderAcceptOrReject();
            
        }
    } catch (error) {
        console.error('Error fetching data:', error);
    } 
}
loadOrders();
async function OrderAcceptOrReject() {
    // console.log(document.querySelectorAll('[data-accepetance]'))
    document.querySelectorAll('[data-acceptance]').forEach(element => {
        element.addEventListener('click', ()=> {
            Swal.fire({
                title: `Do you want to ${element.dataset.acceptance} the Order?`,
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: element.dataset.acceptance.toUpperCase(),
                denyButtonText: 'Cancel',
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const resp = await fetch('{{route('deliveryman.admin.order-confirmation')}}?order_id=' + element.dataset.orderId+"&status="+element.dataset.acceptance);
                        if (!resp.ok){
                          const error = await resp.json(); 
                            throw new Error(error.message);
                        }
                        else{
                            const data = await resp.json();
                            Swal.fire('Saved!', data.message, 'success');
                            loadOrders();
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
}   
</script>

@endpush
