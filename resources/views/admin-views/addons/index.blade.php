@extends('layouts.dashboard-main')

@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div class="row">
        <div class="col-sm-12 col-lg-12">
            <!-- Display Success/Error Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>{{ __('Please fix the following errors:') }}</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="header-title">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-plus-circle me-2"></i>
                            {{ __('Add New Addon') }}
                        </h4>
                    </div>
                    <div class="header-action">
                        <small class="text-muted">{{ __('Create additional menu items') }}</small>
                    </div>
                </div>
                <div class="card-body">
                    <form class="row g-4 needs-validation" method="POST" action="{{route('admin.addon.store')}}" id="addon-form" novalidate>
                        @csrf
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label required" for="name">
                                    <i class="fas fa-tag me-1"></i>
                                    {{ __('Addon Name') }}
                                </label>
                                <input 
                                    id="name" 
                                    type="text" 
                                    name="name"
                                    class="form-control @error('name') is-invalid @enderror" 
                                    placeholder="{{ __('e.g., Extra Cheese, Large Size') }}"
                                    value="{{ old('name') }}" 
                                    required
                                    maxlength="255">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">{{ __('Enter a descriptive name for the addon') }}</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label required" for="price">
                                    <i class="fas fa-dollar-sign me-1"></i>
                                    {{ __('Price') }}
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input 
                                        id="price" 
                                        type="number" 
                                        name="price"
                                        class="form-control @error('price') is-invalid @enderror" 
                                        placeholder="{{ __('0.00') }}"
                                        value="{{ old('price') }}" 
                                        required
                                        min="0" 
                                        max="999999.99" 
                                        step="0.01">
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text">{{ __('Set the additional cost for this addon') }}</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label required" for="restaurant-name">
                                    <i class="fas fa-store me-1"></i>
                                    {{ __('Restaurant') }}
                                </label>
                                <select 
                                    class="form-select @error('restaurant_id') is-invalid @enderror" 
                                    name="restaurant_id" 
                                    id="restaurant-name" 
                                    required>
                                    <option value="">{{ __('Choose Restaurant...') }}</option>
                                    @foreach ($restaurants as $restaurant)
                                        <option value="{{ $restaurant->id }}" 
                                            {{ old('restaurant_id') == $restaurant->id ? 'selected' : '' }}>
                                            {{ $restaurant->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('restaurant_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">{{ __('Select the restaurant this addon belongs to') }}</div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="d-flex justify-content-end gap-2">
                                <button type="reset" class="btn btn-outline-secondary">
                                    <i class="fas fa-undo me-1"></i>
                                    {{ __('Reset') }}
                                </button>
                                <button type="submit" class="btn btn-primary" id="submit-btn">
                                    <i class="fas fa-save me-1"></i>
                                    {{ __('Create Addon') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Addons List Table -->
    <div class="row mt-4">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="header-title">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-list me-2"></i>
                            {{ __('Addons Management') }}
                        </h4>
                        <p class="text-muted mb-0 mt-1">{{ __('Manage all restaurant addons') }}</p>
                    </div>
                    <div class="header-action d-flex gap-2">
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-filter me-1"></i>
                                {{ __('Filter') }}
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" onclick="filterTable('all')">{{ __('All Addons') }}</a></li>
                                <li><a class="dropdown-item" href="#" onclick="filterTable('active')">{{ __('Active Only') }}</a></li>
                                <li><a class="dropdown-item" href="#" onclick="filterTable('inactive')">{{ __('Inactive Only') }}</a></li>
                            </ul>
                        </div>
                        <div class="search-wrapper">
                            <input type="text" class="form-control" id="search-addons" placeholder="{{ __('Search addons...') }}">
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($addons->count() > 0)
                        <div class="table-responsive">
                            <table id="addons-table" class="table table-striped table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" width="5%">#</th>
                                        <th width="20%">
                                            <i class="fas fa-tag me-1"></i>
                                            {{ __('Name') }}
                                        </th>
                                        <th width="15%" class="text-center">
                                            <i class="fas fa-dollar-sign me-1"></i>
                                            {{ __('Price') }}
                                        </th>
                                        <th width="25%">
                                            <i class="fas fa-store me-1"></i>
                                            {{ __('Restaurant') }}
                                        </th>
                                        <th width="15%" class="text-center">
                                            <i class="fas fa-toggle-on me-1"></i>
                                            {{ __('Status') }}
                                        </th>
                                        <th width="10%" class="text-center">
                                            <i class="fas fa-calendar me-1"></i>
                                            {{ __('Created') }}
                                        </th>
                                        <th width="10%" class="text-center">
                                            <i class="fas fa-cogs me-1"></i>
                                            {{ __('Actions') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($addons as $addon)
                                        <tr data-status="{{ $addon->status ? 'active' : 'inactive' }}">
                                            <td class="text-center">
                                                <span class="fw-semibold">{{ $loop->iteration + ($addons->currentPage() - 1) * $addons->perPage() }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-sm bg-primary-subtle text-primary rounded-circle me-2">
                                                        <i class="fas fa-plus"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">{{ $addon->name }}</h6>
                                                        <small class="text-muted">ID: {{ $addon->id }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-success-subtle text-success fs-6">
                                                    ${{ $addon->formatted_price }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-sm bg-info-subtle text-info rounded-circle me-2">
                                                        <i class="fas fa-store"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">{{ $addon->restaurant->name ?? __('N/A') }}</h6>
                                                        <small class="text-muted">{{ $addon->restaurant->id ?? '' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="form-check form-switch d-flex justify-content-center">
                                                    <input 
                                                        type="checkbox" 
                                                        class="form-check-input addon-status-toggle" 
                                                        data-addon-id="{{ $addon->id }}"
                                                        value="{{$addon->status ? 1 : 0}}"
                                                        {{ $addon->status ? 'checked' : '' }}
                                                        id="addon-status-{{ $addon->id }}">
                                                    <label class="form-check-label" for="addon-status-{{ $addon->id }}">
                                                        <span class="badge bg-{{ $addon->status ? 'success' : 'danger' }}">
                                                            {{ $addon->status_text }}
                                                        </span>
                                                    </label>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <small class="text-muted">
                                                    {{ $addon->created_at->format('M d, Y') }}
                                                </small>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.addon.view', $addon->id) }}" 
                                                       class="btn btn-sm btn-outline-info" 
                                                       title="{{ __('View Details') }}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.addon.edit', $addon->id) }}" 
                                                       class="btn btn-sm btn-outline-warning" 
                                                       title="{{ __('Edit Addon') }}">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-danger delete-addon" 
                                                            data-addon-id="{{ $addon->id }}"
                                                            data-addon-name="{{ $addon->name }}"
                                                            title="{{ __('Delete Addon') }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="card-footer d-flex justify-content-between align-items-center border-top">
                            <div class="text-muted">
                                {{ __('Showing :from to :to of :total results', [
                                    'from' => $addons->firstItem(),
                                    'to' => $addons->lastItem(),
                                    'total' => $addons->total()
                                ]) }}
                            </div>
                            <div>
                                {{ $addons->links() }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-plus-circle fa-3x text-muted"></i>
                            </div>
                            <h5 class="text-muted">{{ __('No Addons Found') }}</h5>
                            <p class="text-muted mb-3">{{ __('Create your first addon to get started') }}</p>
                            <button type="button" class="btn btn-primary" onclick="document.getElementById('name').focus()">
                                <i class="fas fa-plus me-1"></i>
                                {{ __('Create First Addon') }}
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('javascript')
<script>
    $(document).ready(function() {
        // Form submission with loading state
        $('#addon-form').on('submit', function() {
            const submitBtn = $('#submit-btn');
            submitBtn.prop('disabled', true);
            submitBtn.html('<i class="fas fa-spinner fa-spin me-1"></i>{{ __("Creating...") }}');
        });

        // Status toggle functionality
        $('.addon-status-toggle').on('change', function() {
            const addonId = $(this).data('addon-id');
            const status = $(this).is(':checked') ? 1 : 0;
            const toggleElement = $(this);
            
            $.ajax({
                url: '{{ route("admin.addon.status") }}',
                type: 'GET',
                data: {
                    id: addonId,
                    status: status
                },
                success: function(response) {
                    if (response.success) {
                        // Update badge
                        const badge = toggleElement.siblings('label').find('.badge');
                        if (status) {
                            badge.removeClass('bg-danger').addClass('bg-success').text('{{ __("Active") }}');
                        } else {
                            badge.removeClass('bg-success').addClass('bg-danger').text('{{ __("Inactive") }}');
                        }
                        
                        // Show success message
                        showAlert('success', response.message);
                    } else {
                        // Revert toggle state
                        toggleElement.prop('checked', !status);
                        showAlert('error', response.message);
                    }
                },
                error: function(xhr) {
                    // Revert toggle state
                    toggleElement.prop('checked', !status);
                    showAlert('error', '{{ __("An error occurred while updating status") }}');
                }
            });
        });

        // Delete addon functionality
        $('.delete-addon').on('click', function() {
            const addonId = $(this).data('addon-id');
            const addonName = $(this).data('addon-name');
            const row = $(this).closest('tr');
            
            if (confirm('{{ __("Are you sure you want to delete") }} "' + addonName + '"?')) {
                $.ajax({
                    url: '{{ route("admin.addon.destroy", ":id") }}'.replace(':id', addonId),
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            row.fadeOut(300, function() {
                                $(this).remove();
                                // Check if table is empty
                                if ($('#addons-table tbody tr').length === 0) {
                                    location.reload();
                                }
                            });
                            showAlert('success', response.message);
                        } else {
                            showAlert('error', response.message);
                        }
                    },
                    error: function(xhr) {
                        showAlert('error', '{{ __("An error occurred while deleting the addon") }}');
                    }
                });
            }
        });

        // Search functionality
        $('#search-addons').on('keyup', function() {
            const searchTerm = $(this).val().toLowerCase();
            $('#addons-table tbody tr').each(function() {
                const text = $(this).text().toLowerCase();
                $(this).toggle(text.indexOf(searchTerm) > -1);
            });
        });

        // Auto-dismiss alerts
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
    });

    // Filter functionality
    function filterTable(status) {
        const rows = $('#addons-table tbody tr');
        
        if (status === 'all') {
            rows.show();
        } else {
            rows.each(function() {
                const rowStatus = $(this).data('status');
                if (rowStatus === status) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }
    }

    // Show alert function
    function showAlert(type, message) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const iconClass = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle';
        
        const alertHtml = '<div class="alert ' + alertClass + ' alert-dismissible fade show" role="alert">' +
            '<i class="fas ' + iconClass + ' me-2"></i>' + message +
            '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
            '</div>';
        
        $('.conatiner-fluid .row .col-sm-12:first').prepend(alertHtml);
        
        // Auto dismiss after 3 seconds
        setTimeout(function() {
            $('.alert').first().alert('close');
        }, 3000);
    }
</script>
@endpush
@endsection
