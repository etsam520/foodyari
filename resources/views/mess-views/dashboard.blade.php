
@extends('mess-views.layouts.dashboard-main')
@php
   $today = \Carbon\Carbon::now()->toDateString();
   $messId = Session::get('mess')->id;

@endphp


@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
   <div class="row">
      <div class="col-md-12 col-lg-12">
         <div class="row row-cols-1">
            <div class="overflow-hidden d-slider1 ">
               <ul  class="p-0 m-0 mb-2 swiper-wrapper list-inline">
                  <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="700">
                     <div class="card-body">
                        <div class="progress-widget">
                           <div id="circle-progress-01" class="text-center circle-progress-01 circle-progress circle-progress-primary" data-min-value="0" data-max-value="100" data-value="60" data-type="percent">
                              <svg class="card-slie-arrow icon-24" width="24"  viewBox="0 0 24 24">
                                 <path fill="currentColor" d="M5,17.59L15.59,7H9V5H19V15H17V8.41L6.41,19L5,17.59Z" />
                              </svg>
                           </div>
                           <div class="progress-detail">
                              <p  class="mb-2">Total Users</p>
                              <h4 class="counter">40</h4>
                           </div>
                        </div>
                     </div>
                  </li>
                  <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="800">
                     <div class="card-body">
                        <div class="progress-widget">
                           <div id="circle-progress-02" class="text-center circle-progress-01 circle-progress circle-progress-info" data-min-value="0" data-max-value="100" data-value="80" data-type="percent">
                              <svg class="card-slie-arrow icon-24" width="24" viewBox="0 0 24 24">
                                 <path fill="currentColor" d="M5,17.59L15.59,7H9V5H19V15H17V8.41L6.41,19L5,17.59Z" />
                              </svg>
                           </div>
                           <div class="progress-detail">
                              <p  class="mb-2">Break-fast</p>
                              <h4 class="counter">38</h4>
                           </div>
                        </div>
                     </div>
                  </li>
                  <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="900">
                     <div class="card-body">
                        <div class="progress-widget">
                           <div id="circle-progress-03" class="text-center circle-progress-01 circle-progress circle-progress-primary" data-min-value="0" data-max-value="100" data-value="70" data-type="percent">
                              <svg class="card-slie-arrow icon-24" width="24" viewBox="0 0 24 24">
                                 <path fill="currentColor" d="M19,6.41L17.59,5L7,15.59V9H5V19H15V17H8.41L19,6.41Z" />
                              </svg>
                           </div>
                           <div class="progress-detail">
                              <p  class="mb-2">Lunch</p>
                              <h4 class="counter">02</h4>
                           </div>
                        </div>
                     </div>
                  </li>
                  <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="1000">
                     <div class="card-body">
                        <div class="progress-widget">
                           <div id="circle-progress-04" class="text-center circle-progress-01 circle-progress circle-progress-info" data-min-value="0" data-max-value="100" data-value="60" data-type="percent">
                              <svg class="card-slie-arrow icon-24" width="24px"  viewBox="0 0 24 24">
                                 <path fill="currentColor" d="M5,17.59L15.59,7H9V5H19V15H17V8.41L6.41,19L5,17.59Z" />
                              </svg>
                           </div>
                           <div class="progress-detail">
                              <p  class="mb-2">Dinner</p>
                              <h4 class="counter">02</h4>
                           </div>
                        </div>
                     </div>
                  </li>
                 
               </ul>
               <div class="swiper-button swiper-button-next"></div>
               <div class="swiper-button swiper-button-prev"></div>
            </div>
         </div>
      </div> 

      <div class="col-md-8">
         <div class="card">
            <div class="card-header">
               Today's Food Requests
            </div>
            <div class="card-body">
               <table class="table table-responsive">
                  <thead>
                     <tr>
                       <th>Diet Name</th> 
                       <th>Speciality</th>
                       <th>Status</th>
                       <th>Delivery<sup class="badge bg-warning ms-1 rounded-3">
                           {{$attCounts['breakfast']['delivery'] + $attCounts['lunch']['delivery'] + $attCounts['dinner']['delivery']}}
                        </sup></th>
                       <th>Dine In <sup class="badge bg-warning ms-1 rounded-3">
                           {{$attCounts['breakfast']['dine_in'] + $attCounts['lunch']['dine_in'] + $attCounts['dinner']['dine_in']}}
                        </sup></th>
                       <th>Count<sup class="badge bg-warning ms-1 rounded-3">
                        {{$attCounts['breakfast']['total'] + $attCounts['lunch']['total'] + $attCounts['dinner']['total']}}
                        </sup></th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr>
                        <td>Breakfast</td>
                        <td>Normal</td>
                        <td>
                           @php($breakfastProcess = App\Models\MessFoodProcessing::getProcess($messId,'breakfast')->first())
                           @if($breakfastProcess && $breakfastProcess->steps == "processed")
                           <button  data-process-food="breakfast" data-process-name="readyToDeliver" class="btn btn-warning">Ready To Deliver / Dine In</button>
                           @elseif ($breakfastProcess && $breakfastProcess->steps = "readyToDeliver")
                           <button  data-process-food="breakfast" data-process-name="delivered" class="btn btn-warning">Deliver / Serve</button>
                           @elseif ( $breakfastProcess && $breakfastProcess->steps = "delivered")
                           <span class="badge bg-success p-2">Complete</span>
                           @else
                           <button  data-process-food="breakfast" data-process-name="processed" class="btn btn-warning">Process Food</button>
                           @endif
                        </td>
                        <td>{{$attCounts['breakfast']['delivery']}}</td>
                        <td>{{$attCounts['breakfast']['dine_in']}}</td>
                        <td>{{$attCounts['breakfast']['total']}}</td>
                     </tr>
                     <tr>
                        <td>Lunch</td>
                        <td>Normal</td>
                        <td>
                           @php($lunchProcess = App\Models\MessFoodProcessing::getProcess($messId,'lunch')->first())
                           @if($lunchProcess &&$lunchProcess->steps == "processed")
                           <button  data-process-food="lunch" data-process-name="readyToDeliver" class="btn btn-warning">Ready To Deliver / Dine In</button>
                           @elseif ($lunchProcess && $lunchProcess->steps = "readyToDeliver")
                           <button  data-process-food="lunch" data-process-name="delivered" class="btn btn-warning">Deliver / Serve</button>
                           @elseif ( $lunchProcess && $lunchProcess->steps = "delivered")
                           <span class="badge bg-success p-2">Complete</span>
                           @else
                           <button  data-process-food="lunch" data-process-name="processed" class="btn btn-warning">Process Food</button>
                           @endif
                        </td>
                        <td>{{$attCounts['lunch']['delivery']}}</td>
                        <td>{{$attCounts['lunch']['dine_in']}}</td>
                        <td>{{$attCounts['lunch']['total']}}</td>
                     </tr>
                     <tr>
                        <td>Dinner</td>
                        <td>Normal</td>
                        <td>
                           @php($dinnerProcess = App\Models\MessFoodProcessing::getProcess($messId,'dinner')->first())
                           @if( $dinnerProcess && $dinnerProcess->steps == "processed")
                           <button  data-process-food="dinner" data-process-name="readyToDeliver" class="btn btn-warning">Ready To Deliver / Dine In</button>
                           @elseif ( $dinnerProcess && $dinnerProcess->steps = "readyToDeliver")
                           <button  data-process-food="dinner" data-process-name="delivered" class="btn btn-warning">Deliver / Serve</button>
                           @elseif ( $dinnerProcess && $dinnerProcess->steps = "delivered")
                           <span class="badge bg-success p-2">Complete</span>
                           @else
                           <button  data-process-food="dinner" data-process-name="processed" class="btn btn-warning">Process Food</button>
                           @endif
                        </td>
                        <td>{{$attCounts['dinner']['delivery']}}</td>
                        <td>{{$attCounts['dinner']['dine_in']}}</td>
                        <td>{{$attCounts['dinner']['total']}}</td>
                     </tr>
                     
                  </tbody>
               </table>
            </div>
         </div>
      </div>
      <div class="col-md-4">
         <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    Auto Attendance Timing
                </div>
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
            <div class="card-body">
        
                <form class="row g-3 needs-validation" method="POST" enctype="multipart/form-data"
                    action="{{route('mess.profile.auto-ateendance-timing')}}">
                    @csrf
                    <div class="col-12 mt-1 d-flex" >
                        <div class="form-group w-50">
                            <label class="input-label" for="breakfast_start">Breakfast Start</label>
                            @php($breakfast = App\Models\MessService::where('name',App\CentralLogics\Helpers::getService('B'))->where('mess_id',Session::get('mess')->id)->first())
                            <input id="breakfast_start" type="time" name="breakfast_start" class="form-control h--45px"
                                value="{{$breakfast?$breakfast->available_time_starts:null}}">
                        </div>
                        <div class="form-group w-50">
                           <label class="input-label" for="breakfast_end">Breakfast End</label>
                           <input id="breakfast_end" type="time" name="breakfast_end" class="form-control h--45px"
                               value="{{$breakfast?$breakfast->available_time_ends:null}}">
                       </div>
                    </div>

                    <div class="col-12 mt-1 d-flex">
                        <div class="form-group w-50">
                           <label class="input-label" for="lunch_start">Lunch Start</label>
                           @php($lunch = App\Models\MessService::where('name',App\CentralLogics\Helpers::getService('L'))->where('mess_id',Session::get('mess')->id)->first())
                           <input id="lunch_start" type="time" name="lunch_start" class="form-control h--45px"
                              value="{{$lunch?$lunch->available_time_starts:null}}">
                        </div>
                        <div class="form-group w-50">
                           <label class="input-label" for="lunch_end">Lunch End</label>
                           <input id="lunch_end" type="time" name="lunch_end" class="form-control h--45px"
                              value="{{$lunch?$lunch->available_time_ends:null}}">
                        </div>
                    </div>

                     <div class="col-12 mt-1 d-flex ">
                        <div class="form-group w-50">
                           <label class="input-label" for="dinner_start">Dinner Start</label>
                           @php($dinner = App\Models\MessService::where('name',App\CentralLogics\Helpers::getService('D'))->where('mess_id',Session::get('mess')->id)->first())
                           <input id="dinner_start" type="time" name="dinner_start" class="form-control h--45px"
                              value="{{$dinner?$dinner->available_time_starts:null}}">
                        </div>
                        <div class="form-group w-50">
                           <label class="input-label" for="dinner_end">Dinner End</label>
                           <input id="dinner_end" type="time" name="dinner_end" class="form-control h--45px"
                              value="{{$dinner?$dinner->available_time_ends:null}}">
                     </div>
                  </div>

                  <div class="col-md-6 ">
                     <button class="btn btn-primary" type="submit">Save</button>
                  </div>
                </form>
            </div>
        </div>
      </div>

      <div class="col-12 d-none" data-wrapper="qr" >
         <div class="scanner-wrapper ">
            <div class="scanner">
               <video id="qr-preview"></video>
            </div>
         </div>
      </div>
      <div class="col-12 d-none" id="otp-container" data-wrapper="otp">
         <div class="d-flex flex-column justify-content-center otp-box ">
            <h1 class="text-center text-white">ENTER OTP</h1>  
            <div class="userInput" data-userInput="list">
               <input type="text" id='ist' maxlength="1" onkeyup="clickEvent(this,'sec')">
               <input type="text" id="sec" maxlength="1" onkeyup="clickEvent(this,'third')">
               <input type="text" id="third" maxlength="1" onkeyup="clickEvent(this,'fourth')">
               <input type="text" id="fourth" maxlength="1" onkeyup="clickEvent(this,'fifth')">
               <input type="text" id="fifth" maxlength="1" onkeyup="clickEvent(this,'sixth')">
               <input type="text" id="sixth" maxlength="1">
            </div>
            <button class="otp-submit-button" data-submit="otp" >CONFIRM</button>
         </div>
        
         <script>
            function clickEvent(first,last){
               if(first.value.length){
                  document.getElementById(last).focus();
               }
            }
         </script>
      </div>
   </div>
