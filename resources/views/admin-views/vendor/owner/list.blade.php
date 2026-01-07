
@extends('layouts.dashboard-main')

@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-1">{{ $vendors->count() }}</h4>
                            <p class="mb-0">Total Owners</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-1">{{ $vendors->filter(function($v) { return $v->status == 1 && !$v->is_blocked; })->count() }}</h4>
                            <p class="mb-0">Active Owners</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-user-check fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-1">{{ $vendors->sum(function($v) { return $v->restaurants->count(); }) }}</h4>
                            <p class="mb-0">Total Restaurants</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-store fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-1">{{ $vendors->filter(function($v) { return $v->is_blocked; })->count() }}</h4>
                            <p class="mb-0">Blocked Owners</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-user-slash fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div>
       <div class="row">
          <div class="col-sm-12">
             <div class="card">
                <div class="card-header d-flex justify-content-between">
                   <div class="header-title">
                      <h4 class="card-title">Restaurant Owners List</h4>
                      <p class="card-category">Manage all restaurant owners and their businesses</p>
                   </div>
                   <div>
                        <span class="badge bg-info me-2">Total: {{ $vendors->count() }}</span>
                        <span class="badge bg-success me-2">Active: {{ $vendors->filter(function($v) { return $v->status == 1 && !$v->is_blocked; })->count() }}</span>
                        <span class="badge bg-danger">Blocked: {{ $vendors->filter(function($v) { return $v->is_blocked; })->count() }}</span>
                   </div>
                </div>

                <!-- Search and Filter Section -->
                <div class="card-body border-bottom">
                    <form method="GET" action="{{ route('admin.owner.list') }}" class="row g-3">
                        <div class="col-md-4">
                            <label for="search" class="form-label">Search Owners</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   placeholder="Search by name, email, or phone..." 
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="status" class="form-label">Filter by Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="all" {{ request('status') == 'all' || !request('status') ? 'selected' : '' }}>All Owners</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active Owners</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive Owners</option>
                                <option value="blocked" {{ request('status') == 'blocked' ? 'selected' : '' }}>Blocked Owners</option>
                                <option value="has_restaurants" {{ request('status') == 'has_restaurants' ? 'selected' : '' }}>Has Restaurants</option>
                                <option value="no_restaurants" {{ request('status') == 'no_restaurants' ? 'selected' : '' }}>No Restaurants</option>
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-search"></i> Search
                            </button>
                            <a href="{{ route('admin.owner.list') }}" class="btn btn-secondary me-2">
                                <i class="fas fa-times"></i> Clear
                            </a>
                            <a href="{{ route('admin.owner.export') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}" 
                               class="btn btn-success">
                                <i class="fas fa-download"></i> Export CSV
                            </a>
                        </div>
                    </form>
                </div>
                <div class="card-body px-0">
                   @if($vendors->count() > 0)
                   <div class="table-responsive">
                      <table id="datatable" class="table" role="grid" data-toggle="data-table">
                         <thead>
                            <tr class="ligth">
                                <th>SL</th>
                                <th >Name </th>
                                <th >Email</th>
                                <th >Phone</th>
                                <th >Restaurants</th>
                                <th >Status</th>
                               <th style="min-width: 150px">ACTION</th>
                            </tr>
                         </thead>
                         <tbody>
                            @foreach($vendors as $vendor)
                            <tr>
                                <td>{{$loop->index + 1}}</td>
                                <td>
                                    <span class="bg-gradient-light text-dark">
                                        {{Str::ucfirst($vendor->f_name)." ".Str::ucfirst($vendor->l_name) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="bg-gradient-light text-dark">
                                        {{ $vendor->email }}
                                    </span>
                                </td>
                                <td>
                                    <span class="bg-gradient-light text-dark">
                                     {{ $vendor->phone }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ $vendor->restaurants->count() }} {{ $vendor->restaurants->count() == 1 ? 'Restaurant' : 'Restaurants' }}
                                    </span>
                                    @if($vendor->restaurants->where('status', 1)->count() > 0)
                                        <br><small class="text-success">{{ $vendor->restaurants->where('status', 1)->count() }} Active</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        @if($vendor->is_blocked)
                                            <span class="badge bg-danger">Blocked</span>
                                            @if($vendor->blocked_reason)
                                                <small class="text-muted">{{ $vendor->blocked_reason }}</small>
                                            @endif
                                        @else
                                            @if($vendor->status == 1)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        @endif
                                        
                                        @if($vendor->restaurants->count() > 0)
                                            <small class="text-info">Has Restaurants</small>
                                        @else
                                            <small class="text-warning">No Restaurants</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="btn--container justify-content-center flex-wrap">
                                        <!-- View Button -->
                                        <a class="btn btn-sm btn--success btn-outline-success action-btn" title="View Owner Details" href="{{route('admin.owner.view',$vendor->id)}}">
                                            <svg class="icon-16" width="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M15.5849 12.0001C15.5849 14.0302 13.9427 15.6724 11.9126 15.6724C9.88253 15.6724 8.24023 14.0302 8.24023 12.0001C8.24023 9.96999 9.88253 8.32769 11.9126 8.32769C13.9427 8.32769 15.5849 9.96999 15.5849 12.0001Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M11.9126 20.1895C16.0048 20.1895 19.8224 16.9708 21.5493 12.82C22.1502 11.4177 22.1502 10.5823 21.5493 9.17999C19.8224 5.02916 16.0048 1.81055 11.9126 1.81055C7.82041 1.81055 4.00283 5.02916 2.27593 9.17999C1.67503 10.5823 1.67503 11.4177 2.27593 12.82C4.00283 16.9708 7.82041 20.1895 11.9126 20.1895Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            </svg>
                                        </a>

                                        <!-- Edit Button -->
                                        <a class="btn btn-sm btn--primary btn-outline-primary action-btn" title="Edit Owner" href="{{route('admin.owner.edit',$vendor->id)}}">
                                            <svg class="icon-16" width="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82812 10.921Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M15.1655 4.60254L19.7315 9.16854" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            </svg>
                                        </a>

                                        @if(!$vendor->is_blocked)
                                            <!-- Access Button -->
                                            <a class="btn btn-sm btn--info btn-outline-info action-btn" title="Access Owner Account" href="{{route('admin.owner.access',$vendor->id)}}" onclick="return confirm('Are you sure you want to access this owner account?')">
                                                <svg class="icon-16" width="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M12 1L3 9L12 17L21 9L12 1Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    <path d="M12 13V7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                </svg>
                                            </a>

                                            <!-- Status Toggle Button -->
                                            @if($vendor->status == 1)
                                                <a class="btn btn-sm btn--warning btn-outline-warning action-btn" title="Deactivate Owner" href="{{route('admin.owner.status',[$vendor->id, 0])}}" onclick="return confirm('Are you sure you want to deactivate this owner?')">
                                                    <svg class="icon-16" width="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        <path d="M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    </svg>
                                                </a>
                                            @else
                                                <a class="btn btn-sm btn--success btn-outline-success action-btn" title="Activate Owner" href="{{route('admin.owner.status',[$vendor->id, 1])}}" onclick="return confirm('Are you sure you want to activate this owner?')">
                                                    <svg class="icon-16" width="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M20 6L9 17L4 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    </svg>
                                                </a>
                                            @endif

                                            <!-- Block Button -->
                                            <a class="btn btn-sm btn--danger btn-outline-danger action-btn" title="Block Owner" href="javascript:void(0)" onclick="blockOwner({{$vendor->id}})">
                                                <svg class="icon-16" width="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"></circle>
                                                    <path d="M4.93 4.93L19.07 19.07" stroke="currentColor" stroke-width="2"></path>
                                                </svg>
                                            </a>
                                        @else
                                            <!-- Unblock Button -->
                                            <a class="btn btn-sm btn--success btn-outline-success action-btn" title="Unblock Owner" href="{{route('admin.owner.unblock',$vendor->id)}}" onclick="return confirm('Are you sure you want to unblock this owner?')">
                                                <svg class="icon-16" width="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2" stroke="currentColor" stroke-width="2"></rect>
                                                    <circle cx="12" cy="16" r="1" stroke="currentColor" stroke-width="2"></circle>
                                                </svg>
                                            </a>
                                        @endif

                                        <!-- Restaurant Management Dropdown -->
                                        @if($vendor->restaurants->count() > 0)
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary action-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" title="Manage Restaurants">
                                                    <svg class="icon-16" width="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M19 21V5C19 4.4 18.6 4 18 4H6C5.4 4 5 4.4 5 5V21L12 18L19 21Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    </svg>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end" style="min-width: 280px;">
                                                    @foreach($vendor->restaurants as $restaurant)
                                                        <li>
                                                            <div class="dropdown-item-text">
                                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                                    <div class="flex-grow-1">
                                                                        <strong class="small d-block">{{ Str::limit($restaurant->name, 25) }}</strong>
                                                                        <div class="mt-1">
                                                                            @if($restaurant->is_blocked)
                                                                                <span class="badge bg-danger badge-sm">Blocked</span>
                                                                                @if($restaurant->blocked_reason)
                                                                                    <small class="text-muted d-block">{{ Str::limit($restaurant->blocked_reason, 30) }}</small>
                                                                                @endif
                                                                            @else
                                                                                @if($restaurant->status == 1)
                                                                                    <span class="badge bg-success badge-sm">Active</span>
                                                                                @else
                                                                                    <span class="badge bg-secondary badge-sm">Inactive</span>
                                                                                @endif
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="btn-group btn-group-sm w-100">
                                                                    @if($restaurant->is_blocked)
                                                                        <a href="{{route('admin.owner.restaurant-unblock', $restaurant->id)}}" class="btn btn-outline-success btn-xs" title="Unblock Restaurant" onclick="return confirm('Unblock this restaurant?')">
                                                                            <i class="fas fa-unlock"></i> Unblock
                                                                        </a>
                                                                    @else
                                                                        @if($restaurant->status == 1)
                                                                            <a href="{{route('admin.owner.restaurant-status',[$restaurant->id, 0])}}" class="btn btn-outline-warning btn-xs" title="Deactivate" onclick="return confirm('Deactivate this restaurant?')">
                                                                                <i class="fas fa-times"></i> Deactivate
                                                                            </a>
                                                                        @else
                                                                            <a href="{{route('admin.owner.restaurant-status',[$restaurant->id, 1])}}" class="btn btn-outline-success btn-xs" title="Activate" onclick="return confirm('Activate this restaurant?')">
                                                                                <i class="fas fa-check"></i> Activate
                                                                            </a>
                                                                        @endif
                                                                        <button class="btn btn-outline-danger btn-xs" title="Block Restaurant" onclick="blockRestaurant({{ $restaurant->id }}, '{{ addslashes($restaurant->name) }}')">
                                                                            <i class="fas fa-ban"></i> Block
                                                                        </button>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </li>
                                                        @if(!$loop->last)
                                                            <li><hr class="dropdown-divider"></li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            </div>
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
                               <path d="M12 12C14.7614 12 17 9.76142 17 7C17 4.23858 14.7614 2 12 2C9.23858 2 7 4.23858 7 7C7 9.76142 9.23858 12 12 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                               <path d="M20.5899 22C20.5899 18.13 16.7399 15 11.9999 15C7.25991 15 3.40991 18.13 3.40991 22" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                           </svg>
                       </div>
                       <h5 class="text-muted">No Restaurant Owners Found</h5>
                       <p class="text-muted">
                           @if(request('search') || request('status') !== 'all')
                               No owners match your search criteria. Try adjusting your filters.
                           @else
                               There are no restaurant owners in the system yet.
                           @endif
                       </p>
                       @if(request('search') || request('status') !== 'all')
                           <a href="{{ route('admin.owner.list') }}" class="btn btn-primary">
                               <i class="fas fa-times"></i> Clear Filters
                           </a>
                       @endif
                   </div>
                   @endif
                </div>
             </div>
          </div>
       </div>
    </div>
          </div>
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
.card-category {
    font-size: 0.875rem;
    color: #6c757d;
    margin-bottom: 0;
}
.icon-50 {
    width: 50px;
    height: 50px;
}
.icon-16 {
    width: 16px;
    height: 16px;
}
.btn-xs {
    padding: 0.25rem 0.5rem;
    font-size: 0.675rem;
}
.dropdown-item-text {
    padding: 0.25rem 1rem;
}
.badge-sm {
    font-size: 0.65rem;
}
.dropdown-menu {
    max-height: 400px;
    overflow-y: auto;
}
.dropdown-divider {
    margin: 0.25rem 0;
}
</style>
@endpush

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
    console.log('Modal element found:', modalElement);
    
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
    console.log('Form action set to:', form.action);
    
    // Try different Bootstrap modal approaches
    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
    } else if (typeof $ !== 'undefined' && $.fn.modal) {
        // jQuery Bootstrap modal
        $(modalElement).modal('show');
    } else {
        alert('Bootstrap modal not available');
    }
}

function blockRestaurant(restaurantId, restaurantName) {
    console.log('Block restaurant function called for ID:', restaurantId);
    const modalElement = document.getElementById('blockRestaurantModal');
    console.log('Restaurant modal element found:', modalElement);
    
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
    
    console.log('Restaurant form action set to:', form.action);
    
    // Try different Bootstrap modal approaches
    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
    } else if (typeof $ !== 'undefined' && $.fn.modal) {
        // jQuery Bootstrap modal
        $(modalElement).modal('show');
    } else {
        alert('Bootstrap modal not available');
    }
}

// Initialize Bootstrap dropdowns
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing dropdowns');
    
    // Check if Bootstrap is available
    if (typeof bootstrap !== 'undefined') {
        console.log('Bootstrap 5 detected');
        var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
        var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
            return new bootstrap.Dropdown(dropdownToggleEl);
        });
        console.log('Dropdowns initialized:', dropdownList.length);
    } else if (typeof $ !== 'undefined' && $.fn.dropdown) {
        console.log('Bootstrap 4/jQuery detected');
        $('.dropdown-toggle').dropdown();
    } else {
        console.log('No Bootstrap dropdown support detected');
    }
});
</script>   
@endpush


<!-- End Table -->
