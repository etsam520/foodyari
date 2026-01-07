@extends('layouts.dashboard-main')

@section('content')
<div class="container-fluid content-inner mt-n5 py-0">
    <div>
        <!-- Owner Information Card -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="header-title">
                            <h4 class="card-title">Owner Details</h4>
                            <div class="mt-2">
                                @if($vendor->is_blocked)
                                    <span class="badge bg-danger me-2">Blocked Owner</span>
                                    @if($vendor->blocked_reason)
                                        <small class="text-muted">Reason: {{ $vendor->blocked_reason }}</small>
                                    @endif
                                @else
                                    @if($vendor->status == 1)
                                        <span class="badge bg-success me-2">Active Owner</span>
                                    @else
                                        <span class="badge bg-secondary me-2">Inactive Owner</span>
                                    @endif
                                @endif
                                
                                @if($vendor->restaurants->count() > 0)
                                    <span class="badge bg-info">Has {{ $vendor->restaurants->count() }} Restaurant(s)</span>
                                @else
                                    <span class="badge bg-warning">No Restaurants</span>
                                @endif
                            </div>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('admin.owner.list') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                            <a href="{{ route('admin.owner.edit', $vendor->id) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i> Edit Owner
                            </a>
                            
                            @if(!$vendor->is_blocked)
                                <!-- Access Button -->
                                <a href="{{ route('admin.owner.access', $vendor->id) }}" class="btn btn-info btn-sm" onclick="return confirm('Are you sure you want to access this owner account?')">
                                    <i class="fas fa-sign-in-alt"></i> Access Account
                                </a>

                                <!-- Status Toggle Button -->
                                @if($vendor->status == 1)
                                    <a href="{{ route('admin.owner.status', [$vendor->id, 0]) }}" class="btn btn-warning btn-sm" onclick="return confirm('Are you sure you want to deactivate this owner?')">
                                        <i class="fas fa-times"></i> Deactivate
                                    </a>
                                @else
                                    <a href="{{ route('admin.owner.status', [$vendor->id, 1]) }}" class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to activate this owner?')">
                                        <i class="fas fa-check"></i> Activate
                                    </a>
                                @endif

                                <!-- Block Button -->
                                <button class="btn btn-danger btn-sm" onclick="blockOwner({{ $vendor->id }})">
                                    <i class="fas fa-ban"></i> Block Owner
                                </button>
                            @else
                                <!-- Unblock Button -->
                                <a href="{{ route('admin.owner.unblock', $vendor->id) }}" class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to unblock this owner?')">
                                    <i class="fas fa-unlock"></i> Unblock Owner
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Name:</strong></td>
                                        <td>{{ Str::ucfirst($vendor->f_name) . " " . Str::ucfirst($vendor->l_name) }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Email:</strong></td>
                                        <td>{{ $vendor->email ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Phone:</strong></td>
                                        <td>{{ $vendor->phone }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Joined:</strong></td>
                                        <td>{{ $vendor->created_at->format('d M, Y') }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <div class="row text-center">
                                    <div class="col-4">
                                        <div class="card bg-info text-white">
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $totalRestaurants }}</h5>
                                                <p class="card-text">Total Restaurants</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="card bg-success text-white">
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $activeRestaurants }}</h5>
                                                <p class="card-text">Active Restaurants</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="card bg-danger text-white">
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $vendor->restaurants->where('is_blocked', 1)->count() }}</h5>
                                                <p class="card-text">Blocked Restaurants</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row text-center mt-3">
                                    <div class="col-6">
                                        <div class="card bg-warning text-white">
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $totalFoods }}</h5>
                                                <p class="card-text">Total Food Items</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="card bg-secondary text-white">
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $vendor->restaurants->where('status', 0)->count() }}</h5>
                                                <p class="card-text">Inactive Restaurants</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Restaurants List -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Restaurants ({{ $totalRestaurants }})</h4>
                        </div>
                        @if($totalRestaurants > 0)
                        <div>
                            <a href="{{ route('admin.restaurant.add') }}" class="btn btn-success btn-sm">
                                <i class="fas fa-plus"></i> Add New Restaurant
                            </a>
                        </div>
                        @endif
                    </div>
                    <div class="card-body px-0">
                        @if($vendor->restaurants->count() > 0)
                        <div class="table-responsive">
                            <table class="table" role="grid">
                                <thead>
                                    <tr class="light">
                                        <th>SL</th>
                                        <th>Restaurant Name</th>
                                        <th>Zone</th>
                                        <th>Contact</th>
                                        <th>Food Items</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th style="min-width: 150px">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($vendor->restaurants as $restaurant)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <h6 class="mb-0">{{ $restaurant->name }}</h6>
                                                    @if($restaurant->description)
                                                        <small class="text-muted">{{ Str::limit($restaurant->description, 50) }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($restaurant->zone)
                                                <span class="badge bg-secondary">{{ $restaurant->zone->name }}</span>
                                            @else
                                                <span class="badge bg-warning">No Zone</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div>
                                                @if($restaurant->email)
                                                    <small><i class="fas fa-envelope"></i> {{ $restaurant->email }}</small><br>
                                                @endif
                                                @if($restaurant->phone)
                                                    <small><i class="fas fa-phone"></i> {{ $restaurant->phone }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $restaurant->foods->count() }} Items</span>
                                            @if($restaurant->foods->where('status', 1)->count() > 0)
                                                <br><small class="text-success">{{ $restaurant->foods->where('status', 1)->count() }} Active</small>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column gap-1">
                                                @if($restaurant->is_blocked)
                                                    <span class="badge bg-danger">Blocked</span>
                                                    @if($restaurant->blocked_reason)
                                                        <small class="text-muted">{{ Str::limit($restaurant->blocked_reason, 30) }}</small>
                                                    @endif
                                                @else
                                                    @if($restaurant->status == 1)
                                                        <span class="badge bg-success">Active</span>
                                                    @else
                                                        <span class="badge bg-secondary">Inactive</span>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <small>{{ $restaurant->created_at->format('d M, Y') }}</small>
                                        </td>
                                        <td>
                                            <div class="btn--container justify-content-center flex-wrap">
                                                <!-- View Button -->
                                                <a class="btn btn-sm btn--success btn-outline-success action-btn" 
                                                   title="View Restaurant" 
                                                   href="{{ route('admin.restaurant.view', $restaurant->id) }}">
                                                    <svg class="icon-16" width="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M15.5849 12.0001C15.5849 14.0302 13.9427 15.6724 11.9126 15.6724C9.88253 15.6724 8.24023 14.0302 8.24023 12.0001C8.24023 9.96999 9.88253 8.32769 11.9126 8.32769C13.9427 8.32769 15.5849 9.96999 15.5849 12.0001Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        <path d="M11.9126 20.1895C16.0048 20.1895 19.8224 16.9708 21.5493 12.82C22.1502 11.4177 22.1502 10.5823 21.5493 9.17999C19.8224 5.02916 16.0048 1.81055 11.9126 1.81055C7.82041 1.81055 4.00283 5.02916 2.27593 9.17999C1.67503 10.5823 1.67503 11.4177 2.27593 12.82C4.00283 16.9708 7.82041 20.1895 11.9126 20.1895Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    </svg>
                                                </a>

                                                <!-- Edit Button -->
                                                <a class="btn btn-sm btn--primary btn-outline-primary action-btn" 
                                                   title="Edit Restaurant" 
                                                   href="{{ route('admin.restaurant.edit', $restaurant->id) }}">
                                                    <svg class="icon-16" width="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82912 10.921Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        <path d="M15.1655 4.60254L19.7315 9.16854" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    </svg>
                                                </a>

                                                @if($restaurant->is_blocked)
                                                    <!-- Unblock Restaurant Button -->
                                                    <a href="{{ route('admin.owner.restaurant-unblock', $restaurant->id) }}" 
                                                       class="btn btn-sm btn--success btn-outline-success action-btn" 
                                                       title="Unblock Restaurant" 
                                                       onclick="return confirm('Are you sure you want to unblock this restaurant?')">
                                                        <svg class="icon-16" width="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2" stroke="currentColor" stroke-width="2"></rect>
                                                            <circle cx="12" cy="16" r="1" stroke="currentColor" stroke-width="2"></circle>
                                                        </svg>
                                                    </a>
                                                @else
                                                    <!-- Status Toggle Button -->
                                                    @if($restaurant->status == 1)
                                                        <a href="{{ route('admin.owner.restaurant-status', [$restaurant->id, 0]) }}" 
                                                           class="btn btn-sm btn--warning btn-outline-warning action-btn" 
                                                           title="Deactivate Restaurant" 
                                                           onclick="return confirm('Are you sure you want to deactivate this restaurant?')">
                                                            <svg class="icon-16" width="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                                <path d="M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                            </svg>
                                                        </a>
                                                    @else
                                                        <a href="{{ route('admin.owner.restaurant-status', [$restaurant->id, 1]) }}" 
                                                           class="btn btn-sm btn--success btn-outline-success action-btn" 
                                                           title="Activate Restaurant" 
                                                           onclick="return confirm('Are you sure you want to activate this restaurant?')">
                                                            <svg class="icon-16" width="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M20 6L9 17L4 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                            </svg>
                                                        </a>
                                                    @endif

                                                    <!-- Block Restaurant Button -->
                                                    <button class="btn btn-sm btn--danger btn-outline-danger action-btn" 
                                                            title="Block Restaurant" 
                                                            onclick="blockRestaurant({{ $restaurant->id }}, '{{ addslashes($restaurant->name) }}')">
                                                        <svg class="icon-16" width="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"></circle>
                                                            <path d="M4.93 4.93L19.07 19.07" stroke="currentColor" stroke-width="2"></path>
                                                        </svg>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <svg class="icon-50 text-muted" width="50" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3 9L12 2L21 9V20C21 20.5304 20.7893 21.0391 20.4142 21.4142C20.0391 21.7893 19.5304 22 19 22H5C4.46957 22 3.96086 21.7893 3.58579 21.4142C3.21071 21.0391 3 20.5304 3 20V9Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M9 22V12H15V22" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </div>
                            <h5 class="text-muted">No Restaurants Found</h5>
                            <p class="text-muted">This owner doesn't have any restaurants yet.</p>
                            <a href="{{ route('admin.restaurant.add') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add First Restaurant
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Block Owner Modal -->
<div class="modal fade" id="blockOwnerModal" tabindex="-1" aria-labelledby="blockOwnerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="blockOwnerModalLabel">Block Owner</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="blockOwnerForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Owner:</label>
                        <p class="fw-bold">{{ Str::ucfirst($vendor->f_name) . " " . Str::ucfirst($vendor->l_name) }}</p>
                    </div>
                    <div class="mb-3">
                        <label for="blockReason" class="form-label">Reason for blocking (optional)</label>
                        <textarea class="form-control" id="blockReason" name="reason" rows="3" placeholder="Enter reason for blocking this owner..."></textarea>
                    </div>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Warning:</strong> Blocking this owner will also deactivate all their restaurants.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Block Owner</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Block Restaurant Modal -->
