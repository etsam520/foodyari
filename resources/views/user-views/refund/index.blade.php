@extends('user-views.restaurant.layouts.main')

@section('containt')
<div class="container">
    <div class="py-3">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold">My Refund Requests</h4>
            <a href="{{ route('user.dashboard') }}" class="btn btn-outline-primary btn-sm">
                <i class="fa fa-arrow-left"></i> Back to Dashboard
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
            <div class="col-12">
                @forelse($refunds as $refund)
                <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="d-flex align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">
                                            <strong>Order #{{ $refund->order_id }}</strong>
                                            @if($refund->refund_status === 'pending')
                                                <span class="badge bg-warning ms-2">Pending Review</span>
                                            @elseif($refund->refund_status === 'approved')
                                                <span class="badge bg-info ms-2">Approved</span>
                                            @elseif($refund->refund_status === 'processed')
                                                <span class="badge bg-success ms-2">Refund Processed</span>
                                            @elseif($refund->refund_status === 'rejected')
                                                <span class="badge bg-danger ms-2">Rejected</span>
                                            @endif
                                        </h6>
                                        <p class="text-muted mb-2">
                                            <i class="fa fa-calendar me-1"></i>
                                            Requested on {{ $refund->created_at->format('M d, Y \a\t h:i A') }}
                                        </p>
                                        <p class="mb-2">
                                            <strong>Reason:</strong> {{ $refund->refund_reason }}
                                        </p>
                                        @if($refund->customer_note)
                                        <p class="mb-2">
                                            <strong>Note:</strong> {{ $refund->customer_note }}
                                        </p>
                                        @endif
                                        @if($refund->admin_note && in_array($refund->refund_status, ['approved', 'rejected', 'processed']))
                                        <div class="alert alert-light mb-2">
                                            <strong>Admin Response:</strong> {{ $refund->admin_note }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <h5 class="text-success mb-2">
                                    {{ App\CentralLogics\Helpers::format_currency($refund->refund_amount) }}
                                </h5>
                                @if($refund->restaurant_deduction_amount > 0)
                                <p class="text-warning small mb-1">
                                    <i class="fa fa-minus-circle"></i>
                                    Restaurant Penalty: {{ App\CentralLogics\Helpers::format_currency($refund->restaurant_deduction_amount) }}
                                </p>
                                @endif
                                <p class="text-muted small mb-3">
                                    {{ ucfirst($refund->refund_type) }} Refund
                                </p>
                                
                                <div class="btn-group-vertical w-100" role="group">
                                    <a href="{{ route('user.refund.show', $refund->id) }}" 
                                       class="btn btn-outline-primary btn-sm mb-2">
                                        <i class="fa fa-eye"></i> View Details
                                    </a>
                                    
                                    @if($refund->refund_status === 'pending')
                                    <button class="btn btn-outline-danger btn-sm" 
                                            onclick="cancelRefund({{ $refund->id }})">
                                        <i class="fa fa-times"></i> Cancel Request
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        @if($refund->refund_status === 'processed' && $refund->refund_method === 'wallet')
                        <div class="mt-3 p-2 bg-light rounded">
                            <small class="text-success">
                                <i class="fa fa-check-circle"></i>
                                Refund has been processed to your wallet on {{ $refund->processed_at->format('M d, Y') }}
                            </small>
                        </div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fa fa-receipt fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No Refund Requests</h5>
                        <p class="text-muted mb-4">You haven't made any refund requests yet.</p>
                        <a href="{{ route('user.restaurant.order-list', 'all') }}" class="btn btn-primary">
                            View Your Orders
                        </a>
                    </div>
                </div>
                @endforelse

                @if($refunds->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $refunds->links() }}
                </div>
                @endif
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
