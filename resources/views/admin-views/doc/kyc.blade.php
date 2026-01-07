@extends('layouts.dashboard-main')
<style>
    /* Cross-browser placeholder styling with light grey color */
    input::placeholder {
        color: #d3d3d3 !important;
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
    <div class="conatiner-fluid px-5">
        <div>
            <div class="row d-flex justify-content-center">
                <div class="col-sm-12 col-lg-8">
                    <div class="card">
                        <div class="card-header list-group-item-light custom-list-dark px-4 py-3">
                            <div class="header-title d-flex justify-content-between align-items-center">
                                <div class="card-title fs-5 text-dark">KYC Details</div>
                                <div>
                                    <span class="badge text-white py-2" style="background:rgba(121, 135, 161, 1);">
                                        Not Uploaded
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form>
                                <h6>Aadhaar Card</h6>
                                <div class="border rounded-1 p-4">
                                    <div class="form-group">
                                        <label class="form-label text-dark" for="email">Aadhaar Card ID/Number <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="email1" placeholder="Aadhaar Card ID/Number ">
                                    </div>
                                    <div class="form-group mb-0">
                                        <label class="form-label text-dark" for="pwd">Upload Aadhaar Card <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control" id="pwd">
                                    </div>
                                </div>
                                <h6 class="mt-4">PAN</h6>
                                <div class="border rounded-1 p-4">
                                    <div class="form-group">
                                        <label class="form-label text-dark" for="email">PAN ID/Number <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="email1" placeholder="PAN ID/Number ">
                                    </div>
                                    <div class="form-group mb-0">
                                        <label class="form-label text-dark" for="pwd">Upload PAN <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control" id="pwd">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary w-100 mt-4">SAVE DETAILS</button>
                                {{-- <button type="submit" class="btn btn-danger">cancel</button> --}}
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
