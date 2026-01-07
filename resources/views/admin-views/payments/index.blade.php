
@extends('layouts.dashboard-main')

@section('content')
<div class="container-fluid content-inner mt-n5 py-0">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h3 class="mb-0">{{ $stats['total'] }}</h3>
                    <small>Total Requests</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h3 class="mb-0">{{ $stats['pending'] }}</h3>
                    <small>Pending</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h3 class="mb-0">{{ $stats['approved'] }}</h3>
                    <small>Approved</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-secondary text-white">
                <div class="card-body text-center">
                    <h3 class="mb-0">{{ $stats['processing'] }}</h3>
                    <small>Processing</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h3 class="mb-0">{{ $stats['completed'] }}</h3>
                    <small>Completed</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <h3 class="mb-0">{{ $stats['rejected'] }}</h3>
                    <small>Rejected</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Amount Statistics -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card bg-gradient-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">{{ Helpers::format_currency($stats['total_amount']) }}</h4>
                            <small>Total Requested Amount</small>
                        </div>
                        <i class="fas fa-money-bill-wave fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-gradient-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">{{ Helpers::format_currency($stats['paid_amount']) }}</h4>
                            <small>Total Paid Amount</small>
                        </div>
                        <i class="fas fa-check-circle fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Payment Management Card -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">
                    <i class="fas fa-credit-card me-2"></i>
                    Payment Requests Management
                </h4>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary btn-sm" id="bulkApproveBtn" disabled>
                        <i class="fas fa-check"></i> Bulk Approve
                    </button>
                    <button class="btn btn-outline-danger btn-sm" id="bulkRejectBtn" disabled>
                        <i class="fas fa-times"></i> Bulk Reject
                    </button>
                    <a href="{{ route('admin.payments.export') }}" class="btn btn-outline-success btn-sm">
                        <i class="fas fa-download"></i> Export
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body">

            <!-- Filters -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <label class="form-label">Status Filter</label>
                    <select class="form-select" id="statusFilter">
                        <option value="all" {{ $status == 'all' ? 'selected' : '' }}>All Status</option>
                        <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="processing" {{ $status == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="complete" {{ $status == 'complete' ? 'selected' : '' }}>Completed</option>
                        <option value="rejected" {{ $status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date Filter</label>
                    <select class="form-select" id="dateFilter">
                        <option value="">All Dates</option>
                        <option value="today" {{ $dateRange == 'today' ? 'selected' : '' }}>Today</option>
                        <option value="week" {{ $dateRange == 'week' ? 'selected' : '' }}>This Week</option>
                        <option value="month" {{ $dateRange == 'month' ? 'selected' : '' }}>This Month</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Search</label>
                    <input type="text" class="form-control" id="searchInput" placeholder="Search vendor, amount, transaction ID..." value="{{ $searchTerm }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <button class="btn btn-primary w-100" id="applyFilters">
                        <i class="fas fa-search"></i> Filter
                    </button>
                </div>
            </div>

            <!-- Payment Requests Table -->
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th width="40">
                                <input type="checkbox" id="selectAll" class="form-check-input">
                            </th>
                            <th>ID</th>
                            <th>Vendor Details</th>
                            <th>Amount</th>
                            <th>Banking Details</th>
                            <th>Status</th>
                            <th>Payment Method</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($paymentRequests as $request)
                            <tr data-request-id="{{ $request->id }}">
                                <td>
                                    <input type="checkbox" class="form-check-input payment-checkbox" value="{{ $request->id }}" 
                                           {{ in_array($request->payment_status, ['pending', 'approved']) ? '' : 'disabled' }}>
                                </td>
                                <td>
                                    <strong>#{{ $request->id }}</strong>
                                    @if($request->txn_id)
                                        <br><small class="text-muted">{{ $request->txn_id }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar bg-primary text-white rounded-circle me-2" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                            {{ strtoupper(substr($request->vendor->f_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <strong>{{ $request->vendor->f_name }} {{ $request->vendor->l_name }}</strong>
                                            <br><small class="text-muted">{{ $request->vendor->phone }}</small>
                                            @if($request->vendor->email)
                                                <br><small class="text-muted">{{ $request->vendor->email }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong class="text-primary">{{ Helpers::format_currency($request->amount) }}</strong>
                                        @if($request->amount_paid && $request->amount_paid != $request->amount)
                                            <br><small class="text-success">Paid: {{ Helpers::format_currency($request->amount_paid) }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($request->bankingDetails)
                                        @if($request->payment_method === 'bank_transfer')
                                            <div class="small">
                                                <strong>{{ $request->bankingDetails->bank_name }}</strong><br>
                                                {{ $request->bankingDetails->account_holder_name }}<br>
                                                <span class="text-muted">****{{ substr($request->bankingDetails->account_number, -4) }}</span><br>
                                                <span class="badge bg-info">{{ $request->bankingDetails->ifsc_code }}</span>
                                            </div>
                                        @elseif($request->payment_method === 'upi')
                                            <div class="small">
                                                <strong>UPI Payment</strong><br>
                                                <span class="text-primary">{{ $request->bankingDetails->upi_id }}</span>
                                            </div>
                                        @endif
                                    @else
                                        <span class="text-muted small">Banking details not available</span>
                                    @endif
                                </td>
                                <td>
                                    @switch($request->payment_status)
                                        @case('pending')
                                            <span class="badge bg-warning">
                                                <i class="fas fa-clock"></i> Pending
                                            </span>
                                            @break
                                        @case('approved')
                                            <span class="badge bg-info">
                                                <i class="fas fa-thumbs-up"></i> Approved
                                            </span>
                                            @break
                                        @case('processing')
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-spinner"></i> Processing
                                            </span>
                                            @break
                                        @case('complete')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle"></i> Completed
                                            </span>
                                            @break
                                        @case('rejected')
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times-circle"></i> Rejected
                                            </span>
                                            @break
                                        @default
                                            <span class="badge bg-light text-dark">{{ ucfirst($request->payment_status) }}</span>
                                    @endswitch
                                </td>
                                <td>
                                    @if($request->payment_method)
                                        @switch($request->payment_method)
                                            @case('bank_transfer')
                                                <span class="badge bg-primary">
                                                    <i class="fas fa-university"></i> Bank Transfer
                                                </span>
                                                @break
                                            @case('upi')
                                                <span class="badge bg-success">
                                                    <i class="fas fa-mobile-alt"></i> UPI
                                                </span>
                                                @break
                                            @case('cash')
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-money-bill"></i> Cash
                                                </span>
                                                @break
                                            @case('cheque')
                                                <span class="badge bg-info">
                                                    <i class="fas fa-file-invoice"></i> Cheque
                                                </span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $request->payment_method)) }}</span>
                                        @endswitch
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="small">
                                        {{ $request->created_at->format('d M Y') }}<br>
                                        <span class="text-muted">{{ $request->created_at->format('h:i A') }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group-vertical btn-group-sm">
                                        @if($request->payment_status === 'pending')
                                            <button class="btn btn-outline-success btn-sm approve-btn" data-id="{{ $request->id }}" title="Approve">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn btn-outline-danger btn-sm reject-btn" data-id="{{ $request->id }}" title="Reject">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif
                                        
                                        @if(in_array($request->payment_status, ['approved', 'processing', 'pending']))
                                            <button class="btn btn-outline-primary btn-sm process-btn" data-pay-key="{{ $request->id }}" title="Process Payment">
                                                <i class="fas fa-credit-card"></i> Process
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3"></i>
                                        <h5>No payment requests found</h5>
                                        <p>There are no payment requests matching your criteria.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                        </table>
            </div>

            <!-- Pagination -->
            @if($paymentRequests->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $paymentRequests->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Payment Processing Modal -->
<div class="modal fade" id="payModal" tabindex="-1" aria-labelledby="payModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="payModalLabel">
                    <i class="fas fa-credit-card me-2"></i>
                    Process Payment Request
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Approval Modal -->
<div class="modal fade" id="approvalModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Approve Payment Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="approvalForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Approval Notes</label>
                        <textarea class="form-control" name="remarks" rows="3" placeholder="Add approval notes (optional)"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Approve
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Rejection Modal -->
<div class="modal fade" id="rejectionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Payment Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectionForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="rejection_reason" rows="3" placeholder="Please provide a reason for rejection" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times"></i> Reject
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('javascript')
<script>
    $(document).ready(function() {
        // Export functionality
        $('#exportButton').on('click', function() {
            let url = new URL(window.location.href);
            url.pathname = "{{ route('admin.payments.export') }}";
            window.location.href = url.toString();
        });

        // Bulk operations
        $('#selectAll').on('change', function() {
            $('.payment-checkbox').prop('checked', this.checked);
            toggleBulkActions();
        });

        $('.payment-checkbox').on('change', function() {
            toggleBulkActions();
        });

        function toggleBulkActions() {
            const checkedCount = $('.payment-checkbox:checked').length;
            $('#bulkActions').toggle(checkedCount > 0);
        }

        // Bulk approve
        $('#bulkApprove').on('click', function() {
            const selectedIds = $('.payment-checkbox:checked').map(function() {
                return this.value;
            }).get();

            if (selectedIds.length === 0) {
                alert('Please select at least one payment request.');
                return;
            }

            if (confirm('Are you sure you want to approve selected payment requests?')) {
                bulkAction('approve', selectedIds);
            }
        });

        // Bulk reject
        $('#bulkReject').on('click', function() {
            const selectedIds = $('.payment-checkbox:checked').map(function() {
                return this.value;
            }).get();

            if (selectedIds.length === 0) {
                alert('Please select at least one payment request.');
                return;
            }

            if (confirm('Are you sure you want to reject selected payment requests?')) {
                const reason = prompt('Please enter rejection reason:');
                if (reason) {
                    bulkAction('reject', selectedIds, reason);
                }
            }
        });

        function bulkAction(action, ids, reason = null) {
            $.ajax({
                url: "{{ route('admin.payments.bulk-action') }}",
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    action: action,
                    ids: ids,
                    reason: reason
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('An error occurred while processing the request.');
                }
            });
        }

        // Individual approve action
        $('.approve-btn').on('click', function() {
            const id = $(this).data('id');
            $('#approvalForm').data('id', id);
            $('#approvalModal').modal('show');
        });

        // Individual reject action
        $('.reject-btn').on('click', function() {
            const id = $(this).data('id');
            $('#rejectionForm').data('id', id);
            $('#rejectionModal').modal('show');
        });

        // Process payment action (existing functionality)
        const payButtons = document.querySelectorAll('[data-pay-key]');
        const payModal = new bootstrap.Modal(document.getElementById('payModal'));

        payButtons.forEach((payButton) => {
            payButton.addEventListener('click', async () => {
                try {
                    const resp = await fetch(`{{ route('admin.payments.pay-form')}}?pay_key=${payButton.dataset.payKey}`);
                    if (!resp.ok) {
                        throw new Error(`HTTP error! Status: ${resp.status}`);
                    }
                    const result = await resp.json();
                    if(result.view != null){
                        payModal.show();
                        document.querySelector("#payModal .modal-body").innerHTML = result.view;
                        savePaymentRequest();
                    }
                } catch (error) {
                    console.error('Error fetching payment form:', error);
                    toastr.error('Error loading payment form');
                }
            });
        });

        // Approval form submit
        $('#approvalForm').on('submit', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            const remarks = $(this).find('[name="remarks"]').val();

            $.ajax({
                url: "{{ route('admin.payments.approve-request', '') }}/" + id,
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    remarks: remarks
                },
                success: function(response) {
                    if (response.success) {
                        $('#approvalModal').modal('hide');
                        toastr.success('Payment request approved successfully');
                        location.reload();
                    } else {
                        toastr.error('Error: ' + response.message);
                    }
                },
                error: function() {
                    toastr.error('An error occurred while approving the request.');
                }
            });
        });

        // Rejection form submit
        $('#rejectionForm').on('submit', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            const reason = $(this).find('[name="rejection_reason"]').val();

            $.ajax({
                url: "{{ route('admin.payments.reject-request', '') }}/" + id,
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    rejection_reason: reason
                },
                success: function(response) {
                    if (response.success) {
                        $('#rejectionModal').modal('hide');
                        toastr.success('Payment request rejected successfully');
                        location.reload();
                    } else {
                        toastr.error('Error: ' + response.message);
                    }
                },
                error: function() {
                    toastr.error('An error occurred while rejecting the request.');
                }
            });
        });
    });

    // Payment form submission function
    function savePaymentRequest() {
        const paymentForm = document.getElementById('paymentForm');
        if (!paymentForm) return;

        paymentForm.addEventListener('submit', async (event) => {
            try {
                event.preventDefault();
                const formData = new FormData(paymentForm);

                const resp = await fetch(paymentForm.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData,
                });

                if (!resp.ok) {
                    const error = await resp.json();
                    throw new Error(error.message || 'Payment processing failed');
                }

                const result = await resp.json();
                toastr.success(result.message || 'Payment processed successfully');
                paymentForm.reset();
                const payModal = bootstrap.Modal.getInstance(document.getElementById('payModal'));
                payModal.hide();
                location.reload();
            } catch (error) {
                toastr.error(error.message || 'An error occurred while processing payment');
                console.error(error.message);
            }
        });
    }
</script>
@endpush
