@extends('user-views.restaurant.layouts.main')

@section('containt')
<div class="d-lg-none d-block">
    <div class="bg-primary p-3 d-flex align-items-center">
        <a class="text-white fw-bolder fs-4 me-3" onclick="window.history.back()">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h4 class="fw-bold m-0 text-white">Scheduled Orders</h4>
    </div>
</div>

<div class="container position-relative">
    <div class="row mt-3 justify-content-center mx-1">
        <div class="col-lg-8 col-12 mb-3 p-0">
            <div class="bg-white rounded shadow-sm p-3 mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">
                        <i class="feather-clock text-primary me-2"></i>
                        Scheduled Orders
                    </h5>
                    <span class="badge bg-primary">{{ $orders->total() }} Orders</span>
                </div>
                <p class="text-muted mb-0 mt-2">
                    Manage your upcoming scheduled orders
                </p>
            </div>
        </div>

        <div class="col-lg-8 col-12 p-0">
            @if($orders->count() > 0)
                @foreach ($orders as $order)
                    @php($restaurant = $order->restaurant)
                    <div class="order-body mb-3">
                        <div class="p-3 rounded shadow-sm bg-white">
                            <div class="d-flex"
                                onclick="location.href='{{ route('user.restaurant.scheduled-order-details', ['orderId' => $order->id]) }}'"
                                style="cursor: pointer;">
                                
                                <div class="text-muted me-3">
                                    <img alt="#" src="{{ Helpers::getUploadFile($restaurant->logo, 'restaurant') }}"
                                        class="img-fluid order_img rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                </div>
                                
                                <div class="w-100">
                                    <div>
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <p class="mb-1 fw-bold">ORDER #{{ $order->id }}</p>
                                                <p class="fw-bolder mb-0 text-primary">{{ Str::upper($restaurant->name) }}</p>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge bg-warning text-dark mb-1">
                                                    <i class="feather-clock me-1"></i>Scheduled
                                                </span>
                                                <p class="small mb-0 text-muted">
                                                    Amount: <span class="fw-bold">{{ Helpers::format_currency($order->order_amount) }}</span>
                                                </p>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-2 pt-2 border-top">
                                            <div class="row">
                                                <div class="col-6">
                                                    <p class="small mb-1 text-muted">Scheduled For:</p>
                                                    <p class="fw-bold mb-0 text-success">
                                                        <i class="feather-calendar me-1"></i>
                                                        {{ Carbon\Carbon::parse($order->schedule_at)->format('d M Y') }}
                                                    </p>
                                                    <p class="fw-bold mb-0 text-success">
                                                        <i class="feather-clock me-1"></i>
                                                        {{ Carbon\Carbon::parse($order->schedule_at)->format('h:i A') }}
                                                    </p>
                                                </div>
                                                <div class="col-6 text-end">
                                                    @php($timeRemaining = Carbon\Carbon::parse($order->schedule_at)->diffInHours(now()))
                                                    @if($timeRemaining > 1)
                                                        <p class="small mb-1 text-muted">Time Remaining:</p>
                                                        <p class="fw-bold mb-0 text-info">
                                                            {{ Carbon\Carbon::parse($order->schedule_at)->diffForHumans() }}
                                                        </p>
                                                    @else
                                                        <p class="small mb-1 text-warning">
                                                            <i class="feather-alert-triangle me-1"></i>
                                                            Starting Soon
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        
                                        @if($order->details->count() > 0)
                                            <div class="mt-2 pt-2 border-top">
                                                <p class="small mb-1 text-muted">Items ({{ $order->details->count() }}):</p>
                                                <div class="d-flex flex-wrap">
                                                    @foreach($order->details->take(3) as $detail)
                                                        <span class="badge bg-light text-dark me-1 mb-1">
                                                            {{ $detail->quantity }}x {{ $detail->food->name ?? 'Item' }}
                                                        </span>
                                                    @endforeach
                                                    @if($order->details->count() > 3)
                                                        <span class="badge bg-secondary">
                                                            +{{ $order->details->count() - 3 }} more
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between pt-3 mt-3 border-top">
                                <a class="btn btn-outline-primary btn-sm" 
                                   href="{{ route('user.restaurant.scheduled-order-details', ['orderId' => $order->id]) }}">
                                    <i class="feather-eye me-1"></i>View Details
                                </a>
                                
                                @if(Carbon\Carbon::parse($order->schedule_at)->diffInHours(now()) >= 1)
                                    <button class="btn btn-outline-danger btn-sm" 
                                            onclick="cancelScheduledOrder({{ $order->id }})">
                                        <i class="feather-x me-1"></i>Cancel Order
                                    </button>
                                @else
                                    <span class="text-muted small">
                                        <i class="feather-info me-1"></i>Cannot cancel (< 1hr to start)
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $orders->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <div class="bg-white rounded shadow-sm p-5">
                        <h5 class="fw-bold text-muted">No Scheduled Orders</h5>
                        <p class="text-muted">You don't have any scheduled orders at the moment.</p>
                        <a href="{{route('userHome')}}" class="btn btn-primary">
                            <i class="feather-plus me-1"></i>Schedule New Order
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Cancel Order Modal -->
<div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelOrderModalLabel">Cancel Scheduled Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to cancel this scheduled order?</p>
                <p class="text-muted small">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Keep Order</button>
                <button type="button" class="btn btn-danger" id="confirmCancelOrder">Yes, Cancel Order</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('script')
<script>
let orderToCancel = null;

function cancelScheduledOrder(orderId) {
    orderToCancel = orderId;
    $('#cancelOrderModal').modal('show');
}

document.getElementById('confirmCancelOrder').addEventListener('click', function() {
    if (orderToCancel) {
        // Show loading state
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Cancelling...';
        this.disabled = true;
        
        fetch(`{{ route('user.restaurant.cancel-scheduled-order', '') }}/${orderToCancel}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                toastr.success(data.message);
                // Reload page after a short delay
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                toastr.error(data.message || 'Failed to cancel order');
                this.innerHTML = 'Yes, Cancel Order';
                this.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            toastr.error('An error occurred. Please try again.');
            this.innerHTML = 'Yes, Cancel Order';
            this.disabled = false;
        })
        .finally(() => {
            $('#cancelOrderModal').modal('hide');
        });
    }
});
</script>
@endpush
