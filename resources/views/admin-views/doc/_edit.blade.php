@extends('layouts.dashboard-main')
<style>
    /* Cross-browser placeholder styling with light grey color */
    input::placeholder {
        color: #c5c4c4 !important;
        /* Modern browsers */
    }

    input::-webkit-input-placeholder {
        color: #d3d3d3;
        /* Chrome, Safari, Edge */
    }

    input:-moz-placeholder {
        color: #d3d3d3;
        /* Firefox 18- */
    }

    input::-moz-placeholder {
        color: #d3d3d3;
        /* Firefox 19+ */
    }

    input:-ms-input-placeholder {
        color: #d3d3d3;
        /* IE 10+ */
    }
</style>
@section('content')
    <div class="conatiner-fluid px-5 mt-3">
        <div>
            <div class="row d-flex justify-content-center">
                <div class="col-sm-12 col-lg-8">
                    <div class="card">
                        <div class="card-header list-group-item-light custom-list-dark px-4 py-3">
                            <div class="header-title d-flex justify-content-between align-items-center">
                                <div class="card-title fs-5 text-dark">Edit Document</div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form method="POST" action="{{ route('admin.doc.update', $document->id) }}">
                                @csrf
                                @method('PUT') <!-- Use PUT for updating data -->
                                <div class="mb-3">
                                    <label for="disabledSelect" class="form-label">Document Type</label>
                                    <select id="disabledSelect" name="type" class="form-select">
                                        {{-- <option>Select Type</option> --}}
                                        <option value="user_kyc" {{ $document->type == 'user_kyc' ? 'selected' : '' }}>User KYC</option>
                                        <option value="restaurant_kyc" {{ $document->type == 'restaurant_kyc' ? 'selected' : '' }}>Restaurant KYC</option>
                                        <option value="deliveryman_kyc" {{ $document->type == 'deliveryman_kyc' ? 'selected' : '' }}>Deliveryman KYC</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="document_name">Document Name</label>
                                    <input type="text" class="form-control" name="name" id="document_name" placeholder="Document Name" value="{{ old('name', $document->name) }}">
                                </div>
                                <div class="form-group">
                                    <label class="form-label w-100" for="is_required">Is Required?
                                        <div class="form-control py-3">
                                            <input type="checkbox" class="form-check-input mt-0" name="is_required" id="is_required" value="1" {{ $document->is_required ? 'checked' : '' }}>
                                        </div>
                                    </label>
                                </div>
                                <div class="border-top pt-3"></div>
                                <div class="form-group">
                                    <label class="form-label w-100" for="is_text">Is Text?
                                        <div class="form-control py-3">
                                            <input type="checkbox" class="form-check-input mt-0" id="is_text" name="is_text" value="1" {{ $document->is_text ? 'checked' : '' }}>
                                        </div>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="form-label w-100" for="is_text_required">Is Text Required?
                                        <div class="form-control py-3">
                                            <input type="checkbox" class="form-check-input mt-0" name="is_text_required" id="is_text_required" value="1" {{ $document->is_text_required ? 'checked' : '' }}>
                                        </div>
                                    </label>
                                </div>
                                <div class="border-top pt-3"></div>
                                <div class="form-group">
                                    <label class="form-label w-100" for="is_media">Is Media?
                                        <div class="form-control py-3">
                                            <input type="checkbox" class="form-check-input mt-0" name="is_media" id="is_media" value="1" {{ $document->is_media ? 'checked' : '' }}>
                                        </div>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="form-label w-100" for="is_media_required">Is Media Required?
                                        <div class="form-control py-3">
                                            <input type="checkbox" class="form-check-input mt-0" name="is_media_required" id="is_media_required" value="1" {{ $document->is_media_required ? 'checked' : '' }}>
                                        </div>
                                    </label>
                                </div>
                                <div class="border-top pt-3"></div>
                                <div class="form-group">
                                    <label class="form-label w-100" for="has_expiry_date">Has Expire Date?
                                        <div class="form-control py-3">
                                            <input type="checkbox" class="form-check-input mt-0" name="has_expiry_date" id="has_expiry_date" value="1" {{ $document->has_expiry_date ? 'checked' : '' }}>
                                        </div>
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" value="active" name="status" id="status_active" required {{ $document->status == 'active' ? 'checked' : '' }}>
                                    <label for="status_active" class="form-check-label pl-2">Active</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" value="inactive" name="status" id="status_inactive" {{ $document->status == 'inactive' ? 'checked' : '' }}>
                                    <label for="status_inactive" class="form-check-label pl-2">Inactive</label>
                                </div>
                                <button type="submit" class="btn btn-primary w-100 mt-4">Update Document</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
