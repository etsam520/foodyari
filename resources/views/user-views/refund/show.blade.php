@extends('user-views.restaurant.layouts.main')

@section('containt')
<div class="container">
    <div class="py-3">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold">Refund Request Details</h4>
            <a href="{{ route('user.refund.index') }}" class="btn btn-outline-primary btn-sm">
                <i class="fa fa-arrow-left"></i> Back to Refund Requests
            </a>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="row">
            <div class="col-md-8">
                <!-- Refund Status Card -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="mb-1">Refund Request #{{ $refund->id }}</h5>
                                <p class="text-muted mb-0">
                                    Requested on {{ $refund->created_at->format('M d, Y \a\t h:i A') }}
                                </p>
                            </div>
                            <div class="text-end">
                                @if($refund->refund_status === 'pending')
                                    <span class="badge bg-warning fs-6">Pending Review</span>
                                @elseif($refund->refund_status === 'approved')
                                    <span class="badge bg-info fs-6">Approved</span>
                                @elseif($refund->refund_status === 'processed')
                                    <span class="badge bg-success fs-6">Refund Processed</span>
                                @elseif($refund->refund_status === 'rejected')
                                    <span class="badge bg-danger fs-6">Rejected</span>
                                @endif
                            </div>
                        </div>

                        <!-- Progress Timeline -->
                        <div class="timeline-container mb-4">
                            <div class="timeline-item {{ $refund->created_at ? 'completed' : '' }}">
                                <div class="timeline-marker">
                                    <i class="fa fa-paper-plane"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Request Submitted</h6>
                                    <small class="text-muted">
                                        {{ $refund->created_at->format('M d, Y \a\t h:i A') }}
                                    </small>
                                </div>
                            </div>

                            <div class="timeline-item {{ in_array($refund->refund_status, ['approved', 'processed', 'rejected']) ? 'completed' : '' }}">
                                <div class="timeline-marker">
                                    <i class="fa fa-search"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Under Review</h6>
                                    <small class="text-muted">
                                        @if($refund->processed_at)
                                            Reviewed on {{ $refund->processed_at->format('M d, Y \a\t h:i A') }}
                                        @else
                                            Being reviewed by our team
                                        @endif
                                    </small>
                                </div>
                            </div>

                            @if($refund->refund_status === 'approved' || $refund->refund_status === 'processed')
                            <div class="timeline-item {{ $refund->refund_status === 'processed' ? 'completed' : '' }}">
                                <div class="timeline-marker">
                                    <i class="fa fa-check"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Approved</h6>
                                    <small class="text-muted">
                                        Approved on {{ $refund->processed_at ? $refund->processed_at->format('M d, Y \a\t h:i A') : 'Processing...' }}
                                    </small>
                                </div>
                            </div>
                            @endif

                            @if($refund->refund_status === 'processed')
                            <div class="timeline-item completed">
                                <div class="timeline-marker">
                                    <i class="fa fa-money-bill"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Refund Processed</h6>
                                    <small class="text-muted">
                                        Refund completed via {{ ucwords(str_replace('_', ' ', $refund->refund_method ?? 'wallet')) }}
                                    </small>
                                </div>
                            </div>
                            @endif

                            @if($refund->refund_status === 'rejected')
                            <div class="timeline-item rejected">
                                <div class="timeline-marker">
                                    <i class="fa fa-times"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Request Rejected</h6>
                                    <small class="text-muted">
                                        Rejected on {{ $refund->processed_at ? $refund->processed_at->format('M d, Y \a\t h:i A') : 'N/A' }}
                                    </small>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        @if($refund->refund_status === 'pending')
                        <div class="text-center">
                            <button class="btn btn-outline-danger" onclick="cancelRefund({{ $refund->id }})">
                                <i class="fa fa-times"></i> Cancel Request
                            </button>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Refund Details Card -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Refund Details</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Refund Amount:</strong> 
                                   <span class="text-success fs-5">{{ App\CentralLogics\Helpers::format_currency($refund->refund_amount) }}</span>
                                </p>
                                @if($refund->restaurant_deduction_amount > 0)
                                <p><strong>Restaurant Penalty:</strong>
                                   <span class="text-warning fs-6">{{ App\CentralLogics\Helpers::format_currency($refund->restaurant_deduction_amount) }}</span>
                                </p>
                                @endif
                                <p><strong>Refund Type:</strong> {{ ucfirst($refund->refund_type) }}</p>
                                <p><strong>Order ID:</strong> 
                                   <a href="{{ route('user.restaurant.order-list', 'all') }}?order_id={{ $refund->order_id }}" class="text-decoration-none">
                                       #{{ $refund->order_id }}
                                   </a>
                                </p>
                            </div>
                            <div class="col-md-6">
                                @if($refund->refund_method)
                                <p><strong>Refund Method:</strong> {{ ucwords(str_replace('_', ' ', $refund->refund_method)) }}</p>
                                @endif
                                @if($refund->transaction_reference)
                                <p><strong>Transaction Reference:</strong> {{ $refund->transaction_reference }}</p>
                                @endif
                                @if($refund->processed_at)
                                <p><strong>Processed Date:</strong> {{ $refund->processed_at->format('M d, Y \a\t h:i A') }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="mt-3">
                            <h6>Refund Reason:</h6>
                            <div class="alert alert-light">
                                {{ $refund->refund_reason }}
                            </div>
                        </div>

                        @if($refund->customer_note)
                        <div class="mt-3">
                            <h6>Your Note:</h6>
                            <div class="alert alert-light">
                                {{ $refund->customer_note }}
                            </div>
                        </div>
                        @endif

                        @if($refund->restaurant_deduction_reason && $refund->restaurant_deduction_amount > 0)
                        <div class="mt-3">
                            <h6>Restaurant Penalty Reason:</h6>
                            <div class="alert alert-warning">
                                {{ $refund->restaurant_deduction_reason }}
                            </div>
                        </div>
                        @endif

                        @if($refund->admin_note && in_array($refund->refund_status, ['approved', 'rejected', 'processed']))
                        <div class="mt-3">
                            <h6>Admin Response:</h6>
                            <div class="alert alert-{{ $refund->refund_status === 'rejected' ? 'danger' : 'success' }}">
                                {{ $refund->admin_note }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Order Summary -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Original Order</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>Order Total:</strong> 
                           {{ App\CentralLogics\Helpers::format_currency($refund->order->order_amount) }}
                        </p>
                        <p><strong>Restaurant:</strong> {{ $refund->order->restaurant->name ?? 'N/A' }}</p>
                        <p><strong>Order Date:</strong> {{ $refund->order->created_at->format('M d, Y') }}</p>
                        <p><strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $refund->order->payment_method)) }}</p>
                        <p><strong>Order Status:</strong> 
                           <span class="badge bg-secondary">
                               {{ ucfirst(str_replace('_', ' ', $refund->order->order_status)) }}
                           </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Refund Modal -->
<div class="modal fade" id="cancelRefundModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cancel Refund Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to cancel this refund request? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, Keep Request</button>
                <form id="cancelRefundForm" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger">Yes, Cancel Request</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('css')
<style>
.timeline-container {
    position: relative;
    padding-left: 30px;
}

.timeline-container::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
}

.timeline-marker {
    position: absolute;
    left: -37px;
    top: 0;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    font-size: 12px;
}

.timeline-item.completed .timeline-marker {
    background: #28a745;
    color: white;
}

.timeline-item.rejected .timeline-marker {
    background: #dc3545;
    color: white;
}

.timeline-content h6 {
    color: #495057;
}

.timeline-item.completed .timeline-content h6 {
    color: #28a745;
}

.timeline-item.rejected .timeline-content h6 {
    color: #dc3545;
}
</style>
@endpush

@push('js')
<script>
function cancelRefund(refundId) {
    const modal = new bootstrap.Modal(document.getElementById('cancelRefundModal'));
    const form = document.getElementById('cancelRefundForm');
    form.action = `{{ route('user.refund.cancel', '') }}/${refundId}`;
    modal.show();
}
</script>
@endpush
