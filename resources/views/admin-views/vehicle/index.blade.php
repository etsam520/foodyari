@extends('layouts.dashboard-main')

@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                       <h4 class="card-title">
                        <svg width="30px" viewBox="-2.1 -2.1 25.20 25.20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>plus_circle [#1441]</title> <desc>Created with Sketch.</desc> <defs> </defs> <g id="Page-1" stroke-width="0.315" fill="none" fill-rule="evenodd"> <g id="Dribbble-Light-Preview" transform="translate(-419.000000, -520.000000)" fill="currentColor"> <g id="icons" transform="translate(56.000000, 160.000000)"> <path d="M374.55,369 L377.7,369 L377.7,371 L374.55,371 L374.55,374 L372.45,374 L372.45,371 L369.3,371 L369.3,369 L372.45,369 L372.45,366 L374.55,366 L374.55,369 Z M373.5,378 C368.86845,378 365.1,374.411 365.1,370 C365.1,365.589 368.86845,362 373.5,362 C378.13155,362 381.9,365.589 381.9,370 C381.9,374.411 378.13155,378 373.5,378 L373.5,378 Z M373.5,360 C367.70085,360 363,364.477 363,370 C363,375.523 367.70085,380 373.5,380 C379.29915,380 384,375.523 384,370 C384,364.477 379.29915,360 373.5,360 L373.5,360 Z" id="plus_circle-[#1441]"> </path> </g> </g> </g> </g></svg>
                        Vehical List
                        </h4>
                    </div>
                </div>
                <div class="card-body">
                    <form action="javascript:void()" method="post" enctype="multipart/form-data" id="vehicle-form">
                        @csrf
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label text-uppercase" for="Vehicle_type">Vehicle Type</label>
                                            <input type="text" id="Vehicle_type" class="form-control" placeholder="Enter Vehicle Type" required name="type">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label text-uppercase" for="extra_charges">extra charges (₹) </label>
                                            <input type="number" id="extra_charges" class="form-control" placeholder="Enter Extra Charges" step="0.001" min="0" required name="extra_charges">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label text-uppercase" for="starting_coverage_area">Minimum Coverage Area (KM) </label>
                                            <input type="number" id="starting_coverage_area" class="form-control" placeholder="Enter Minimum Coverage Area" step="0.001" min="0" required name="starting_coverage_area">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label text-uppercase" for="maximum_coverage_area">maximum coverage area (KM) </label>
                                            <input type="number" id="maximum_coverage_area" class="form-control" placeholder="Enter Maximum Coverage Area" step="0.001" min="0"  required name="maximum_coverage_area">
                                        </div>
                                    </div>
        
                                </div>
                            </div>
                        </div>
        
                        <div class="btn-container justify-content-end mt-3">
                            <button type="reset" id="reset_btn" class="btn btn-outline-secondary">Reset</button>
                            <button type="submit" class="btn btn-outline-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('javascript')
<script>
    /**
     * =================// vehicle form submission //================= 
     */
    const vehicleForm = document.querySelector('#vehicle-form');

    vehicleForm.addEventListener('submit', async (event) => {
        event.preventDefault(); 

        const formData = new FormData(vehicleForm);

        try {
            const res = await fetch("{{ route('admin.vehicle.store') }}", {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                body: formData
            });

            if (!res.ok) {
                const errorMessage = await res.json();
                throw new Error(handleError(errorMessage));
            }

            const result = await res.json();
            if(result.error || result.errors){
                throw new Error(handleError(result));
            }

            if (result.success) { 
                toastr.success(result.success);
                setTimeout(() => {
                    location.href = "{{ route('admin.vehicle.list') }}";
                }, 5000);
            }
        } catch (error) {
            toastr.error(error.message);
            console.error(error);
        }
    });
    /**
     * =================// vehicle form submission end //================= 
     */

    function handleError(errorResponse) {
        console.log(errorResponse)
        if (errorResponse && errorResponse.errors) {
            if (Array.isArray(errorResponse.errors)) {
                const errorMessages = Object.values(errorResponse.errors);
                const errorList = errorMessages.map(item => `<li>${item.message}</li>`);
                return `<ul>${errorList.join('')}</ul>`;
            }
            if (typeof errorResponse.errors === 'string') {
                return errorResponse.errors;
            }
            if (typeof errorResponse.errors === 'object') {
                const errorMessages = Object.values(errorResponse.errors);
                const errorList = errorMessages.map(item => `<li>${item}</li>`);
                return `<ul>${errorList.join('')}</ul>`;
            }
        }
        return errorResponse.error;
    }
</script>
    
@endpush
            