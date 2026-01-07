
@extends('layouts.dashboard-main')

@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div>
       <div class="row">
          <div class="col-sm-12">
             <div class="card">
                <div class="card-header d-flex justify-content-between">
                   <div class="header-title">
                      <h4 class="card-title">Shift List (Zone-wise Management)</h4>
                   </div>
                   <div class="header-button d-flex gap-2">
                        <!-- Zone Filter -->
                        <select class="form-select" id="zone-filter" style="width: 200px;">
                            <option value="">All Zones</option>
                            @foreach($zones as $zone)
                                <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                            @endforeach
                        </select>
                        
                        <a href="javascript:void(0)" class="btn btn-outline-primary p-2" data-bs-toggle="modal" data-bs-target="#add-new-shift" > 
                            <svg width="20px" viewBox="-2.1 -2.1 25.20 25.20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>plus_circle [#1441]</title> <desc>Created with Sketch.</desc> <defs> </defs> <g id="Page-1" stroke-width="0.315" fill="none" fill-rule="evenodd"> <g id="Dribbble-Light-Preview" transform="translate(-419.000000, -520.000000)" fill="currentColor"> <g id="icons" transform="translate(56.000000, 160.000000)"> <path d="M374.55,369 L377.7,369 L377.7,371 L374.55,371 L374.55,374 L372.45,374 L372.45,371 L369.3,371 L369.3,369 L372.45,369 L372.45,366 L374.55,366 L374.55,369 Z M373.5,378 C368.86845,378 365.1,374.411 365.1,370 C365.1,365.589 368.86845,362 373.5,362 C378.13155,362 381.9,365.589 381.9,370 C381.9,374.411 378.13155,378 373.5,378 L373.5,378 Z M373.5,360 C367.70085,360 363,364.477 363,370 C363,375.523 367.70085,380 373.5,380 C379.29915,380 384,375.523 384,370 C384,364.477 379.29915,360 373.5,360 L373.5,360 Z" id="plus_circle-[#1441]"> </path> </g> </g> </g> </g></svg>
                             Add Shift</a>
                   </div>
                </div>
                <div class="card-body px-0">
                   <div class="table-responsive">
                      <table id="datatable" class="table" role="grid" data-toggle="data-table">
                         <thead>
                            <tr class="ligth">
                                <th>SL</th>
                                <th >NAME</th>
                                <th >ZONE</th>
                                <th >START TIME</th>
                                <th >END TIME</th>
                                <th class="text-center">STATUS</th>
                               <th style="min-width: 100px">ACTION</th>
                            </tr>
                         </thead>
                         <tbody>
                            @foreach($shifts as $key => $shift)
                            <tr>
                                <td>{{$loop->index + 1}}</td>
                                <td>
                                    <span class="bg-gradient-light text-dark">
                                        {{$shift['name']}}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-info text-white">
                                        {{ $shift->zone ? $shift->zone->name : 'No Zone' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="bg-gradient-light text-dark">
                                        {{ Carbon\Carbon::parse($shift->start_time)->format('h:i A') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="bg-gradient-light text-dark">
                                        {{date('h:i A', strtotime($shift['end_time']))}}
                                    </span>
                                </td>
                                <td>
                                    <label class="form-check form-check form-switch form-check-inline" for="stocksCheckbox{{$shift->id}}">
                                        <input type="checkbox" onclick="location.href='{{route('admin.shift.status',[$shift['id'],$shift->status?0:1])}}'"class="form-check-input" id="stocksCheckbox{{$shift->id}}" {{$shift->status?'checked':''}}>
                                        <span class="toggle-switch-label">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                    </label>
                                </td>
                                <td>
                                    <div class="btn--container justify-content-center">
                                        <a class="btn btn-sm btn--primary btn-outline-primary action-btn" href="{{route('admin.shift.edit',[$shift['id']])}}" title="{{__('messages.edit')}} {{__('messages.shift')}}">
                                            <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82812 10.921Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M15.1655 4.60254L19.7315 9.16854" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                             </svg>
                                        </a>
                                        <a class="btn btn-sm btn-outline-danger action-btn" href="javascript:"
                                            onclick="form_alert('shift-{{$shift['id']}}','{{__('messages.Want_to_delete_this_item')}}')" title="{{__('messages.delete')}} {{__('messages.shift')}}">
                                                <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor">
                                                    <path d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    <path d="M20.708 6.23975H3.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    <path d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                </svg>
                                        </a>
                                        <form action="{{route('admin.shift.delete',['shift' =>$shift['id']])}}"
                                                    method="post" id="shift-{{$shift['id']}}">
                                            @csrf @method('delete')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach  
                         </tbody>
                      </table>
                   </div>
                </div>
             </div>
          </div>
       </div>
    </div>
</div>
<div class="modal fade" id="add-new-shift" tabindex="-1" aria-labelledby="addNewModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="addNewModal">Add Shift</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-30">
            <form action="javascript:void(0)" id="system-form"   method="post">
                @csrf
                @method('post')
                <div class="form-group">
                    <label for="name" class="mb-2">{{ __('messages.name') }}</label>
                    <input type="name" name="name" required  id="name" class="form-control" placeholder="Ex: Morning">
                </div>
                <br>
                <div class="form-group">
                    <label for="zone_id" class="mb-2">{{ __('messages.zone') }} <span class="text-danger">*</span></label>
                    <select name="zone_id" id="zone_id" class="form-select" required>
                        <option value="">Select Zone</option>
                        @foreach($zones as $zone)
                            <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                        @endforeach
                    </select>
                </div>
                <br>
                <div class="form-group">
                    <label for="start_time" class="mb-2">{{ __('messages.Start_Time') }}</label>
                    <input type="time" required  name="start_time" id="start_time" class="form-control">
                </div>
                <br>
                <div class="form-group">
                    <label for="end_time" class="mb-2">{{ __('End_Time') }}</label>
                    <input type="time" required name="end_time" id="end_time" class="form-control" >
                </div>
                <br>

                <div class="modal-footer">
                    <button id="reset_btn" type="reset" data-dismiss="modal" class="btn btn-secondary" >{{ __('Reset') }} </button>
                    <button class="btn btn-primary" type="submit">{{ __('Submit') }}</button>
                </div>
            </form>
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
            const res = await fetch("{{ route('admin.shift.store') }}", {
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
                // Reset form and close modal
                vehicleForm.reset();
                $('#add-new-shift').modal('hide');
                // Reload page to show new shift
                setTimeout(() => {
                    location.reload();
                }, 1000);
            }
        } catch (error) {
            toastr.error(error.message);
            console.error(error);
        }
    });

    /**
     * =================// Zone filtering functionality //================= 
     */
    document.getElementById('zone-filter').addEventListener('change', function() {
        const zoneId = this.value;
        if (zoneId) {
            // Filter table rows based on selected zone
            filterTableByZone(zoneId);
        } else {
            // Show all rows
            showAllRows();
        }
    });

    function filterTableByZone(zoneId) {
        const tableRows = document.querySelectorAll('#datatable tbody tr');
        tableRows.forEach(function(row) {
            const zoneCell = row.cells[2]; // Zone is the 3rd column (index 2)
            if (zoneCell) {
                const zoneName = zoneCell.textContent.trim();
                // You might need to adjust this logic based on how you want to match zones
                if (zoneName === 'No Zone' && zoneId !== 'no-zone') {
                    row.style.display = 'none';
                } else {
                    // For a more precise filter, you might want to add data attributes to rows
                    row.style.display = '';
                }
            }
        });
    }

    function showAllRows() {
        const tableRows = document.querySelectorAll('#datatable tbody tr');
        tableRows.forEach(function(row) {
            row.style.display = '';
        });
    }
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