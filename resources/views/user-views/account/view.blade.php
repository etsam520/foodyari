@extends('user-views.restaurant.layouts.main')
@section('containt')
<div class="d-lg-none d-block m-1">
    <div class="bg-primary d-flex w-100 rounded-4">
        <a class="text-white fw-bolder fs-4 me-auto mb-0 py-3 px-4" onclick="window.history.back()"
            style="border-right: 1px solid white;">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h4 class="fw-bold m-0 text-white w-100 align-self-baseline text-center p-3 ps-0"><i
                class="fas fa-user me-2"></i>Profile</h4>
    </div>
</div>
<div class="container position-relative">
    <div class="py-5 osahan-profile row">
        <div class="col-md-4 mb-3 m-auto">
            <div class="bg-white rounded shadow-sm sticky_sidebar overflow-hidden w-100">
                <div style="height: 120px;background:#ffbf828a;"></div>
                {{-- <a href="profile.html" class=""> --}}
                    <div class="p-3" style="    margin-top: -75px;">
                        <div class="text-center">
                            <label for="user-profile" class="left" type="button">
                                <img alt="user profile" style="width: 100px;
                                    height: 100px;" id="user-profile-image"
                                    src="{{$user->image ? asset('customers/' . $user->image) : asset('assets/images/icons/user.png')}}"
                                    class="rounded-circle bg-white p-1 shadow">
                            </label>
                        </div>
                        <div class="mt-3">
                            <div class="text-center">
                                <h3 class="mb-1 fw-bold">
                                    {{Str::ucfirst($user->f_name) . ' ' . Str::ucfirst($user->l_name)}} <i
                                        class="feather-check-circle text-success"></i>
                                </h3>
                                <p class="text-muted m-0">{{$user->phone}}</p>
                                <p class="text-muted m-0">{{$user->email}}</p>
                                <a href="" class="btn btn-primary mt-3 rounded-4" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true"
                                    aria-controls="collapseOne">Update
                                    Profile</a>
                            </div>
                            <div id="collapseOne" class="collapse mt-4" aria-labelledby="headingOne"
                                data-bs-parent="#accordionExample">
                                <div class="osahan-card-body border-top p-3">
                                    <form action="{{route('user.update')}}" method="POST" enctype="multipart/form-data">
                                        @csrf

                                        <input type="file" name="image" id="user-profile"
                                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*"
                                                onchange="readImage(this,'#user-profile-image')" hidden>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="pb-1">Full Name</label>
                                                    <input type="text" class="form-control" name="f_name"
                                                        value="{{Str::ucfirst($user->f_name)}}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="pb-1">Mobile Number</label>
                                                    <input type="text" class="form-control" name="phone"
                                                        value="{{$user->phone}}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="pb-1">Email</label>
                                                    <input type="email" class="form-control" name="email"
                                                        value="{{$user->email}}">
                                                </div>
                                            </div>
                                            @php($address = json_decode($user->address))
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="pb-1">Gender</label>
                                                    <select name="gender" id="" class="form-control">
                                                        <option {{$user->gender == "Male" ? "selected": null}} value="Male">Male</option>
                                                        <option {{$user->gender == "Female" ? "selected": null}}value="Female">Female</option>
                                                        <option {{$user->gender == "Transgender" ? "selected": null}}value="Transgender">Transgender</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="pb-1">Date of Birth</label>
                                                    <input type="date" class="form-control" name="dob"
                                                        value="{{$user->dob ?? null}}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="pb-1">Martial Status</label>
                                                    <select name="merital_status" id="" class="form-control">
                                                        <option {{$user->merital_status =="Single" ? "selected" : null}} value="Single">Single</option>
                                                        <option {{$user->merital_status =="Married" ? "selected" : null}} value="Married">Married</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="pb-1">Anniversary Date</label>
                                                    <input type="date" class="form-control" name="anniversary_date"
                                                        value="{{$user->anniversary ?? null}}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="pb-1">Street</label>
                                                    <input type="text" class="form-control" name="street"
                                                        value="{{$address->street ?? null}}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="pb-1">City</label>
                                                    <input type="text" class="form-control" name="city"
                                                        value="{{$address->city ?? null}}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="pb-1">Pincode</label>
                                                    <input type="text" class="form-control" name="pincode"
                                                        value="{{$address->pincode ?? null}}">
                                                </div>
                                            </div>


                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary w-100">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{--
                </a> --}}
                <!-- profile-details -->
                {{-- <div class="bg-white profile-details">
                    <a class="d-flex align-items-center mt-2">
                        <div class="additional mx-2">
                            <div class="deactivate_account">
                                <a href="{{route('user.auth.delete-account')}}"
                                    class="p-3 border text-white rounded bg-primary btn d-flex align-items-center">Delete
                                    Account
                                    <i class="feather-arrow-right text-white ms-auto"></i></a>
                            </div>
                        </div>
                    </a>

                    <a href="javascript:void(0)" class="d-flex w-100 align-items-center border-bottom px-3 py-4">
                        <div class="left me-3">
                            <h6 class="fw-bold m-0 text-dark"><i
                                    class="feather-phone bg-primary text-white p-2 rounded-circle me-2"></i> Contact
                            </h6>
                        </div>
                        <div class="right ms-auto">
                            <span class="fw-bold m-0"><i class="feather-chevron-right h6 m-0"></i></span>
                        </div>
                    </a>
                    <a href="javascript:void(0)" class="d-flex w-100 align-items-center border-bottom px-3 py-4">
                        <div class="left me-3">
                            <h6 class="fw-bold m-0 text-dark"><i
                                    class="feather-info bg-success text-white p-2 rounded-circle me-2"></i> Term &
                                Condition</h6>
                        </div>
                        <div class="right ms-auto">
                            <span class="fw-bold m-0"><i class="feather-chevron-right h6 m-0"></i></span>
                        </div>
                    </a>
                    <a href="javascript:void(0)" class="d-flex w-100 align-items-center px-3 py-4">
                        <div class="left me-3">
                            <h6 class="fw-bold m-0 text-dark"><i
                                    class="feather-lock bg-warning text-white p-2 rounded-circle me-2"></i> Privacy
                                policy</h6>
                        </div>
                        <div class="right ms-auto">
                            <span class="fw-bold m-0"><i class="feather-chevron-right h6 m-0"></i></span>
                        </div>
                    </a>
                </div> --}}
            </div>
        </div>

    </div>
</div>
@endsection
@push('javascript')
    <script>
        function readImage(input, selector) {
            try {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const imgSrc = e.target.result;
                    document.querySelector(selector).src = imgSrc;
                };
                reader.readAsDataURL(input.files[0]);
            } catch (error) {
                console.error(error);
            }

        }
    </script>
@endpush
