@extends('user-views.restaurant.layouts.main')

@section('containt')
<div class="container">
    <div class="py-3">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold">Request Refund</h4>
            <a href="{{ route('user.restaurant.order-list', 'all') }}" class="btn btn-outline-primary btn-sm">
                <i class="fa fa-arrow-left"></i> Back to Orders
            </a>
        </div>

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Refund Request Form</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('user.refund.store') }}" id="refundForm">
                            @csrf
                            <input type="hidden" name="order_id" value="{{ $order->id }}">

                            <!-- Refund Type -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Refund Type <span class="text-danger">*</span></label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card border">
                                            <div class="card-body text-center">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="refund_type" 
                                                           id="fullRefund" value="full" checked 
                                                           onchange="updateRefundAmount()">
                                                    <label class="form-check-label w-100" for="fullRefund">
                                                        <h6 class="mb-1">Full Refund</h6>
                                                        <p class="text-muted small mb-0">
                                                            Get full amount back
                                                        </p>
                                                        <strong class="text-success">
                                                            {{ App\CentralLogics\Helpers::format_currency($order->order_amount) }}
                                                        </strong>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card border">
                                            <div class="card-body text-center">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="refund_type" 
                                                           id="partialRefund" value="partial" 
                                                           onchange="updateRefundAmount()">
                                                    <label class="form-check-label w-100" for="partialRefund">
                                                        <h6 class="mb-1">Partial Refund</h6>
                                                        <p class="text-muted small mb-0">
                                                            Specify custom amount
                                                        </p>
                                                        <strong class="text-info">Custom Amount</strong>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Custom Amount (for partial refund) -->
                            <div class="mb-4" id="customAmountDiv" style="display: none;">
                                <label for="refundAmount" class="form-label fw-bold">
                                    Refund Amount <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" name="refund_amount" id="refundAmount" 
                                           class="form-control" step="0.01" min="0.01" 
                                           max="{{ $order->order_amount }}" 
                                           placeholder="Enter amount to refund">
                                </div>
                                <small class="text-muted">
                                    Maximum refund amount: {{ App\CentralLogics\Helpers::format_currency($order->order_amount) }}
                                </small>
                            </div>

                            <!-- Refund Reason -->
                            <div class="mb-4">
                                <label for="refundReason" class="form-label fw-bold">
                                    Refund Reason <span class="text-danger">*</span>
                                </label>
                                <select name="refund_reason" id="refundReason" class="form-select" required>
                                    <option value="">Select a reason</option>
                                    @foreach($refundReasons as $reason)
                                    <option value="{{ $reason->reason }}">{{ $reason->reason }}</option>
                                    @endforeach
                                    <option value="other">Other (specify below)</option>
                                </select>
                            </div>

                            <!-- Custom Reason -->
                            <div class="mb-4" id="customReasonDiv" style="display: none;">
                                <label for="customReason" class="form-label fw-bold">
                                    Specify Reason <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="customReason" class="form-control" 
                                       placeholder="Please specify your reason for refund">
                            </div>

                            <!-- Additional Note -->
                            <div class="mb-4">
                                <label for="customerNote" class="form-label fw-bold">
                                    Additional Note (Optional)
                                </label>
                                <textarea name="customer_note" id="customerNote" class="form-control" rows="3" 
                                          placeholder="Any additional information you'd like to provide..."></textarea>
                                <small class="text-muted">This will help us process your refund request faster.</small>
                            </div>

                            <!-- Terms and Conditions -->
                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="agreeTerms" required>
                                    <label class="form-check-label" for="agreeTerms">
                                        I understand that refund requests are subject to review and approval. 
                                        Processing time may take 3-7 business days.
                                    </label>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="{{ route('user.restaurant.order-list', 'all') }}" 
                                   class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-paper-plane"></i> Submit Refund Request
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Order Summary -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Order Summary</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>Order ID:</strong> #{{ $order->id }}</p>
                        <p><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y') }}</p>
                        <p><strong>Restaurant:</strong> {{ $order->restaurant->name ?? 'N/A' }}</p>
                        <p><strong>Total Amount:</strong> 
                           <span class="text-success fw-bold">
                               {{ App\CentralLogics\Helpers::format_currency($order->order_amount) }}
                           </span>
                        </p>
                        <p><strong>Payment Status:</strong> 
                           <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">
                               {{ ucfirst($order->payment_status) }}
                           </span>
                        </p>
                        <p><strong>Order Status:</strong> 
                           <span class="badge bg-secondary">
                               {{ ucfirst(str_replace('_', ' ', $order->order_status)) }}
                           </span>
                        </p>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Order Items</h6>
                    </div>
                    <div class="card-body">
                        @if($order->details && $order->details->count() > 0)
                        @foreach($order->details as $item)
                        <div class="d-flex justify-content-between align-items-center mb-2 pb-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div>
                                <h6 class="mb-1">{{ $item->food->name ?? $item->foodName ?? 'Item' }}</h6>
                                <small class="text-muted">Qty: {{ $item->quantity }}</small>
                            </div>
                            <div class="text-end">
                                <strong>{{ App\CentralLogics\Helpers::format_currency($item->foodPrice * $item->quantity) }}</strong>
                            </div>
                        </div>
                        @endforeach
                        @else
                        <p class="text-muted">No items found for this order.</p>
                        @endif
                    </div>
                </div>

                <!-- Refund Policy -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Refund Policy</h6>
                    </div>
                    <div class="card-body">
                        <small class="text-muted">
                            • Refund requests are processed within 3-7 business days<br>
                            • Only delivered orders can be refunded<br>
                            • Refunds will be processed to your wallet or original payment method<br>
                            • Please provide accurate information for faster processing
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
<script>
function updateRefundAmount() {
    const fullRefund = document.getElementById('fullRefund').checked;
    const customAmountDiv = document.getElementById('customAmountDiv');
    const refundAmountInput = document.getElementById('refundAmount');
    
    if (fullRefund) {
        customAmountDiv.style.display = 'none';
        refundAmountInput.removeAttribute('required');
    } else {
        customAmountDiv.style.display = 'block';
        refundAmountInput.setAttribute('required', 'required');
    }
}

// Handle custom reason
document.getElementById('refundReason').addEventListener('change', function() {
    const customReasonDiv = document.getElementById('customReasonDiv');
    const customReasonInput = document.getElementById('customReason');
    
    if (this.value === 'other') {
        customReasonDiv.style.display = 'block';
        customReasonInput.setAttribute('required', 'required');
    } else {
        customReasonDiv.style.display = 'none';
        customReasonInput.removeAttribute('required');
    }
});

// Handle form submission
document.getElementById('refundForm').addEventListener('submit', function(e) {
    const customReason = document.getElementById('customReason');
    const refundReasonSelect = document.getElementById('refundReason');
    
    // If "other" is selected, use custom reason
    if (refundReasonSelect.value === 'other' && customReason.value.trim()) {
        // Create a hidden input with the custom reason
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'refund_reason';
        hiddenInput.value = customReason.value.trim();
        this.appendChild(hiddenInput);
        
        // Remove the required attribute from select to prevent validation error
        refundReasonSelect.removeAttribute('required');
    }
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateRefundAmount();
});
</script>
@endpush
