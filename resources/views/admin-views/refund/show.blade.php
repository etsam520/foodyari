@extends('layouts.dashboard-main')

@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Refund Request Details</h4>
                    <div>
                        @if($refund->refund_status === 'pending')
                        <button class="btn btn-success btn-sm me-2" onclick="processRefund({{ $refund->id }}, 'approve')">
                            <i class="fa fa-check"></i> Approve
                        </button>
                        <button class="btn btn-danger btn-sm me-2" onclick="processRefund({{ $refund->id }}, 'reject')">
                            <i class="fa fa-times"></i> Reject
                        </button>
                        <button class="btn btn-warning btn-sm me-2" onclick="editDeduction({{ $refund->id }})">
                            <i class="fa fa-edit"></i> Edit Deduction
                        </button>
                        @endif
                        <a href="{{ route('admin.refund.index') }}" class="btn btn-secondary btn-sm ms-2">
                            <i class="fa fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Refund Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="mb-3">Refund Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Refund ID:</strong></td>
                                    <td>#{{ $refund->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Order ID:</strong></td>
                                    <td>
                                        <a href="{{ route('admin.order.details', $refund->order_id) }}" class="text-decoration-none">
                                            #{{ $refund->order_id }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Refund Amount:</strong></td>
                                    <td class="text-success fw-bold">{{ \App\CentralLogics\Helpers::format_currency($refund->refund_amount) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Restaurant Deduction:</strong></td>
                                    <td class="text-danger fw-bold">{{ \App\CentralLogics\Helpers::format_currency($refund->restaurant_deduction_amount ?? 0) }}</td>
                                </tr>
                                @if($refund->restaurant_deduction_reason)
                                <tr>
                                    <td><strong>Deduction Reason:</strong></td>
                                    <td class="text-muted">{{ $refund->restaurant_deduction_reason }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td><strong>Refund Type:</strong></td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst($refund->refund_type) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @if($refund->refund_status === 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($refund->refund_status === 'approved')
                                            <span class="badge bg-info">Approved</span>
                                        @elseif($refund->refund_status === 'processed')
                                            <span class="badge bg-success">Processed</span>
                                        @elseif($refund->refund_status === 'rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Request Date:</strong></td>
                                    <td>{{ $refund->created_at->format('d M Y, h:i A') }}</td>
                                </tr>
                                @if($refund->processed_at)
                                <tr>
                                    <td><strong>Processed Date:</strong></td>
                                    <td>{{ $refund->processed_at->format('d M Y, h:i A') }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5 class="mb-3">Customer Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>{{ $refund->customer->f_name }} {{ $refund->customer->l_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Phone:</strong></td>
                                    <td>{{ $refund->customer->phone }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $refund->customer->email ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Customer ID:</strong></td>
                                    <td>
                                        <a href="{{ route('admin.customer.view', $refund->customer_id) }}" class="text-decoration-none">
                                            #{{ $refund->customer_id }}
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Refund Reason -->
                    <div class="mb-4">
                        <h5>Refund Reason</h5>
                        <div class="alert alert-light">
                            {{ $refund->refund_reason }}
                        </div>
                    </div>

                    <!-- Customer Note -->
                    @if($refund->customer_note)
                    <div class="mb-4">
                        <h5>Customer Note</h5>
                        <div class="alert alert-light">
                            {{ $refund->customer_note }}
                        </div>
                    </div>
                    @endif

                    <!-- Admin Note -->
                    @if($refund->admin_note)
                    <div class="mb-4">
                        <h5>Admin Note</h5>
                        <div class="alert alert-secondary">
                            {{ $refund->admin_note }}
                        </div>
                    </div>
                    @endif

                    <!-- Refund Method and Transaction Reference -->
                    @if($refund->refund_method || $refund->transaction_reference)
                    <div class="mb-4">
                        <h5>Processing Information</h5>
                        <table class="table table-borderless">
                            @if($refund->refund_method)
                            <tr>
                                <td><strong>Refund Method:</strong></td>
                                <td>{{ ucwords(str_replace('_', ' ', $refund->refund_method)) }}</td>
                            </tr>
                            @endif
                            @if($refund->transaction_reference)
                            <tr>
                                <td><strong>Transaction Reference:</strong></td>
                                <td>{{ $refund->transaction_reference }}</td>
                            </tr>
                            @endif
                            @if($refund->processedBy)
                            <tr>
                                <td><strong>Processed By:</strong></td>
                                <td>{{ $refund->processedBy->name ?? 'System' }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Order Summary -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title">Order Summary</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td><strong>Order Amount:</strong></td>
                            <td>{{ \App\CentralLogics\Helpers::format_currency($refund->order->order_amount) }}</td>
                        </tr>
                        <tr>
                            <td><strong>Order Status:</strong></td>
                            <td>
                                <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $refund->order->order_status)) }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Payment Status:</strong></td>
                            <td>
                                <span class="badge bg-{{ $refund->order->payment_status === 'paid' ? 'success' : 'warning' }}">
                                    {{ ucfirst($refund->order->payment_status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Payment Method:</strong></td>
                            <td>{{ ucfirst(str_replace('_', ' ', $refund->order->payment_method)) }}</td>
                        </tr>
                        <tr>
                            <td><strong>Order Date:</strong></td>
                            <td>{{ $refund->order->created_at->format('d M Y') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Process Refund Modal -->
<div class="modal fade" id="processRefundModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="processRefundForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="processRefundModalLabel">Process Refund Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" id="refundAction">
                    
                    <div id="approveFields" style="display: none;">
                        <div class="mb-3">
                            <label for="refundMethod" class="form-label">Refund Method <span class="text-danger">*</span></label>
                            <select name="refund_method" id="refundMethod" class="form-select" required>
                                <option value="">Select Method</option>
                                <option value="wallet">Customer Wallet</option>
                                <option value="original_payment">Original Payment Method</option>
                                <option value="bank_transfer">Bank Transfer</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="adminNote" class="form-label">Admin Note</label>
                        <textarea name="admin_note" id="adminNote" class="form-control" rows="3" placeholder="Add any notes about this refund decision..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="processRefundBtn">Process</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Deduction Modal -->
<div class="modal fade" id="editDeductionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editDeductionForm" method="POST">
                @csrf
                {{-- @method('PUT') --}}
                <div class="modal-header">
                    <h5 class="modal-title">Edit Deduction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i>
                        <strong>Refund Amount:</strong> {{ \App\CentralLogics\Helpers::format_currency($refund->refund_amount) }}
                    </div>
                    <div class="mb-3">
                        <label for="refund_type" class="form-label">Refund Type <span class="text-danger">*</span></label>
                        <select class="form-control" id="refund_type" name="refund_type" required>
                            <option value="">Select refund type</option>
                            <option value="full" {{ $refund->refund_type == 'full' ? 'selected' : '' }}>Full Refund</option>
                            <option value="partial" {{ $refund->refund_type == 'partial' ? 'selected' : '' }}>Partial Refund</option>
                        </select>
                    </div>
                        
                    <div class="mb-3">
                        <label for="refund_amount" class="form-label">Refund Amount <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">{{ \App\CentralLogics\Helpers::currency_symbol() }}</span>
                            <input type="number" class="form-control" id="refund_amount" name="refund_amount" value="{{ $refund->refund_amount }}"
                                step="0.01" min="0"  required>
                        </div>
                        {{-- <small class="text-muted">Maximum refundable: {{ \App\CentralLogics\Helpers::currency_symbol() }}{{ $order->order_amount }}</small> --}}
                    </div>
                    
                    <div class="mb-3">
                        <label for="restaurant_deduction_amount" class="form-label">Restaurant Deduction Amount <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">{{ \App\CentralLogics\Helpers::currency_symbol() }}</span>
                            <input type="number" class="form-control" id="restaurant_deduction_amount" name="restaurant_deduction_amount" 
                                step="0.01" min="0" value="{{ $refund->restaurant_deduction_amount }}" required>
                        </div>
                        <small class="text-muted">Amount to be deducted from restaurant earnings</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="restaurant_deduction_reason" class="form-label">Restaurant Deduction Reason</label>
                        <textarea class="form-control" id="restaurant_deduction_reason" name="restaurant_deduction_reason" rows="2" 
                                placeholder="Optional reason for restaurant deduction...">{{ $refund->restaurant_deduction_reason }}</textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="refund_reason" class="form-label">Reason <span class="text-danger">*</span></label>
                        <select class="form-control" id="refund_reason" name="refund_reason" required>
                            <option value="">Select reason</option>
                            @foreach(\App\Models\RefundReason::active()->forUserType('admin')->get() as $reason)
                                <option value="{{ $reason->reason }}" {{ $refund->refund_reason == $reason->reason ? 'selected' : '' }}>{{ $reason->reason }}</option>
                            @endforeach
                        </select>
                    </div>
                        
                    <div class="mb-3">
                        <label for="admin_note" class="form-label">Admin Note</label>
                        <textarea class="form-control" id="admin_note" name="admin_note" rows="3" 
                                placeholder="Optional note about this refund...">{{ $refund->admin_note }}</textarea>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Note:</strong> This refund will be automatically approved and processed since you are creating it as an admin.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning" id="updateDeductionBtn">Update Deduction</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('javascript')
<script>
const refundAmount = {{ $refund->refund_amount }};

function processRefund(refundId, action) {
    const modal = new bootstrap.Modal(document.getElementById('processRefundModal'));
    const form = document.getElementById('processRefundForm');
    const actionInput = document.getElementById('refundAction');
    const approveFields = document.getElementById('approveFields');
    const modalTitle = document.getElementById('processRefundModalLabel');
    const submitBtn = document.getElementById('processRefundBtn');
    const refundMethodField = document.getElementById('refundMethod');
    
    form.action = `{{ route('admin.refund.process', '') }}/${refundId}`;
    actionInput.value = action;
    
    if (action === 'approve') {
        modalTitle.textContent = 'Approve Refund Request';
        submitBtn.textContent = 'Approve Refund';
        submitBtn.className = 'btn btn-success';
        approveFields.style.display = 'block';
        refundMethodField.required = true;
    } else {
        modalTitle.textContent = 'Reject Refund Request';
        submitBtn.textContent = 'Reject Refund';
        submitBtn.className = 'btn btn-danger';
        approveFields.style.display = 'none';
        refundMethodField.required = false;
    }
    
    modal.show();
}

function editDeduction(refundId) {
    const modal = new bootstrap.Modal(document.getElementById('editDeductionModal'));
    const form = document.getElementById('editDeductionForm');
    
    form.action = `{{ route('admin.refund.update-deduction', '') }}/${refundId}`;
    
    modal.show();
}



// Form submission handler for edit deduction
document.getElementById('editDeductionForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(e.target);
    const deductionAmount = parseFloat(formData.get('restaurant_deduction_amount')) || 0;
    
    // Final validation before submission
    if (deductionAmount > refundAmount || deductionAmount < 0) {
        return false;
    }
    
    // Show loading state
    const submitBtn = document.getElementById('updateDeductionBtn');
    const originalText = submitBtn.textContent;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Updating...';
    
    // Submit the form
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Close modal and reload page to show updated values
            bootstrap.Modal.getInstance(document.getElementById('editDeductionModal')).hide();
            location.reload();
        } else {
            alert('Error updating deduction: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating deduction. Please try again.');
    })
    .finally(() => {
        // Restore button state
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
    });
});
</script>
@endpush
