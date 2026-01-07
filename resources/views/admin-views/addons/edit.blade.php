@extends('layouts.dashboard-main')

@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div class="row">
        <div class="col-sm-12 col-lg-12">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.addon.add') }}">{{ __('Addons') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Edit Addon') }}</li>
                </ol>
            </nav>

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
                            <i class="fas fa-edit me-2"></i>
                            {{ __('Edit Addon') }}
                        </h4>
                        <p class="text-muted mb-0 mt-1">{{ __('Update addon information') }}</p>
                    </div>
                    <div class="header-action">
                        <a href="{{ route('admin.addon.add') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i>
                            {{ __('Back to List') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Current Addon Info -->
                    <div class="alert alert-info mb-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle me-2"></i>
                            <div>
                                <strong>{{ __('Current Addon:') }}</strong> {{ $addon->name }} 
                                <span class="badge bg-{{ $addon->status ? 'success' : 'danger' }} ms-2">
                                    {{ $addon->status_text }}
                                </span>
                                <br>
                                <small class="text-muted">
                                    {{ __('Created on') }}: {{ $addon->created_at->format('M d, Y h:i A') }} |
                                    {{ __('Last updated') }}: {{ $addon->updated_at->format('M d, Y h:i A') }}
                                </small>
                            </div>
                        </div>
                    </div>

                    <form class="row g-4 needs-validation" method="POST" action="{{ route('admin.addon.update') }}" id="addon-edit-form" novalidate>
                        @method('PUT')
                        <input type="hidden" name="id" value="{{ $addon->id }}">
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
                                    value="{{ old('name', $addon->name) }}" 
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
                                        value="{{ old('price', $addon->price) }}" 
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
                                            {{ old('restaurant_id', $addon->restaurant_id) == $restaurant->id ? 'selected' : '' }}>
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

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="status">
                                    <i class="fas fa-toggle-on me-1"></i>
                                    {{ __('Status') }}
                                </label>
                                <div class="form-check form-switch">
                                    <input 
                                        class="form-check-input" 
                                        type="checkbox" 
                                        role="switch" 
                                        id="status" 
                                        name="status" 
                                        value="1"
                                        {{ old('status', $addon->status) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status">
                                        {{ __('Active') }}
                                    </label>
                                </div>
                                <div class="form-text">{{ __('Toggle to activate or deactivate this addon') }}</div>
                            </div>
                        </div>

                        <div class="col-12">
                            <hr class="my-4">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <a href="{{ route('admin.addon.add') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times me-1"></i>
                                        {{ __('Cancel') }}
                                    </a>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="reset" class="btn btn-outline-warning">
                                        <i class="fas fa-undo me-1"></i>
                                        {{ __('Reset') }}
                                    </button>
                                    <button type="submit" class="btn btn-primary" id="update-btn">
                                        <i class="fas fa-save me-1"></i>
                                        {{ __('Update Addon') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('javascript')
<script>
    $(document).ready(function() {
        // Form submission with loading state
        $('#addon-edit-form').on('submit', function() {
            const updateBtn = $('#update-btn');
            updateBtn.prop('disabled', true);
            updateBtn.html('<i class="fas fa-spinner fa-spin me-1"></i>{{ __("Updating...") }}');
        });

        // Auto-dismiss alerts
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);

        // Form validation feedback
        const form = document.getElementById('addon-edit-form');
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });
</script>
@endpush
@endsection
