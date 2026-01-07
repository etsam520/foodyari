
 @extends('layouts.dashboard-main')
{{-- @dd('lkfjd'); --}}
@push('css')
<style>
.restaurant-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    overflow: hidden;
    margin-bottom: 1.5rem;
}

.restaurant-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.15);
}

.restaurant-card-header {
    background: #056e0545;
    padding: 1.5rem;
    position: relative;
    color: white;
}

.restaurant-logo-wrapper {
    position: relative;
    display: inline-block;
}

.restaurant-logo {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    border: 4px solid white;
    object-fit: cover;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.restaurant-info {
    flex: 1;
    margin-left: 1.5rem;
}

.restaurant-name {
   color: #034203;
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    text-shadow: 1px 1px 3px rgba(0,0,0,0.3);
}

.restaurant-address {
    opacity: 0.9;
    font-size: 0.95rem;
    margin-bottom: 0.5rem;
    color: #000000d6;
}

.restaurant-meta {
    display: flex;
    gap: 1rem;
    font-size: 0.9rem;
    opacity: 0.8;
    color: #000000d6;
}

.status-badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-active {
    background: #28a745;
    color: white;
}

.status-inactive {
    background: #dc3545;
    color: white;
}

.restaurant-card-body {
    padding: 1.5rem;
}

.qr-section {
    text-align: center;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 10px;
    margin-bottom: 1rem;
}

.qr-code {
    width: 80px;
    height: 80px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.vendor-info {
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
}

.vendor-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #056e0545;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    margin-right: 0.75rem;
}

.vendor-details h6 {
    margin: 0;
    font-weight: 600;
    color: #333;
}

.vendor-details small {
    color: #666;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
}

.btn-action {
    border: none;
    border-radius: 8px;
    padding: 0.5rem 1rem;
    font-size: 0.85rem;
    font-weight: 500;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
}

.btn-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.btn-view {
    background: #007bff;
    color: white;
}

.btn-edit {
    background: #ffc107;
    color: #212529;
}

.btn-access {
    background: #28a745;
    color: white;
}

.list-controls {
    background: white;
    padding: 1.5rem;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    margin-bottom: 2rem;
}

.search-box {
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 0.75rem 1rem;
    font-size: 0.95rem;
    transition: border-color 0.3s ease;
}

.search-box:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

/* .view-toggle {
    display: flex;
    background: #f8f9fa;
    border-radius: 8px;
    overflow: hidden;
}

.view-toggle button {
    border: none;
    background: transparent;
    padding: 0.5rem 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.view-toggle button.active {
    background: #667eea;
    color: white;
}

.toggle-switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
}

.toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
} */

.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: .4s;
    border-radius: 34px;
}

/* .slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
} */

input:checked + .slider {
    background-color: #28a745;
}

input:focus + .slider {
    box-shadow: 0 0 1px #28a745;
}

input:checked + .slider:before {
    transform: translateX(26px);
}

.toggle-label {
    margin-left: 10px;
    font-size: 0.9rem;
    font-weight: 500;
}
.toggle-switch input:disabled + .slider {
    opacity: 0.6;
    cursor: not-allowed;
}

