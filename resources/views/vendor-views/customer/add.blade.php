@extends('vendor-views.layouts.dashboard-main')
@push('css')
<link rel="stylesheet" href="{{asset('assets/vendor/flatpickr/dist/flatpickr.min.css')}}">
@endpush
@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
    <form method="POST" id="cusomer-form" enctype="multipart/form-data">
        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Add New Customer</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <div class="profile-img-edit position-relative">
                                <img src="{{asset('assets/images/avatars/01.png')}}" alt="profile-pic" id="avatar-img"
                                    class="theme-color-default-img profile-pic rounded avatar-100">
                                <div class="upload-icone bg-primary">
                                    <label for="avatar"><svg class="upload-button icon-14" width="14"
                                            viewBox="0 0 24 24">
                                            <path fill="#ffffff"
                                                d="M14.06,9L15,9.94L5.92,19H5V18.08L14.06,9M17.66,3C17.41,3 17.15,3.1 16.96,3.29L15.13,5.12L18.88,8.87L20.71,7.04C21.1,6.65 21.1,6 20.71,5.63L18.37,3.29C18.17,3.09 17.92,3 17.66,3M14.06,6.19L3,17.25V21H6.75L17.81,9.94L14.06,6.19Z" />
                                        </svg></label>
                                    <input class="file-upload" id="avatar" name="image" data-user-image="image"
                                        type="file" accept="image/*">
                                </div>
                            </div>
                            <div class="img-extension mt-3">
                                <div class="d-inline-block align-items-center">
                                    <span>Only</span>
                                    <a href="javascript:void();">.jpg</a>
                                    <a href="javascript:void();">.png</a>
                                    <a href="javascript:void();">.jpeg</a>
                                    <span>allowed</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="new-user-info mx-3">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="form-label" for="fname">First Name:</label>
                                <input type="text" class="form-control" name="f_name" id="fname"
                                    placeholder="First Name">
                            </div>
                            
                            <div class="form-group col-md-6">
                                <label class="form-label" for="mobno">Mobile Number:</label>
                                <input type="text" class="form-control" id="mobno" name="phone"
                                    placeholder="Mobile Number">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label" for="email">Email:</label>
                                <input type="email" class="form-control" name="email" id="email" placeholder="Email">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label" for="add1">Street Address :</label>
                                <input type="text" class="form-control" name="street" id="add1"
                                    placeholder="Street Address ">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label" for="pno">Pin Code:</label>
                                <input type="text" class="form-control" name="pincode" id="pno" placeholder="Pin Code">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label" for="city">Town/City:</label>
                                <input type="text" class="form-control" id="city" name="city" placeholder="Town/City">
                            </div>
                        </div>
                        <hr>
                        <h5 class="mb-3">Security</h5>
                        <div class="row">
                            {{-- <div class="form-group col-md-12">
                                <label class="form-label" for="uname">User Name:</label>
                                <input type="text" class="form-control" id="uname" placeholder="User Name">
                            </div> --}}
                            <div class="form-group col-md-6">
                                <label class="form-label" for="pass">Password:</label>
                                <input type="password" class="form-control" autocomplete name="password" id="pass"
                                    placeholder="Password">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label" for="rpass">Repeat Password:</label>
                                <input type="password" class="form-control" id="rpass" autocomplete name="c_password"
                                    placeholder="Repeat Password ">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mb-3">Add New User</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('javascript')
<script src="{{asset('assets/vendor/flatpickr/dist/flatpickr.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js" ></script>

<script type="module">
    import { subscription_discount,currencySymbolsuffix ,readImage} from "{{ asset('assets/js/Helpers/helper.js') }}";

    let subscriptionCopy;
    $('#select-type').on("select2:select", async function (e) {
        const elm = e.target;
        const url = "{{route('mess.subscription.p.lists')}}?type="
        try {
            if (elm.value != null) {
                const targetElemToappend = document.querySelector('#select-subscription');
                const resp = await fetch(url+elm.value);
                const result = await resp.json();
                subscriptionCopy = [...result];
                console.log(result);
                targetElemToappend.innerHTML = '<option value="">Choose One</option>';
                result.forEach(item => {
                    const opt = document.createElement('option');
                    opt.value = item.id;
                    opt.textContent = `${item.title}  (validity : ${item.validity}days) [\@  ${currencySymbolsuffix(subscription_discount(item.price,item.discount,item.discount_type))}] `;
                    targetElemToappend.append(opt);
                });

            } else {
                throw new Error('Request Value cannot be null');
            }
        } catch (error) {
            console.error(error);
            toastr.error(error.message);
        }
    });

    document.querySelector('[data-user-image]').addEventListener('change', function(event) {
        readImage(event.target, '#avatar-img');
    });

</script>

<script>
/*
* =================// customer form submission starts//============================
*/
const CustomerForm = document.querySelector('#cusomer-form');
CustomerForm.addEventListener('submit', async function(event) {
    event.preventDefault();
    try {
        const url = "{{route('admin.customer.add')}}";
        const formData = new FormData(CustomerForm);
        if (!formData) {
            throw new Error('Please fill the form properly');
        }
        const res = await fetch(url, {
            method: 'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            body: formData
        });
        if (!res.ok) {
            const errorMessage = await res.json(); 
            throw new Error(handleError(errorMessage)); 
        }
        const result = await res.json();
        // console.log(result);
        if(result.success){
            toastr.success(result.success);
            CustomerForm.reset();
            setTimeout(() => {   
                window.location.href = "{{ route('admin.customer.list') }}";
            }, 5000);
        }
    } catch (error) {
        console.error(error)
        toastr.error(error.message);
    }
});

function handleError(errorResponse) {
    if (errorResponse && errorResponse.errors) {
        if (Array.isArray(errorResponse.errors)) {
            return errorResponse.errors.join(', ');
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
    return JSON.stringify(errorResponse);
}
/*
* =================// customer form submission ends//============================
*/
</script>

@endpush

