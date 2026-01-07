@extends('layouts.dashboard-main')

@push('css')
<style>
    .contact-detail-card {
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        border: none;
    }
    
    .contact-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px 15px 0 0;
        padding: 2rem;
    }
    
    .status-timeline {
        position: relative;
        padding-left: 2rem;
    }
    
    .status-timeline::before {
        content: '';
        position: absolute;
        left: 0.5rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e9ecef;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 1.5rem;
    }
    
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -0.5rem;
        top: 0.5rem;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #6c757d;
        border: 3px solid white;
        box-shadow: 0 0 0 2px #e9ecef;
    }
    
    .timeline-item.active::before {
        background: #0d6efd;
        box-shadow: 0 0 0 2px #0d6efd;
    }
    
    .message-box {
        background: #f8f9fa;
        border-left: 4px solid #0d6efd;
        border-radius: 0 10px 10px 0;
        padding: 1.5rem;
        margin: 1rem 0;
    }
    
    .reply-box {
        background: #e8f5e8;
        border-left: 4px solid #28a745;
        border-radius: 0 10px 10px 0;
        padding: 1.5rem;
        margin: 1rem 0;
    }
    
    .contact-info-item {
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1rem;
        transition: transform 0.2s;
    }
    
    .contact-info-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .action-buttons .btn {
        margin-right: 0.5rem;
        margin-bottom: 0.5rem;
    }
    
    .reply-form {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        padding: 2rem;
        margin-top: 2rem;
    }
    
    .customer-badge {
        display: inline-flex;
        align-items: center;
        background: #e3f2fd;
        color: #1976d2;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 500;
    }
    
    .guest-badge {
        display: inline-flex;
        align-items: center;
        background: #f3e5f5;
        color: #7b1fa2;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 500;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.contact-us.index') }}">Contact Messages</a>
            </li>
            <li class="breadcrumb-item active">Message Details</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <div class="card contact-detail-card">
                <!-- Header -->
                <div class="contact-header">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h3 class="mb-2">{{ $contact->subject }}</h3>
                            <p class="mb-0 opacity-75">
                                <i class="fas fa-calendar-alt me-2"></i>
                                Submitted on {{ $contact->created_at->format('M d, Y \a\t h:i A') }}
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            @php
                                $statusColors = [
                                    'pending' => 'warning',
                                    'in_progress' => 'info', 
                                    'resolved' => 'success',
                                    'closed' => 'secondary'
                                ];
                                $color = $statusColors[$contact->status] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $color }} fs-6 px-3 py-2">
                                {{ $contact->status_label }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Message Content -->
                <div class="card-body">
                    <div class="message-box">
                        <h6 class="mb-3">
                            <i class="fas fa-comment-alt text-primary me-2"></i>
                            Customer Message
                        </h6>
                        <p class="mb-0 lh-lg">{{ $contact->message }}</p>
                    </div>

                    @if($contact->admin_reply)
                        <div class="reply-box">
                            <h6 class="mb-3">
                                <i class="fas fa-reply text-success me-2"></i>
                                Admin Reply
                                @if($contact->repliedBy)
                                    <small class="text-muted">by {{ $contact->repliedBy->name }}</small>
                                @endif
                            </h6>
                            <p class="mb-3 lh-lg">{{ $contact->admin_reply }}</p>
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>
                                Replied on {{ $contact->replied_at->format('M d, Y \a\t h:i A') }}
                            </small>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Reply Form -->
            @if(!$contact->admin_reply || $contact->status !== 'closed')
                <div class="reply-form">
                    <h5 class="mb-4">
                        <i class="fas fa-reply me-2"></i>
                        {{ $contact->admin_reply ? 'Update Reply' : 'Send Reply' }}
                    </h5>
                    
                    <form method="POST" action="{{ route('admin.contact-us.reply', $contact->id) }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Reply Message <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('admin_reply') is-invalid @enderror" 
                                      name="admin_reply" 
                                      rows="5" 
                                      placeholder="Type your reply message here..."
                                      required>{{ old('admin_reply', $contact->admin_reply) }}</textarea>
                            @error('admin_reply')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Update Status <span class="text-danger">*</span></label>
                                <select class="form-control @error('status') is-invalid @enderror" name="status" required>
                                    @foreach($statuses as $key => $label)
                                        <option value="{{ $key }}" 
                                                {{ old('status', $contact->status) == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-paper-plane me-2"></i>
                                {{ $contact->admin_reply ? 'Update Reply' : 'Send Reply' }}
                            </button>
                            <a href="{{ route('admin.contact-us.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to List
                            </a>
                        </div>
                    </form>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Contact Information -->
            <div class="contact-info-item">
                <h6 class="mb-3">
                    <i class="fas fa-user text-primary me-2"></i>
                    Contact Information
                </h6>
                
                <div class="mb-3">
                    <strong>{{ $contact->name }}</strong>
                    <div class="mt-1">
                        @if($contact->customer)
                            <span class="customer-badge">
                                <i class="fas fa-user-check me-1"></i>
                                Registered Customer
                            </span>
                        @else
                            <span class="guest-badge">
                                <i class="fas fa-user me-1"></i>
                                Guest User
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="mb-2">
                    <i class="fas fa-envelope text-muted me-2"></i>
                    <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                </div>
                
                <div class="mb-2">
                    <i class="fas fa-phone text-muted me-2"></i>
                    <a href="tel:{{ $contact->phone }}">{{ $contact->phone }}</a>
                </div>
                
                @if($contact->ip_address)
                    <div class="mb-2">
                        <i class="fas fa-globe text-muted me-2"></i>
                        <small class="text-muted">{{ $contact->ip_address }}</small>
                    </div>
                @endif
            </div>

            <!-- Status Timeline -->
            <div class="contact-info-item">
                <h6 class="mb-3">
                    <i class="fas fa-history text-primary me-2"></i>
                    Status Timeline
                </h6>
                
                <div class="status-timeline">
                    <div class="timeline-item {{ $contact->status == 'pending' ? 'active' : '' }}">
                        <h6 class="mb-1">Pending</h6>
                        <small class="text-muted">
                            {{ $contact->created_at->format('M d, Y h:i A') }}
                        </small>
                    </div>
                    
                    @if(in_array($contact->status, ['in_progress', 'resolved', 'closed']))
                        <div class="timeline-item {{ $contact->status == 'in_progress' ? 'active' : '' }}">
                            <h6 class="mb-1">In Progress</h6>
                            <small class="text-muted">Status updated</small>
                        </div>
                    @endif
                    
                    @if(in_array($contact->status, ['resolved', 'closed']))
                        <div class="timeline-item {{ $contact->status == 'resolved' ? 'active' : '' }}">
                            <h6 class="mb-1">Resolved</h6>
                            @if($contact->replied_at)
                                <small class="text-muted">
                                    {{ $contact->replied_at->format('M d, Y h:i A') }}
                                </small>
                            @endif
                        </div>
                    @endif
                    
                    @if($contact->status == 'closed')
                        <div class="timeline-item active">
                            <h6 class="mb-1">Closed</h6>
                            <small class="text-muted">Case closed</small>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="contact-info-item">
                <h6 class="mb-3">
                    <i class="fas fa-bolt text-primary me-2"></i>
                    Quick Actions
                </h6>
                
                <div class="action-buttons">
                    @if($contact->status != 'in_progress')
                        <form method="POST" action="{{ route('admin.contact-us.update-status', $contact->id) }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="status" value="in_progress">
                            <button type="submit" class="btn btn-outline-info btn-sm">
                                <i class="fas fa-play me-1"></i>Mark In Progress
                            </button>
                        </form>
                    @endif
                    
                    @if($contact->status != 'resolved')
                        <form method="POST" action="{{ route('admin.contact-us.update-status', $contact->id) }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="status" value="resolved">
                            <button type="submit" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-check me-1"></i>Mark Resolved
                            </button>
                        </form>
                    @endif
                    
                    @if($contact->status != 'closed')
                        <form method="POST" action="{{ route('admin.contact-us.update-status', $contact->id) }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="status" value="closed">
                            <button type="submit" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-archive me-1"></i>Close
                            </button>
                        </form>
                    @endif
                    
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="deleteContact()">
                        <i class="fas fa-trash me-1"></i>Delete
                    </button>
                </div>
            </div>

            @if($contact->customer)
                <!-- Customer History -->
                <div class="contact-info-item">
                    <h6 class="mb-3">
                        <i class="fas fa-history text-primary me-2"></i>
                        Customer History
                    </h6>
                    
                    <div class="small">
                        <div class="mb-2">
                            <strong>Total Messages:</strong> 
                            {{ \App\Models\ContactUs::where('customer_id', $contact->customer_id)->count() }}
                        </div>
                        <div class="mb-2">
                            <strong>Customer Since:</strong> 
                            {{ $contact->customer->created_at->format('M Y') }}
                        </div>
                        <div class="mb-2">
                            <strong>Orders:</strong> 
                            {{ $contact->customer->orders()->count() ?? 0 }}
                        </div>
                    </div>
                    
                    <a href="{{ route('admin.customer.view', $contact->customer_id) }}" 
                       class="btn btn-outline-primary btn-sm mt-2">
                        <i class="fas fa-user me-1"></i>View Customer Profile
                    </a>
                </div>
            @endif
        </div>
    </div>
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
                <div class="alert alert-warning">
                    <strong>Message Subject:</strong> {{ $contact->subject }}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="{{ route('admin.contact-us.delete', $contact->id) }}" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Message</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('javascript')
<script>
function deleteContact() {
    $('#deleteModal').modal('show');
}

$(document).ready(function() {
    // Auto-resize textarea
    $('textarea[name="admin_reply"]').on('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
});
</script>
@endpush