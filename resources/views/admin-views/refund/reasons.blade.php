@extends('layouts.dashboard-main')

@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">{{__('messages.Refund Reason List')}}</h4>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addReasonModal">
                        <i class="fa fa-plus"></i> {{__('messages.Add New Refund Reason')}}
                    </button>
                </div>
                <div class="card-body px-0">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th>{{__('messages.sl#')}}</th>
                                    <th>{{__('Reason')}}</th>
                                    <th>{{__('User Type')}}</th>
                                    <th>{{__('messages.Status')}}</th>
                                    <th>{{__('Created By')}}</th>
                                    <th>{{__('Created Date')}}</th>
                                    <th>{{__('messages.actions')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reasons as $key => $reason)
                                <tr>
                                    <td>{{ $reasons->firstItem() + $key }}</td>
                                    <td>{{ $reason->reason }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ ucfirst($reason->user_type) }}</span>
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" 
                                                   id="status{{ $reason->id }}" 
                                                   {{ $reason->status ? 'checked' : '' }}
                                                   onchange="toggleStatus({{ $reason->id }})">
                                            <label class="form-check-label" for="status{{ $reason->id }}">
                                                {{ $reason->status ? 'Active' : 'Inactive' }}
                                            </label>
                                        </div>
                                    </td>
                                    <td>{{ $reason->createdBy->name ?? 'System' }}</td>
                                    <td>{{ $reason->created_at->format('d M Y') }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-danger" 
                                                onclick="deleteReason({{ $reason->id }})"
                                                title="Delete Reason">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        {{__('No refund reasons found')}}
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($reasons->hasPages())
                    <div class="d-flex justify-content-center">
                        {{ $reasons->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Reason Modal -->
<div class="modal fade" id="addReasonModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.refund.reasons.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">{{__('messages.Add New Refund Reason')}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="reason" class="form-label">{{__('Reason')}} <span class="text-danger">*</span></label>
                        <input type="text" name="reason" id="reason" class="form-control" required 
                               placeholder="Enter refund reason...">
                    </div>
                    
                    <div class="mb-3">
                        <label for="user_type" class="form-label">{{__('User Type')}} <span class="text-danger">*</span></label>
                        <select name="user_type" id="user_type" class="form-select" required>
                            <option value="">Select User Type</option>
                            <option value="customer">Customer</option>
                            <option value="admin">Admin</option>
                            <option value="restaurant">Restaurant</option>
                            <option value="delivery_man">Delivery Man</option>
                        </select>
                        <small class="text-muted">Who can use this reason for refund requests</small>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="status" id="status" checked>
                            <label class="form-check-label" for="status">
                                {{__('Active Status')}}
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">{{__('messages.Add Now')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteReasonModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{__('messages.Want to delete this refund reason ?')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this refund reason? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteReasonForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
<script>
function toggleStatus(reasonId) {
    fetch(`{{ route('admin.refund.reasons.toggle', '') }}/${reasonId}`, {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            const toast = document.createElement('div');
            toast.className = 'toast align-items-center text-white bg-success border-0 position-fixed';
            toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999;';
            toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">
                        ${data.message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" onclick="this.parentElement.parentElement.remove()"></button>
                </div>
            `;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Revert the toggle on error
        const checkbox = document.getElementById(`status${reasonId}`);
        checkbox.checked = !checkbox.checked;
    });
}

function deleteReason(reasonId) {
    const modal = new bootstrap.Modal(document.getElementById('deleteReasonModal'));
    const form = document.getElementById('deleteReasonForm');
    form.action = `{{ route('admin.refund.reasons.delete', '') }}/${reasonId}`;
    modal.show();
}

// Show success/error messages
@if(session('success'))
    document.addEventListener('DOMContentLoaded', function() {
        const toast = document.createElement('div');
        toast.className = 'toast align-items-center text-white bg-success border-0 position-fixed';
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999;';
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    {{ session('success') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" onclick="this.parentElement.parentElement.remove()"></button>
            </div>
        `;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 5000);
    });
@endif

@if(session('error'))
    document.addEventListener('DOMContentLoaded', function() {
        const toast = document.createElement('div');
        toast.className = 'toast align-items-center text-white bg-danger border-0 position-fixed';
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999;';
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    {{ session('error') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" onclick="this.parentElement.parentElement.remove()"></button>
            </div>
        `;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 5000);
    });
@endif
</script>
@endpush
