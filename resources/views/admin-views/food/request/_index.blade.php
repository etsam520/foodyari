
@extends('layouts.dashboard-main')

@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div class="row">
        <div class="col-sm-12 col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title"> Payment Request <div class="list-group"></div>
                </div>
                </div>
                <div class="card-body">

                    <!-- Table -->
                    <div class="table-responsive datatable-custom">
                        <table id="datatable" class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table"  data-toggle="data-table">
                            <thead class="thead-light">
                                <tr>
                                    <th class="w-60px">
                                        {{ __('messages.sl') }}
                                    </th>
                                    <th class="w-90px table-column-pl-0">Action</th>
                                    <th class="w-90px table-column-pl-0">Restaurant</th>
                                    <th class="w-140px">Date</th>
                                    <th class="w-90px table-column-pl-0">Downlod</th>
                                    <th class="w-90px table-column-pl-0">Status</th>
                                    <th class="w-140px">Request Note</th>
                                    <th class="w-140px">Response Note</th>
                                </tr>
                            </thead>


                            <tbody id="set-rows">
                                @foreach($serviceFoodRequests as $key=>$foodRequest)
                                <tr class="class-all">
                                    <td class="">{{$key+ 1}}</td>
                                    <td>@if($foodRequest->status !='approve')
                                        <button class="btn btn-warning btn-sm" data-request-key="{{$foodRequest->id}}">Action</button>
                                        @endif
                                        {{-- <button class="btn btn-warning btn-sm" data-pay-key="{{$paymentRequest->id}}">Action</button> --}}
                                    <td>{{Str::ucfirst($foodRequest->restaurant->name?? '')}}</td>
                                    </td>
                                    <td>
                                        <span class="d-block">
                                            {{date('d M Y',strtotime($foodRequest['created_at']))}}
                                        </span><br>
                                        <span class="d-block text-uppercase">
                                            {{date(config('timeformat'),strtotime($foodRequest['created_at']))}}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($foodRequest->pdf != null)
                                            <a href="{{ asset('foodRequest/' . $foodRequest->pdf) }}" download="requestedFoods.{{ pathinfo($foodRequest->pdf, PATHINFO_EXTENSION) }}">
                                                <i data-feather="download"></i> Download File
                                            </a>
                                        @elseif ($foodRequest->image != null)
                                            <a href="{{ asset('foodRequest/' . $foodRequest->image) }}" download="requestedFoods.{{ pathinfo($foodRequest->image, PATHINFO_EXTENSION) }}">
                                                <i data-feather="download"></i> Download File
                                            </a>
                                        @elseif ($foodRequest->excel != null)
                                            <a href="{{ asset('foodRequest/' . $foodRequest->excel) }}" download="requestedFoods.{{ pathinfo($foodRequest->excel, PATHINFO_EXTENSION) }}">
                                                <i data-feather="download"></i> Download File
                                            </a>
                                        @endif

                                    </td>
                                    <td>
                                        <div class="text-right mw-85px">
                                            @if($foodRequest->status=='approve')
                                            <strong class="text-success">
                                                Approved
                                            </strong>
                                            @elseif($foodRequest->status=='reject')
                                            <strong class="text-danger">
                                                Reject
                                            </strong>
                                            @else
                                            <strong class="text-info">
                                                Pending
                                            </strong>
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{$foodRequest->restaurant_remarks?? ''}}</td>
                                    <td>{{$foodRequest->admin_remarks?? ''}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if(count($paymentRequests) === 0)
                    <div class="text-center">
                        <img src="{{asset('assets/images/icons/nodata.png')}}" alt="public">
                    </div>
                    @endif
                    <!-- End Table -->
                </div>
                <!-- End Card -->
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="requestModal" tabindex="-1" aria-labelledby="requestModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          {{-- <h1 class="modal-title fs-5" id="requestModalLabel">Modal title</h1> --}}
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

        </div>
        {{-- <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div> --}}
      </div>
    </div>
  </div>
@endsection
@push('javascript')
<script>
    const requestButtons = document.querySelectorAll('[data-request-key]');
    const requestModal = new bootstrap.Modal(document.getElementById('requestModal'));

    requestButtons.forEach((requestButton , index) => {
        requestButton.addEventListener('click', async () => {
            try {
                const resp = await fetch(`{{ route('admin.food.reqeustsform')}}?request_key=${requestButton.dataset.requestKey}`);
                if (!resp.ok) {
                    throw new Error(`HTTP error! Status: ${resp.status}`);
                }
                const result = await resp.json();
                if(result.view != null){
                    requestModal.show();
                    document.querySelector("#requestModal .modal-body").innerHTML = result.view;

                    savePaymenetRequest();

                }

            } catch (error) {
                console.error('Error fetching bank details:', error);
            }
        })
    })
</script>

<script>

function savePaymenetRequest(){
    const requestForm = document.getElementById('requestForm');
    requestForm.addEventListener('submit',async (event) => {
        try {
            event.preventDefault(); // Prevent the asform from submitting the traditional way
            const formData = new FormData(requestForm);

            const resp = await fetch(requestForm.action, { // Replace with your actual endpoint
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // If using Laravel CSRF token
                },
                body: formData, // Send the form data as JSON
            });

            if(!resp.ok){
                const error = await resp.json();
                throw new Error(error.message);
            }

            const result = await resp.json();

           await toastr.success(result.message);
           await setTimeout(() => location.reload(), 2000);

            requestModal.hide();
            requestForm.reset();
        } catch (error) {
                toastr.error(error.message);
                console.error(error.message);
        }
    });
}
</script>

@endpush
