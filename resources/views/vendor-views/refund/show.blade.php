@extends('vendor-views.layouts.dashboard-main')

@section('title', 'Refund Details')

@push('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="content container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm mb-2 mb-sm-0">
                <h1 class="page-header-title">Refund Request Details</h1>
            </div>
            <div class="col-sm-auto">
                <a href="{{ route('vendor.refund.index') }}" class="btn btn-outline-primary">
                    <i class="tio-arrow-backward"></i> Back to List
                </a>
            </div>
        </div>
    </div>
    <!-- End Page Header -->

    <div class="row">
        <div class="col-lg-8">
            <!-- Refund Information -->
            <div class="card mb-3">
                <div class="card-header">
                    <h4 class="card-header-title">Refund Information</h4>
                    <div class="ms-auto">
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
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Refund ID:</strong></td>
                                    <td>#{{ $refund->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Order ID:</strong></td>
                                    <td>
                                        <a href="{{ route('vendor.order.details', $refund->order_id) }}" class="text-decoration-none">
                                            #{{ $refund->order_id }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Restaurant Penalty | Refund Amount:</strong></td>
                                    <td class="{{ $refund->restaurant_deduction_amount > 0 ? 'text-danger' : 'text-muted' }} fw-bold">
                                        {{ \App\CentralLogics\Helpers::format_currency($refund->restaurant_deduction_amount ?? 0) }}
                                    </td>
                                </tr>
                               
                                <tr>
                                    <td><strong>Refund Type:</strong></td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst($refund->refund_type) }}</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
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
                                
                                @if($refund->transaction_reference)
                                <tr>
                                    <td><strong>Transaction Reference:</strong></td>
                                    <td>{{ $refund->transaction_reference }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            

            <!-- Refund Reason & Notes -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-header-title">Refund Details</h4>
                </div>
                <div class="card-body">
                    <!-- Refund Reason -->
                    <div class="mb-4">
                        <h6>Refund Reason:</h6>
                        <div class="alert alert-light">
                            {{ $refund->refund_reason }}
                        </div>
                    </div>

                    <!-- Customer Note -->
                    @if($refund->customer_note)
                    <div class="mb-4">
                        <h6>Customer Note:</h6>
                        <div class="alert alert-info">
                            {{ $refund->customer_note }}
                        </div>
                    </div>
                    @endif

                    <!-- Restaurant Deduction Reason -->
                    @if($refund->restaurant_deduction_reason && $refund->restaurant_deduction_amount > 0)
                    <div class="mb-4">
                        <h6>Penalty Reason:</h6>
                        <div class="alert alert-warning">
                            {{ $refund->restaurant_deduction_reason }}
                        </div>
                    </div>
                    @endif

                    <!-- Admin Note -->
                    @if($refund->admin_note && in_array($refund->refund_status, ['approved', 'rejected', 'processed']))
                    <div class="mb-4">
                        <h6>Admin Response:</h6>
                        <div class="alert alert-{{ $refund->refund_status === 'rejected' ? 'danger' : 'success' }}">
                            {{ $refund->admin_note }}
                        </div>
                    </div>
                    @endif

                    <!-- Vendor Comments Section -->
                    <div class="mb-4">
                        <h6>Restaurant Comments:</h6>
                        @if(isset($refund->refund_details['vendor_notes']) && count($refund->refund_details['vendor_notes']) > 0)
                            @foreach($refund->refund_details['vendor_notes'] as $note)
                            <div class="alert alert-secondary mb-2">
                                <div class="d-flex justify-content-between">
                                    <div>{{ $note['note'] }}</div>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($note['added_at'])->format('d M Y, h:i A') }}</small>
                                </div>
                                <small class="text-muted">by {{ $note['added_by'] }}</small>
                            </div>
                            @endforeach
                        @else
                            <p class="text-muted">No comments yet</p>
                        @endif

                        <!-- Add Comment Form -->
                        @if(in_array($refund->refund_status, ['pending', 'approved']))
                        <form id="addCommentForm" class="mt-3">
                            <div class="form-group">
                                <textarea name="vendor_note" class="form-control" rows="3" 
                                          placeholder="Add your comment about this refund request" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                Add Comment
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Order Summary -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-header-title">Order Summary</h4>
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
                                <span class="badge bg-secondary">
                                    {{ ucfirst(str_replace('_', ' ', $refund->order->order_status)) }}
                                </span>
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
@endsection

@push('script_2')
<script>
    $('#addCommentForm').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const textarea = form.find('textarea[name="vendor_note"]');
        const submitBtn = form.find('button[type="submit"]');
        
        if (!textarea.val().trim()) {
            toastr.error("Please enter your comment");
            return;
        }
        
        // Show loading state
        submitBtn.prop('disabled', true).html("Adding...");
        
        $.ajax({
            url: '{{ route("vendor.refund.add-comment", $refund->id) }}',
            method: 'POST',
            data: {
                vendor_note: textarea.val(),
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    toastr.success("Comment added successfully");
                    location.reload();
                } else {
                    toastr.error(response.message || "Something went wrong");
                }
            },
            error: function(xhr) {
                toastr.error("Something went wrong");
            },
            complete: function() {
                submitBtn.prop('disabled', false).html('Add Comment');
            }
        });
    });
</script>
@endpush
