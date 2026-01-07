@extends('layouts.dashboard-main')

@push('css')
<style>
    .template-preview {
        border: 1px solid #ddd;
        border-radius: 8px;
        overflow: hidden;
        transition: transform 0.2s;
    }
    
    .template-preview:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .template-preview img {
        width: 100%;
        height: 150px;
        object-fit: cover;
    }
    
    .status-badge {
        position: absolute;
        top: 8px;
        right: 8px;
        z-index: 10;
    }
    
    .default-badge {
        position: absolute;
        top: 8px;
        left: 8px;
        z-index: 10;
    }
</style>
@endpush

@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">QR Templates</h4>
                        <small class="text-muted">Create and manage zone-wise QR code templates</small>
                    </div>
                    <div class="header-action">
                        <a href="{{ route('admin.qr-template.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Create Template
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($templates->count() > 0)
                    <div class="row p-4">
                        @foreach($templates as $template)
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="template-preview position-relative">
                                <!-- Status Badge -->
                                <span class="status-badge badge badge-{{ $template->status ? 'success' : 'danger' }}">
                                    {{ $template->status ? 'Active' : 'Inactive' }}
                                </span>
                                
                                <!-- Default Badge -->
                                @if($template->is_default)
                                <span class="default-badge badge badge-warning">
                                    <i class="fas fa-star"></i> Default
                                </span>
                                @endif
                                
                                <!-- Preview Image -->
                                <div class="template-image bg-light d-flex align-items-center justify-content-center" style="height: 150px;">
                                    @if($template->background_type === 'image' && $template->background_url)
                                        <img src="{{ $template->background_url }}" alt="Template Background" class="img-fluid">
                                    @else
                                        <div class="text-center" style="background-color: {{ $template->background_value ?? '#ffffff' }}; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                                            <div class="bg-dark text-white p-2 rounded" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-qrcode fa-2x"></i>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Template Info -->
                                <div class="p-3">
                                    <h6 class="mb-1">{{ $template->name }}</h6>
                                    <p class="text-muted small mb-2">
                                        <i class="fas fa-map-marker-alt"></i> {{ $template->zone->name ?? 'All Zones' }}
                                    </p>
                                    {{-- @dd($template->) --}}
                                    <p class="text-muted small mb-3">
                                        Size: {{ $template->template_data['canvas']['width'] ?? 'N/A' }}x{{ $template->template_data['canvas']['height'] ?? 'N/A' }}px
                                    </p>
                                    
                                    <!-- Actions -->
                                    <div class="btn-group w-100" role="group">
                                        <a href="{{ route('admin.qr-template.preview', $template->id) }}" 
                                           class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.qr-template.edit', $template->id) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-{{ $template->status ? 'warning' : 'success' }}" 
                                                onclick="toggleStatus({{ $template->id }})">
                                            <i class="fas fa-{{ $template->status ? 'pause' : 'play' }}"></i>
                                        </button>
                                        @if(!$template->is_default)
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-warning" 
                                                onclick="setDefault({{ $template->id }})">
                                            <i class="fas fa-star"></i>
                                        </button>
                                        @endif
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-danger" 
                                                onclick="deleteTemplate({{ $template->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center p-4">
                        {{ $templates->links() }}
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-qrcode fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No QR Templates Found</h5>
                        <p class="text-muted mb-4">Create your first QR template to get started</p>
                        <a href="{{ route('admin.qr-template.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Create Template
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('javascript')
<script>
    const assetPath = document.querySelector('meta[name="asset-path"]').getAttribute('content') || '';
    const basePath = document.querySelector('meta[name="base-path"]').getAttribute('content') || '';
    function toggleStatus(templateId) {
        if (confirm('Are you sure you want to change the status of this template?')) {
            fetch(`${basePath}/admin/qr-template/toggle-status/${templateId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    toastr.success(data.message);
                    location.reload();
                } else {
                    toastr.error(data.message);
                }
            })
            .catch(error => {
                toastr.error('An error occurred');
            });
        }
    }
    
    function setDefault(templateId) {
        if (confirm('Are you sure you want to set this template as default for its zone?')) {
            fetch(`${basePath}/admin/qr-template/set-default/${templateId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    toastr.success(data.message);
                    location.reload();
                } else {
                    toastr.error(data.message);
                }
            })
            .catch(error => {
                toastr.error('An error occurred');
            });
        }
    }
    
    function deleteTemplate(templateId) {
        if (confirm('Are you sure you want to delete this template? This action cannot be undone.')) {
            fetch(`${basePath}/admin/qr-template/delete/${templateId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    toastr.success(data.message);
                    location.reload();
                } else {
                    toastr.error(data.message);
                }
            })
            .catch(error => {
                toastr.error('An error occurred');
            });
        }
    }
</script>
@endpush
