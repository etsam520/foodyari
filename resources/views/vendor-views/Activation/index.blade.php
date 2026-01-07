<!doctype html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>Food Yari| Admin Dashboard </title>
      
      <!-- Favicon -->
      <link rel="shortcut icon" href="{{asset('assets/images/favicon.ico')}}" />
      
      <!-- Library / Plugin Css Build -->
      <link rel="stylesheet" href="{{asset('assets/css/core/libs.min.css')}}" />
      
      
      <!-- Hope Ui Design System Css -->
      <link rel="stylesheet" href="{{asset('assets/css/hope-ui.min.css?v=2.0.0')}}" />
      
      <!-- Custom Css -->
      <link rel="stylesheet" href="{{asset('assets/css/custom.min.css?v=2.0.0')}}" />
      
      <!-- Dark Css -->
      <link rel="stylesheet" href="{{asset('assets/css/dark.min.css')}}"/>
      
      <!-- Customizer Css -->
      <link rel="stylesheet" href="{{asset('assets/css/customizer.min.css')}}" />
      <link rel="stylesheet" href="{{asset('assets/css/carousel-ticker.css')}}">
      <link rel="stylesheet" href="{{asset('assets/css/dash.css')}}" />
      
      <!-- RTL Css -->
      <link rel="stylesheet" href="{{asset('assets/css/rtl.min.css')}}"/>
      <link rel="stylesheet" href="{{asset('assets/vendor/toastr/toastr.min.css')}}">
      <style>
       
        .package-check input:checked[type=radio] + label {
           /* color: red; */
           width: 50px;
           background: url("{{asset('assets/images/icons/product-added.svg')}}");
         }
         .package-check label{
            width: 50px;
            height: 50px;
            background:url("{{ asset('assets/images/icons/buy.svg') }}");
         }
     </style>

    
      
      
  </head>
  <body class=" " data-bs-spy="scroll" data-bs-target="#elements-section" data-bs-offset="0" tabindex="0">
    <!-- loader Start -->
    <div id="loading">
      <div class="loader simple-loader">
          <div class="loader-body"></div>
      </div>    </div>
    <!-- loader END -->
    
      <div class="wrapper">
      <section class="login-content">
         <div class="row m-0 align-items-center bg-white vh-100">            
            <div class="col-md-6">
               <div class="row justify-content-center">
                  <div class="col-md-10">
                     <div class="card card-transparent shadow-none d-flex justify-content-center mb-0 auth-card">
                        <div class="card-body">
                           <a href="/" class="navbar-brand d-flex align-items-center mb-3">
                              <!--Logo start-->
                              <!--logo End-->
                              
                              <!--Logo start-->
                              {{--  --}}
                              <div class="logo-main">
                                 <div class="logo-normal">
                                     <img src="{{asset('assets/images/icons/foodyari.logo.jpg')}}" alt="logo" style="width: 200px;border-radius: 50%;">
                                 </div>
                              </div>
                              <!--logo End-->
                              
                              
                              
                              
                              {{-- <h4 class="logo-title ms-3">Food Yari</h4> --}}
                           </a>
                           <br>
                           <br>
                           <h2 class="mb-2 text-center">Select Subscription Metod</h2>
                           <p class="text-center">Login to stay connected.</p>
                           <br><br>
                           {{-- <p class="text-center"><a href="javascript:void(0)">Restaurent Login Click Here</a></p> --}}
                           
                           <form action="{{route('vendor.activate')}}" method="POST">
                            @csrf
                              <div class="row">
                                 <div class="col-lg-12">
                                    <div class="form-group m-0">
                                          <label class="form-label text-capitalize input-label font-medium" for="name">Choose Subscription Type</label> <br>
                                          <div class="form-check form-check-inline mt-4">
                                             <input class="form-check-input" type="radio" name="subscription_type" id="commision" onclick="hide_order_input()" checked="" value="comission">
                                             <label class="form-check-label text-dark" for="commision">Commission
                                                (Default)</label>
                                          </div>
                                          
                                          <div class="form-check form-check-inline">
                                             <input class="form-check-input" type="radio" name="subscription_type" id="Package" onclick="show_order_input()" value="subscription">
                                             <label class="form-check-label text-dark" for="Package">Package</label>
                                          </div>
                                    </div>
                                    <div class="form-group mt-4 m-0 d-none" id="subscription-gallery">
                                          @foreach ($subscription as $sb)
                                          <div class="card">
                                             <div class="card-body">
                                                <div class="flex-wrap mb-2 d-flex align-itmes-center justify-content-between">
                                                   <div class="d-flex align-itmes-center me-0 me-md-4">
                                                      <div>
                                                         <div class="p-3 mb-2 rounded bg-soft-primary">
                                                            <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M16.9303 7C16.9621 6.92913 16.977 6.85189 16.9739 6.77432H17C16.8882 4.10591 14.6849 2 12.0049 2C9.325 2 7.12172 4.10591 7.00989 6.77432C6.9967 6.84898 6.9967 6.92535 7.00989 7H6.93171C5.65022 7 4.28034 7.84597 3.88264 10.1201L3.1049 16.3147C2.46858 20.8629 4.81062 22 7.86853 22H16.1585C19.2075 22 21.4789 20.3535 20.9133 16.3147L20.1444 10.1201C19.676 7.90964 18.3503 7 17.0865 7H16.9303ZM15.4932 7C15.4654 6.92794 15.4506 6.85153 15.4497 6.77432C15.4497 4.85682 13.8899 3.30238 11.9657 3.30238C10.0416 3.30238 8.48184 4.85682 8.48184 6.77432C8.49502 6.84898 8.49502 6.92535 8.48184 7H15.4932ZM9.097 12.1486C8.60889 12.1486 8.21321 11.7413 8.21321 11.2389C8.21321 10.7366 8.60889 10.3293 9.097 10.3293C9.5851 10.3293 9.98079 10.7366 9.98079 11.2389C9.98079 11.7413 9.5851 12.1486 9.097 12.1486ZM14.002 11.2389C14.002 11.7413 14.3977 12.1486 14.8858 12.1486C15.3739 12.1486 15.7696 11.7413 15.7696 11.2389C15.7696 10.7366 15.3739 10.3293 14.8858 10.3293C14.3977 10.3293 14.002 10.7366 14.002 11.2389Z" fill="currentColor"></path>                                            
                                                            </svg>
                                                         </div>
                                                      </div>
                                                      <div class="ms-3">
                                                         <h5>{{$sb->validity." Days"}}</h5>
                                                         <small class="mb-0">Validity</small>
                                                      </div>
                                                   </div>
                                                   <div class="d-flex align-itmes-center">
                                                      <div>
                                                         <div class="p-3 mb-2 rounded bg-soft-info">
                                                            <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                               <path fill-rule="evenodd" clip-rule="evenodd" d="M14.1213 11.2331H16.8891C17.3088 11.2331 17.6386 10.8861 17.6386 10.4677C17.6386 10.0391 17.3088 9.70236 16.8891 9.70236H14.1213C13.7016 9.70236 13.3719 10.0391 13.3719 10.4677C13.3719 10.8861 13.7016 11.2331 14.1213 11.2331ZM20.1766 5.92749C20.7861 5.92749 21.1858 6.1418 21.5855 6.61123C21.9852 7.08067 22.0551 7.7542 21.9652 8.36549L21.0159 15.06C20.8361 16.3469 19.7569 17.2949 18.4879 17.2949H7.58639C6.25742 17.2949 5.15828 16.255 5.04837 14.908L4.12908 3.7834L2.62026 3.51807C2.22057 3.44664 1.94079 3.04864 2.01073 2.64043C2.08068 2.22305 2.47038 1.94649 2.88006 2.00874L5.2632 2.3751C5.60293 2.43735 5.85274 2.72207 5.88272 3.06905L6.07257 5.35499C6.10254 5.68257 6.36234 5.92749 6.68209 5.92749H20.1766ZM7.42631 18.9079C6.58697 18.9079 5.9075 19.6018 5.9075 20.459C5.9075 21.3061 6.58697 22 7.42631 22C8.25567 22 8.93514 21.3061 8.93514 20.459C8.93514 19.6018 8.25567 18.9079 7.42631 18.9079ZM18.6676 18.9079C17.8282 18.9079 17.1487 19.6018 17.1487 20.459C17.1487 21.3061 17.8282 22 18.6676 22C19.4969 22 20.1764 21.3061 20.1764 20.459C20.1764 19.6018 19.4969 18.9079 18.6676 18.9079Z" fill="currentColor"></path>                                            
                                                            </svg>                                        
                                                         </div>
                                                      </div>
                                                      <div class="ms-3">
                                                         <h6>{{Str::ucfirst($sb->max_order)}}</h6>
                                                         <small class="mb-0">Max Orders</small>
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="flex-wrap mb-4 d-flex align-itmes-center justify-content-between">
                                                   <div class="d-flex align-itmes-center">
                                                      <div>
                                                         <div class="p-3 mb-2 rounded bg-soft-info">
                                                            <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                               <path fill-rule="evenodd" clip-rule="evenodd" d="M14.1213 11.2331H16.8891C17.3088 11.2331 17.6386 10.8861 17.6386 10.4677C17.6386 10.0391 17.3088 9.70236 16.8891 9.70236H14.1213C13.7016 9.70236 13.3719 10.0391 13.3719 10.4677C13.3719 10.8861 13.7016 11.2331 14.1213 11.2331ZM20.1766 5.92749C20.7861 5.92749 21.1858 6.1418 21.5855 6.61123C21.9852 7.08067 22.0551 7.7542 21.9652 8.36549L21.0159 15.06C20.8361 16.3469 19.7569 17.2949 18.4879 17.2949H7.58639C6.25742 17.2949 5.15828 16.255 5.04837 14.908L4.12908 3.7834L2.62026 3.51807C2.22057 3.44664 1.94079 3.04864 2.01073 2.64043C2.08068 2.22305 2.47038 1.94649 2.88006 2.00874L5.2632 2.3751C5.60293 2.43735 5.85274 2.72207 5.88272 3.06905L6.07257 5.35499C6.10254 5.68257 6.36234 5.92749 6.68209 5.92749H20.1766ZM7.42631 18.9079C6.58697 18.9079 5.9075 19.6018 5.9075 20.459C5.9075 21.3061 6.58697 22 7.42631 22C8.25567 22 8.93514 21.3061 8.93514 20.459C8.93514 19.6018 8.25567 18.9079 7.42631 18.9079ZM18.6676 18.9079C17.8282 18.9079 17.1487 19.6018 17.1487 20.459C17.1487 21.3061 17.8282 22 18.6676 22C19.4969 22 20.1764 21.3061 20.1764 20.459C20.1764 19.6018 19.4969 18.9079 18.6676 18.9079Z" fill="currentColor"></path>                                            
                                                            </svg>                                        
                                                         </div>
                                                      </div>
                                                      <div class="ms-3">
                                                         <h6>{{Str::ucfirst($sb->max_product)}}</h6>
                                                         <small class="mb-0">Max Products</small>
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="mb-4">
                                                   <div class="flex-wrap d-flex justify-content-between">
                                                      <h2 class="mb-2">{{App\CentralLogics\Helpers::format_currency($sb->price)}}</h2>
                                                      {{-- <div>
                                                         <span class="badge bg-success rounded-pill">YoY 24%</span>
                                                      </div> --}}
                                                      
                                                      <div class="form-group package-check">
                                                         <input type="radio" name="package_id" id="buy-{{$sb->id}}" hidden value="{{$sb->id}}">
                                                         <label for="buy-{{$sb->id}}" class="buy-button p-2 btn text-uppercase">
                                                         </label>
                                                      </div>

                                                   </div>
                                                   <p class="text-info">{{Str::upper($sb->package_name)}}</p>
                                                   <p class="text-muted">{{Str::ucfirst($sb->text??'')}}</p>
                                                </div>
                                             </div>
                                                
                                          </div>
                                          @endforeach
                                    </div>
                                 </div>
                              </div>
                              <div class="d-flex justify-content-center mt-3">
                                 <button type="submit" class="btn btn-primary">Submit</button>
                              </div>
                           </form>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="sign-bg">
                  <svg width="280" height="230" viewBox="0 0 431 398" fill="none" xmlns="http://www.w3.org/2000/svg">
                     <g opacity="0.05">
                     <rect x="-157.085" y="193.773" width="543" height="77.5714" rx="38.7857" transform="rotate(-45 -157.085 193.773)" fill="#3B8AFF"/>
                     <rect x="7.46875" y="358.327" width="543" height="77.5714" rx="38.7857" transform="rotate(-45 7.46875 358.327)" fill="#3B8AFF"/>
                     <rect x="61.9355" y="138.545" width="310.286" height="77.5714" rx="38.7857" transform="rotate(45 61.9355 138.545)" fill="#3B8AFF"/>
                     <rect x="62.3154" y="-190.173" width="543" height="77.5714" rx="38.7857" transform="rotate(45 62.3154 -190.173)" fill="#3B8AFF"/>
                     </g>
                  </svg>
               </div>
            </div>
            <div class="col-md-6 d-md-block d-none bg-primary p-0 mt-n1 vh-100 overflow-hidden">
               <img src="{{asset('assets/images/auth/01.png')}}" class="img-fluid gradient-main animated-scaleX" alt="images">
            </div>
         </div>
      </section>
      </div>
    
    <!-- Library Bundle Script -->
    <script src="{{asset('assets/js/core/libs.min.js')}}"></script>
    
    <!-- External Library Bundle Script -->
    <script src="{{asset('assets/js/core/external.min.js')}}"></script>
    
    <!-- Widgetchart Script -->
    <script src="{{asset('assets/js/charts/widgetcharts.js')}}"></script>
    
    <!-- mapchart Script -->
    <script src="{{asset('assets/js/charts/vectore-chart.js')}}"></script>
    <script src="{{asset('assets/js/charts/dashboard.js')}}" ></script>
    
    <!-- fslightbox Script -->
    <script src="{{asset('assets/js/plugins/fslightbox.js')}}"></script>
    
    <!-- Settings Script -->
    <script src="{{asset('assets/js/plugins/setting.js')}}"></script>
    
    <!-- Slider-tab Script -->
    <script src="{{asset('assets/js/plugins/slider-tabs.js')}}"></script>
    
    <!-- Form Wizard Script -->
    <script src="{{asset('assets/js/plugins/form-wizard.js')}}"></script>
    
    <!-- AOS Animation Plugin-->
    
    <!-- App Script -->
    <script src="{{asset('assets/js/hope-ui.js')}}" defer></script>
    <script src="{{asset('assets/vendor/toastr/toastr.min.js')}}"></script>
    <script src="{{asset('assets/js/plugins/jquery.carousel-ticker.js')}}"></script>
    @if(Session::has('error'))
    <script>
        toastr.info('{{ Session::get('error') }}');
    </script>
    @endif
    <script>
      function show_order_input(){
            document.querySelector('#subscription-gallery').classList.replace("d-none","d-block");

        }
    function hide_order_input(){
      document.querySelector('#subscription-gallery').classList.replace("d-block","d-none");
        }
    </script>
  </body>
</html>