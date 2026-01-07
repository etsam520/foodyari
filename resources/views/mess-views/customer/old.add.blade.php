@extends('mess-views.layouts.dashboard-main')
@push('css')
<link rel="stylesheet" href="{{asset('assets/vendor/flatpickr/dist/flatpickr.min.css')}}">
@endpush
@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div>
        <form  method="POST" id="cusomer-form" enctype="multipart/form-data">
       <div class="row">
            <div class="col-xl-4 col-lg-5">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Add New Customer</h4>
                    </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <div class="profile-img-edit position-relative">
                                <img src="{{asset('assets/images/avatars/01.png')}}"  alt="profile-pic" id="avatar-img" class="theme-color-default-img profile-pic rounded avatar-100">
                                <div class="upload-icone bg-primary">
                                <label for="avatar"><svg class="upload-button icon-14" width="14"  viewBox="0 0 24 24">
                                    <path fill="#ffffff" d="M14.06,9L15,9.94L5.92,19H5V18.08L14.06,9M17.66,3C17.41,3 17.15,3.1 16.96,3.29L15.13,5.12L18.88,8.87L20.71,7.04C21.1,6.65 21.1,6 20.71,5.63L18.37,3.29C18.17,3.09 17.92,3 17.66,3M14.06,6.19L3,17.25V21H6.75L17.81,9.94L14.06,6.19Z" />
                                </svg></label> 
                                <input class="file-upload" id="avatar" name="image" onchange="readImage(this,'#avatar-img')" type="file" accept="image/*">
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
                        <h6 class="mb-2">Service Type</h6>
                        <div class="d-flex flex-column border border-1 border-light p-2 rounded" id="service-container" >
                            <div class="form-group">
                                <div class="item float-end">
                                    <span class="form-label"  for="break-fast">Quantity :</span>
                                    <span class="product-plus" data-service-type="breakfast" data-plus="1" ><svg class="icon-32" width="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M13.131 7.36922C13.189 7.42572 13.437 7.63906 13.641 7.8378C14.924 9.00292 17.024 12.0424 17.665 13.6332C17.768 13.8748 17.986 14.4856 18 14.812C18 15.1247 17.928 15.4228 17.782 15.7073C17.578 16.0619 17.257 16.3463 16.878 16.5022C16.615 16.6025 15.828 16.7584 15.814 16.7584C14.953 16.9143 13.554 17 12.008 17C10.535 17 9.193 16.9143 8.319 16.7867C8.305 16.772 7.327 16.6162 6.992 16.4457C6.38 16.133 6 15.5222 6 14.8685V14.812C6.015 14.3863 6.395 13.491 6.409 13.491C7.051 11.9859 9.048 9.01656 10.375 7.82319C10.375 7.82319 10.716 7.48709 10.929 7.34096C11.235 7.11301 11.614 7 11.993 7C12.416 7 12.81 7.12762 13.131 7.36922Z" fill="currentColor"></path></svg></span>
                                    <span class="product-quantity" data-service-type="breakfast" data-quantity="0" >0</span>
                                    <span class="product-minus" data-service-type="breakfast" data-plus="0" ><svg class="icon-32" width="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10.869 16.6308C10.811 16.5743 10.563 16.3609 10.359 16.1622C9.076 14.9971 6.976 11.9576 6.335 10.3668C6.232 10.1252 6.014 9.51437 6 9.18802C6 8.8753 6.072 8.5772 6.218 8.29274C6.422 7.93814 6.743 7.65368 7.122 7.49781C7.385 7.39747 8.172 7.2416 8.186 7.2416C9.047 7.08573 10.446 7 11.992 7C13.465 7 14.807 7.08573 15.681 7.21335C15.695 7.22796 16.673 7.38383 17.008 7.55431C17.62 7.86702 18 8.47784 18 9.13151V9.18802C17.985 9.61374 17.605 10.509 17.591 10.509C16.949 12.0141 14.952 14.9834 13.625 16.1768C13.625 16.1768 13.284 16.5129 13.071 16.659C12.765 16.887 12.386 17 12.007 17C11.584 17 11.19 16.8724 10.869 16.6308Z" fill="red"></path></svg></span>
                                </div>
                                <div class="item">
                                <label class="form-label">Break Fast Tiffin(In. ₹/day):</label>
                                <input type="text" data-service-type="breakfast" data-price="0"  class="form-control" id="break-fast" value="0">   
                                </div>  
                            </div>
                            <div class="form-group">
                                <div class="item float-end">
                                    <span class="form-label" for="lunch">Quantity :</span>
                                    <span class="plus" data-service-type="lunch" data-plus="1" ><svg class="icon-32" width="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M13.131 7.36922C13.189 7.42572 13.437 7.63906 13.641 7.8378C14.924 9.00292 17.024 12.0424 17.665 13.6332C17.768 13.8748 17.986 14.4856 18 14.812C18 15.1247 17.928 15.4228 17.782 15.7073C17.578 16.0619 17.257 16.3463 16.878 16.5022C16.615 16.6025 15.828 16.7584 15.814 16.7584C14.953 16.9143 13.554 17 12.008 17C10.535 17 9.193 16.9143 8.319 16.7867C8.305 16.772 7.327 16.6162 6.992 16.4457C6.38 16.133 6 15.5222 6 14.8685V14.812C6.015 14.3863 6.395 13.491 6.409 13.491C7.051 11.9859 9.048 9.01656 10.375 7.82319C10.375 7.82319 10.716 7.48709 10.929 7.34096C11.235 7.11301 11.614 7 11.993 7C12.416 7 12.81 7.12762 13.131 7.36922Z" fill="currentColor"></path></svg></span>
                                    <span class="product-quantity" data-service-type="lunch" data-quantity="0" >0</span>
                                    <span class="product-minus" data-service-type="lunch" data-plus="0" ><svg class="icon-32" width="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10.869 16.6308C10.811 16.5743 10.563 16.3609 10.359 16.1622C9.076 14.9971 6.976 11.9576 6.335 10.3668C6.232 10.1252 6.014 9.51437 6 9.18802C6 8.8753 6.072 8.5772 6.218 8.29274C6.422 7.93814 6.743 7.65368 7.122 7.49781C7.385 7.39747 8.172 7.2416 8.186 7.2416C9.047 7.08573 10.446 7 11.992 7C13.465 7 14.807 7.08573 15.681 7.21335C15.695 7.22796 16.673 7.38383 17.008 7.55431C17.62 7.86702 18 8.47784 18 9.13151V9.18802C17.985 9.61374 17.605 10.509 17.591 10.509C16.949 12.0141 14.952 14.9834 13.625 16.1768C13.625 16.1768 13.284 16.5129 13.071 16.659C12.765 16.887 12.386 17 12.007 17C11.584 17 11.19 16.8724 10.869 16.6308Z" fill="red"></path></svg></span>
                                </div>
                                <div class="item">
                                <label class="form-label">Lunch Tiffin(In. ₹/day):</label>
                                <input type="text" class="form-control" data-service-type="lunch" data-price="0" id="lunch" value="0">   
                                </div>  
                            </div>
                            <div class="form-group">
                                <div class="item float-end">
                                    <span class="form-label" for="dinner">Quantity :</span>
                                    <span class="plus" data-service-type="dinner" data-plus="1" ><svg class="icon-32" width="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M13.131 7.36922C13.189 7.42572 13.437 7.63906 13.641 7.8378C14.924 9.00292 17.024 12.0424 17.665 13.6332C17.768 13.8748 17.986 14.4856 18 14.812C18 15.1247 17.928 15.4228 17.782 15.7073C17.578 16.0619 17.257 16.3463 16.878 16.5022C16.615 16.6025 15.828 16.7584 15.814 16.7584C14.953 16.9143 13.554 17 12.008 17C10.535 17 9.193 16.9143 8.319 16.7867C8.305 16.772 7.327 16.6162 6.992 16.4457C6.38 16.133 6 15.5222 6 14.8685V14.812C6.015 14.3863 6.395 13.491 6.409 13.491C7.051 11.9859 9.048 9.01656 10.375 7.82319C10.375 7.82319 10.716 7.48709 10.929 7.34096C11.235 7.11301 11.614 7 11.993 7C12.416 7 12.81 7.12762 13.131 7.36922Z" fill="currentColor"></path></svg></span>
                                    <span class="product-quantity" data-service-type="dinner" data-quantity="0" >0</span>
                                    <span class="product-minus" data-service-type="dinner" data-plus="0"  ><svg class="icon-32" width="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10.869 16.6308C10.811 16.5743 10.563 16.3609 10.359 16.1622C9.076 14.9971 6.976 11.9576 6.335 10.3668C6.232 10.1252 6.014 9.51437 6 9.18802C6 8.8753 6.072 8.5772 6.218 8.29274C6.422 7.93814 6.743 7.65368 7.122 7.49781C7.385 7.39747 8.172 7.2416 8.186 7.2416C9.047 7.08573 10.446 7 11.992 7C13.465 7 14.807 7.08573 15.681 7.21335C15.695 7.22796 16.673 7.38383 17.008 7.55431C17.62 7.86702 18 8.47784 18 9.13151V9.18802C17.985 9.61374 17.605 10.509 17.591 10.509C16.949 12.0141 14.952 14.9834 13.625 16.1768C13.625 16.1768 13.284 16.5129 13.071 16.659C12.765 16.887 12.386 17 12.007 17C11.584 17 11.19 16.8724 10.869 16.6308Z" fill="red"></path></svg></span>
                                </div>
                                <div class="item">
                                <label class="form-label">Dinner Tiffin(In. ₹/day):</label>
                                <input type="text" class="form-control" data-service-type="dinner"  data-price="0" id="dinner" value="0">   
                                </div>  
                            </div>
                            <div class="form-group">
                                <span>Daily Cost :</span>
                                <span id="average-cost" data-daily-cost="0" >356 ₹</span>
                            </div>
                            <div class="form-group">
                                <label for="advance-payment" class="form-label">Advanced Payment :</label>
                                <input type="number" class="form-control" name="advance_payment"  id="advance-payment" placeholder="Advanced Payment" >   
                            </div>
                        
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-8 col-lg-7">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">New Customer Information</h4>
                    </div>
                    </div>
                    <div class="card-body">
                    <div class="new-user-info">
                            <div class="row">
                                <div class="form-group col-md-6">
                                <label class="form-label" for="fname">First Name:</label>
                                <input type="text" class="form-control" name="f_name" id="fname" placeholder="First Name">
                                </div>
                                <div class="form-group col-md-6">
                                <label class="form-label" for="lname">Last Name:</label>
                                <input type="text" class="form-control" name="l_name" id="lname" placeholder="Last Name">
                                </div>
                                <div class="form-group col-md-12">
                                <label class="form-label" for="add1">Street Address :</label>
                                <input type="text" class="form-control" name="street" id="add1" placeholder="Street Address ">
                                </div>
                                <div class="form-group col-md-6">
                                <label class="form-label" for="mobno">Mobile Number:</label>
                                <input type="text" class="form-control" id="mobno" name="phone" placeholder="Mobile Number">
                                </div>
                                <div class="form-group col-md-6">
                                <label class="form-label" for="email">Email:</label>
                                <input type="email" class="form-control" name="email" id="email" placeholder="Email">
                                </div>
                                <div class="form-group col-md-6">
                                <label class="form-label" for="pno">Pin Code:</label>
                                <input type="text" class="form-control" name="pincode" id="pno" placeholder="Pin Code">
                                </div>
                                <div class="form-group col-md-6">
                                <label class="form-label" for="city">Town/City:</label>
                                <input type="text" class="form-control" id="city" name="city" placeholder="Town/City">
                                </div>
                                <div class="col-md-6 form-group"> 
                                    <label class="form-label" for="city">Start Date:</label>
                                    <input type="text" name="start" class="form-control date_flatpicker" placeholder="Pick Start Day">  
                                </div>
                                <div class="col-md-6 form-group"> 
                                    <label class="form-label" for="food-type">Food Type:</label>
                                    <select name="type" class="form-control" id="food-type">
                                        <option value="">Select One</option>
                                        <option value="0">Veg</option>
                                        <option value="1">Non-Veg</option>
                                    </select>  
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
                                <input type="password" class="form-control" name="password" id="pass" placeholder="Password">
                                </div>
                                <div class="form-group col-md-6">
                                <label class="form-label" for="rpass">Repeat Password:</label>
                                <input type="password" class="form-control" id="rpass" name="c_password" placeholder="Repeat Password ">
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Add New User</button>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    </div>
 </div>