</div>

   {{-- scan qr --}}
   
  
   <div class="scan-button" data-scan="true" >
      <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="50px" height="50px" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" ><g><path d="M411.689 455.871h-8.029c-1.117-5.121-3.117-15.165-6.342-34.094-4.889-28.856-1.251-54.505 10.813-76.231 12.341-22.202 1.405-48.954.064-52.047-1.769-4.813-24.828-67.377-33.811-84.072-2.475-4.597-7.614-10.163-18.158-12.71V44.578C356.226 19.997 336.228 0 311.648 0H137.901c-24.58 0-44.578 19.997-44.578 44.578v354.184c0 24.58 19.997 44.577 44.578 44.577h155.21a68.098 68.098 0 0 1 2.606 12.531h-5.047a6.988 6.988 0 0 0-6.988 6.988v42.153a6.988 6.988 0 0 0 6.988 6.988h121.02a6.988 6.988 0 0 0 6.988-6.988v-42.153a6.988 6.988 0 0 0-6.989-6.987zm-273.788-26.507c-16.874 0-30.602-13.728-30.602-30.602V44.578c0-16.874 13.728-30.603 30.602-30.603h173.747c16.874 0 30.602 13.728 30.602 30.603v268.285c-.07 3.808-.267 6.451-.768 7.765-.329.862-1.502 1.568-5.992 3.912-2.445 1.278-5.473 2.862-9.201 5.07V62.21a6.988 6.988 0 0 0-6.988-6.988H130.246a6.988 6.988 0 0 0-6.988 6.988v285.469a6.988 6.988 0 0 0 6.988 6.988h130.196c.737 4.194 1.409 8.444 2.074 12.66 3.415 21.653 6.64 42.103 18.098 54.136a50.924 50.924 0 0 1 6.125 7.902H137.901zm112.108-108.998c3.319 5.733 5.687 12.733 7.554 20.326H137.234V69.198h175.08v254.517c-5.311-8.873-10.366-23.875-14.992-37.611-1.519-4.511-3.09-9.175-4.734-13.874-10.562-30.162-26.115-36.739-34.445-37.974-9.307-1.38-18.268 2.45-23.969 10.247-10.417 14.235-5.089 39.761 15.835 75.863zm40.732 91.466c-8.46-8.883-11.49-28.099-14.42-46.682-2.902-18.401-5.903-37.429-14.218-51.79-17.133-29.562-23.202-51.651-16.649-60.607 2.621-3.583 6.496-5.282 10.638-4.673 8.738 1.296 17.451 12.052 23.305 28.768 1.616 4.619 3.174 9.245 4.68 13.717 9.134 27.12 16.349 48.541 30.542 53.787 5.831 2.153 12.026 1.316 18.412-2.492 3.586-2.14 6.549-3.688 8.93-4.932 5.766-3.012 10.321-5.391 12.581-11.324 1.78-4.672 1.756-10.272 1.704-22.544a1684.5 1684.5 0 0 1-.021-7.599v-84.165c2.792 1.127 4.753 2.714 5.852 4.755 8.678 16.128 32.83 81.809 33.074 82.471.053.146.119.304.182.446.101.227 9.981 22.878.581 39.791-13.644 24.572-17.808 53.289-12.374 85.358 2.699 15.838 4.58 25.69 5.839 31.753h-79.594c-.904-10.123-4.595-28.821-19.044-44.038zm113.961 86.192H297.658v-28.178h107.044zM246.262 97.59a6.988 6.988 0 0 1 6.988-6.988h30.673a6.988 6.988 0 0 1 6.988 6.988v30.645a6.988 6.988 0 1 1-13.976 0v-23.657H253.25a6.99 6.99 0 0 1-6.988-6.988zm44.649 87.624v30.673a6.988 6.988 0 0 1-6.988 6.988H253.25a6.988 6.988 0 1 1 0-13.976h23.685v-23.685a6.988 6.988 0 1 1 13.976 0zm-87.653 30.673a6.988 6.988 0 0 1-6.988 6.988h-30.645a6.988 6.988 0 0 1-6.988-6.988v-30.673a6.988 6.988 0 1 1 13.976 0v23.685h23.657a6.988 6.988 0 0 1 6.988 6.988zm-44.621-87.653V97.59a6.988 6.988 0 0 1 6.988-6.988h30.645a6.988 6.988 0 1 1 0 13.976h-23.657v23.657a6.988 6.988 0 1 1-13.976-.001zm54.401 56.98a6.988 6.988 0 0 1-6.988 6.988h-9.78a6.988 6.988 0 0 1-6.988-6.988v-9.78a6.988 6.988 0 1 1 13.976 0v2.792h2.792a6.988 6.988 0 0 1 6.988 6.988zm-16.768-63.967h9.78a6.988 6.988 0 1 1 0 13.976h-2.792v2.792a6.988 6.988 0 1 1-13.976 0v-9.78a6.988 6.988 0 0 1 6.988-6.988zm56.98 70.955h-9.78a6.988 6.988 0 1 1 0-13.976h2.792v-2.792a6.988 6.988 0 1 1 13.976 0v9.78a6.988 6.988 0 0 1-6.988 6.988zm-16.768-63.968a6.988 6.988 0 0 1 6.988-6.988h9.78a6.988 6.988 0 0 1 6.988 6.988v9.78a6.988 6.988 0 1 1-13.976 0v-2.792h-2.792a6.988 6.988 0 0 1-6.988-6.988zm47.441 35.477H165.625a6.988 6.988 0 1 1 0-13.976h118.298a6.988 6.988 0 1 1 0 13.976z" fill="currentColor" opacity="1" data-original="#000000" ></path></g></svg>
    </div>
    <div class="otp-button" data-otp="true" >
      <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="50px" height="50px" x="0" y="0" viewBox="0 0 64 64" style="enable-background:new 0 0 512 512" ><g><path d="M47 2H17a5.006 5.006 0 0 0-5 5v50a5.006 5.006 0 0 0 5 5h30a5.006 5.006 0 0 0 5-5V7a5.006 5.006 0 0 0-5-5ZM26 4h12v1a1.001 1.001 0 0 1-1 1H27a1.001 1.001 0 0 1-1-1Zm-9 0h7v1a3.003 3.003 0 0 0 3 3h10a3.003 3.003 0 0 0 3-3V4h7a3.003 3.003 0 0 1 3 3v45H14V7a3.003 3.003 0 0 1 3-3Zm30 56H17a3.003 3.003 0 0 1-3-3v-3h36v3a3.003 3.003 0 0 1-3 3Zm-11-3a1 1 0 0 1-1 1h-6a1 1 0 0 1 0-2h6a1 1 0 0 1 1 1Zm-.902-13.634L34 44l1.098.634a1 1 0 0 1-1 1.732L33 45.732V47a1 1 0 0 1-2 0v-1.268l-1.098.634a1 1 0 1 1-1-1.732L30 44l-1.098-.634a1 1 0 1 1 1-1.732l1.098.634V41a1 1 0 0 1 2 0v1.268l1.098-.634a1 1 0 1 1 1 1.732Zm-16.196 1.268L20 44l-1.098-.634a1 1 0 1 1 1-1.732l1.098.634V41a1 1 0 0 1 2 0v1.268l1.098-.634a1 1 0 1 1 1 1.732L24 44l1.098.634a1 1 0 0 1-1 1.732L23 45.732V47a1 1 0 0 1-2 0v-1.268l-1.098.634a1 1 0 1 1-1-1.732ZM43 42.268l1.098-.634a1 1 0 1 1 1 1.732L44 44l1.098.634a1 1 0 0 1-1 1.732L43 45.732V47a1 1 0 0 1-2 0v-1.268l-1.098.634a1 1 0 1 1-1-1.732L40 44l-1.098-.634a1 1 0 1 1 1-1.732l1.098.634V41a1 1 0 0 1 2 0ZM31.664 36.94a.995.995 0 0 0 .672 0C32.443 36.903 43 33.018 43 23v-6a1 1 0 0 0-1-1 14.426 14.426 0 0 1-9.294-3.708 1.002 1.002 0 0 0-1.412-.001A14.422 14.422 0 0 1 22 16a1 1 0 0 0-1 1v6c0 10.018 10.557 13.903 10.664 13.941ZM23 17.966a16.37 16.37 0 0 0 9-3.633 16.37 16.37 0 0 0 9 3.633V23c0 7.76-7.301 11.226-9 11.927-1.699-.701-9-4.166-9-11.927Zm4.293 7.741a1 1 0 0 1 1.414-1.414L31 26.586l5.293-5.293a1 1 0 1 1 1.414 1.414l-6 6a1 1 0 0 1-1.414 0Z" data-name="19-Mobile" fill="currentColor" opacity="1" data-original="currentColor"></path></g></svg>
    </div>
