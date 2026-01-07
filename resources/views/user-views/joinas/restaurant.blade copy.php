@extends('user-views.restaurant.layouts.main')


@section('containt')
    <div
        style="background: url(https://static.vecteezy.com/system/resources/previews/009/715/641/non_2x/abstract-gradient-geometric-background-dynamic-orange-poster-graphics-abstract-background-texture-design-vector.jpg);
    background-color: #ffffff;
    background-blend-mode: darken;">
        <div class="container position-relative" style="background: #ffffff63;max-width: 100%;">
            <div class="py-5 osahan-profile row d-flex justify-content-center" style="backdrop-filter: blur(6px);">
                <div class="col-md-8 mb-3">
                    <div class="rounded shadow-sm p-4 bg-white">
                        <h5 class="mb-4 border-bottom pb-3 fw-bolder">Join us as Restaurant</h5>
                        <div id="edit_profile">
                            <div>
                                <form action="{{ route('user.update') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="file" name="image" id="user-profile" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" onchange="readImage(this,'#user-profile-image')" hidden>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="pb-1">Upload Restaurant Logo <span class="text-danger">*</span></label>
                                                <input type="file" class="form-control" name="f_name" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="pb-1">Restaurant Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="l_name" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="pb-1">GST No.</label>
                                                <input type="number" class="form-control" name="phone" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="pb-1">Email ID <span class="text-danger">*</span></label>
                                                <input type="email" class="form-control" name="email" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="pb-1">Full Address <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="street" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="pb-1">Restaurant No. <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="city" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="pb-1">Restaurant FSSAI(Food License)</label>
                                                <input type="text" class="form-control" name="pincode" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="pb-1">Restaurant Bank Details <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="pincode" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="pb-1">Restaurant Owner Aadhar Card <span class="text-danger">*</span></label>
                                                <input type="file" class="form-control" name="pincode" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="pb-1">Restaurant Owner Aadhar Card No. <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="pincode" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="pb-1">Restaurant Owner PAN Card <span class="text-danger">*</span></label>
                                                <input type="file" class="form-control" name="pincode" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="pb-1">Restaurant Owner PAN No. <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="pincode" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center mt-4">
                                        <button type="submit" class="btn btn-primary w-100">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
