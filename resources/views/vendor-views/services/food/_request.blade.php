@extends('vendor-views.layouts.dashboard-main')

@section('content')
    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h5 class="page-header-title">
                                <svg width="30px" viewBox="-2.1 -2.1 25.20 25.20" version="1.1"
                                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    fill="#000000">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <title>plus_circle [#1441]</title>
                                        <desc>Created with Sketch.</desc>
                                        <defs> </defs>
                                        <g id="Page-1" stroke-width="0.315" fill="none" fill-rule="evenodd">
                                            <g id="Dribbble-Light-Preview" transform="translate(-419.000000, -520.000000)"
                                                fill="currentColor">
                                                <g id="icons" transform="translate(56.000000, 160.000000)">
                                                    <path
                                                        d="M374.55,369 L377.7,369 L377.7,371 L374.55,371 L374.55,374 L372.45,374 L372.45,371 L369.3,371 L369.3,369 L372.45,369 L372.45,366 L374.55,366 L374.55,369 Z M373.5,378 C368.86845,378 365.1,374.411 365.1,370 C365.1,365.589 368.86845,362 373.5,362 C378.13155,362 381.9,365.589 381.9,370 C381.9,374.411 378.13155,378 373.5,378 L373.5,378 Z M373.5,360 C367.70085,360 363,364.477 363,370 C363,375.523 367.70085,380 373.5,380 C379.29915,380 384,375.523 384,370 C384,364.477 379.29915,360 373.5,360 L373.5,360 Z"
                                                        id="plus_circle-[#1441]"> </path>
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                </svg>
                                {{__('messages.new') . " " . __('Payment Request')}}
                            </h5>
                        </div>
                    </div>
                    <div class="card-body">

                        <form action="" method="post" id="food_add_request" enctype="multipart/form-data">
                            @csrf
                            {{-- <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">
                                        <span class="card-title-icon"><i class="tio-user"></i></span>
                                        <span>
                                            {{ __('messages.general_info') }}
                                        </span>
                                    </h5>
                                </div>

                                <div class="card-body pb-2"> --}}
                                    <div class="row">
                                        <div class="col-12">
                                            @if ($errors->any())
                                                <div class="alert alert-danger">
                                                    <ul>
                                                        @foreach ($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="input-label" for="image">Image</label>
                                                        <input type="file" name="image" id="image"
                                                            accept="image/jpeg, image/png" class="form-control h--45px">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    {{-- <p class="text-muted mb-3">OR</p> --}}
                                                    <div class="form-group">
                                                        <label class="input-label" for="pdf">PDF</label>
                                                        <input type="file" name="pdf" id="pdf" accept="application/pdf"
                                                            class="form-control h--45px">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    {{-- <p class="text-muted mb-3">OR</p> --}}
                                                    <div class="form-group">
                                                        <label class="input-label" for="excel">Excel || CSV</label>
                                                        <input type="file" name="excel" id="excel" accept=".xls,.xlsx, .csv"
                                                            class="form-control h--45px">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label class="input-label" for="notes">Request Note</label>
                                                    <textarea name="notes" id="notes" class="form-control" cols="30"
                                                        rows="3"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <hr style="border: 1px solid #cecbcb;">
                                        <div class="text-end">
                                            <button type="reset" id="reset_btn"
                                                class="btn btn-danger">Reset</button>
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                    {{--
                                </div>
                            </div> --}}
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h5 class="page-header-title"> Payment Request <div class="list-group"></div>
                        </div>
                    </div>
                    <div class="card-body">

                        <!-- Table -->
                        <div class="table-responsive datatable-custom">
                            <table id="datatable"
                                class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                                data-toggle="data-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="w-60px">
                                            {{ __('messages.sl') }}
                                        </th>
                                        <th class="w-140px">Date</th>
                                        <th class="w-140px">Downlod</th>
                                        <th class="w-140px">R Download</th>
                                        <th class="w-140px">Status</th>
                                        <th class="w-100px text-center">Request Note</th>
                                        <th class="w-100px text-center">Response Note</th>
                                    </tr>
                                </thead>


                                <tbody id="set-rows">
                                    @foreach($serviceFoodRequests as $key => $foodRequest)
                                        <tr class="class-all">
                                            <td class="">{{$key + 1}}</td>
                                            <td>
                                                <span class="d-block">
                                                    {{date('d M Y', strtotime($foodRequest['created_at']))}}
                                                </span><br>
                                                <span class="d-block text-uppercase">
                                                    {{date(config('timeformat'), strtotime($foodRequest['created_at']))}}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($foodRequest->pdf != null)
                                                    <a href="{{ asset('foodRequest/' . $foodRequest->pdf) }}"
                                                        download="requestedFoods.{{ pathinfo($foodRequest->pdf, PATHINFO_EXTENSION) }}">
                                                        <i data-feather="download"></i> Download File
                                                    </a>
                                                @elseif ($foodRequest->image != null)
                                                    <a href="{{ asset('foodRequest/' . $foodRequest->image) }}"
                                                        download="requestedFoods.{{ pathinfo($foodRequest->image, PATHINFO_EXTENSION) }}">
                                                        <i data-feather="download"></i> Download File
                                                    </a>
                                                @elseif ($foodRequest->excel != null)
                                                    <a href="{{ asset('foodRequest/' . $foodRequest->excel) }}"
                                                        download="requestedFoods.{{ pathinfo($foodRequest->excel, PATHINFO_EXTENSION) }}">
                                                        <i data-feather="download"></i> Download File
                                                    </a>
                                                @endif

                                            </td>
                                            <td>
                                                @if ($foodRequest->attachement != null)
                                                    <a href="{{ asset('foodRequest/' . $foodRequest->attachement) }}"
                                                        download="requestedFoods.{{ pathinfo($foodRequest->attachement, PATHINFO_EXTENSION) }}">
                                                        <i data-feather="download"></i> Download File
                                                    </a>
                                                @endif

                                            </td>
                                            <td>
                                                <div class="text-right mw-85px">
                                                    @if($foodRequest->status == 'approve')
                                                        <strong class="text-success">
                                                            Approved
                                                        </strong>
                                                    @elseif($foodRequest->status == 'reject')
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
                                            <td>{{$foodRequest->restaurant_remarks ?? ''}}</td>
                                            <td>{{$foodRequest->admin_remarks ?? ''}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if(count($serviceFoodRequests) === 0)
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

@endsection
@push('javascript')
    {{--
    <script>
        const paymentForm = document.getElementById('paymentForm');
        paymentForm.addEventListener('submit', async (event) => {
            try {
                event.preventDefault(); // Prevent the asform from submitting the traditional way


                const formData = new FormData(paymentForm);

                const resp = await fetch(paymentForm.action, { // Replace with your actual endpoint
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // If using Laravel CSRF token
                    },
                    body: formData, // Send the form data as JSON
                });

                if (!resp.ok) {
                    const error = await resp.json();
                    throw new Error(error.message);
                }

                const result = await resp.json();

                toastr.success(result.message);
                paymentForm.reset();
            } catch (error) {
                toastr.error(error.message);
                console.error(error.message);
            }
        });

        (async () => {
            try {
                const resp = await fetch(`{{ route('vendor.banking.get-bank-details') }}`);
                if (!resp.ok) {
                    throw new Error(`HTTP error! Status: ${resp.status}`);
                }
                const result = await resp.json();
                paymentForm.account_number.value = result?.account_number;
                paymentForm.bank_name.value = result?.bank_name;
                paymentForm.ifsc_code.value = result?.ifsc_code;
                paymentForm.upi_id.value = result?.upi_id;
                paymentForm.account_holder_name.value = result?.account_holder_name;
            } catch (error) {
                console.error('Error fetching bank details:', error);
            }
        })();

        paymentRequests
    </script> --}}

@endpush