@endsection
<!-- End Table -->
@push('javascript')
<script src="{{asset('assets/vendor/sweetalert2/sweetalert2@11.js')}}"></script>

{{-- <script src="{{asset('assets/vendor/qr-scanner/qr-scanner.min.js')}}"></script>
<script src="{{asset('assets/vendor/qr-scanner/qr-scanner-worker.min.js')}}"></script> --}}

<script type="module">
   import QrScanner from "{{asset('assets/vendor/qr-scanner/qr-scanner.min.js')}}";

   const scanBtn = document.querySelector('[data-scan]');
   const otpBtn = document.querySelector('[data-otp]');
   const qrWrapper =document.querySelector('[data-wrapper=qr]');
   const otpWrapper = document.querySelector('[data-wrapper=otp]');
   var qrScanner = null;
   var camarOpen = false;

   otpBtn.addEventListener('click', function(){
     
      otpWrapper.classList.replace('d-none', 'd-block');
      qrWrapper.classList.replace('d-block','d-none');
      if(camarOpen){
         qrScanner.stop();
      }
   });
   
   scanBtn.addEventListener('click', async function() {
      camarOpen = false;
    const videoElem = document.querySelector('#qr-preview');
    if (!camarOpen) {
        let scanned = false;
        qrScanner = new QrScanner(videoElem, async (result) => {
            if (result && !scanned) {
                scanned = true;
                qrScanner.stop();
                try {
                    const resp = await fetch("{{ route('mess.diet-order.varyfyQR') }}?encrypted_code=" + result);
                    const resultData = await resp.json();
                    if (resultData.success) {
                        Swal.fire({ title: resultData.success,icon: "success", timer: 1500 });
                        qrWrapper.classList.replace('d-block','d-none' );
                    } else {
                        throw new Error(resultData.error || 'Something went wrong');
                    }
                } catch (error) {
                    toastr.error(error.message);
                }
            }
        });
        camarOpen = true;
    }

    if (qrScanner.start()) {
        videoElem.closest('[data-wrapper=qr]').classList.replace('d-none', 'd-block');
        otpWrapper.classList.replace('d-block', 'd-none');
    }
});