@endsection

@push('javascript')
<script src="{{asset('assets/vendor/flatpickr/dist/flatpickr.min.js')}}"></script>
<script>
function readImage(input,selector) {
    try{
        const reader = new FileReader();
        reader.onload = function (e) {
            const imgSrc = e.target.result;
            document.querySelector(selector).src = imgSrc;
        };
        reader.readAsDataURL(input.files[0]);
    }catch(error){
        console.error(error);
    }
    
}
</script>
<script>
/*
* setting avarave cost and Increament of tiffi quantity  ==========// start //==============
*
*/
let pricing = {
    breakfast: { price: 0, quantity: 0 }, 
    lunch: { price: 0, quantity: 0 },     
    dinner: { price: 0, quantity: 0 },    
    costPerDay: function() {
        this.totalCost = (this.breakfast.price * this.breakfast.quantity) + 
                        (this.lunch.price * this.lunch.quantity) + 
                        (this.dinner.price * this.dinner.quantity);
        return this.totalCost;
    },
    updateCostPerDay: function(selector) {
       const elem =  document.querySelector(selector);
       if (elem.dataset.dailyCost) {
            let costByDay = pricing.costPerDay();
            elem.textContent = costByDay + " ₹";
            elem.dataset.dailyCost = costByDay;
        }
    }
};

