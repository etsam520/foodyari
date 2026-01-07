@php($user = App\Models\Customer::find(Auth::guard('customer')->user()->id))
<div class="col-md-4 mb-3">
    <div class="bg-white rounded shadow-sm sticky_sidebar overflow-hidden">
        <a href="profile.html" class="">
            <div class="d-flex align-items-center p-3">
                <div class="left me-3">
                    <img alt="#" src="{{asset('customers/'.$user->image)}}" class="rounded-circle" style="width: 50px;">
                </div>
                <div class="right">
                    <h6 class="mb-1 fw-bold">{{Str::ucfirst($user->f_name).' '.Str::ucfirst($user->l_name)}} <i class="feather-check-circle text-success"></i></h6>
                    <p class="text-muted m-0 small">{{$user->email}}</p>
                </div>
            </div>
        </a>
        <!-- profile-details -->
        <div class="bg-white profile-details">
            <a class="d-flex align-items-center mt-2" >
                <div class="additional mx-2">
                    <div class="deactivate_account">
                        <a href="javascript:void(0)" class="p-3 border text-white rounded bg-primary btn d-flex align-items-center">Deactivate Account
                        <i class="feather-arrow-right text-white ms-auto"></i></a>
                    </div>
                </div>
            </a>
            <a class="d-flex align-items-center border-bottom p-3" data-bs-toggle="modal" data-bs-target="#inviteModal">
                <div class="left me-3">
                    <h6 class="fw-bold mb-1">Refer Friends</h6>
                    <p class="small text-primary m-0">Get $10.00 FREE</p>
                </div>
                <div class="right ms-auto">
                    <span class="fw-bold m-0"><i class="feather-chevron-right h6 m-0"></i></span>
                </div>
            </a>
            <a href="{{route('user.contact-us')}}" class="d-flex w-100 align-items-center border-bottom px-3 py-4">
                <div class="left me-3">
                    <h6 class="fw-bold m-0 text-dark"><i class="feather-phone bg-primary text-white p-2 rounded-circle me-2"></i> Contact</h6>
                </div>
                <div class="right ms-auto">
                    <span class="fw-bold m-0"><i class="feather-chevron-right h6 m-0"></i></span>
                </div>
            </a>
            <a href="{{route('user.pages',['name' => 'terms_and_conditions'])}}" class="d-flex w-100 align-items-center border-bottom px-3 py-4">
                <div class="left me-3">
                    <h6 class="fw-bold m-0 text-dark"><i class="feather-info bg-success text-white p-2 rounded-circle me-2"></i> Term & Condition</h6>
                </div>
                <div class="right ms-auto">
                    <span class="fw-bold m-0"><i class="feather-chevron-right h6 m-0"></i></span>
                </div>
            </a>
            <a href="{{route('user.pages',['name' => 'privacy_policy'])}}" class="d-flex w-100 align-items-center px-3 py-4">
                <div class="left me-3">
                    <h6 class="fw-bold m-0 text-dark"><i class="feather-lock bg-warning text-white p-2 rounded-circle me-2"></i> Privacy policy</h6>
                </div>
                <div class="right ms-auto">
                    <span class="fw-bold m-0"><i class="feather-chevron-right h6 m-0"></i></span>
                </div>
            </a>
        </div>
    </div>
</div>
