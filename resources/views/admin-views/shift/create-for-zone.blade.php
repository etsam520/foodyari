@extends('layouts.dashboard-main')

@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div>
       <div class="row">
          <div class="col-sm-12">
             <div class="card">
                <div class="card-header d-flex justify-content-between">
                   <div class="header-title">
                      <h4 class="card-title">Add Shift for {{ $zone->name }}</h4>
                      <p class="text-muted">Create a new shift for {{ $zone->name }} zone</p>
                   </div>
                   <div class="header-button">
                        <a href="{{ route('admin.shift.list-by-zone', $zone->id) }}" class="btn btn-outline-secondary p-2">
                            <svg width="20" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
                            </svg>
                            Back to {{ $zone->name }} Shifts
                        </a>
                   </div>
                </div>
                <div class="card-body px-3">
                    <!-- Zone Info -->
                    <div class="alert alert-info mb-4">
                        <h6 class="mb-1">Zone Information</h6>
                        <p class="mb-0">You are creating a shift for <strong>{{ $zone->name }}</strong> zone. This shift will only be available within this zone.</p>
                    </div>

                    <form action="javascript:void(0)" id="zone-shift-form" class="row" method="post">
                        @csrf
                        @method('post')
                        <input type="hidden" name="zone_id" value="{{ $zone->id }}">
                        
                        <div class="form-group col-6">
                            <label for="name" class="mb-2">{{ __('messages.name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name" required id="name" class="form-control" placeholder="Ex: Morning Shift">
                        </div>
                        
                        <div class="form-group col-6">
                            <label for="zone_display" class="mb-2">{{ __('messages.zone') }}</label>
                            <input type="text" value="{{ $zone->name }}" class="form-control" readonly>
                        </div>
                        
                        <div class="form-group col-6">
                            <label for="start_time" class="mb-2">{{ __('messages.Start_Time') }} <span class="text-danger">*</span></label>
                            <input type="time" required name="start_time" id="start_time" class="form-control">
                        </div>
                        
                        <div class="form-group col-6">
                            <label for="end_time" class="mb-2">{{ __('messages.End_Time') }} <span class="text-danger">*</span></label>
                            <input type="time" required name="end_time" id="end_time" class="form-control">
                        </div>

                        <div class="col-12">
                            <div class="alert alert-warning">
                                <strong>Note:</strong> The system will check for overlapping shifts within the {{ $zone->name }} zone. Make sure the time doesn't conflict with existing shifts in this zone.
                            </div>
                        </div>
        
                        <div class="col-12">
                            <button type="reset" class="btn btn-secondary me-2">{{ __('Reset') }}</button>
                            <button class="btn btn-primary" type="submit">{{ __('Create Shift') }}</button>
                        </div>
                    </form>
                </div>
             </div>
          </div>
       </div>
    </div>
</div>
@endsection

@push('javascript')
<script>
    /**
     * =================// Zone shift form submission //================= 
     */
    const zoneShiftForm = document.querySelector('#zone-shift-form');

    zoneShiftForm.addEventListener('submit', async (event) => {
        event.preventDefault(); 

        const formData = new FormData(zoneShiftForm);

        try {
            const res = await fetch("{{ route('admin.shift.store-for-zone', $zone->id) }}", {
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
                // Redirect to zone shifts list after successful creation
                setTimeout(() => {
                    window.location.href = "{{ route('admin.shift.list-by-zone', $zone->id) }}";
                }, 1000);
            }
        } catch (error) {
            toastr.error(error.message);
            console.error(error);
        }
    });

    function handleError(errorResponse) {
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
        return errorResponse.error || 'An error occurred';
    }
</script>
@endpush
