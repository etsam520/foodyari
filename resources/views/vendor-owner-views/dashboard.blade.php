@extends('vendor-owner-views.layouts.dashboard-main')
@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div class="row">
        <div class="col-md-12 col-lg-12">
           <div class="row row-cols-1">
              <div class="overflow-hidden d-slider1 ">
                 <ul class="p-0 m-0 mb-2 swiper-wrapper list-inline">
                    <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="700">
                       <div class="card-body">
                          <div class="progress-widget">
                             <div id="circle-progress-01"
                                class="text-center circle-progress-01 circle-progress circle-progress-primary"
                                data-min-value="0" data-max-value="100" data-value="90" data-type="percent">
                                <svg class="card-slie-arrow icon-24" width="24" viewBox="0 0 24 24">
                                   <path fill="currentColor" d="M5,17.59L15.59,7H9V5H19V15H17V8.41L6.41,19L5,17.59Z" />
                                </svg>
                             </div>
                             <div class="progress-detail">
                                <p class="mb-2">Total Sales</p>
                                <h4 class="counter">$560K</h4>
                             </div>
                          </div>
                       </div>
                    </li>
                    <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="800">
                       <div class="card-body">
                          <div class="progress-widget">
                             <div id="circle-progress-02"
                                class="text-center circle-progress-01 circle-progress circle-progress-info"
                                data-min-value="0" data-max-value="100" data-value="80" data-type="percent">
                                <svg class="card-slie-arrow icon-24" width="24" viewBox="0 0 24 24">
                                   <path fill="currentColor" d="M19,6.41L17.59,5L7,15.59V9H5V19H15V17H8.41L19,6.41Z" />
                                </svg>
                             </div>
                             <div class="progress-detail">
                                <p class="mb-2">Total Profit</p>
                                <h4 class="counter">$185K</h4>
                             </div>
                          </div>
                       </div>
                    </li>
                    <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="900">
                       <div class="card-body">
                          <div class="progress-widget">
                             <div id="circle-progress-03"
                                class="text-center circle-progress-01 circle-progress circle-progress-primary"
                                data-min-value="0" data-max-value="100" data-value="70" data-type="percent">
                                <svg class="card-slie-arrow icon-24" width="24" viewBox="0 0 24 24">
                                   <path fill="currentColor" d="M19,6.41L17.59,5L7,15.59V9H5V19H15V17H8.41L19,6.41Z" />
                                </svg>
                             </div>
                             <div class="progress-detail">
                                <p class="mb-2">Total Cost</p>
                                <h4 class="counter">$375K</h4>
                             </div>
                          </div>
                       </div>
                    </li>
                    <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="1000">
                       <div class="card-body">
                          <div class="progress-widget">
                             <div id="circle-progress-04"
                                class="text-center circle-progress-01 circle-progress circle-progress-info"
                                data-min-value="0" data-max-value="100" data-value="60" data-type="percent">
                                <svg class="card-slie-arrow icon-24" width="24px" viewBox="0 0 24 24">
                                   <path fill="currentColor" d="M5,17.59L15.59,7H9V5H19V15H17V8.41L6.41,19L5,17.59Z" />
                                </svg>
                             </div>
                             <div class="progress-detail">
                                <p class="mb-2">Revenue</p>
                                <h4 class="counter">$742K</h4>
                             </div>
                          </div>
                       </div>
                    </li>
                    <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="1100">
                       <div class="card-body">
                          <div class="progress-widget">
                             <div id="circle-progress-05"
                                class="text-center circle-progress-01 circle-progress circle-progress-primary"
                                data-min-value="0" data-max-value="100" data-value="50" data-type="percent">
                                <svg class="card-slie-arrow icon-24" width="24px" viewBox="0 0 24 24">
                                   <path fill="currentColor" d="M5,17.59L15.59,7H9V5H19V15H17V8.41L6.41,19L5,17.59Z" />
                                </svg>
                             </div>
                             <div class="progress-detail">
                                <p class="mb-2">Net Income</p>
                                <h4 class="counter">$150K</h4>
                             </div>
                          </div>
                       </div>
                    </li>
                    <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="1200">
                       <div class="card-body">
                          <div class="progress-widget">
                             <div id="circle-progress-06"
                                class="text-center circle-progress-01 circle-progress circle-progress-info"
                                data-min-value="0" data-max-value="100" data-value="40" data-type="percent">
                                <svg class="card-slie-arrow icon-24" width="24" viewBox="0 0 24 24">
                                   <path fill="currentColor" d="M19,6.41L17.59,5L7,15.59V9H5V19H15V17H8.41L19,6.41Z" />
                                </svg>
                             </div>
                             <div class="progress-detail">
                                <p class="mb-2">Today</p>
                                <h4 class="counter">$4600</h4>
                             </div>
                          </div>
                       </div>
                    </li>
                    <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="1300">
                       <div class="card-body">
                          <div class="progress-widget">
                             <div id="circle-progress-07"
                                class="text-center circle-progress-01 circle-progress circle-progress-primary"
                                data-min-value="0" data-max-value="100" data-value="30" data-type="percent">
                                <svg class="card-slie-arrow icon-24 " width="24" viewBox="0 0 24 24">
                                   <path fill="currentColor" d="M19,6.41L17.59,5L7,15.59V9H5V19H15V17H8.41L19,6.41Z" />
                                </svg>
                             </div>
                             <div class="progress-detail">
                                <p class="mb-2">Members</p>
                                <h4 class="counter">11.2M</h4>
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
     </div>

     <div class="row">
      @if($vendor->messes)
      <div class="col-lg-4">
          <div class="card-transparent mb-0 desk-info">
              <div class="card-body p-0">
                  <div class="row">
                      <div class="group4-wrap">
                          <div class="group" id="group4">
                              <h2 class="mb-2 text-gray">Messes</h2>
                              @foreach ($vendor->messes as $mess)    
                              <div class="col-lg-12 card bg-warning  px-0 " onclick="location.href='{{route('vendorOwner.myMess',$mess->id)}}'">
                                  <div class="card-body p-1">
                                      <div class="d-flex justify-content-center  px-1">
                                          <div class="card w-25 p-1 mx-1 mb-0">
                                              <img src= "{{asset('vendorMess/'.$mess->logo)}}" class="w-100 img-fluid " alt="" srcset="">
                                          </div>
                                          <div class=" w-75 card mb-0 bg-white p-2">
                                              <h3 class="mb-3 text-warning text-center">{{Str::ucfirst($mess->name)}}</h3>
                                              <hr class="hr-horizontal">
                                              <div class="d-flex align-items-center mb-3">
                                                  <div class="btn btn-icon btn-soft-light me-2">
                                                      <div class="btn-inner text-warning">
                                                         <i class="fa fa-map-marker-alt"></i>
                                                         @php($address = json_decode($mess->address))
                                                          <a href="https://www.google.com/maps?q={{$mess->latitude.",".$mess->longitude}}" class="text-warning m-0"><i class="feather-map-pin me-1"></i>
                                                              {{Str::ucfirst($address->street??null)}}, {{Str::ucfirst($address->city??null)}} - {{Str::ucfirst($address->pincode??null)}}
                                                          </a>
                                                          
                                                      </div>
                                                  </div>
                                                  <div class="btn btn-icon btn-soft-light me-2">
                                                      <div class="btn-inner text-warning">
                                                          <i class="fa fa-phone-alt"></i>
                                                          <a class="text-warning" href="tel:{{$mess->phone}}">{{$mess->phone}}</a>
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>
                                          
                                      </div>
                                  </div>
                              </div>
                              @endforeach
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
      @endif

      
      
  </div>

     {{-- <div class="row">
        <div class="col-md-8">
            <div class="card aos-init aos-animate" data-aos="fade-up" data-aos-delay="500">
                <div class="text-center card-body d-flex justify-content-around">
                   <div>
                      <h2 class="mb-2 text-gray">Restaurants</h2>
                   </div>
                   <hr class="hr-vertial">
                   <div>
                    <h2 class="mb-2 text-gray">Messes</h2>
                   </div>
                </div>
             </div>
        </div>
     </div> --}}
 </div>


 
@endsection