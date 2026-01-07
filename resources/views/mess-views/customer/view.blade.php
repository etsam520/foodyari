@extends('mess-views.layouts.dashboard-main')
@push('css')
<link rel="stylesheet" href="{{asset('assets/vendor/flatpickr/dist/flatpickr.min.css')}}">
@endpush
@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div>
        <form  method="POST" id="cusomer-form" enctype="multipart/form-data">
       <div class="row">
            <div class="col-xl-5 col-lg-5 ">
                <div class="card ">
                    <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Customer Details</h4>
                    </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-evenly">
                            <div class="profile-img-edit position-relative">
                                <img src="{{asset('customers').'/'.$customerView->image}}"  alt="profile-pic" id="avatar-img" class="theme-color-default-img profile-pic rounded avatar-100">  
                            </div>
                            <div class="media-body">
                                <ul class="list-unstyled m-0">
                                    <li class="pb-1">
                                        <svg viewBox="0 0 24 24" width="20"  fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <circle cx="12" cy="6" r="4" stroke="currentColor" stroke-width="1.5"></circle> <path opacity="0.5" d="M20 17.5C20 19.9853 20 22 12 22C4 22 4 19.9853 4 17.5C4 15.0147 7.58172 13 12 13C16.4183 13 20 15.0147 20 17.5Z" stroke="currentColor" stroke-width="1.5"></path> </g></svg>
                                        {{$customerView->f_name}} {{$customerView->l_name}}
                                    </li>
                                    <li class="pb-1">
                                        <svg  width="20" viewBox="-2.4 -2.4 28.80 28.80" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g id="style=linear"> <g id="email"> <path id="vector" d="M17 20.5H7C4 20.5 2 19 2 15.5V8.5C2 5 4 3.5 7 3.5H17C20 3.5 22 5 22 8.5V15.5C22 19 20 20.5 17 20.5Z" stroke="currentColor" stroke-width="1.176" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path> <path id="vector_2" d="M18.7698 7.7688L13.2228 12.0551C12.5025 12.6116 11.4973 12.6116 10.777 12.0551L5.22998 7.7688" stroke="currentColor" stroke-width="1.176" stroke-linecap="round"></path> </g> </g> </g></svg>
                                        {{$customerView->email}}
                                    </li>
                                    <li class="pb-1">
                                        <svg width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M16.1007 13.359L16.5562 12.9062C17.1858 12.2801 18.1672 12.1515 18.9728 12.5894L20.8833 13.628C22.1102 14.2949 22.3806 15.9295 21.4217 16.883L20.0011 18.2954C19.6399 18.6546 19.1917 18.9171 18.6763 18.9651M4.00289 5.74561C3.96765 5.12559 4.25823 4.56668 4.69185 4.13552L6.26145 2.57483C7.13596 1.70529 8.61028 1.83992 9.37326 2.85908L10.6342 4.54348C11.2507 5.36691 11.1841 6.49484 10.4775 7.19738L10.1907 7.48257" stroke="currentColor" stroke-width="1.5"></path> <path opacity="0.5" d="M18.6763 18.9651C17.0469 19.117 13.0622 18.9492 8.8154 14.7266C4.81076 10.7447 4.09308 7.33182 4.00293 5.74561" stroke="currentColor" stroke-width="1.5"></path> <path opacity="0.5" d="M16.1007 13.3589C16.1007 13.3589 15.0181 14.4353 12.0631 11.4971C9.10807 8.55886 10.1907 7.48242 10.1907 7.48242" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path> </g></svg>
                                        {{$customerView->phone}}
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="address m-3" > 
                                <svg width="20" viewBox="0 0 1024 1024" fill="currentColor" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg" stroke="currentColor"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M512 1012.8c-253.6 0-511.2-54.4-511.2-158.4 0-92.8 198.4-131.2 283.2-143.2h3.2c12 0 22.4 8.8 24 20.8 0.8 6.4-0.8 12.8-4.8 17.6-4 4.8-9.6 8.8-16 9.6-176.8 25.6-242.4 72-242.4 96 0 44.8 180.8 110.4 463.2 110.4s463.2-65.6 463.2-110.4c0-24-66.4-70.4-244.8-96-6.4-0.8-12-4-16-9.6-4-4.8-5.6-11.2-4.8-17.6 1.6-12 12-20.8 24-20.8h3.2c85.6 12 285.6 50.4 285.6 143.2 0.8 103.2-256 158.4-509.6 158.4z m-16.8-169.6c-12-11.2-288.8-272.8-288.8-529.6 0-168 136.8-304.8 304.8-304.8S816 145.6 816 313.6c0 249.6-276.8 517.6-288.8 528.8l-16 16-16-15.2zM512 56.8c-141.6 0-256.8 115.2-256.8 256.8 0 200.8 196 416 256.8 477.6 61.6-63.2 257.6-282.4 257.6-477.6C768.8 172.8 653.6 56.8 512 56.8z m0 392.8c-80 0-144.8-64.8-144.8-144.8S432 160 512 160c80 0 144.8 64.8 144.8 144.8 0 80-64.8 144.8-144.8 144.8zM512 208c-53.6 0-96.8 43.2-96.8 96.8S458.4 401.6 512 401.6c53.6 0 96.8-43.2 96.8-96.8S564.8 208 512 208z" fill=""></path></g></svg>
                                @php($address = json_decode($customerView->address, true))
                                <span id="customer-address">{{$address['street']}}, {{$address['city']}}-{{$address['pincode']}} </span>   
                        </div>
                        <div id="customer-map" style="height: 300px !important; width:100%; z-index:1;"></div>

                        @php($subscriptionTXN =  $customerView->subscription[0])
                        @php($subscription =  $subscriptionTXN->subscription)
                        {{-- @dd($subscriptionTXN) --}}
                        <h6 class="mb-2 mt-2">Suscription</h6>
                        @if (Carbon\Carbon::parse($subscriptionTXN->expiry)->isPast() )    
                        
                        <div class="d-flex flex-column border border-1 border-light p-2 rounded" id="service-container" >
                            <div class="form-group">
                                <label class="form-label">Type</label>
                                <select name="type"  class="select-2 form-control" id="select-type" required data-style="py-0">
                                    <option disabled selected value="">Select One</option>
                                    <option value="V">Veg</option>
                                    <option value="N">Non Veg</option>
                                    <option value="B">Both</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Select Suscripton Package</label>
                                <select name="subscription_id"  class="selectpicker select-2 form-control" id="select-subscription" required data-style="py-0">
                                    <option disabled selected value="">Select One</option>
                                </select>
                            </div>
                        
                        </div>
                        @else
                        <div class="form-group">
                           
                            <p class=" p-2">
                                @if($subscription)
                                    @if ($subscription->type =='veg')
                                        <span class="badge bg-success">
                                            {{$subscription->title}}<br> (validity : {{$subscription->validity}}) <br>
                                            [@ <strike>{{App\CentralLogics\Helpers::format_currency($subscription->price)}}</strike>
                                            @if ($subscription->discount_type == 'percent')
                                            {{App\CentralLogics\Helpers::format_currency(App\CentralLogics\Helpers::percent_discount($subscription->price,$subscription->discount) )}}
                                            @else
                                            {{App\CentralLogics\Helpers::format_currency(App\CentralLogics\Helpers::flat_discount($subscription->price,$subscription->discount) )}}
                                            @endif]
                                        </span>
                                    @else
                                        <span class="badge" style="background: #8e4426">
                                            {{$subscription->title}}<br> (validity : {{$subscription->validity}}) <br>
                                            [@ <strike>{{App\CentralLogics\Helpers::format_currency($subscription->price)}}</strike>
                                            @if ($subscription->discount_type == 'percent')
                                            {{App\CentralLogics\Helpers::format_currency(App\CentralLogics\Helpers::percent_discount($subscription->price,$subscription->discount) )}}
                                            @else
                                            {{App\CentralLogics\Helpers::format_currency(App\CentralLogics\Helpers::flat_discount($subscription->price,$subscription->discount) )}}
                                            @endif]
                                        </span>
                                    @endif
                                @endif
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-xl-7 col-lg-7">
                <div class="card p-2">
                    <div class="card-header d-flex justify-content-between">
                       <div class="header-title">
                          <h4 class="card-title">Diet Coupons</h4>
                       </div>
                    </div>
                    <div class="card-body">

                        <nav>
                            <div class="nav nav-tabs mb-3" id="nav-tab" role="tablist">
                                <button class="nav-link active" id="nav-breakfast-tab" data-bs-toggle="tab" data-bs-target="#nav-breakfast" type="button" role="tab" aria-controls="nav-breakfast" aria-selected="true">Breakfast</button>
                                <button class="nav-link" id="nav-lunch-tab" data-bs-toggle="tab" data-bs-target="#nav-lunch" type="button" role="tab" aria-controls="nav-lunch" aria-selected="false">Lunch</button>
                                <button class="nav-link" id="nav-dinner-tab" data-bs-toggle="tab" data-bs-target="#nav-dinner" type="button" role="tab" aria-controls="nav-dinner" aria-selected="false">Dinner</button>
                                <button class="nav-link" id="nav-special-tab" data-bs-toggle="tab" data-bs-target="#nav-special" type="button" role="tab" aria-controls="nav-special" aria-selected="false">Special</button>
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-breakfast" role="tabpanel" aria-labelledby="nav-breakfast-tab">
                                <div class="table-responsive">
                                    <table id="datatable" class="table table-striped" data-toggle="data-table">
                                       <thead>
                                          <tr>
                                             <th>Coupon No.</th>
                                             <th>State</th>
                                          </tr>
                                       </thead>
                                       <tbody>
                                          @php($dietCoupons = App\Models\DietCoupon::where('subscription_id',$subscriptionTXN->subscription_id)->get())
                                          @foreach ($dietCoupons as $coupon)
                                          @if($coupon->diet_name == App\CentralLogics\Helpers::getService('B') && $coupon->speciality == App\CentralLogics\Helpers::getSpeciality('N'))
                                            <tr>
                                                <td>{{ $coupon->coupon_no}}</td>
                                                <td>{{ $coupon->state}}</td> 
                                            </tr>
                                            @endif 
                                              
                                              
                                          @endforeach
                                       </tbody>
                                       <tfoot>
                                          <tr>
                                              <th>Coupon No.</th>
                                              <th>State</th>
                                           </tr>
                                       </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-lunch" role="tabpanel" aria-labelledby="nav-lunch-tab">
                                <div class="table-responsive">
                                    <table id="datatable" class="table table-striped" data-toggle="data-table">
                                       <thead>
                                          <tr>
                                             <th>Coupon No.</th>
                                             <th>State</th>
                                          </tr>
                                       </thead>
                                       <tbody>
                                          
                                          @foreach ($dietCoupons as $coupon)
                                            @if($coupon->diet_name == App\CentralLogics\Helpers::getService('L') && $coupon->speciality == App\CentralLogics\Helpers::getSpeciality('N'))
                                            <tr>
                                                <td>{{ $coupon->coupon_no}}</td>
                                                <td>{{ $coupon->state}}</td> 
                                            </tr>
                                            @endif 
                                              
                                          @endforeach
                                       </tbody>
                                       <tfoot>
                                          <tr>
                                              <th>Coupon No.</th>
                                              <th>State</th>
                                           </tr>
                                       </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-dinner" role="tabpanel" aria-labelledby="nav-dinner-tab">
                                <div class="table-responsive">
                                    <table id="datatable" class="table table-striped" data-toggle="data-table">
                                       <thead>
                                          <tr>
                                             <th>Coupon No.</th>
                                             <th>State</th>
                                          </tr>
                                       </thead>
                                       <tbody>
                                          
                                          @foreach ($dietCoupons as $coupon)
                                            @if($coupon->diet_name == App\CentralLogics\Helpers::getService('D') && $coupon->speciality == App\CentralLogics\Helpers::getSpeciality('N'))
                                            <tr>
                                                <td>{{ $coupon->coupon_no}}</td>
                                                <td>{{ $coupon->state}}</td> 
                                            </tr>
                                            @endif 
                                              
                                              
                                          @endforeach
                                       </tbody>
                                       <tfoot>
                                          <tr>
                                              <th>Coupon No.</th>
                                              <th>State</th>
                                           </tr>
                                       </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-special" role="tabpanel" aria-labelledby="nav-special-tab">
                                <div class="table-responsive">
                                    <table id="datatable" class="table table-striped" data-toggle="data-table">
                                       <thead>
                                          <tr>
                                             <th>Coupon No.</th>
                                             <th>Diet Name</th>
                                             <th>State</th>
                                          </tr>
                                       </thead>
                                       <tbody>
                                          
                                          @foreach ($dietCoupons as $coupon)
                                            @if($coupon->speciality == App\CentralLogics\Helpers::getSpeciality('S'))
                                            <tr>
                                                <td>{{ $coupon->coupon_no}}</td>
                                                <td>{{ $coupon->diet_name}}</td>
                                                <td>{{ $coupon->state}}</td> 
                                            </tr>
                                            @endif  
                                              
                                          @endforeach
                                       </tbody>
                                       <tfoot>
                                          <tr>
                                              <th>Coupon No.</th>
                                              <th>State</th>
                                           </tr>
                                       </tfoot>
                                    </table>
                                </div>
                            </div>
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
<scrip src="{{asset('assets/vendor/flatpickr/dist/flatpickr.min.js')}}"></script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBPVVxuVkmdE1GzSgIqm_9dx64tea7Wltk&libraries=places&callback=initMap"></script>
<script>
 function initMap() {
            var map = new google.maps.Map(document.querySelector('#customer-map'), {
                center: {lat: -34.397, lng: 150.644},
                zoom: 6
            });
            var geocoder = new google.maps.Geocoder();
            var address = document.getElementById('customer-address').textContent;
             
            geocoder.geocode({'address': address}, function(results, status) {
                if (status === 'OK') {
            
                    const location = results[0].geometry.location;
                
                    map.setCenter(location);
                    var marker = new google.maps.Marker({
                        map: map,
                        position: {lat : location.lat(), lng : location.lng()}
                    });
                    
                } else {
                    // If geocoding fails, alert the user
                    toastr.warning('Geocode was not successful for the following reason: ' + status);
                }
            });
      
        }
    
</script>
<script type="module">
    import { subscription_discount,currencySymbolsuffix } from "{{ asset('assets/js/Helpers/helper.js') }}";

$('#select-type').on("select2:select", async function (e) {
    const elm = e.target;
    const url = "{{route('mess.subscription.p.lists')}}?type="
    try {
        if (elm.value != null) {
            const targetElemToappend = document.querySelector('#select-subscription');
            const resp = await fetch(url + elm.value);
            const result = await resp.json();
            if(result.error){
                throw new Error('Subscription Not Available');
            }
            console.log(result);
            targetElemToappend.innerHTML = '<option value="">Choose One</option>';
            result.forEach(item => {
                const opt = document.createElement('option');
                opt.value = item.id;
                // Using the imported function here
                opt.textContent = `${item.title} (validity : ${item.validity} days) [\@  ${currencySymbolsuffix(subscription_discount(item.price,item.discount,item.discount_type))}] `;
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

</script>


<script>
/*
* =================// customer form submission starts//============================
*/
const CustomerForm = document.querySelector('#cusomer-form');
CustomerForm.addEventListener('submit', async function(event) {
    event.preventDefault();
    try {
        const url = "{{route('mess.customer.add')}}";
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
