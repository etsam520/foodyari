
@extends('layouts.dashboard-main')

@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div>
       <div class="row">
          <div class="col-sm-12">
             <div class="card">
                <div class="card-header d-flex justify-content-between">
                   <div class="header-title">
                      <h4 class="card-title">Shift Edit</h4>
                   </div>
                </div>
                <div class="card-body px-3">
                  
                    <h5 class="modal-title" id="addNewModal">Edit Shift</h5>
                    
                    <form action="javascript:void(0)" id="system-form" class="row"  method="post">
                        @csrf
                        @method('post')
                        <input type="hidden" name="id" value="{{$shift->id}}">
                        <div class="form-group col-6">
                            <label for="name" class="mb-2">{{ __('messages.name') }}</label>
                            <input type="name" name="name" required value="{{$shift->name}}" id="name" class="form-control" placeholder="Ex: Morning">
                        </div>
                        <div class="form-group col-6">
                            <label for="zone_id" class="mb-2">{{ __('messages.zone') }} <span class="text-danger">*</span></label>
                            <select name="zone_id" id="zone_id" class="form-select" required>
                                <option value="">Select Zone</option>
                                @foreach($zones as $zone)
                                    <option value="{{ $zone->id }}" {{ $shift->zone_id == $zone->id ? 'selected' : '' }}>
                                        {{ $zone->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-6">
                            <label for="start_time" class="mb-2">{{ __('messages.Start_Time') }}</label>
                            <input type="time" required  name="start_time" id="start_time" value="{{$shift->start_time}}" class="form-control">
                        </div>
                        <div class="form-group col-6">
                            <label for="end_time" class="mb-2">{{ __('messages.End_Time') }}</label>
                            <input type="time" required name="end_time" id="end_time" value="{{$shift->end_time}}" class="form-control" >
                        </div>
        
                        <div class="col-6">
                            <button id="reset_btn" type="reset" data-dismiss="modal" class="btn btn-secondary" >{{ __('Reset') }} </button>
                            <button class="btn btn-primary" type="submit">{{ __('Submit') }}</button>
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
     * =================// shift form submission //================= 
     */
    const vehicleForm = document.querySelector('#system-form');

    vehicleForm.addEventListener('submit', async (event) => {
        event.preventDefault(); 

        const formData = new FormData(vehicleForm);

        try {
            const res = await fetch("{{ route('admin.shift.update') }}", {
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
                // Redirect back to shift list after successful update
                setTimeout(() => {
                    window.location.href = "{{ route('admin.shift.list') }}";
                }, 1000);
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