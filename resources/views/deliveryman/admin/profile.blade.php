@extends('deliveryman.admin.layouts.main')
@section('content')
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
            <div class="res-section">
                <div class="col-md-5 m-auto">
                    <div class="bg-white rounded shadow-sm sticky_sidebar overflow-hidden w-100">
                        <div style="height: 120px;background:#ffbf828a;"></div>
                        {{-- <a href="profile.html" class=""> --}}
                            <div class="p-3" style="    margin-top: -75px;">
                                <div class="text-center">
                                    <label for="user-profile" class="left" type="button">
                                        <img alt="user profile" style="width: 100px;
                                            height: 100px;" id="user-profile-image"
                                            src="{{ $dm['image'] != null ? asset('delivery-man/'.$dm['image']) :  asset('assets/user/img/user2.png')}}"
                                            class="rounded-circle bg-white p-1 shadow">
                                    </label>
                                    <span class="text-danger error-profile_image"></span>
                                </div>
                                <div class="mt-3">
                                    <div class="text-center">
                                        <h3 class="mb-1 fw-bold">Swati
                                            <i class="feather-check-circle text-success"></i>
                                        </h3>
                                        <p class="text-muted m-0">{{$dm['phone']}}</p>
                                        <p class="text-muted m-0">{{$dm['email']}}</p>
                                        <div class="d-flex justify-content-center">
                                            <a href="" class="btn btn-primary mt-3 rounded-4 me-2" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true"
                                                aria-controls="collapseOne"
                                                onclick="closeOtherCollapse('#collapseTwo')">Update
                                                Profile</a>
                                            <a href="" class="btn btn-primary mt-3 rounded-4" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true"
                                                aria-controls="collapseTwo"
                                                onclick="closeOtherCollapse('#collapseOne')">View Document</a>
                                        </div>
                                        <script>
                                            function closeOtherCollapse(collapseId) {
                                                const collapseElement = document.querySelector(collapseId);
                                                if (collapseElement && collapseElement.classList.contains('show')) {
                                                    new bootstrap.Collapse(collapseElement, { toggle: true });
                                                }
                                            }
                                        </script>
                                    </div>
                                    <div id="collapseOne" class="collapse mt-4" aria-labelledby="headingOne"
                                        data-bs-parent="#profileAccordion">
                                        <div class="osahan-card-body border rounded-4 p-3">
                                            <form action="{{route('deliveryman.profile-update')}}" method="POST" id="profile_form"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <div class="invalid-feedback"></div>
                                                <input type="file" name="profile_image" id="user-profile"
                                                    accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*"
                                                    onchange="readImage(this,'#user-profile-image')" hidden>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-3">
                                                            <label class="pb-1">First Name</label>
                                                            <input type="text" class="form-control" name="f_name" value="{{$dm['f_name']}}">
                                                            <span class="text-danger error-fname"></span>
                                                        </div>
                                                  </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-3">
                                                            <label class="pb-1">Last Name</label>
                                                            <input type="text" class="form-control" name="l_name" value="{{$dm['l_name']}}">
                                                            <span class="text-danger error-lname"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-3">
                                                            <label class="pb-1">Mobile Number</label>
                                                            <input type="text" class="form-control" name="phone" value="{{$dm['phone']}}">
                                                            <span class="text-danger error-phone"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-3">
                                                            <label class="pb-1">Email</label>
                                                            <input type="email" class="form-control" name="email" value="{{$dm['email']}}">
                                                            <span class="text-danger error-email"></span>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group mb-3">
                                                            <label class="pb-1">Gender</label>
                                                            <select name="gender" id="" class="form-control">
                                                                <option value="male">Male</option>
                                                                <option value="female">Female</option>
                                                                <option value="other">Other</option>
                                                            </select>
                                                        </div>
                                                        <span class="text-danger error-gender"></span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-3">
                                                            <label class="pb-1">Date of Birth</label>
                                                            <input type="date" class="form-control" name="dob" value="">
                                                        </div>
                                                        <span class="text-danger error-dob"></span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-3">
                                                            <label class="pb-1">Martial Status</label>
                                                            <select name="merital_status" id="" class="form-control">
                                                                <option value="single">Single</option>
                                                                <option value="married">Married</option>
                                                            </select>
                                                        </div>
                                                        <span class="text-danger error-merital_status"></span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-3">
                                                            <label class="pb-1">Anniversary Date</label>
                                                            <input type="date" class="form-control" name="anniversary_date"
                                                                value="">
                                                        </div>
                                                        <span class="text-danger error-anniversary_date"></span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-3">
                                                            <label class="pb-1">Street</label>
                                                            <input type="text" class="form-control" name="street" value="">
                                                        </div>
                                                        <span class="text-danger error-street"></span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-3">
                                                            <label class="pb-1">City</label>
                                                            <input type="text" class="form-control" name="city" value="">
                                                        </div>
                                                        <span class="text-danger error-city"></span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-3">
                                                            <label class="pb-1">Pincode</label>
                                                            <input type="text" class="form-control" name="pincode" value="">
                                                        </div>
                                                        <span class="text-danger error-pincode"></span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-3">
                                                            <label class="pb-1">Blud Group</label>
                                                            <select name="blood_group" id="" class="form-control">
                                                                <option value="A+">A+</option>
                                                                <option value="A-">A-</option>
                                                                <option value="B+">B+</option>
                                                                <option value="B-">B-</option>
                                                                <option value="O+">O+</option>
                                                                <option value="O-">O-</option>
                                                                <option value="AB+">AB+</option>
                                                                <option value="AB-">AB-</option>
                                                            </select>
                                                        </div>
                                                        <span class="text-danger error-blood_group"></span>
                                                    </div>
                                                </div>
                                                <div class="text-center">
                                                    <button type="submit" class="btn btn-primary w-100">Save
                                                        Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div id="collapseTwo" class="collapse mt-4" aria-labelledby="headingOne"
                                        data-bs-parent="#profileAccordion">
                                        <div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-lg-3 mt-3">
                                                    <a href="" data-bs-toggle="modal" data-bs-target="#document-image">
                                                        <div class="position-relative img_border" style="width:110px;">
                                                            <span
                                                                class="badge bg-primary position-absolute top-0 start-50 translate-middle">Aadhar
                                                                Image</span>
                                                            <img style="width:110px;height:95px;" class="rounded-3"
                                                                src="https://media.istockphoto.com/id/1226328537/vector/image-place-holder-with-a-gray-camera-icon.jpg?s=612x612&w=0&k=20&c=qRydgCNlE44OUSSoz5XadsH7WCkU59-l-dwrvZzhXsI="
                                                                alt="">
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="col-lg-3 mt-3">
                                                    <a href="" data-bs-toggle="modal" data-bs-target="#document-image">
                                                        <div class="position-relative img_border" style="width:110px;">
                                                            <span
                                                                class="badge bg-primary position-absolute top-0 start-50 translate-middle">Aadhar
                                                                Image</span>
                                                            <img style="width:110px;height:95px;" class="rounded-3"
                                                                src="https://media.istockphoto.com/id/1226328537/vector/image-place-holder-with-a-gray-camera-icon.jpg?s=612x612&w=0&k=20&c=qRydgCNlE44OUSSoz5XadsH7WCkU59-l-dwrvZzhXsI="
                                                                alt="">
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="col-lg-3 mt-3">
                                                    <a href="" target="_blank">
                                                        <div class="position-relative img_border" style="width:110px;">
                                                            <span
                                                                class="badge bg-primary position-absolute top-0 start-50 translate-middle">Aadhar
                                                                Image</span>
                                                            <img style="width:110px;height:95px;" class="rounded-3"
                                                                src="https://geoinfo.iplaneg.net/static/documents/pdf-placeholder.png"
                                                                alt="">
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="col-lg-3 mt-3">
                                                    <a href="" data-bs-toggle="modal" data-bs-target="#document-image">
                                                        <div class="position-relative img_border" style="width:110px;">
                                                            <span
                                                                class="badge bg-primary position-absolute top-0 start-50 translate-middle">Aadhar
                                                                Image</span>
                                                            <img style="width:110px;height:95px;" class="rounded-3"
                                                                src="https://media.istockphoto.com/id/1226328537/vector/image-place-holder-with-a-gray-camera-icon.jpg?s=612x612&w=0&k=20&c=qRydgCNlE44OUSSoz5XadsH7WCkU59-l-dwrvZzhXsI="
                                                                alt="">
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="col-lg-3 mt-3">
                                                    <a href="" data-bs-toggle="modal" data-bs-target="#document-image">
                                                        <div class="position-relative img_border" style="width:110px;">
                                                            <span
                                                                class="badge bg-primary position-absolute top-0 start-50 translate-middle">Aadhar
                                                                Image</span>
                                                            <img style="width:110px;height:95px;" class="rounded-3"
                                                                src="https://media.istockphoto.com/id/1226328537/vector/image-place-holder-with-a-gray-camera-icon.jpg?s=612x612&w=0&k=20&c=qRydgCNlE44OUSSoz5XadsH7WCkU59-l-dwrvZzhXsI="
                                                                alt="">
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="col-lg-3 mt-3">
                                                    <a href="" data-bs-toggle="modal" data-bs-target="#document-image">
                                                        <div class="position-relative img_border" style="width:110px;">
                                                            <span
                                                                class="badge bg-primary position-absolute top-0 start-50 translate-middle">Aadhar
                                                                Image</span>
                                                            <img style="width:110px;height:95px;" class="rounded-3"
                                                                src="https://media.istockphoto.com/id/1226328537/vector/image-place-holder-with-a-gray-camera-icon.jpg?s=612x612&w=0&k=20&c=qRydgCNlE44OUSSoz5XadsH7WCkU59-l-dwrvZzhXsI="
                                                                alt="">
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <div class="modal fade" id="document-image" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bolder" id="exampleModalLongTitle">Check In Image</h5>
                    <button type="button" class="close border-0 bg-transparent" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="new-user-info">
                        <div class="row">
                            <div class="col-md-12 mx-auto">
                                <img src="https://media.istockphoto.com/id/1226328537/vector/image-place-holder-with-a-gray-camera-icon.jpg?s=612x612&w=0&k=20&c=qRydgCNlE44OUSSoz5XadsH7WCkU59-l-dwrvZzhXsI="
                                    class="img_border w-100" id="profile-pic" role="button"
                                    style="max-height: 400px; object-fit: contain;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Close</button>
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

        document.querySelector("#profile_form").addEventListener('submit', async (event) => {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);
            // Clear all previous error states
            form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            form.querySelectorAll('span.text-danger').forEach(span => span.textContent = '');

            const response = await fetch(form.action, {
                method: form.method,
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            const json = await response.json();
            if (json.errors) {
                for (const [key, messages] of Object.entries(json.errors)) {
                    const input = form.querySelector(`[name="${key}"]`);
                    if (input) {
                        input.classList.add('is-invalid');

                        const errorSpan = form.querySelector(`.error-${key}`);
                        if (errorSpan) {
                            errorSpan.textContent = messages[0];
                        }
                    }
                }
            }
            if (json.success) {
                Swal.fire({
                    icon: 'success',
                    title: json.success,
                    timer: 2000,
                    showConfirmButton: false
                })
            }
        })

    </script>
@endpush