document.querySelector('[data-submit=otp]').addEventListener('click',async ()=> {
   const inputLists = document.querySelectorAll('[data-userInput=list] input');
   let insertedOTP ='';

   for(let input of inputLists){
         insertedOTP += input.value;
         input.value = null;
   }
   try {
      if(insertedOTP != ''){
         const resp = await fetch("{{ route('mess.diet-order.varyfyQR') }}?otp=" + insertedOTP);
         const resultData = await resp.json();
         if (resultData.success) {
            Swal.fire({ title: resultData.success,icon: "success", timer: 1500 });
            otpWrapper.classList.replace('d-block','d-none' );
         } else {
            throw new Error(resultData.error || 'Something went wrong');
         }
      }
   } catch (error) {
      toastr.error(error.message);
   }
   
});
   
</script>
<script>
   document.querySelectorAll('[data-process-food]').forEach(element => {
       element.addEventListener('click', async () => {
           Swal.fire({
               title: "Do you really want to process the " + element.dataset.processFood.toUpperCase() + " food?",
               showDenyButton: true,
               showCancelButton: true,
               confirmButtonText: element.textContent.charAt(0).toUpperCase() + element.textContent.slice(1),
               showDenyButton : false,
           }).then(async (result) => { // Added async keyword
               if (result.isConfirmed) {
                   try {
                       const resp = await fetch("{{ route('mess.process-food.process') }}?service="+element.dataset.processFood+"&process_name="+element.dataset.processName);
                       if(!resp.ok){
                        error = await resp.json();
                      
                           throw new Error(error.message);
                       }
                       const resultData = await resp.json();
                        Swal.fire({ title: resultData.message, icon: "success", timer: 1500 });
                        setTimeout(() => {
                           location.reload();
                        }, 3000);
                       
                   } catch (error) {
                       toastr.error(error.message);
                   }
               } else if (result.isDenied) {
                   Swal.fire("Request Cancelled", "", "info");
               }
           });
       });
   });
</script>

@endpush