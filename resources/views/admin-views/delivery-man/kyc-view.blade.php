@extends('layouts.dashboard-main')

@section('content')
    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div class="row" style="background-color: #f4f5f7;">
            <div class="container py-5 h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col col-lg-12 mb-4 mb-lg-0">
                        <div class="card mb-3" style="border-radius: .5rem;">
                            <div class="row g-0">
                                <!-- Left Column -->
                                <div class="d-none col-md-4 gradient-custom text-center text-white" style="border-top-left-radius: .5rem; border-bottom-left-radius: .5rem;">
                                    {{-- <a href="http://localhost:8080/foodyari_live/restaurant-panel/profile/edit" class="btn btn-sm btn-soft-info">Edit</a>
                                    <a href="http://localhost:8080/foodyari_live/restaurant-panel/business-settings/restaurant-setup" class="btn btn-sm btn-soft-info">Business Setting</a> --}}
                                </div>

                                <!-- Right Column -->
                                <div class="col-md-12">
                                    <div class="card-body p-4">
                                        <!-- Contact Information -->
                                        <h3>Deliveryman Kyc Information</h3>
                                        <hr class="mt-0 mb-4">
                                        <div class="row pt-1">
                                            <div class="col-md-6 mb-2">
                                                <h6>Delivleryman Name</h6>
                                                <p class="text-muted">
                                                    {{Str::ucfirst($delivery_man['name'])}}
                                                </p>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <h6>Phone No.</h6>
                                                <p class="text-muted">{{$delivery_man['phone']}}</p>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <h6>Email ID</h6>
                                                <p class="text-muted">{{$delivery_man['email']}}</p>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <h6>Full Address</h6>
                                                {{-- <p class="text-muted">{{$delivery_man['deliveryman_address']}}</p> --}}
                                            </div>
                                            {{-- <div class="col-md-6 mb-2">
                                                <h6>Bike Number</h6> --}}
                                                {{-- <p class="text-muted">{{$delivery_man['bike_number']}}</p> --}}
                                            </div>
                                            <div class="col-12">
                                                <hr class="hr-horizontal">
                                            </div>
                                            <div class="col-md-12 mb-2 mt-3">
                                                <h6>KYC Status ({{Str::ucfirst($delivery_man->kyc->status)}})</h6>
                                                @if($delivery_man->kyc->status == "pending")
                                                <p class="text-muted mt-2">
                                                    <span class="d-flex">
                                                        <button class="btn btn-sm btn-primary me-2" onclick="updateKycStatus(this)" url="{{route('admin.joinas.deliveryman-kyc-update-status', ['id' => $delivery_man->kyc->id, 'status' => 'rejected'])}}">Reject</button>
                                                        <button class="btn btn-sm btn-success" onclick="updateKycStatus(this)" url="{{route('admin.joinas.deliveryman-kyc-update-status', ['id' => $delivery_man->kyc->id, 'status' => 'approved'])}}">Approve</button>
                                                    </span>
                                                </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                           <div class="card-header d-flex justify-content-between">
                              <div class="header-title">
                                 <h4 class="card-title">Documents</h4>
                              </div>
                           </div>
                           <div class="card-body px-0">
                               <div class="table-responsive">
                                   <table id="datatable" class="table" role="grid" >
                                       <thead>
                                           <tr>
                                               <th scope="col">Document Name</th>
                                               <th scope="col">Id/No.</th>
                                               <th scope="col">View</th>
                                               <th scope="col">Expiry</th>
                                               <th scope="col">Status</th>
                                               <th scope="col" class="text-right">Actions</th>
                                           </tr>
                                       </thead>
                                       <tbody>
                                        @foreach ($documentDetails as $documentDetail)
                                        <tr>
                                            <td>{{Str::ucfirst($documentDetail->document->name)}}</td>
                                            <td>{{Str::ucfirst($documentDetail->text_value)}}</td>
                                            <td><a href="{{asset('uploads/kyc/'.$documentDetail->media_value)}}"><i><svg class="icon-32" width="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                            d="M8.09756 12C8.09756 14.1333 9.8439 15.8691 12 15.8691C14.1463 15.8691 15.8927 14.1333 15.8927 12C15.8927 9.85697 14.1463 8.12121 12 8.12121C9.8439 8.12121 8.09756 9.85697 8.09756 12ZM17.7366 6.04606C19.4439 7.36485 20.8976 9.29455 21.9415 11.7091C22.0195 11.8933 22.0195 12.1067 21.9415 12.2812C19.8537 17.1103 16.1366 20 12 20H11.9902C7.86341 20 4.14634 17.1103 2.05854 12.2812C1.98049 12.1067 1.98049 11.8933 2.05854 11.7091C4.14634 6.88 7.86341 4 11.9902 4H12C14.0683 4 16.0293 4.71758 17.7366 6.04606ZM12.0012 14.4124C13.3378 14.4124 14.4304 13.3264 14.4304 11.9979C14.4304 10.6597 13.3378 9.57362 12.0012 9.57362C11.8841 9.57362 11.767 9.58332 11.6597 9.60272C11.6207 10.6694 10.7426 11.5227 9.65971 11.5227H9.61093C9.58166 11.6779 9.56215 11.833 9.56215 11.9979C9.56215 13.3264 10.6548 14.4124 12.0012 14.4124Z"
                                                            fill="currentColor"></path>
                                                    </svg> view</i>
                                                </a>
                                            </td>
                                            <td>{{ $documentDetail->expire_date }}</td>
                                            <td>{{Str::ucfirst($documentDetail->status)}}</td>
                                            <td class="d-flex flex-wrap">
                                                {{-- @dd($documentDetail->status) --}}
                                                @if($documentDetail->status == "pending" )
                                                <button class="btn btn-sm btn-primary" onclick="updateDocumentStatus(this)" url="{{route('admin.joinas.deliveryman-doc-update-status',['id'=>$documentDetail->id, 'status'=>'rejected'])}}">Reject</button>
                                                @endif
                                                @if($documentDetail->status == "rejected" || $documentDetail->status == "pending")
                                                <button class="btn btn-sm btn-success" onclick="updateDocumentStatus(this)" url="{{route('admin.joinas.deliveryman-doc-update-status',['id'=>$documentDetail->id, 'status'=>'approved'])}}">Approve</button>
                                                @endif
                                                <button class="btn btn-sm btn-danger">Remove</button>
                                                <button class="btn btn-sm btn-warning" data-update-document-key="{{$documentDetail->document->id}}">Edit</button>
                                            </td>
                                        </tr>
                                        <tr class="{{old($documentDetail->document->text_input_name)==null ? "d-none" : null}}" data-update-document-id="{{ $documentDetail->document->id }}">
                                            <td colspan="6">
                                                <form action="{{route('admin.joinas.restaurant-doc-update')}}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="document_id" value="{{ $documentDetail->document->id }}">
                                                    <input type="hidden" name="document_detail_id" value="{{ $documentDetail->id }}">
                                                    <input type="hidden" name="kyc_id" value="{{ $documentDetail->kyc_id }}">
                                                    @if ($errors->any())
                                                        <div class="alert alert-danger">
                                                            <ul>
                                                                @foreach ($errors->all() as $error)
                                                                    <li>{{ $error }}</li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    @endif
                                                    <div class="mb-3">
                                                        <span class="fw-bold"><b>{{ $documentDetail->document->name }}</b></span>
                                                        <div class="border rounded p-3">
                                                        @if ($documentDetail->document->is_text)
                                                            <div class="mb-3">
                                                            <small>{{ $documentDetail->document->name }} ID/Number
                                                                @if ($documentDetail->document->is_text_required)
                                                                <span class="text-danger">*</span>
                                                                @endif
                                                            </small>
                                                            <input type="text" class="form-control" data-type="{{ strtolower($documentDetail->document->name) }}" name="{{ $documentDetail->document->text_input_name }}" value="{{$documentDetail->text_value?? old($documentDetail->document->text_input_name) ?? null }}" placeholder="{{ $documentDetail->document->name }} ID/Number" @if ($documentDetail->document->is_text_required) required @endif>
                                                            </div>
                                                        @endif
                                                        @if ($documentDetail->document->is_media)
                                                            <div class="mb-3">
                                                            <small>Upload documentDetail {{ $documentDetail->document->name }}
                                                                @if ($documentDetail->document->is_media_required)
                                                                <span class="text-danger">*</span>
                                                                @endif
                                                            </small>
                                                            <div class="input-group" role="button" data-toggle="FileUploader" data-type="image" id="{{ $documentDetail->document->media_input_name }}" data-preview="#{{ $documentDetail->document->media_input_name }}_preview">
                                                                {{-- <div class="input-group-text bg-soft-secondary">Browse</div> --}}
                                                                <input type="file"  class="form-control" name="{{ $documentDetail->document->media_input_name }}"  class="selected-files">
                                                                {{-- <div class="form-control file-amount text-truncate">Choose Files</div> --}}
                                                            </div>
                                                            <div id="{{ $documentDetail->document->media_input_name }}_preview" data-parent="#{{ $documentDetail->document->media_input_name }}"></div>
                                                            </div>
                                                        @endif
                                                        @if ($documentDetail->document->has_expiry_date)
                                                            <div class="mb-3">
                                                            <small>Expire Date</small>
                                                            <input type="date" class="form-control" name="{{ $documentDetail->document->expire_date_input_name }}" value="{{$documentDetail->expire_date?? old($documentDetail->document->expire_date_input_name) ?? null }}">
                                                            </div>
                                                        @endif
                                                            <button type="submit" class="btn btn-warning btn-sm ">Update</button>
                                                        </div>
                                                    </div>
                                                </form>
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
    </div>
@endsection

@push('javascript')
<script>
    $(document).ready(function() {
        $('[data-update-document-key]').on('click', function() {
            const key = $(this).data('update-document-key');
            $('[data-update-document-id]').addClass('d-none');
            $('[data-update-document-id="'+key+'"]').removeClass('d-none');
        });
    });
    function updateDocumentStatus(element){
        const url = element.getAttribute('url');
        window.location.href = url;
    }

    function updateKycStatus(element){
        const url = element.getAttribute('url');
        window.location.href = url;
    }
    function updateFormStatus(element){
        const url = element.getAttribute('url');
        window.location.href = url;
    }
</script>
@endpush