const service = document.querySelector('#service-container');
service.querySelectorAll("span[data-service-type], input[data-service-type]").forEach(item => {
    if (item.tagName.toLowerCase() === 'span') {
        if (item.dataset.plus && item.dataset.serviceType) {
            item.addEventListener('click', () => {
                let serviceType = item.dataset.serviceType;
                if (pricing.hasOwnProperty(serviceType)) {
                    if (item.dataset.plus === "1") {
                        pricing[serviceType].quantity += 1;
                    } else if (item.dataset.plus === "0") {
                        if (pricing[serviceType].quantity > 0) {
                            pricing[serviceType].quantity -= 1;
                        }
                    }
                }
                
                let spanQuantity = item.parentElement.querySelector(`span[data-service-type="${serviceType}"][data-quantity]`);
                if (spanQuantity.dataset.quantity && spanQuantity.dataset.serviceType && spanQuantity.dataset.quantity >= 0) {
                    spanQuantity.textContent = pricing[serviceType].quantity;
                    spanQuantity.dataset.quantity = pricing[serviceType].quantity;
                }
                pricing.updateCostPerDay('span[data-daily-cost]');
            });
        }
    }

    if (item.tagName.toLowerCase() === 'input') {
        item.addEventListener('input', () => {
            if (item.value < 0 || item.dataset.price < 0) {
                item.value = 0;
                item.dataset.price = 0;
            }
           
            let serviceType = item.dataset.serviceType;
            pricing[serviceType].price = parseInt(item.value);
            item.dataset.price = pricing[serviceType].price;
            pricing.updateCostPerDay('span[data-daily-cost]');
        });
    }
});

(function(pricing) {
    const service = document.querySelector('#service-container');
    service.querySelectorAll("span[data-service-type][data-quantity], input[data-service-type][data-price]").forEach(item => {
        if (item.tagName.toLowerCase() === 'span') {
            let serviceType = item.dataset.serviceType;
            if (item.dataset.quantity) {
                pricing[serviceType].quantity = parseInt(item.dataset.quantity);
            }
        }

        if (item.tagName.toLowerCase() === 'input') {
            let serviceType = item.dataset.serviceType;
            if (item.dataset.price) {
                pricing[serviceType].price = parseInt(item.value);
            }
        }
        pricing.updateCostPerDay('span[data-daily-cost]');
    });
})(pricing);

/*
* setting avarave cost and Increament of tiffin quantity  ==========// end //==============
*
*/
   
</script>
<script>
/*
* =================// customer form submission starts//============================
*/
const CustomerForm = document.querySelector('#cusomer-form');
CustomerForm.addEventListener('submit', async function(event) {
    event.preventDefault();
    try {
        const url = '{{route('mess.customer.add')}}';
        const formData = new FormData(CustomerForm);
        formData.append('pricing', JSON.stringify(pricing));
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
            // window.location.href     = "{{route('mess.customer.list')}}";
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