<div class="modal fade" id="blockRestaurantModal" tabindex="-1" aria-labelledby="blockRestaurantModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="blockRestaurantModalLabel">Block Restaurant</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="blockRestaurantForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Restaurant:</label>
                        <p class="fw-bold" id="restaurantNameDisplay"></p>
                    </div>
                    <div class="mb-3">
                        <label for="blockRestaurantReason" class="form-label">Reason for blocking (optional)</label>
                        <textarea class="form-control" id="blockRestaurantReason" name="reason" rows="3" placeholder="Enter reason for blocking this restaurant..."></textarea>
                    </div>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Warning:</strong> Blocking this restaurant will also deactivate it.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Block Restaurant</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('javascript')
<script>
function blockOwner(ownerId) {
    console.log('Block owner function called for ID:', ownerId);
    const modalElement = document.getElementById('blockOwnerModal');
    
    if (!modalElement) {
        alert('Owner block modal not found!');
        return;
    }
    
    const form = document.getElementById('blockOwnerForm');
    
    if (!form) {
        alert('Owner block form not found!');
        return;
    }
    
    form.action = "{{ route('admin.owner.block', '') }}/" + ownerId;
    
    // Try different Bootstrap modal approaches
    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
    } else if (typeof $ !== 'undefined' && $.fn.modal) {
        $(modalElement).modal('show');
    } else {
        alert('Bootstrap modal not available');
    }
}

