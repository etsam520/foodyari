@extends('layouts.dashboard-main')

@section('title', 'Referral System Configuration')

@push('css')
<style>
    .referral-config-table {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .config-row {
        border-bottom: 1px solid #e9ecef;
        padding: 15px 0;
    }
    
    .config-row:last-child {
        border-bottom: none;
    }
    
    .form-control-sm {
        height: 32px;
        padding: 4px 8px;
        font-size: 12px;
    }
    
    .btn-sm {
        padding: 4px 8px;
        font-size: 12px;
    }
    
    .reward-type-toggle {
        display: none;
    }
    
    .discount-type-wrapper {
        display: none;
    }
    
    .max-amount-wrapper {
        display: none;
    }
    
    .stats-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .add-config-btn {
        background: #28a745;
        color: white;
        border: none;
        border-radius: 50%;
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .add-config-btn:hover {
        background: #218838;
        transform: scale(1.1);
    }
    
    .remove-config-btn {
        background: #dc3545;
        color: white;
        border: none;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .remove-config-btn:hover {
        background: #c82333;
        transform: scale(1.1);
    }
    
    .form-label-sm {
        font-size: 0.75rem;
        font-weight: 600;
        color: #6c757d;
        margin-bottom: 0.25rem;
        display: block;
    }
</style>
@endpush

@section('content')
<div class="content container-fluid">
    <div class="row align-items-center mb-3">
        <div class="col-sm-6">
            <h1 class="page-header-title">Referral System Configuration</h1>
            <p class="text-muted">Configure rewards for referral sponsors and beneficiaries</p>
        </div>
        <div class="col-sm-6 text-end">
            <button type="button" class="btn btn-primary" onclick="saveConfigurations()">
                <i class="tio-save"></i> Save Configuration
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card">
                <h4 id="total-configs">{{ $configurations->count() }}</h4>
                <p class="mb-0">Total Configurations</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <h4 id="active-configs">{{ $configurations->where('is_active', true)->count() }}</h4>
                <p class="mb-0">Active Configurations</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <h4 id="sponsor-configs">{{ $configurations->where('user_type', 'sponsor')->count() }}</h4>
                <p class="mb-0">Sponsor Rewards</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <h4 id="beneficiary-configs">{{ $configurations->where('user_type', 'beneficiary')->count() }}</h4>
                <p class="mb-0">Beneficiary Rewards</p>
            </div>
        </div>
    </div>

    <!-- Configuration Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title mb-0">Reward Configurations</h4>
            <button type="button" class="add-config-btn" onclick="addConfigurationRow()" title="Add New Configuration">
                +
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <form id="referral-config-form">
                    @csrf
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Order Count</th>
                                <th>User Reward</th>
                                <th>Sponsor Reward</th>
                                <th>Discount Types</th>
                                <th>Max Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="configurations-container">
                            @foreach($configurations as $index => $config)
                            <tr class="config-row" data-index="{{ $index }}">
                                <td>
                                    <input type="number" name="configurations[{{ $index }}][order_limit]" 
                                           class="form-control form-control-sm" 
                                           value="{{ $config->order_limit }}" 
                                           min="1" required>
                                </td>
                                <td>
                                    <!-- User Reward Section -->
                                    <div class="mb-2">
                                        <select name="configurations[{{ $index }}][user_reward_type]" 
                                                class="form-control form-control-sm user-reward-type" 
                                                onchange="toggleUserDiscountType(this)" required>
                                            <option value="cashback" {{ $config->user_reward_type == 'cashback' ? 'selected' : '' }}>Cashback</option>
                                            <option value="discount" {{ $config->user_reward_type == 'discount' ? 'selected' : '' }}>Discount</option>
                                        </select>
                                    </div>
                                    <div>
                                        <input type="number" name="configurations[{{ $index }}][user_reward_value]" 
                                               class="form-control form-control-sm" 
                                               value="{{ $config->user_reward_value }}" 
                                               step="0.01" min="0" required
                                               placeholder="User reward amount">
                                    </div>
                                </td>
                                <td>
                                    <!-- Sponsor Reward Section -->
                                    <div class="mb-2">
                                        <select name="configurations[{{ $index }}][sponsor_reward_type]" 
                                                class="form-control form-control-sm sponsor-reward-type" 
                                                onchange="toggleSponsorDiscountType(this)" required>
                                            <option value="cashback" {{ $config->sponsor_reward_type == 'cashback' ? 'selected' : '' }}>Cashback</option>
                                            <option value="discount" {{ $config->sponsor_reward_type == 'discount' ? 'selected' : '' }}>Discount</option>
                                        </select>
                                    </div>
                                    <div>
                                        <input type="number" name="configurations[{{ $index }}][sponsor_reward_value]" 
                                               class="form-control form-control-sm" 
                                               value="{{ $config->sponsor_reward_value }}" 
                                               step="0.01" min="0" required
                                               placeholder="Sponsor reward amount">
                                    </div>
                                </td>
                                <td>
                                    <!-- User Discount Type -->
                                    <div class="mb-2">
                                        <label class="form-label-sm">User:</label>
                                        <div class="user-discount-type-wrapper" style="{{ $config->user_reward_type == 'discount' ? 'display: block;' : 'display: none;' }}">
                                            <select name="configurations[{{ $index }}][user_discount_type]" 
                                                    class="form-control form-control-sm">
                                                <option value="flat" {{ $config->user_discount_type == 'flat' ? 'selected' : '' }}>Flat</option>
                                                <option value="percentage" {{ $config->user_discount_type == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- Sponsor Discount Type -->
                                    <div>
                                        <label class="form-label-sm">Sponsor:</label>
                                        <div class="sponsor-discount-type-wrapper" style="{{ $config->sponsor_reward_type == 'discount' ? 'display: block;' : 'display: none;' }}">
                                            <select name="configurations[{{ $index }}][sponsor_discount_type]" 
                                                    class="form-control form-control-sm">
                                                <option value="flat" {{ $config->sponsor_discount_type == 'flat' ? 'selected' : '' }}>Flat</option>
                                                <option value="percentage" {{ $config->sponsor_discount_type == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                            </select>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="max-amount-wrapper" style="{{ $config->user_discount_type == 'percentage' ? 'display: block;' : 'display: none;' }}">
                                        <input type="number" name="configurations[{{ $index }}][max_amount]" 
                                               class="form-control form-control-sm" 
                                               value="{{ $config->max_amount }}" 
                                               step="0.01" min="0"
                                               placeholder="Max discount amount">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" 
                                               name="configurations[{{ $index }}][is_active]" 
                                               {{ $config->is_active ? 'checked' : '' }}>
                                        <label class="form-check-label">Active</label>
                                    </div>
                                </td>
                                <td>
                                    <button type="button" class="remove-config-btn" 
                                            onclick="removeConfigurationRow(this)" 
                                            title="Remove Configuration">
                                        ×
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </form>
            </div>

            @if($configurations->isEmpty())
            <div class="text-center py-4" id="empty-state">
                <img src="{{ asset('assets/admin/img/empty-box.png') }}" alt="No configurations" style="width: 100px; opacity: 0.6;">
                <p class="text-muted mt-3">No referral configurations found. Click the + button to add one.</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Configuration Row Template -->
<script type="text/template" id="config-row-template">
    <tr class="config-row" data-index="__INDEX__">
        <td>
            <input type="number" name="configurations[__INDEX__][order_limit]" 
                   class="form-control form-control-sm" 
                   value="1" min="1" required>
        </td>
        <td>
            <!-- User Reward Section -->
            <div class="mb-2">
                <select name="configurations[__INDEX__][user_reward_type]" 
                        class="form-control form-control-sm user-reward-type" 
                        onchange="toggleUserDiscountType(this)" required>
                    <option value="cashback">Cashback</option>
                    <option value="discount">Discount</option>
                </select>
            </div>
            <div>
                <input type="number" name="configurations[__INDEX__][user_reward_value]" 
                       class="form-control form-control-sm" 
                       value="0" step="0.01" min="0" required
                       placeholder="User reward amount">
            </div>
        </td>
        <td>
            <!-- Sponsor Reward Section -->
            <div class="mb-2">
                <select name="configurations[__INDEX__][sponsor_reward_type]" 
                        class="form-control form-control-sm sponsor-reward-type" 
                        onchange="toggleSponsorDiscountType(this)" required>
                    <option value="cashback">Cashback</option>
                    <option value="discount">Discount</option>
                </select>
            </div>
            <div>
                <input type="number" name="configurations[__INDEX__][sponsor_reward_value]" 
                       class="form-control form-control-sm" 
                       value="0" step="0.01" min="0" required
                       placeholder="Sponsor reward amount">
            </div>
        </td>
        <td>
            <!-- User Discount Type -->
            <div class="mb-2">
                <label class="form-label-sm">User:</label>
                <div class="user-discount-type-wrapper" style="display: none;">
                    <select name="configurations[__INDEX__][user_discount_type]" 
                            class="form-control form-control-sm">
                        <option value="flat">Flat</option>
                        <option value="percentage">Percentage</option>
                    </select>
                </div>
            </div>
            <!-- Sponsor Discount Type -->
            <div>
                <label class="form-label-sm">Sponsor:</label>
                <div class="sponsor-discount-type-wrapper" style="display: none;">
                    <select name="configurations[__INDEX__][sponsor_discount_type]" 
                            class="form-control form-control-sm">
                        <option value="flat">Flat</option>
                        <option value="percentage">Percentage</option>
                    </select>
                </div>
            </div>
        </td>
        <td>
            <div class="max-amount-wrapper" style="display: none;">
                <input type="number" name="configurations[__INDEX__][max_amount]" 
                       class="form-control form-control-sm" 
                       value="" step="0.01" min="0"
                       placeholder="Max discount amount">
            </div>
        </td>
        <td>
            <div class="form-check form-switch">
                <input type="checkbox" class="form-check-input" 
                       name="configurations[__INDEX__][is_active]" checked>
                <label class="form-check-label">Active</label>
            </div>
        </td>
        <td>
            <button type="button" class="remove-config-btn" 
                    onclick="removeConfigurationRow(this)" 
                    title="Remove Configuration">
                ×
            </button>
        </td>
    </tr>
</script>
@endsection

@push('javascript')
<script>
let configIndex = {{ $configurations->count() }};

function addConfigurationRow() {
    const template = document.getElementById('config-row-template').innerHTML;
    const newRow = template.replace(/__INDEX__/g, configIndex);
    
    document.getElementById('configurations-container').insertAdjacentHTML('beforeend', newRow);
    document.getElementById('empty-state')?.remove();
    
    // Initialize the new row's discount type toggles
    const container = document.getElementById('configurations-container');
    const newRowElement = container.lastElementChild;
    const userRewardSelect = newRowElement.querySelector('select[name*="[user_reward_type]"]');
    const sponsorRewardSelect = newRowElement.querySelector('select[name*="[sponsor_reward_type]"]');
    
    if (userRewardSelect) {
        toggleUserDiscountType(userRewardSelect);
    }
    if (sponsorRewardSelect) {
        toggleSponsorDiscountType(sponsorRewardSelect);
    }
    
    configIndex++;
}

function removeConfigurationRow(button) {
    const row = button.closest('.config-row');
    row.remove();
    
    // Show empty state if no configurations
    const container = document.getElementById('configurations-container');
    if (container.children.length === 0) {
        container.insertAdjacentHTML('afterend', `
            <div class="text-center py-4" id="empty-state">
                <img src="{{ asset('assets/admin/img/empty-box.png') }}" alt="No configurations" style="width: 100px; opacity: 0.6;">
                <p class="text-muted mt-3">No referral configurations found. Click the + button to add one.</p>
            </div>
        `);
    }
}

function toggleUserDiscountType(selectElement) {
    const row = selectElement.closest('tr');
    const discountTypeWrapper = row.querySelector('.user-discount-type-wrapper');
    const discountTypeSelect = row.querySelector('select[name*="[user_discount_type]"]');
    
    if (selectElement.value === 'discount') {
        discountTypeWrapper.style.display = 'block';
        discountTypeSelect.required = true;
    } else {
        discountTypeWrapper.style.display = 'none';
        discountTypeSelect.required = false;
    }
}

function toggleSponsorDiscountType(selectElement) {
    const row = selectElement.closest('tr');
    const discountTypeWrapper = row.querySelector('.sponsor-discount-type-wrapper');
    const discountTypeSelect = row.querySelector('select[name*="[sponsor_discount_type]"]');
    
    if (selectElement.value === 'discount') {
        discountTypeWrapper.style.display = 'block';
        discountTypeSelect.required = true;
    } else {
        discountTypeWrapper.style.display = 'none';
        discountTypeSelect.required = false;
    }
}

function saveConfigurations() {
    const form = document.getElementById('referral-config-form');
    const formData = new FormData(form);
    
    // Convert form data to object
    const configurations = [];
    const rows = document.querySelectorAll('#configurations-container .config-row');
    
    rows.forEach((row, index) => {
        const config = {};
        const inputs = row.querySelectorAll('input, select');
        
        inputs.forEach(input => {
            const name = input.name;
            if (name && name.includes('configurations')) {
                const key = name.split('][')[1].replace(']', '');
                if (input.type === 'checkbox') {
                    config[key] = input.checked;
                } else if (input.value !== '') {
                    config[key] = input.value;
                }
            }
        });
        
        // Validate that we have all required fields for the new schema
        const requiredFields = ['order_limit', 'user_reward_type', 'user_reward_value', 'sponsor_reward_type', 'sponsor_reward_value'];
        let hasAllRequired = requiredFields.every(field => config.hasOwnProperty(field) && config[field] !== '');
        
        // Check if discount types are required
        if (config.user_reward_type === 'discount' && (!config.user_discount_type || config.user_discount_type === '')) {
            hasAllRequired = false;
        }
        if (config.sponsor_reward_type === 'discount' && (!config.sponsor_discount_type || config.sponsor_discount_type === '')) {
            hasAllRequired = false;
        }
        
        if (hasAllRequired) {
            configurations.push(config);
        }
    });
    
    // Show loading
    Swal.fire({
        title: 'Saving...',
        text: 'Please wait while we save the configurations',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Send AJAX request
    fetch('{{ route("admin.referral.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            configurations: configurations
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: 'Success!',
                text: data.message,
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                location.reload();
            });
        } else {
            throw new Error(data.message || 'Failed to save configurations');
        }
    })
    .catch(error => {
        Swal.fire({
            title: 'Error!',
            text: error.message || 'Failed to save configurations',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    });
}

// Initialize discount type toggles on page load
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('select[name*="[user_reward_type]"]').forEach(toggleUserDiscountType);
    document.querySelectorAll('select[name*="[sponsor_reward_type]"]').forEach(toggleSponsorDiscountType);
});
</script>
@endpush
