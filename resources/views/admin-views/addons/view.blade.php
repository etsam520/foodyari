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
                    <li class="breadcrumb-item active">{{ __('View Addon') }}</li>
                </ol>
            </nav>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="header-title">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-eye me-2"></i>
                            {{ __('Addon Details') }}
                        </h4>
                        <p class="text-muted mb-0 mt-1">{{ __('View detailed information about this addon') }}</p>
                    </div>
                    <div class="header-action d-flex gap-2">
                        <a href="{{ route('admin.addon.edit', $addon->id) }}" class="btn btn-outline-warning">
                            <i class="fas fa-edit me-1"></i>
                            {{ __('Edit') }}
                        </a>
                        <a href="{{ route('admin.addon.add') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i>
                            {{ __('Back to List') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Addon Information -->
                        <div class="col-md-8">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-info-circle me-2"></i>
                                        {{ __('Addon Information') }}
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-sm-6">
                                            <label class="form-label text-muted small">{{ __('Addon ID') }}</label>
                                            <div class="h6">#{{ $addon->id }}</div>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="form-label text-muted small">{{ __('Status') }}</label>
                                            <div>
                                                <span class="badge bg-{{ $addon->status ? 'success' : 'danger' }} fs-6">
                                                    <i class="fas fa-{{ $addon->status ? 'check' : 'times' }} me-1"></i>
                                                    {{ $addon->status_text }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <label class="form-label text-muted small">{{ __('Name') }}</label>
                                            <div class="h5 text-primary">
                                                <i class="fas fa-tag me-2"></i>
                                                {{ $addon->name }}
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="form-label text-muted small">{{ __('Price') }}</label>
                                            <div class="h4 text-success">
                                                <i class="fas fa-dollar-sign me-1"></i>
                                                {{ $addon->formatted_price }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Restaurant Information -->
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-info text-white">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-store me-2"></i>
                                        {{ __('Restaurant') }}
                                    </h5>
                                </div>
                                <div class="card-body text-center">
                                    <div class="avatar avatar-lg bg-info-subtle text-info rounded-circle mx-auto mb-3">
                                        <i class="fas fa-store fa-2x"></i>
                                    </div>
                                    <h5 class="card-title">{{ $addon->restaurant->name ?? __('N/A') }}</h5>
                                    <p class="text-muted small mb-2">{{ __('Restaurant ID') }}: {{ $addon->restaurant->id ?? __('N/A') }}</p>
                                    @if($addon->restaurant)
                                        <a href="{{ route('admin.restaurant.view', $addon->restaurant->id) }}" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-external-link-alt me-1"></i>
                                            {{ __('View Restaurant') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Timestamps -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-secondary text-white">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-clock me-2"></i>
                                        {{ __('Timeline') }}
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-sm-6">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm bg-success-subtle text-success rounded-circle me-3">
                                                    <i class="fas fa-plus"></i>
                                                </div>
                                                <div>
                                                    <label class="form-label text-muted small mb-0">{{ __('Created At') }}</label>
                                                    <div class="fw-semibold">{{ $addon->created_at->format('M d, Y h:i A') }}</div>
                                                    <small class="text-muted">{{ $addon->created_at->diffForHumans() }}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm bg-warning-subtle text-warning rounded-circle me-3">
                                                    <i class="fas fa-edit"></i>
                                                </div>
                                                <div>
                                                    <label class="form-label text-muted small mb-0">{{ __('Last Updated') }}</label>
                                                    <div class="fw-semibold">{{ $addon->updated_at->format('M d, Y h:i A') }}</div>
                                                    <small class="text-muted">{{ $addon->updated_at->diffForHumans() }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-center gap-3">
                                <a href="{{ route('admin.addon.edit', $addon->id) }}" class="btn btn-warning">
                                    <i class="fas fa-edit me-2"></i>
                                    {{ __('Edit Addon') }}
                                </a>
                                <button type="button" class="btn btn-danger" onclick="deleteAddon({{ $addon->id }}, '{{ $addon->name }}')">
                                    <i class="fas fa-trash me-2"></i>
                                    {{ __('Delete Addon') }}
                                </button>
                                <button type="button" class="btn btn-{{ $addon->status ? 'secondary' : 'success' }}" onclick="toggleStatus({{ $addon->id }}, {{ $addon->status ? '0' : '1' }})">
                                    <i class="fas fa-{{ $addon->status ? 'pause' : 'play' }} me-2"></i>
                                    {{ $addon->status ? __('Deactivate') : __('Activate') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function deleteAddon(addonId, addonName) {
        if (confirm('{{ __("Are you sure you want to delete") }} "' + addonName + '"?')) {
            $.ajax({
                url: '{{ route("admin.addon.destroy", ":id") }}'.replace(':id', addonId),
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        window.location.href = '{{ route("admin.addon.add") }}';
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr) {
                    alert('{{ __("An error occurred while deleting the addon") }}');
                }
            });
        }
    }

    function toggleStatus(addonId, status) {
        $.ajax({
            url: '{{ route("admin.addon.status") }}',
            type: 'GET',
            data: {
                id: addonId,
                status: status
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr) {
                alert('{{ __("An error occurred while updating status") }}');
            }
        });
    }
</script>
@endpush
@endsection