function blockRestaurant(restaurantId, restaurantName) {
    console.log('Block restaurant function called for ID:', restaurantId);
    const modalElement = document.getElementById('blockRestaurantModal');
    
    if (!modalElement) {
        alert('Restaurant block modal not found!');
        return;
    }
    
    const form = document.getElementById('blockRestaurantForm');
    const nameDisplay = document.getElementById('restaurantNameDisplay');
    
    if (!form) {
        alert('Restaurant block form not found!');
        return;
    }
    
    // Set form action and restaurant name
    form.action = "{{ route('admin.owner.restaurant-block', '') }}/" + restaurantId;
    if (nameDisplay) {
        nameDisplay.textContent = restaurantName;
    }
    
    // Try different Bootstrap modal approaches
    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
    } else if (typeof $ !== 'undefined' && $.fn.modal) {
        $(modalElement).modal('show');
    } else {
        alert('Bootstrap modal not available');
    }
}

function toggleStatus(restaurantId, currentStatus) {
    const newStatus = currentStatus == 1 ? 0 : 1;
    const statusText = newStatus == 1 ? 'activate' : 'deactivate';
    
    if (confirm(`Are you sure you want to ${statusText} this restaurant?`)) {
        window.location.href = `{{ route('admin.owner.restaurant-status', ['id' => ':id', 'status' => ':status']) }}`
            .replace(':id', restaurantId)
            .replace(':status', newStatus);
    }
}
</script>
@endpush
@endsection

@push('styles')
<style>
.card {
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
.action-btn {
    margin: 2px;
    border-radius: 5px;
}
.badge {
    font-size: 0.75rem;
}
.icon-50 {
    width: 50px;
    height: 50px;
}
.icon-16 {
    width: 16px;
    height: 16px;
}
.table th {
    border-top: none;
    font-weight: 600;
}
.btn--container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 3px;
}
.header-title .badge {
    margin-right: 0.5rem;
}
</style>
@endpush
