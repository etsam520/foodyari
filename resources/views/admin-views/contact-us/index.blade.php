@extends('layouts.dashboard-main')

@push('css')
<style>
    .stat-card {
        border-radius: 10px;
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        transition: transform 0.2s;
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
    }
    
    .status-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
    }
    
    .message-preview {
        max-width: 300px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    .action-buttons .btn {
        margin: 0 2px;
        padding: 5px 10px;
        font-size: 0.8rem;
    }
    
    .filters-card {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .contact-table {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .contact-table th {
        background-color: #f8f9fa;
        border: none;
        font-weight: 600;
        color: #495057;
    }
    
    .contact-table td {
        border: none;
        border-bottom: 1px solid #e9ecef;
        vertical-align: middle;
    }
    
    .unread-row {
        background-color: #fff3cd;
    }
    
    .bulk-actions {
        background: white;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 20px;
        display: none;
    }
    
    .bulk-actions.show {
        display: block;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Contact Us Messages</h2>
            <p class="text-muted mb-0">Manage customer inquiries and support requests</p>
        </div>
        <div>
            <a href="{{ route('admin.contact-us.export', request()->query()) }}" class="btn btn-outline-success">
                <i class="fas fa-download me-2"></i>Export
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card stat-card text-center">
                <div class="card-body">
                    <div class="text-primary mb-2">
                        <i class="fas fa-envelope" style="font-size: 2rem;"></i>
                    </div>
                    <h4 class="mb-1">{{ $stats['total'] }}</h4>
                    <p class="text-muted mb-0 small">Total Messages</p>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card stat-card text-center">
                <div class="card-body">
                    <div class="text-warning mb-2">
                        <i class="fas fa-clock" style="font-size: 2rem;"></i>
                    </div>
                    <h4 class="mb-1">{{ $stats['pending'] }}</h4>
                    <p class="text-muted mb-0 small">Pending</p>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card stat-card text-center">
                <div class="card-body">
                    <div class="text-info mb-2">
                        <i class="fas fa-hourglass-half" style="font-size: 2rem;"></i>
                    </div>
                    <h4 class="mb-1">{{ $stats['in_progress'] }}</h4>
                    <p class="text-muted mb-0 small">In Progress</p>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card stat-card text-center">
                <div class="card-body">
                    <div class="text-success mb-2">
                        <i class="fas fa-check-circle" style="font-size: 2rem;"></i>
                    </div>
                    <h4 class="mb-1">{{ $stats['resolved'] }}</h4>
                    <p class="text-muted mb-0 small">Resolved</p>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card stat-card text-center">
                <div class="card-body">
                    <div class="text-secondary mb-2">
                        <i class="fas fa-archive" style="font-size: 2rem;"></i>
                    </div>
                    <h4 class="mb-1">{{ $stats['closed'] }}</h4>
                    <p class="text-muted mb-0 small">Closed</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filters-card">
        <form method="GET" action="{{ route('admin.contact-us.index') }}" id="filterForm">
            <div class="row align-items-end">
                <div class="col-md-3 mb-3">
                    <label class="form-label small fw-bold">Search</label>
                    <input type="text" class="form-control" name="search" value="{{ request('search') }}" 
                           placeholder="Name, email, phone, subject...">
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label small fw-bold">Status</label>
                    <select class="form-control" name="status">
                        <option value="">All Status</option>
                        @foreach($statuses as $key => $label)
                            <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label small fw-bold">From Date</label>
                    <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label small fw-bold">To Date</label>
                    <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-3 mb-3">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search me-1"></i>Filter
                    </button>
                    <a href="{{ route('admin.contact-us.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i>Clear
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Bulk Actions -->
    <div class="bulk-actions" id="bulkActions">
        <form method="POST" action="{{ route('admin.contact-us.bulk-action') }}" id="bulkForm">
            @csrf
            <div class="row align-items-center">
                <div class="col-md-3">
                    <span class="fw-bold">
                        <span id="selectedCount">0</span> messages selected
                    </span>
                </div>
                <div class="col-md-4">
                    <select class="form-control" name="action" id="bulkAction">
                        <option value="">Choose Action</option>
                        <option value="update_status">Update Status</option>
                        <option value="delete">Delete</option>
                    </select>
                </div>
                <div class="col-md-3" id="statusSelect" style="display: none;">
                    <select class="form-control" name="status">
                        @foreach($statuses as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary" id="bulkSubmit">Apply</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Messages Table -->
    <div class="card contact-table">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th style="width: 50px;">
                                <input type="checkbox" id="selectAll" class="form-check-input">
                            </th>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Subject</th>
                            <th>Message</th>
                            <th>Status</th>
                            <th>Submitted</th>
                            <th style="width: 150px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($contacts as $contact)
                            <tr class="{{ !$contact->is_replied && $contact->status == 'pending' ? 'unread-row' : '' }}">
                                <td>
                                    <input type="checkbox" class="form-check-input row-checkbox" value="{{ $contact->id }}">
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $contact->name }}</strong>
                                        @if($contact->customer)
                                            <br><small class="text-success">
                                                <i class="fas fa-user-check"></i> Registered Customer
                                            </small>
                                        @else
                                            <br><small class="text-muted">
                                                <i class="fas fa-user"></i> Guest
                                            </small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="small">
                                        <div><i class="fas fa-envelope text-muted me-1"></i>{{ $contact->email }}</div>
                                        <div><i class="fas fa-phone text-muted me-1"></i>{{ $contact->phone }}</div>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-bold">{{ $contact->subject }}</span>
                                </td>
                                <td>
                                    <div class="message-preview" title="{{ $contact->message }}">
                                        {{ Str::limit($contact->message, 100) }}
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'pending' => 'warning',
                                            'in_progress' => 'info', 
                                            'resolved' => 'success',
                                            'closed' => 'secondary'
                                        ];
                                        $color = $statusColors[$contact->status] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $color }} status-badge">
                                        {{ $contact->status_label }}
                                    </span>
                                </td>
                                <td>
                                    <div class="small">
                                        <div>{{ $contact->created_at->format('M d, Y') }}</div>
                                        <div class="text-muted">{{ $contact->created_at->format('h:i A') }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.contact-us.show', $contact->id) }}" 
                                           class="btn btn-sm btn-outline-primary" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                onclick="deleteContact({{ $contact->id }})" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3"></i>
                                        <h5>No contact messages found</h5>
                                        <p>No messages match your current filters.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($contacts->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $contacts->appends(request()->query())->links() }}
        </div>
    @endif
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this contact message? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" id="deleteForm" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('javascript')
<script>
$(document).ready(function() {
    // Select All functionality
    $('#selectAll').change(function() {
        $('.row-checkbox').prop('checked', this.checked);
        updateBulkActions();
    });
    
    // Individual checkbox change
    $('.row-checkbox').change(function() {
        updateBulkActions();
        
        // Update select all checkbox
        const totalRows = $('.row-checkbox').length;
        const checkedRows = $('.row-checkbox:checked').length;
        $('#selectAll').prop('checked', totalRows === checkedRows);
    });
    
    // Bulk action change
    $('#bulkAction').change(function() {
        if ($(this).val() === 'update_status') {
            $('#statusSelect').show();
        } else {
            $('#statusSelect').hide();
        }
    });
    
    // Bulk form submission
    $('#bulkForm').submit(function(e) {
        const action = $('#bulkAction').val();
        const selectedIds = $('.row-checkbox:checked').map(function() {
            return this.value;
        }).get();
        
        if (!action) {
            e.preventDefault();
            alert('Please select an action');
            return;
        }
        
        if (selectedIds.length === 0) {
            e.preventDefault();
            alert('Please select at least one message');
            return;
        }
        
        // Add selected IDs to form
        selectedIds.forEach(id => {
            $('<input>').attr({
                type: 'hidden',
                name: 'selected_items[]',
                value: id
            }).appendTo('#bulkForm');
        });
        
        if (action === 'delete') {
            if (!confirm('Are you sure you want to delete the selected messages?')) {
                e.preventDefault();
            }
        }
    });
});

function updateBulkActions() {
    const checkedCount = $('.row-checkbox:checked').length;
    $('#selectedCount').text(checkedCount);
    
    if (checkedCount > 0) {
        $('#bulkActions').addClass('show');
    } else {
        $('#bulkActions').removeClass('show');
    }
}

function deleteContact(id) {
    $('#deleteForm').attr('action', `{{ route('admin.contact-us.index') }}/${id}`);
    $('#deleteModal').modal('show');
}
</script>
@endpush