.toggle-switch input:disabled + .slider:before {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

@media (max-width: 768px) {
    .restaurant-card-header {
        flex-direction: column;
        text-align: center;
    }
    
    .restaurant-info {
        margin-left: 0;
        margin-top: 1rem;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .btn-action {
        justify-content: center;
    }
}
</style>
@endpush

@section('content')
<div class="container-fluid content-inner mt-n5 py-0">
    <!-- Header Controls -->
    <div class="list-controls">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h4 class="mb-0">
                    <i class="fas fa-store me-2 text-primary"></i>
                    Restaurant Management
                </h4>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{route('admin.restaurant.add')}}" class="btn btn-primary me-2">
                    <i class="fas fa-plus me-1"></i>Add Restaurant
                </a>
                <a href="{{route('admin.restaurant.sort')}}" class="btn btn-outline-secondary">
                    <i class="fas fa-sort me-1"></i>Sort
                </a>
            </div>
        </div>
        
        <div class="row mt-3">
            <div class="col-md-8">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text" id="restaurantSearch" class="form-control search-box border-start-0" 
                           placeholder="Search restaurants by name, address, or owner...">
                </div>
            </div>
            <div class="col-md-4">
                <div class="view-toggle">
                    <button type="button" class="active" onclick="toggleView('card')">
                        <i class="fas fa-th-large me-1"></i>Cards
                    </button>
                    <button type="button" onclick="toggleView('table')">
                        <i class="fas fa-table me-1"></i>Table
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Card View -->
    <div id="cardView" class="row">
        @foreach ($restaurants as $restaurant)
            @php
                $address = json_decode($restaurant->address);
                $restaurantLink = url('/restaurant/' . $restaurant->id);
                // $qrbase64 = App\CentralLogics\Helpers::qrGenerate($restaurant->name, $restaurantLink);
                $vendorInitial = strtoupper(substr($restaurant->vendor->f_name, 0, 1));
            @endphp
            
            <div class="col-lg-6 col-xl-4 restaurant-item" 
                 data-name="{{ strtolower($restaurant->name) }}" 
                 data-address="{{ strtolower($address->street ?? '') }} {{ strtolower($address->city ?? '') }}"
                 data-owner="{{ strtolower($restaurant->vendor->f_name . ' ' . $restaurant->vendor->l_name) }}">
                <div class="restaurant-card">
                    <!-- Header with gradient background -->
                    <div class="restaurant-card-header d-flex align-items-center">
                        <div class="restaurant-logo-wrapper">
                            <img src="{{ asset('restaurant/' . $restaurant->logo) }}" 
                                 alt="{{ $restaurant->name }}" 
                                 class="restaurant-logo"
                                 onerror="this.src='{{ asset('assets/images/icons/foodYariLogo.png') }}'">
                        </div>
                        
                        <div class="restaurant-info">
                            <h5 class="restaurant-name">{{ $restaurant->name }}</h5>
                            <div class="restaurant-address">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                {{ Str::ucfirst($address->street ?? '') }} {{ Str::ucfirst($address->city ?? '') }} - {{ $address->pincode ?? '' }}
                            </div>
                            <div class="restaurant-meta">
                                <span><i class="fas fa-phone me-1"></i>{{ $restaurant->phone }}</span>
                            </div>
                        </div>
                        
                        <span class="status-badge {{ $restaurant->status === 1 ? 'status-active' : 'status-inactive' }}">
                            {{ $restaurant->status === 1 ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    
                    <!-- Body with details and QR -->
                    <div class="restaurant-card-body">
                        <div class="row">
                            <div class="col-8">
                                <!-- Vendor Info -->
                                <div class="vendor-info">
                                    <div class="vendor-avatar">
                                        {{ $vendorInitial }}
                                    </div>
                                    <div class="vendor-details">
                                        <h6>{{ $restaurant->vendor->f_name }} {{ $restaurant->vendor->l_name }}</h6>
                                        <small class="text-muted">Restaurant Owner</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <!-- QR Code -->
                                <div class="qr-section">
                                    <svg class="qr-code" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                                        <rect width="100" height="100" fill="white"/>
                                        <!-- Top-left corner -->
                                        <rect x="5" y="5" width="25" height="25" fill="none" stroke="black" stroke-width="2"/>
                                        <rect x="10" y="10" width="15" height="15" fill="black"/>
                                        <!-- Top-right corner -->
                                        <rect x="70" y="5" width="25" height="25" fill="none" stroke="black" stroke-width="2"/>
                                        <rect x="75" y="10" width="15" height="15" fill="black"/>
                                        <!-- Bottom-left corner -->
                                        <rect x="5" y="70" width="25" height="25" fill="none" stroke="black" stroke-width="2"/>
                                        <rect x="10" y="75" width="15" height="15" fill="black"/>
                                        <!-- QR pattern blocks -->
                                        <rect x="35" y="5" width="5" height="5" fill="black"/>
                                        <rect x="45" y="5" width="5" height="5" fill="black"/>
                                        <rect x="55" y="5" width="5" height="5" fill="black"/>
                                        <rect x="35" y="15" width="5" height="5" fill="black"/>
                                        <rect x="50" y="15" width="5" height="5" fill="black"/>
                                        <rect x="60" y="15" width="5" height="5" fill="black"/>
                                        <rect x="40" y="25" width="5" height="5" fill="black"/>
                                        <rect x="55" y="25" width="5" height="5" fill="black"/>
                                        <rect x="5" y="35" width="5" height="5" fill="black"/>
                                        <rect x="15" y="35" width="5" height="5" fill="black"/>
                                        <rect x="25" y="35" width="5" height="5" fill="black"/>
                                        <rect x="35" y="35" width="5" height="5" fill="black"/>
                                        <rect x="50" y="35" width="5" height="5" fill="black"/>
                                        <rect x="70" y="35" width="5" height="5" fill="black"/>
                                        <rect x="85" y="35" width="5" height="5" fill="black"/>
                                        <rect x="10" y="45" width="5" height="5" fill="black"/>
                                        <rect x="25" y="45" width="5" height="5" fill="black"/>
                                        <rect x="40" y="45" width="5" height="5" fill="black"/>
                                        <rect x="60" y="45" width="5" height="5" fill="black"/>
                                        <rect x="75" y="45" width="5" height="5" fill="black"/>
                                        <rect x="90" y="45" width="5" height="5" fill="black"/>
                                        <rect x="5" y="55" width="5" height="5" fill="black"/>
                                        <rect x="20" y="55" width="5" height="5" fill="black"/>
                                        <rect x="35" y="55" width="5" height="5" fill="black"/>
                                        <rect x="55" y="55" width="5" height="5" fill="black"/>
                                        <rect x="80" y="55" width="5" height="5" fill="black"/>
                                        <rect x="35" y="70" width="5" height="5" fill="black"/>
                                        <rect x="50" y="70" width="5" height="5" fill="black"/>
                                        <rect x="65" y="70" width="5" height="5" fill="black"/>
                                        <rect x="80" y="70" width="5" height="5" fill="black"/>
                                        <rect x="40" y="80" width="5" height="5" fill="black"/>
                                        <rect x="55" y="80" width="5" height="5" fill="black"/>
                                        <rect x="70" y="80" width="5" height="5" fill="black"/>
                                        <rect x="90" y="80" width="5" height="5" fill="black"/>
                                        <rect x="35" y="90" width="5" height="5" fill="black"/>
                                        <rect x="50" y="90" width="5" height="5" fill="black"/>
                                        <rect x="75" y="90" width="5" height="5" fill="black"/>
                                        <rect x="90" y="90" width="5" height="5" fill="black"/>
                                    </svg>
                                    <small class="d-block mt-1 text-muted">QR Code</small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="action-buttons mt-3">
                            <a href="{{ route('admin.restaurant.view', $restaurant->id) }}" 
                               class="btn-action btn-view" title="View Details">
                                <i class="fas fa-eye"></i>
                                <span>View</span>
                            </a>
                            <a href="{{ route('admin.restaurant.edit', $restaurant->id) }}" 
                               class="btn-action btn-edit" title="Edit Restaurant">
                                <i class="fas fa-edit"></i>
                                <span>Edit</span>
                            </a>
                            <a href="{{ route('admin.restaurant.access', $restaurant->id) }}" 
                               target="_blank" class="btn-action btn-access" title="Access Dashboard">
                                <i class="fas fa-sign-in-alt"></i>
                                <span>Access</span>
                            </a>
                        </div>
                        
                        <!-- Status Toggle -->
                        <div class="status-toggle-wrapper">
                            <span class="status-toggle-label {{ $restaurant->status == 1 ? 'active' : 'inactive' }}" id="status-label-{{ $restaurant->id }}">
                                {{ $restaurant->status == 1 ? 'Active' : 'Inactive' }}
                            </span>
                            <label class="toggle-switch">
                                <input type="checkbox" 
                                       {{ $restaurant->status == 1 ? 'checked' : '' }}
                                       onchange="toggleStatus({{ $restaurant->id }}, this.checked)">
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Table View (Hidden by default) -->
    <div id="tableView" class="card" style="display: none;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Logo</th>
                            <th>Restaurant</th>
                            <th>Address</th>
                            <th>Phone</th>
                            <th>Owner</th>
                            <th>Status</th>
                            <th>QR Code</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($restaurants as $restaurant)
                            @php
                                $address = json_decode($restaurant->address);
                                $restaurantLink = url('/restaurant/' . $restaurant->id);
                            @endphp
                            <tr class="restaurant-item" 
                                data-name="{{ strtolower($restaurant->name) }}" 
                                data-address="{{ strtolower($address->street ?? '') }} {{ strtolower($address->city ?? '') }}"
                                data-owner="{{ strtolower($restaurant->vendor->f_name . ' ' . $restaurant->vendor->l_name) }}">
                                <td>
                                    <img src="{{ asset('restaurant/' . $restaurant->logo) }}" 
                                         alt="{{ $restaurant->name }}" 
                                         class="rounded-circle"
                                         width="50" height="50"
                                         style="object-fit: cover;"
                                         onerror="this.src='{{ asset('assets/images/icons/foodYariLogo.png') }}'">
                                </td>
                                <td>
                                    <strong>{{ $restaurant->name }}</strong>
                                </td>
                                <td>
                                    {{ Str::ucfirst($address->street ?? '') }} {{ Str::ucfirst($address->city ?? '') }} - {{ $address->pincode ?? '' }}
                                </td>
                                <td>{{ $restaurant->phone }}</td>
                                <td>{{ $restaurant->vendor->f_name }} {{ $restaurant->vendor->l_name }}</td>
                                <td>
                                    <label class="toggle-switch">
                                        <input type="checkbox" 
                                               {{ $restaurant->status == 1 ? 'checked' : '' }}
                                               onchange="toggleStatus({{ $restaurant->id }}, this.checked)">
                                        <span class="slider"></span>
                                    </label>
                                </td>
                                <td>
                                    <svg width="40" height="40" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" class="rounded">
                                        <rect width="100" height="100" fill="white"/>
                                        <rect x="5" y="5" width="25" height="25" fill="none" stroke="black" stroke-width="2"/>
                                        <rect x="10" y="10" width="15" height="15" fill="black"/>
                                        <rect x="70" y="5" width="25" height="25" fill="none" stroke="black" stroke-width="2"/>
                                        <rect x="75" y="10" width="15" height="15" fill="black"/>
                                        <rect x="5" y="70" width="25" height="25" fill="none" stroke="black" stroke-width="2"/>
                                        <rect x="10" y="75" width="15" height="15" fill="black"/>
                                        <rect x="35" y="5" width="5" height="5" fill="black"/>
                                        <rect x="45" y="5" width="5" height="5" fill="black"/>
                                        <rect x="55" y="5" width="5" height="5" fill="black"/>
                                        <rect x="40" y="45" width="5" height="5" fill="black"/>
                                        <rect x="60" y="45" width="5" height="5" fill="black"/>
                                        <rect x="50" y="70" width="5" height="5" fill="black"/>
                                        <rect x="80" y="70" width="5" height="5" fill="black"/>
                                    </svg>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.restaurant.view', $restaurant->id) }}" 
                                           class="btn btn-sm btn-primary" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.restaurant.edit', $restaurant->id) }}" 
                                           class="btn btn-sm btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('admin.restaurant.access', $restaurant->id) }}" 
                                           target="_blank" class="btn btn-sm btn-success" title="Access">
                                            <i class="fas fa-sign-in-alt"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
// Search functionality
document.getElementById('restaurantSearch').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const restaurants = document.querySelectorAll('.restaurant-item');
    
    restaurants.forEach(restaurant => {
        const name = restaurant.dataset.name;
        const address = restaurant.dataset.address;
        const owner = restaurant.dataset.owner;
        
        if (name.includes(searchTerm) || address.includes(searchTerm) || owner.includes(searchTerm)) {
            restaurant.style.display = '';
        } else {
            restaurant.style.display = 'none';
        }
    });
});

// View toggle functionality
function toggleView(view) {
    const cardView = document.getElementById('cardView');
    const tableView = document.getElementById('tableView');
    const buttons = document.querySelectorAll('.view-toggle button');
    
    buttons.forEach(btn => btn.classList.remove('active'));
    
    if (view === 'card') {
        cardView.style.display = 'flex';
        tableView.style.display = 'none';
        buttons[0].classList.add('active');
    } else {
        cardView.style.display = 'none';
        tableView.style.display = 'block';
        buttons[1].classList.add('active');
    }
}

// Status toggle functionality
function toggleStatus(restaurantId, isChecked) {
    const status = isChecked ? 1 : 0;
    const url = `{{ route('admin.restaurant.status', ['id' => ':id', 'status' => ':status']) }}`
        .replace(':id', restaurantId)
        .replace(':status', status);
    
    // Show loading state
    const checkbox = event.target;
    checkbox.disabled = true;
    
    fetch(url, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (response.ok) {
            // Update status badge in card view
            const card = checkbox.closest('.restaurant-item');
            if (card) {
                const statusBadge = card.querySelector('.status-badge');
                const statusLabel = document.getElementById(`status-label-${restaurantId}`);
                
                if (statusBadge) {
                    if (isChecked) {
                        statusBadge.className = 'status-badge status-active';
                        statusBadge.textContent = 'Active';
                    } else {
                        statusBadge.className = 'status-badge status-inactive';
                        statusBadge.textContent = 'Inactive';
                    }
                }
                
                if (statusLabel) {
                    if (isChecked) {
                        statusLabel.className = 'status-toggle-label active';
                        statusLabel.textContent = 'Active';
                    } else {
                        statusLabel.className = 'status-toggle-label inactive';
                        statusLabel.textContent = 'Inactive';
                    }
                }
            }
            

            
            // Show success message
            showToast('success', `Restaurant ${isChecked ? 'activated' : 'deactivated'} successfully!`);
        } else {
            // Revert checkbox state on error
            checkbox.checked = !isChecked;
            showToast('error', 'Failed to update restaurant status. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Revert checkbox state on error
        checkbox.checked = !isChecked;
        showToast('error', 'An error occurred. Please try again.');
    })
    .finally(() => {
        checkbox.disabled = false;
    });
}

// Toast notification function
function showToast(type, message) {
    toastr.options = {
        "positionClass": "toast-bottom-right",
        "timeOut": "3000"
    };
    
    if (type === 'success') {
        toastr.success(message);
    } else if (type === 'error') {
        toastr.error(message);
    } else if (type === 'warning') {
        toastr.warning(message);
    } else {
        toastr.info(message);
    }
}
</script>
@endsection
