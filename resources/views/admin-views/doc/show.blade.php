@extends('layouts.dashboard-main')

@section('content')
<div class="container-fluid content-inner mt-n5 py-0">
    <div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Document Details</h4>
                        </div>
                        <div class="header-button">
                            <a href="{{ route('admin.doc.index') }}" class="btn btn-outline-secondary p-2">
                                <i class="fa fa-arrow-left"></i> Back
                            </a>
                            <a href="{{ route('admin.doc.edit', $document->id) }}" class="btn btn-outline-primary p-2">
                                <i class="fa fa-edit"></i> Edit
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">Document ID</label>
                                    <p class="form-control-plaintext">{{ $document->id }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">Document Type</label>
                                    <p class="form-control-plaintext">
                                        <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $document->type)) }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">Document Name</label>
                                    <p class="form-control-plaintext">{{ $document->name }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">Required</label>
                                    <p class="form-control-plaintext">
                                        <span class="badge {{ $document->is_required ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $document->is_required ? 'Yes' : 'No' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">Has Text Input</label>
                                    <p class="form-control-plaintext">
                                        <span class="badge {{ $document->is_text ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $document->is_text ? 'Yes' : 'No' }}
                                        </span>
                                        @if($document->is_text)
                                            <small class="text-muted d-block">
                                                Required: {{ $document->is_text_required ? 'Yes' : 'No' }}
                                            </small>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">Has Media Upload</label>
                                    <p class="form-control-plaintext">
                                        <span class="badge {{ $document->is_media ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $document->is_media ? 'Yes' : 'No' }}
                                        </span>
                                        @if($document->is_media)
                                            <small class="text-muted d-block">
                                                Required: {{ $document->is_media_required ? 'Yes' : 'No' }}
                                            </small>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">Has Expiry Date</label>
                                    <p class="form-control-plaintext">
                                        <span class="badge {{ $document->has_expiry_date ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $document->has_expiry_date ? 'Yes' : 'No' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">Status</label>
                                    <p class="form-control-plaintext">
                                        <span class="badge {{ $document->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                            {{ ucfirst($document->status) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        @if($document->is_text || $document->is_media || $document->has_expiry_date)
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card bg-light">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">Generated Input Field Names</h6>
                                    </div>
                                    <div class="card-body">
                                        @if($document->is_text)
                                        <div class="mb-2">
                                            <strong>Text Input Name:</strong> 
                                            <code>{{ $document->text_input_name }}</code>
                                        </div>
                                        @endif
                                        
                                        @if($document->is_media)
                                        <div class="mb-2">
                                            <strong>Media Input Name:</strong> 
                                            <code>{{ $document->media_input_name }}</code>
                                        </div>
                                        @endif
                                        
                                        @if($document->has_expiry_date)
                                        <div class="mb-2">
                                            <strong>Expiry Date Input Name:</strong> 
                                            <code>{{ $document->expire_date_input_name }}</code>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">Created At</label>
                                    <p class="form-control-plaintext">{{ $document->created_at->format('d M Y, h:i A') }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">Updated At</label>
                                    <p class="form-control-plaintext">{{ $document->updated_at->format('d M Y, h:i A') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Add any additional JavaScript if needed
</script>
@endpush