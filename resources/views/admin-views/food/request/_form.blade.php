{{-- @dd() --}}
<div class="row">
    <div class="col-sm-12">
        <div class="card p-0 m-0">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                   <h4 class="card-title">
                    <svg width="30px" viewBox="-2.1 -2.1 25.20 25.20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>plus_circle [#1441]</title> <desc>Created with Sketch.</desc> <defs> </defs> <g id="Page-1" stroke-width="0.315" fill="none" fill-rule="evenodd"> <g id="Dribbble-Light-Preview" transform="translate(-419.000000, -520.000000)" fill="currentColor"> <g id="icons" transform="translate(56.000000, 160.000000)"> <path d="M374.55,369 L377.7,369 L377.7,371 L374.55,371 L374.55,374 L372.45,374 L372.45,371 L369.3,371 L369.3,369 L372.45,369 L372.45,366 L374.55,366 L374.55,369 Z M373.5,378 C368.86845,378 365.1,374.411 365.1,370 C365.1,365.589 368.86845,362 373.5,362 C378.13155,362 381.9,365.589 381.9,370 C381.9,374.411 378.13155,378 373.5,378 L373.5,378 Z M373.5,360 C367.70085,360 363,364.477 363,370 C363,375.523 367.70085,380 373.5,380 C379.29915,380 384,375.523 384,370 C384,364.477 379.29915,360 373.5,360 L373.5,360 Z" id="plus_circle-[#1441]"> </path> </g> </g> </g> </g></svg>
                    {{__('messages.new')." ".__('Request Info')}}
                    </h4>
                </div>
            </div>
            <div class="card-body">

                <form action="{{ route('admin.food.reqeustsform-submit') }}" method="post" id="requestForm" enctype="multipart/form-data" >
                    <input type="text" hidden name="request_id" value="{{$serviceFoodRequest->id}}">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">
                                <span class="card-title-icon"><i class="tio-user"></i></span>
                                <span>
                                    {{ __('messages.general_info') }}
                                </span>
                            </h5>
                            <div class="mt-2">
                                <P class="mb-0 text-small"> Vendor Name :{{Str::ucfirst($serviceFoodRequest->restaurant->vendor->f_name).' '.Str::ucfirst($serviceFoodRequest->restaurant->vendor->l_name)}}</P>
                                <P class="mb-0 text-small"> Restaurant Name : {{Str::ucfirst($serviceFoodRequest->restaurant->name)}}</P>
                                <P class="mb-0 text-small"> Request Note : {{Str::ucfirst($serviceFoodRequest->restaurant_remarks??'')}}</P>
                                {{-- <P class="mb-0 text-small"> Request Amount : {{Helpers::format_currency($paymentRequest->amount)}}</P> --}}
                            </div>
                        </div>

                        <div class="card-body pb-2">
                            <div class="row ">
                                <div class="col-sm-12">
                                    <div class="form-group m-0">
                                        <label class="input-label"
                                            for="attachment">Attachement File (if any)</label>
                                        <input type="file" class="form-control" name="attachement" id="attachment">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="row g-3">

                                        <div class="col-sm-12">
                                            <div class="form-group m-0">
                                                <label class="input-label"
                                                    for="status"> Status</label>
                                                <select name="status" id="status" class="form-control h--45px">
                                                    <option value="pending">Pending</option>
                                                    <option value="reject">reject</option>
                                                    <option value="approve">accepted</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="row g-3">
                                        <div class="col-sm-12">
                                            <div class="form-group m-0">
                                                <label class="input-label"
                                                    for="remarks">Remarks</label>
                                                <textarea name="remarks" id="remarks" class="form-control" cols="30" rows="5"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-12 d-flex justify-content-between mt-3">
                                                <button type="reset" id="reset_btn" class="btn btn-outline-secondary me-2">Reset</button>
                                                <button type="submit" class="btn btn-outline-primary">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
