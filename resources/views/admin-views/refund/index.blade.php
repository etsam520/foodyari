@extends('layouts.dashboard-main')

@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title">{{__('messages.Order Refunds')}}</h4>
                    <div class="d-flex">
                        <a href="{{ route('admin.refund.reasons') }}" class="btn btn-sm btn-outline-primary me-2">
                            <i class="fa fa-cog"></i> {{__('messages.Refund Reasons')}}
                        </a>
                        <form method="GET" class="d-flex">
                            <select name="status" class="form-select form-select-sm me-2" onchange="this.form.submit()">
                                <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>All Status</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="processed" {{ request('status') === 'processed' ? 'selected' : '' }}>Processed</option>
                            </select>
                            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary btn-sm ms-2">Search</button>
                        </form>
                    </div>
                </div>
                <div class="card-body px-0">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th>{{__('messages.sl#')}}</th>
                                    <th>{{__('Order ID')}}</th>
                                    <th>{{__('Customer')}}</th>
                                    <th>{{__('Refund Amount')}}</th>
                                    <th>{{__('Restaurant Deduction')}}</th>
                                    <th>{{__('Reason')}}</th>
                                    <th>{{__('Status')}}</th>
                                    <th>{{__('Requested Date')}}</th>
                                    <th>{{__('messages.actions')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($refunds as $key => $refund)
                                <tr>
                                    <td>{{ $refunds->firstItem() + $key }}</td>
                                    <td>
                                        <a href="{{ route('admin.order.details', $refund->order_id) }}" class="text-decoration-none">
                                            #{{ $refund->order_id }}
                                        </a>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $refund->customer->f_name ?? 'N/A' }} {{ $refund->customer->l_name ?? '' }}</strong><br>
                                            <small class="text-muted">{{ $refund->customer->phone ?? 'N/A' }}</small>
                                        </div>
                                    </td>
                                    <td>{{ \App\CentralLogics\Helpers::format_currency($refund->refund_amount) }}</td>
                                    <td class="text-danger">{{ \App\CentralLogics\Helpers::format_currency($refund->restaurant_deduction_amount ?? 0) }}</td>
                                    <td>
                                        <span class="text-truncate d-inline-block" style="max-width: 150px;" title="{{ $refund->refund_reason }}">
                                            {{ $refund->refund_reason }}
                                        </span>
                                    </td>
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
                                    <td>{{ $refund->created_at->format('d M Y, h:i A') }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                Actions
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('admin.refund.show', $refund->id) }}">
                                                        <i class="fa fa-eye"></i> View Details
                                                    </a>
                                                </li>
                                                @if($refund->refund_status === 'pending')
                                                <li>
                                                    <button class="dropdown-item" onclick="processRefund({{ $refund->id }}, 'approve')">
                                                        <i class="fa fa-check text-success"></i> Approve
                                                    </button>
                                                </li>
                                                <li>
                                                    <button class="dropdown-item" onclick="processRefund({{ $refund->id }}, 'reject')">
                                                        <i class="fa fa-times text-danger"></i> Reject
                                                    </button>
                                                </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        {{__('No refund requests found')}}
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($refunds->hasPages())
                    <div class="d-flex justify-content-center">
                        {{ $refunds->appends(request()->query())->links() }}
                    </div>
                    @endif
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

@endsection

@push('js')
<script>
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

// Auto-submit status filter
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.querySelector('select[name="status"]');
    if (statusSelect) {
        statusSelect.addEventListener('change', function() {
            this.form.submit();
        });
    }
});
</script>
@endpush
