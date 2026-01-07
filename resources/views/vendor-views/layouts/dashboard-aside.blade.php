{{-- @dd('dfd'); --}}
{{-- @dd(Helpers::qrGenerate(Session::get('restaurant')->logo,'restaurant')); --}}

<aside class="sidebar pb-5 sidebar-default sidebar-white sidebar-base navs-rounded-all ">
    <div class="sidebar-header d-flex align-items-center justify-content-start">
        <a href="{{route('vendor.dashboard')}}" class="navbar-brand">
                <div class="logo-main">
                    <div class="logo-normal">
                        <img src="{{Helpers::getUploadFile(Session::get('restaurant')->logo,'restaurant')}}" alt="logo" style="width: 50px;border-radius: 50%;">
                    </div>
                    <div class="logo-mini">
                        <img src="{{Helpers::getUploadFile(Session::get('restaurant')->logo,'restaurant')}}" alt="logo" style="width: 50px;border-radius: 50%;">
                    </div>
                </div>
            {{-- <h4 class="logo-title">Food Yari</h4> --}}
        </a>

        <div class="sidebar-toggle" data-toggle="sidebar" data-active="true">
            <i class="icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.25 12.2744L19.25 12.2744" stroke="currentColor" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M10.2998 18.2988L4.2498 12.2748L10.2998 6.24976" stroke="currentColor" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </i>
        </div>
    </div>
    <div class="sidebar-body pt-0 data-scrollbar">
        <div class="sidebar-list">
        @if(Cookie::has('active_store'))
        <?php
            $vendor =  App\Models\Vendor::with(['restaurants','messes'])->find(Session::get('restaurant')->vendor_id);
            $stores = [];
            foreach ($vendor->restaurants as  $restaurant) {
                $stores[] = [
                    'name' => $restaurant->name,
                    'type' => 'restaurant',
                    'id' => $restaurant->id
                ];
            }
            foreach ($vendor->messes as  $mess) {
                $stores[] = [
                    'name' => $mess->name,
                    'type' => 'mess',
                    'id' => $mess->id
                ];
            }

            // dd($stores);
        ?>

            <div class="w-100 mt-3">
                <label class="visually-hidden" for="autoSizingInputGroup">Username</label>
                <div class="input-group">
                  <div class="input-group-text"><i class="fa-solid fa-store"></i></div>
                  <select type="text" class="form-select form-select-sm" onchange="location.href= this.value" id="autoSizingInputGroup">
                    @foreach ($stores as $store)
                    <option value="{{route('vendor.dashboard-changer',['name'=>$store['name'],'type'=> $store['type'],'id'=>$store['id'] ])}}"
                        {{$store['type']=='restaurant'? $store['id']== Session::get('restaurant')->id ? 'selected' :null : null }}
                        >
                        {{Str::ucfirst($store['name'])}}
                    </option>
                    @endforeach
                    {{-- <option value="ABC Restfffaurant">Abc Resfftaurnt</option> --}}
                  </select>
                </div>
            </div>
        {{-- </div> --}}
        @endif
        {{-- <div class="sidebar-list mb-5"> --}}
            <!-- Sidebar Menu Start -->
            <ul class="navbar-nav iq-main-menu mt-3" id="sidebar-menu">
                <li class="nav-item static-item">
                    <div class="card bg-primary p-2 border rounded-3">
                        <div class="card-body p-2 position-relative">
                            <div class="item">
                                @php
                                    $restaurant = Session::get('restaurant');
                                    $restaurantlink = route('user.restaurant.get-restaurant', ['name' => $restaurant->url_slug??Str::slug($restaurant->name)]);
                                    $qrbase64 = Helpers::qrGenerate(Session::get('restaurant')->name,$restaurantlink)

                                @endphp
                                <p class="text-center fw-bold font-18 text-white">{{Str::ucfirst($restaurant->name)}}</p>
                                {{-- <img src="{{Helpers::qrGenerate(Session::get('restaurant')->logo,'restaurant')}}" alt=""> --}}
                                <div id="qrCodeCarousel" class="carousel slide" data-bs-ride="carousel">
                                    <div class="carousel-inner">
                                        

                                        <div class="carousel-item active">
                                            <img src="{{$qrbase64}}" alt="qr restuarant" class="img img-fluid w-100 mb-3">
                                            {{-- {!! $qrCode->image("image alt", ['class' => 'img img-fluid w-100 mb-3']) !!} --}}

                                        </div>
                                        <div class="carousel-item">
                                            <img src="{{$qrbase64}}" alt="qr restuarant" class="img img-fluid w-100 mb-3">
                                        </div>
                                    </div>
                                    <button class="carousel-control-prev" type="button" data-bs-target="#qrCodeCarousel" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#qrCodeCarousel" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Next</span>
                                    </button>
                                    <div class="carousel-indicators" style="margin-bottom: -17px;">
                                        <button type="button" data-bs-target="#qrCodeCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1" style="width: 10px; height: 10px;"></button>
                                        <button type="button" data-bs-target="#qrCodeCarousel" data-bs-slide-to="1" aria-label="Slide 2" style="width: 10px; height: 10px;"></button>
                                    </div>
                                </div>
                                {{-- <img src="" alt="LYBR01001" class="img img-fluid w-100 mb-3"> --}}
                                {{-- <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(200)->generate('https://example.com')) !!} "> --}}
                                    <div class="align-items-center justify-content-between bg-white p-3 rounded">
                                        <div class="text-center" style="max-width: 100%;">
                                            <a href="{{route('user.restaurant.get-restaurant', ['name' => $restaurant->url_slug??Str::slug($restaurant->name)])}}" id="linkToCopy" class="text-primary text-center d-block" style="max-width: 100%; overflow-x: auto; white-space: nowrap; scrollbar-width: thin;" onmouseover="smoothScroll(this)" onmouseout="stopScroll(this)">
                                            {{route('user.restaurant.get-restaurant', ['name' => $restaurant->url_slug??Str::slug($restaurant->name)])}}</a>

                                            <script>
                                                let scrollInterval;

                                                function smoothScroll(element) {
                                                    scrollInterval = setInterval(() => {
                                                        element.scrollLeft += 1;
                                                    }, 10);
                                                }

                                                function stopScroll(element) {
                                                    clearInterval(scrollInterval);
                                                    element.scrollLeft = 0;
                                                }
                                            </script>
                                            <button class="btn btn-sm btn-primary mt-2" onclick="copyToClipboard()">
                                                <i class="fa fa-copy me-1"></i> Copy Link
                                            </button>
                                        </div>
                                    </div>


                                    <script>
                                        function copyToClipboard() {
                                            const link = document.getElementById('linkToCopy').href;
                                            navigator.clipboard.writeText(link).then(() => {
                                                alert('Link copied to clipboard!');
                                            }).catch(err => {
                                                console.error('Failed to copy link: ', err);
                                            });
                                        }
                                    </script>


                                <div class="d-flex justify-content-between mt-3">
                                    <a href="{{route('vendor.qr')}}" class="text-white" title="Download QR Code" download="{{Str::slug($restaurant->name)}}.png"><i class="feather-download me-2"></i>Download</a>
                                    <a href="{{route('user.restaurant.get-restaurant', ['name' => $restaurant->url_slug??Str::slug($restaurant->name)])}}" class="text-white" target="blank" title="Download QR Code"><i class="feather-external-link"></i> Open</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="nav-item static-item">
                    <a class="nav-link static-item disabled" href="javascript:void(0)" tabindex="-1">
                        <span class="default-icon">Home</span>
                        <span class="mini-icon">-</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="{{route('vendor.dashboard')}}">
                        <i class="icon">
                            <svg width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                                class="icon-20">
                                <path opacity="0.4"
                                    d="M16.0756 2H19.4616C20.8639 2 22.0001 3.14585 22.0001 4.55996V7.97452C22.0001 9.38864 20.8639 10.5345 19.4616 10.5345H16.0756C14.6734 10.5345 13.5371 9.38864 13.5371 7.97452V4.55996C13.5371 3.14585 14.6734 2 16.0756 2Z"
                                    fill="currentColor"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M4.53852 2H7.92449C9.32676 2 10.463 3.14585 10.463 4.55996V7.97452C10.463 9.38864 9.32676 10.5345 7.92449 10.5345H4.53852C3.13626 10.5345 2 9.38864 2 7.97452V4.55996C2 3.14585 3.13626 2 4.53852 2ZM4.53852 13.4655H7.92449C9.32676 13.4655 10.463 14.6114 10.463 16.0255V19.44C10.463 20.8532 9.32676 22 7.92449 22H4.53852C3.13626 22 2 20.8532 2 19.44V16.0255C2 14.6114 3.13626 13.4655 4.53852 13.4655ZM19.4615 13.4655H16.0755C14.6732 13.4655 13.537 14.6114 13.537 16.0255V19.44C13.537 20.8532 14.6732 22 16.0755 22H19.4615C20.8637 22 22 20.8532 22 19.44V16.0255C22 14.6114 20.8637 13.4655 19.4615 13.4655Z"
                                    fill="currentColor"></path>
                            </svg>
                        </i>
                        <span class="item-name">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('vendor.coupon.add-new')}}" >
                        <i class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="20">
                                <path
                                    d="M47,21a1,1,0,0,0,1-1V12a3,3,0,0,0-3-3H18a1,1,0,0,0-1,1,2,2,0,0,1-4,0,1,1,0,0,0-1-1H3a3,3,0,0,0-3,3v8a1,1,0,0,0,1,1,3,3,0,0,1,0,6,1,1,0,0,0-1,1v8a3,3,0,0,0,3,3h9a1,1,0,0,0,1-1,2,2,0,0,1,4,0,1,1,0,0,0,1,1H45a3,3,0,0,0,3-3V28a1,1,0,0,0-1-1,3,3,0,0,1,0-6Zm-1,7.9V36a1,1,0,0,1-1,1H18.87a4,4,0,0,0-7.74,0H3a1,1,0,0,1-1-1V28.9a5,5,0,0,0,0-9.8V12a1,1,0,0,1,1-1h8.13a4,4,0,0,0,7.74,0H45a1,1,0,0,1,1,1v7.1A5,5,0,0,0,46,28.9Z"
                                    fill="currentColor" ></path>
                                <path
                                    d="M14 17v2a1 1 0 0 0 2 0V17A1 1 0 0 0 14 17zM14 23v2a1 1 0 0 0 2 0V23A1 1 0 0 0 14 23zM14 29v2a1 1 0 0 0 2 0V29A1 1 0 0 0 14 29zM36.29 16.29l-14 14A1 1 0 0 0 23 32c.59 0-.53.94 14.71-14.29A1 1 0 0 0 36.29 16.29zM35 25a4 4 0 1 0 4 4A4 4 0 0 0 35 25zm0 6a2 2 0 1 1 2-2A2 2 0 0 1 35 31zM25 23a4 4 0 1 0-4-4A4 4 0 0 0 25 23zm0-6a2 2 0 1 1-2 2A2 2 0 0 1 25 17z"
                                    fill="currentColor" ></path>
                            </svg>
                        </i>
                        <span class="item-name">Coupons</span>
                        {{-- <i class="right-icon">
                            <svg class="icon-18" xmlns="http://www.w3.org/2000/svg" width="18" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </i> --}}
                    </a>
                    {{-- <ul class="sub-nav collapse" id="coupons" data-bs-parent="#sidebar-menu">
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('vendor.coupon.add-new')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> C </i>
                                <span class="item-name">Add</span>
                            </a>
                        </li>
                    </ul> --}}
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#orders" role="button"
                        aria-expanded="false" aria-controls="orders">
                        <i class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" class="icon-20" width="20">
                                <path
                                    d="M57.64423,53.41349l-4.995-34.96809a5.79886,5.79886,0,0,0-5.71139-4.953h-4.6807l-.9398-2.821C38.35577,1.78071,25.64643,1.77149,22.682,10.672L21.7422,13.4924H17.06151a5.79885,5.79885,0,0,0-5.71139,4.953l-4.995,34.96809a5.80619,5.80619,0,0,0,5.71146,6.585l39.86629.00006A5.8063,5.8063,0,0,0,57.64423,53.41349ZM24.57994,11.3047C26.9,4.233,37.10036,4.23455,39.41939,11.30433l.72907,2.18807H23.85093ZM54.77876,56.69822a3.76656,3.76656,0,0,1-2.84592,1.29885H12.06648a3.7913,3.7913,0,0,1-3.72934-4.30022l4.995-34.968a3.787,3.787,0,0,1,3.72941-3.23489c.00391-.0025,24.47393.00177,24.47772,0h5.39859a3.78623,3.78623,0,0,1,3.72941,3.23489l4.995,34.96809A3.765,3.765,0,0,1,54.77876,56.69822Z"
                                    fill="currentColor" ></path>
                                <path
                                    d="M37.54686,32.79815,31.3048,39.0412l-2.76774-2.76774A1.00083,1.00083,0,0,0,27.122,37.6886l3.47525,3.47531a1.00637,1.00637,0,0,0,1.41514,0L38.962,34.21329A1.00085,1.00085,0,0,0,37.54686,32.79815Z"
                                    fill="currentColor" ></path>
                                <path
                                    d="M31.99966,23.47072A13.5254,13.5254,0,0,0,18.48935,36.981c.74154,17.92334,26.28184,17.91815,27.02062-.00012A13.52537,13.52537,0,0,0,31.99966,23.47072Zm0,25.01909A11.5222,11.5222,0,0,1,20.49088,36.981c.63226-15.26794,22.38769-15.26354,23.01756.00006A11.52218,11.52218,0,0,1,31.99966,48.48981Z"
                                    fill="currentColor" ></path>
                            </svg>
                        </i>
                        <span class="item-name">Orders</span>
                        <i class="right-icon">
                            <svg class="icon-18" xmlns="http://www.w3.org/2000/svg" width="18" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </i>
                    </a>
                    <ul class="sub-nav collapse" id="orders" data-bs-parent="#sidebar-menu">
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('vendor.order.list', 'all')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> O</i>
                                <span class="item-name">All</span>
                            </a>
                        </li>
                    </ul>
                    <ul class="sub-nav collapse" id="orders" data-bs-parent="#sidebar-menu">
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('vendor.order.list', 'pending')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> O</i>
                                <span class="item-name">Pending</span>
                            </a>
                        </li>
                    </ul>
                    <ul class="sub-nav collapse" id="orders" data-bs-parent="#sidebar-menu">
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('vendor.order.list', 'accepted')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> O</i>
                                <span class="item-name">Approved</span>
                            </a>
                        </li>
                    </ul>
                    <ul class="sub-nav collapse" id="orders" data-bs-parent="#sidebar-menu">
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('vendor.order.list', 'confirmed')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> O</i>
                                <span class="item-name">Confirmed</span>
                            </a>
                        </li>
                    </ul>
                    <ul class="sub-nav collapse" id="orders" data-bs-parent="#sidebar-menu">
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('vendor.order.list', 'processing')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> O</i>
                                <span class="item-name">Proessing</span>
                            </a>
                        </li>
                    </ul>
                    <ul class="sub-nav collapse" id="orders" data-bs-parent="#sidebar-menu">
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('vendor.order.list', 'handover')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> O</i>
                                <span class="item-name">Handover</span>
                            </a>
                        </li>
                    </ul>
                    <ul class="sub-nav collapse" id="orders" data-bs-parent="#sidebar-menu">
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('vendor.order.list', 'delivered')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> O</i>
                                <span class="item-name">Delivered</span>
                            </a>
                        </li>
                    </ul>
                    <ul class="sub-nav collapse" id="orders" data-bs-parent="#sidebar-menu">
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('vendor.order.list', 'canceled')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> O</i>
                                <span class="item-name">Canceled</span>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <!-- Refund Management Section -->
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#refunds" role="button"
                        aria-expanded="false" aria-controls="refunds">
                        <i class="icon">
                            <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.4"
                                    d="M16.0756 2H19.4616C20.8639 2 22.0001 3.14585 22.0001 4.55996V7.97452C22.0001 9.38864 20.8639 10.5345 19.4616 10.5345H16.0756C14.6734 10.5345 13.5371 9.38864 13.5371 7.97452V4.55996C13.5371 3.14585 14.6734 2 16.0756 2Z"
                                    fill="currentColor"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M4.53852 2H7.92449C9.32676 2 10.463 3.14585 10.463 4.55996V7.97452C10.463 9.38864 9.32676 10.5345 7.92449 10.5345H4.53852C3.13626 10.5345 2 9.38864 2 7.97452V4.55996C2 3.14585 3.13626 2 4.53852 2ZM4.53852 13.4655H7.92449C9.32676 13.4655 10.463 14.6114 10.463 16.0255V19.44C10.463 20.8532 9.32676 22 7.92449 22H4.53852C3.13626 22 2 20.8532 2 19.44V16.0255C2 14.6114 3.13626 13.4655 4.53852 13.4655ZM19.4615 13.4655H16.0755C14.6732 13.4655 13.537 14.6114 13.537 16.0255V19.44C13.537 20.8532 14.6732 22 16.0755 22H19.4615C20.8637 22 22 20.8532 22 19.44V16.0255C22 14.6114 20.8637 13.4655 19.4615 13.4655Z"
                                    fill="currentColor"></path>
                            </svg>
                        </i>
                        <i class="sidenav-mini-icon"> RF </i>
                        <span class="item-name">Refund Management</span>
                    </a>
                    <ul class="sub-nav collapse" id="refunds" data-bs-parent="#sidebar-menu">
                        <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('vendor.refund.index') ? 'active' : '' }}" 
                               href="{{ route('vendor.refund.index') }}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> AR </i>
                                <span class="item-name">All Refunds</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('vendor.refund.index') && request('status') == 'pending' ? 'active' : '' }}" 
                               href="{{ route('vendor.refund.index', ['status' => 'pending']) }}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> PR </i>
                                <span class="item-name">Pending Refunds</span>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <li>
                    <hr class="hr-horizontal">
                </li>


                <li>
                    <hr class="hr-horizontal">
                </li>
                <li class="nav-item static-item">
                    <a class="nav-link static-item disabled" href="javascript:void(0)" tabindex="-1">
                        <span class="default-icon">Food Management</span>
                        <span class="mini-icon">-</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#restaurant-menu" role="button"
                        aria-expanded="false" aria-controls="restaurant-menu">
                        <i class="icon">

                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024" width="20">
                                <path d="M907.9 875.8H116.7c-2.6 0-5.2-.1-7.8-.5 3.5.5 7.1 1 10.6 1.4-4.5-.7-8.8-1.8-12.9-3.5 3.2 1.3 6.4 2.7 9.6 4-4-1.8-7.8-4-11.3-6.6l8.1 6.3c-3.4-2.7-6.5-5.8-9.2-9.2l6.3 8.1c-2.7-3.5-4.9-7.3-6.6-11.3 1.3 3.2 2.7 6.4 4 9.6-1.7-4.2-2.9-8.5-3.5-12.9.5 3.5 1 7.1 1.4 10.6-1-8-.5-16.4-.5-24.4V160.3c0-2.6.2-5.1.5-7.7-.5 3.5-1 7.1-1.4 10.6.7-4.5 1.8-8.8 3.5-12.9-1.3 3.2-2.7 6.4-4 9.6 1.8-4 4-7.8 6.6-11.3l-6.3 8.1c2.7-3.4 5.8-6.5 9.2-9.2l-8.1 6.3c3.5-2.7 7.3-4.9 11.3-6.6-3.2 1.3-6.4 2.7-9.6 4 4.2-1.7 8.5-2.9 12.9-3.5-3.5.5-7.1 1-10.6 1.4 9.9-1.2 20.2-.5 30.1-.5h200.1c11.5 0 23.6-1 35 .3-3.5-.5-7.1-1-10.6-1.4 3 .5 5.8 1.2 8.6 2.3-3.2-1.3-6.4-2.7-9.6-4 2.7 1.2 5.2 2.7 7.6 4.4l-8.1-6.3c2.4 1.9 4.4 4 6.3 6.4l-6.3-8.1c25.4 33.7 50.9 67.4 76.3 101.1 9.9 13.1 19.7 26.1 29.6 39.2.1.1.1.2.2.2 2.1 2.7-11.1-9.2 6.3 8.1 7.6 7.6 17.6 11.5 28.3 11.7H815.5c30.5 0 60.9-.1 91.4 0 2.6 0 5.2.2 7.8.5-3.5-.5-7.1-1-10.6-1.4 4.5.7 8.8 1.8 12.9 3.5-3.2-1.3-6.4-2.7-9.6-4 4 1.8 7.8 4 11.3 6.6l-8.1-6.3c3.4 2.7 6.5 5.8 9.2 9.2l-6.3-8.1c2.7 3.5 4.9 7.3 6.6 11.3-1.3-3.2-2.7-6.4-4-9.6 1.7 4.2 2.9 8.5 3.5 12.9-.5-3.5-1-7.1-1.4-10.6.8 6.7.5 13.6.5 20.4V799c0 21.9.2 43.8 0 65.7 0 2.5-.2 5-.5 7.5.5-3.5 1-7.1 1.4-10.6-.7 4.5-1.8 8.8-3.5 12.9 1.3-3.2 2.7-6.4 4-9.6-1.8 4-4 7.8-6.6 11.3l6.3-8.1c-2.7 3.4-5.8 6.5-9.2 9.2l8.1-6.3c-3.5 2.7-7.3 4.9-11.3 6.6 3.2-1.3 6.4-2.7 9.6-4-4.2 1.7-8.5 2.9-12.9 3.5 3.5-.5 7.1-1 10.6-1.4-2.1 0-4.4.1-6.8.1-10.3.1-20.9 4.4-28.3 11.7-6.9 6.9-12.2 18.3-11.7 28.3 1 21.4 17.6 40.3 40 40 28.4-.4 54.5-13.2 71.8-35.6 12.5-16.1 19-35.3 19.2-55.7.1-5.6 0-11.1 0-16.7V348.8c0-28.7 2.8-59.3-14.2-84.5-8.2-12.2-18.2-22.4-30.9-29.9-13-7.6-26.9-11.1-41.8-12.3-2.7-.2-5.4-.2-8.1-.2H502.7l34.5 19.8c-21-27.8-41.9-55.5-62.9-83.3-12.1-16-24.1-32-36.2-47.9-2.3-3.1-4.6-6.1-6.9-9.2-3.1-4.1-6.4-9-10.3-12.6-7.2-6.6-14-10.9-22.8-14.6-9.3-3.9-18.5-5.9-28.8-6.1-31.1-.5-62.3 0-93.4 0H141.5c-8.5 0-17.1-.1-25.6 0-25.2.3-50.9 10.8-67.5 30-14.9 17.3-23.3 38.1-23.6 61.1-.1 13.8 0 27.7 0 41.6V822.2c0 14.1-.2 28.2 0 42.3.1 7.5 1.1 15.1 2.8 22.4 3.3 14.2 10.6 26.1 19.6 37.2 16 20 42.1 30.8 67.3 31.6 3.4.1 6.8 0 10.2 0h783c20.9 0 41-18.4 40-40-.7-21.5-17.3-39.9-39.8-39.9z" fill="currentColor"></path>
                                <path d="M907.9 875.8H116.7c-2.6 0-5.2-.1-7.8-.5 3.5.5 7.1 1 10.6 1.4-4.5-.7-8.8-1.8-12.9-3.5 3.2 1.3 6.4 2.7 9.6 4-4-1.8-7.8-4-11.3-6.6l8.1 6.3c-3.4-2.7-6.5-5.8-9.2-9.2l6.3 8.1c-2.7-3.5-4.9-7.3-6.6-11.3 1.3 3.2 2.7 6.4 4 9.6-1.7-4.2-2.9-8.5-3.5-12.9.5 3.5 1 7.1 1.4 10.6-.8-6.7-.5-13.6-.5-20.4V379.3c0-21.9-.2-43.8 0-65.7 0-2.5.2-5 .5-7.5-.5 3.5-1 7.1-1.4 10.6.7-4.5 1.8-8.8 3.5-12.9-1.3 3.2-2.7 6.4-4 9.6 1.8-4 4-7.8 6.6-11.3l-6.3 8.1c2.7-3.4 5.8-6.5 9.2-9.2l-8.1 6.3c3.5-2.7 7.3-4.9 11.3-6.6-3.2 1.3-6.4 2.7-9.6 4 4.2-1.7 8.5-2.9 12.9-3.5-3.5.5-7.1 1-10.6 1.4 8.8-1.1 17.9-.5 26.7-.5H906.9c2.6 0 5.2.1 7.8.5-3.5-.5-7.1-1-10.6-1.4 4.5.7 8.8 1.8 12.9 3.5-3.2-1.3-6.4-2.7-9.6-4 4 1.8 7.8 4 11.3 6.6l-8.1-6.3c3.4 2.7 6.5 5.8 9.2 9.2l-6.3-8.1c2.7 3.5 4.9 7.3 6.6 11.3-1.3-3.2-2.7-6.4-4-9.6 1.7 4.2 2.9 8.5 3.5 12.9-.5-3.5-1-7.1-1.4-10.6.8 6.7.5 13.6.5 20.4v472.1c0 21.9.2 43.8 0 65.7 0 2.5-.2 5-.5 7.5.5-3.5 1-7.1 1.4-10.6-.7 4.5-1.8 8.8-3.5 12.9 1.3-3.2 2.7-6.4 4-9.6-1.8 4-4 7.8-6.6 11.3l6.3-8.1c-2.7 3.4-5.8 6.5-9.2 9.2l8.1-6.3c-3.5 2.7-7.3 4.9-11.3 6.6 3.2-1.3 6.4-2.7 9.6-4-4.2 1.7-8.5 2.9-12.9 3.5 3.5-.5 7.1-1 10.6-1.4-2.1.4-4.4.5-6.8.5-10.3.1-20.9 4.4-28.3 11.7-6.9 6.9-12.2 18.3-11.7 28.3 1 21.4 17.6 40.3 40 40 17.5-.2 36-5 50.3-15.3 17.4-12.5 29.4-28.1 36.5-48.3 3.4-9.9 4.3-20.6 4.3-31V313c-.2-16.5-4.5-33.7-13.5-47.7-4.7-7.3-10.3-14.6-17.1-20.3-9.8-8.1-18.2-13-30-17.7-13.8-5.6-28.5-5.4-43-5.4H120.1c-2.3 0-4.7 0-7 .1-39.4 2.1-73.7 27.8-84.8 66.1-4.2 14.4-3.4 29.4-3.4 44.2V840c0 8.2-.1 16.4 0 24.6.2 17.2 4.9 35.6 14.9 49.8 12.2 17.4 27.6 29.1 47.5 36.5 9.8 3.7 20.6 4.7 31 4.7H907.9c20.9 0 41-18.4 40-40-.9-21.4-17.5-39.8-40-39.8z" fill="currentColor"></path>
                            </svg>
                        </i>
                        <span class="item-name"> Menu</span>
                        <i class="right-icon">
                            <svg class="icon-18" xmlns="http://www.w3.org/2000/svg" width="18" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </i>
                    </a>
                    <ul class="sub-nav collapse" id="restaurant-menu" data-bs-parent="#sidebar-menu">
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('vendor.restaurant-menu.index')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> S </i>
                                <span class="item-name"> Menu List</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('vendor.restaurant-menu.create')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> S </i>
                                <span class="item-name"> Create Menu</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#sub-menu" role="button"
                        aria-expanded="false" aria-controls="sub-menu">
                        <i class="icon">

                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024" width="20">
                                <path d="M907.9 875.8H116.7c-2.6 0-5.2-.1-7.8-.5 3.5.5 7.1 1 10.6 1.4-4.5-.7-8.8-1.8-12.9-3.5 3.2 1.3 6.4 2.7 9.6 4-4-1.8-7.8-4-11.3-6.6l8.1 6.3c-3.4-2.7-6.5-5.8-9.2-9.2l6.3 8.1c-2.7-3.5-4.9-7.3-6.6-11.3 1.3 3.2 2.7 6.4 4 9.6-1.7-4.2-2.9-8.5-3.5-12.9.5 3.5 1 7.1 1.4 10.6-1-8-.5-16.4-.5-24.4V160.3c0-2.6.2-5.1.5-7.7-.5 3.5-1 7.1-1.4 10.6.7-4.5 1.8-8.8 3.5-12.9-1.3 3.2-2.7 6.4-4 9.6 1.8-4 4-7.8 6.6-11.3l-6.3 8.1c2.7-3.4 5.8-6.5 9.2-9.2l-8.1 6.3c3.5-2.7 7.3-4.9 11.3-6.6-3.2 1.3-6.4 2.7-9.6 4 4.2-1.7 8.5-2.9 12.9-3.5-3.5.5-7.1 1-10.6 1.4 9.9-1.2 20.2-.5 30.1-.5h200.1c11.5 0 23.6-1 35 .3-3.5-.5-7.1-1-10.6-1.4 3 .5 5.8 1.2 8.6 2.3-3.2-1.3-6.4-2.7-9.6-4 2.7 1.2 5.2 2.7 7.6 4.4l-8.1-6.3c2.4 1.9 4.4 4 6.3 6.4l-6.3-8.1c25.4 33.7 50.9 67.4 76.3 101.1 9.9 13.1 19.7 26.1 29.6 39.2.1.1.1.2.2.2 2.1 2.7-11.1-9.2 6.3 8.1 7.6 7.6 17.6 11.5 28.3 11.7H815.5c30.5 0 60.9-.1 91.4 0 2.6 0 5.2.2 7.8.5-3.5-.5-7.1-1-10.6-1.4 4.5.7 8.8 1.8 12.9 3.5-3.2-1.3-6.4-2.7-9.6-4 4 1.8 7.8 4 11.3 6.6l-8.1-6.3c3.4 2.7 6.5 5.8 9.2 9.2l-6.3-8.1c2.7 3.5 4.9 7.3 6.6 11.3-1.3-3.2-2.7-6.4-4-9.6 1.7 4.2 2.9 8.5 3.5 12.9-.5-3.5-1-7.1-1.4-10.6.8 6.7.5 13.6.5 20.4V799c0 21.9.2 43.8 0 65.7 0 2.5-.2 5-.5 7.5.5-3.5 1-7.1 1.4-10.6-.7 4.5-1.8 8.8-3.5 12.9 1.3-3.2 2.7-6.4 4-9.6-1.8 4-4 7.8-6.6 11.3l6.3-8.1c-2.7 3.4-5.8 6.5-9.2 9.2l8.1-6.3c-3.5 2.7-7.3 4.9-11.3 6.6 3.2-1.3 6.4-2.7 9.6-4-4.2 1.7-8.5 2.9-12.9 3.5 3.5-.5 7.1-1 10.6-1.4-2.1 0-4.4.1-6.8.1-10.3.1-20.9 4.4-28.3 11.7-6.9 6.9-12.2 18.3-11.7 28.3 1 21.4 17.6 40.3 40 40 28.4-.4 54.5-13.2 71.8-35.6 12.5-16.1 19-35.3 19.2-55.7.1-5.6 0-11.1 0-16.7V348.8c0-28.7 2.8-59.3-14.2-84.5-8.2-12.2-18.2-22.4-30.9-29.9-13-7.6-26.9-11.1-41.8-12.3-2.7-.2-5.4-.2-8.1-.2H502.7l34.5 19.8c-21-27.8-41.9-55.5-62.9-83.3-12.1-16-24.1-32-36.2-47.9-2.3-3.1-4.6-6.1-6.9-9.2-3.1-4.1-6.4-9-10.3-12.6-7.2-6.6-14-10.9-22.8-14.6-9.3-3.9-18.5-5.9-28.8-6.1-31.1-.5-62.3 0-93.4 0H141.5c-8.5 0-17.1-.1-25.6 0-25.2.3-50.9 10.8-67.5 30-14.9 17.3-23.3 38.1-23.6 61.1-.1 13.8 0 27.7 0 41.6V822.2c0 14.1-.2 28.2 0 42.3.1 7.5 1.1 15.1 2.8 22.4 3.3 14.2 10.6 26.1 19.6 37.2 16 20 42.1 30.8 67.3 31.6 3.4.1 6.8 0 10.2 0h783c20.9 0 41-18.4 40-40-.7-21.5-17.3-39.9-39.8-39.9z" fill="currentColor"></path>
                                <path d="M907.9 875.8H116.7c-2.6 0-5.2-.1-7.8-.5 3.5.5 7.1 1 10.6 1.4-4.5-.7-8.8-1.8-12.9-3.5 3.2 1.3 6.4 2.7 9.6 4-4-1.8-7.8-4-11.3-6.6l8.1 6.3c-3.4-2.7-6.5-5.8-9.2-9.2l6.3 8.1c-2.7-3.5-4.9-7.3-6.6-11.3 1.3 3.2 2.7 6.4 4 9.6-1.7-4.2-2.9-8.5-3.5-12.9.5 3.5 1 7.1 1.4 10.6-.8-6.7-.5-13.6-.5-20.4V379.3c0-21.9-.2-43.8 0-65.7 0-2.5.2-5 .5-7.5-.5 3.5-1 7.1-1.4 10.6.7-4.5 1.8-8.8 3.5-12.9-1.3 3.2-2.7 6.4-4 9.6 1.8-4 4-7.8 6.6-11.3l-6.3 8.1c2.7-3.4 5.8-6.5 9.2-9.2l-8.1 6.3c3.5-2.7 7.3-4.9 11.3-6.6-3.2 1.3-6.4 2.7-9.6 4 4.2-1.7 8.5-2.9 12.9-3.5-3.5.5-7.1 1-10.6 1.4 8.8-1.1 17.9-.5 26.7-.5H906.9c2.6 0 5.2.1 7.8.5-3.5-.5-7.1-1-10.6-1.4 4.5.7 8.8 1.8 12.9 3.5-3.2-1.3-6.4-2.7-9.6-4 4 1.8 7.8 4 11.3 6.6l-8.1-6.3c3.4 2.7 6.5 5.8 9.2 9.2l-6.3-8.1c2.7 3.5 4.9 7.3 6.6 11.3-1.3-3.2-2.7-6.4-4-9.6 1.7 4.2 2.9 8.5 3.5 12.9-.5-3.5-1-7.1-1.4-10.6.8 6.7.5 13.6.5 20.4v472.1c0 21.9.2 43.8 0 65.7 0 2.5-.2 5-.5 7.5.5-3.5 1-7.1 1.4-10.6-.7 4.5-1.8 8.8-3.5 12.9 1.3-3.2 2.7-6.4 4-9.6-1.8 4-4 7.8-6.6 11.3l6.3-8.1c-2.7 3.4-5.8 6.5-9.2 9.2l8.1-6.3c-3.5 2.7-7.3 4.9-11.3 6.6 3.2-1.3 6.4-2.7 9.6-4-4.2 1.7-8.5 2.9-12.9 3.5 3.5-.5 7.1-1 10.6-1.4-2.1.4-4.4.5-6.8.5-10.3.1-20.9 4.4-28.3 11.7-6.9 6.9-12.2 18.3-11.7 28.3 1 21.4 17.6 40.3 40 40 17.5-.2 36-5 50.3-15.3 17.4-12.5 29.4-28.1 36.5-48.3 3.4-9.9 4.3-20.6 4.3-31V313c-.2-16.5-4.5-33.7-13.5-47.7-4.7-7.3-10.3-14.6-17.1-20.3-9.8-8.1-18.2-13-30-17.7-13.8-5.6-28.5-5.4-43-5.4H120.1c-2.3 0-4.7 0-7 .1-39.4 2.1-73.7 27.8-84.8 66.1-4.2 14.4-3.4 29.4-3.4 44.2V840c0 8.2-.1 16.4 0 24.6.2 17.2 4.9 35.6 14.9 49.8 12.2 17.4 27.6 29.1 47.5 36.5 9.8 3.7 20.6 4.7 31 4.7H907.9c20.9 0 41-18.4 40-40-.9-21.4-17.5-39.8-40-39.8z" fill="currentColor"></path>
                            </svg>
                        </i>
                        <span class="item-name">Sub Menu</span>
                        <i class="right-icon">
                            <svg class="icon-18" xmlns="http://www.w3.org/2000/svg" width="18" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </i>
                    </a>
                    <ul class="sub-nav collapse" id="sub-menu" data-bs-parent="#sidebar-menu">
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('vendor.restaurant-sub-menu.index')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> S </i>
                                <span class="item-name">  List</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('vendor.restaurant-sub-menu.create')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> S </i>
                                <span class="item-name"> Create </span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{route('vendor.category.list')}}">
                        <i class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024" width="20">
                                <path d="M907.9 875.8H116.7c-2.6 0-5.2-.1-7.8-.5 3.5.5 7.1 1 10.6 1.4-4.5-.7-8.8-1.8-12.9-3.5 3.2 1.3 6.4 2.7 9.6 4-4-1.8-7.8-4-11.3-6.6l8.1 6.3c-3.4-2.7-6.5-5.8-9.2-9.2l6.3 8.1c-2.7-3.5-4.9-7.3-6.6-11.3 1.3 3.2 2.7 6.4 4 9.6-1.7-4.2-2.9-8.5-3.5-12.9.5 3.5 1 7.1 1.4 10.6-1-8-.5-16.4-.5-24.4V160.3c0-2.6.2-5.1.5-7.7-.5 3.5-1 7.1-1.4 10.6.7-4.5 1.8-8.8 3.5-12.9-1.3 3.2-2.7 6.4-4 9.6 1.8-4 4-7.8 6.6-11.3l-6.3 8.1c2.7-3.4 5.8-6.5 9.2-9.2l-8.1 6.3c3.5-2.7 7.3-4.9 11.3-6.6-3.2 1.3-6.4 2.7-9.6 4 4.2-1.7 8.5-2.9 12.9-3.5-3.5.5-7.1 1-10.6 1.4 9.9-1.2 20.2-.5 30.1-.5h200.1c11.5 0 23.6-1 35 .3-3.5-.5-7.1-1-10.6-1.4 3 .5 5.8 1.2 8.6 2.3-3.2-1.3-6.4-2.7-9.6-4 2.7 1.2 5.2 2.7 7.6 4.4l-8.1-6.3c2.4 1.9 4.4 4 6.3 6.4l-6.3-8.1c25.4 33.7 50.9 67.4 76.3 101.1 9.9 13.1 19.7 26.1 29.6 39.2.1.1.1.2.2.2 2.1 2.7-11.1-9.2 6.3 8.1 7.6 7.6 17.6 11.5 28.3 11.7H815.5c30.5 0 60.9-.1 91.4 0 2.6 0 5.2.2 7.8.5-3.5-.5-7.1-1-10.6-1.4 4.5.7 8.8 1.8 12.9 3.5-3.2-1.3-6.4-2.7-9.6-4 4 1.8 7.8 4 11.3 6.6l-8.1-6.3c3.4 2.7 6.5 5.8 9.2 9.2l-6.3-8.1c2.7 3.5 4.9 7.3 6.6 11.3-1.3-3.2-2.7-6.4-4-9.6 1.7 4.2 2.9 8.5 3.5 12.9-.5-3.5-1-7.1-1.4-10.6.8 6.7.5 13.6.5 20.4V799c0 21.9.2 43.8 0 65.7 0 2.5-.2 5-.5 7.5.5-3.5 1-7.1 1.4-10.6-.7 4.5-1.8 8.8-3.5 12.9 1.3-3.2 2.7-6.4 4-9.6-1.8 4-4 7.8-6.6 11.3l6.3-8.1c-2.7 3.4-5.8 6.5-9.2 9.2l8.1-6.3c-3.5 2.7-7.3 4.9-11.3 6.6 3.2-1.3 6.4-2.7 9.6-4-4.2 1.7-8.5 2.9-12.9 3.5 3.5-.5 7.1-1 10.6-1.4-2.1 0-4.4.1-6.8.1-10.3.1-20.9 4.4-28.3 11.7-6.9 6.9-12.2 18.3-11.7 28.3 1 21.4 17.6 40.3 40 40 28.4-.4 54.5-13.2 71.8-35.6 12.5-16.1 19-35.3 19.2-55.7.1-5.6 0-11.1 0-16.7V348.8c0-28.7 2.8-59.3-14.2-84.5-8.2-12.2-18.2-22.4-30.9-29.9-13-7.6-26.9-11.1-41.8-12.3-2.7-.2-5.4-.2-8.1-.2H502.7l34.5 19.8c-21-27.8-41.9-55.5-62.9-83.3-12.1-16-24.1-32-36.2-47.9-2.3-3.1-4.6-6.1-6.9-9.2-3.1-4.1-6.4-9-10.3-12.6-7.2-6.6-14-10.9-22.8-14.6-9.3-3.9-18.5-5.9-28.8-6.1-31.1-.5-62.3 0-93.4 0H141.5c-8.5 0-17.1-.1-25.6 0-25.2.3-50.9 10.8-67.5 30-14.9 17.3-23.3 38.1-23.6 61.1-.1 13.8 0 27.7 0 41.6V822.2c0 14.1-.2 28.2 0 42.3.1 7.5 1.1 15.1 2.8 22.4 3.3 14.2 10.6 26.1 19.6 37.2 16 20 42.1 30.8 67.3 31.6 3.4.1 6.8 0 10.2 0h783c20.9 0 41-18.4 40-40-.7-21.5-17.3-39.9-39.8-39.9z" fill="currentColor"></path>
                                <path d="M907.9 875.8H116.7c-2.6 0-5.2-.1-7.8-.5 3.5.5 7.1 1 10.6 1.4-4.5-.7-8.8-1.8-12.9-3.5 3.2 1.3 6.4 2.7 9.6 4-4-1.8-7.8-4-11.3-6.6l8.1 6.3c-3.4-2.7-6.5-5.8-9.2-9.2l6.3 8.1c-2.7-3.5-4.9-7.3-6.6-11.3 1.3 3.2 2.7 6.4 4 9.6-1.7-4.2-2.9-8.5-3.5-12.9.5 3.5 1 7.1 1.4 10.6-.8-6.7-.5-13.6-.5-20.4V379.3c0-21.9-.2-43.8 0-65.7 0-2.5.2-5 .5-7.5-.5 3.5-1 7.1-1.4 10.6.7-4.5 1.8-8.8 3.5-12.9-1.3 3.2-2.7 6.4-4 9.6 1.8-4 4-7.8 6.6-11.3l-6.3 8.1c2.7-3.4 5.8-6.5 9.2-9.2l-8.1 6.3c3.5-2.7 7.3-4.9 11.3-6.6-3.2 1.3-6.4 2.7-9.6 4 4.2-1.7 8.5-2.9 12.9-3.5-3.5.5-7.1 1-10.6 1.4 8.8-1.1 17.9-.5 26.7-.5H906.9c2.6 0 5.2.1 7.8.5-3.5-.5-7.1-1-10.6-1.4 4.5.7 8.8 1.8 12.9 3.5-3.2-1.3-6.4-2.7-9.6-4 4 1.8 7.8 4 11.3 6.6l-8.1-6.3c3.4 2.7 6.5 5.8 9.2 9.2l-6.3-8.1c2.7 3.5 4.9 7.3 6.6 11.3-1.3-3.2-2.7-6.4-4-9.6 1.7 4.2 2.9 8.5 3.5 12.9-.5-3.5-1-7.1-1.4-10.6.8 6.7.5 13.6.5 20.4v472.1c0 21.9.2 43.8 0 65.7 0 2.5-.2 5-.5 7.5.5-3.5 1-7.1 1.4-10.6-.7 4.5-1.8 8.8-3.5 12.9 1.3-3.2 2.7-6.4 4-9.6-1.8 4-4 7.8-6.6 11.3l6.3-8.1c-2.7 3.4-5.8 6.5-9.2 9.2l8.1-6.3c-3.5 2.7-7.3 4.9-11.3 6.6 3.2-1.3 6.4-2.7 9.6-4-4.2 1.7-8.5 2.9-12.9 3.5 3.5-.5 7.1-1 10.6-1.4-2.1.4-4.4.5-6.8.5-10.3.1-20.9 4.4-28.3 11.7-6.9 6.9-12.2 18.3-11.7 28.3 1 21.4 17.6 40.3 40 40 17.5-.2 36-5 50.3-15.3 17.4-12.5 29.4-28.1 36.5-48.3 3.4-9.9 4.3-20.6 4.3-31V313c-.2-16.5-4.5-33.7-13.5-47.7-4.7-7.3-10.3-14.6-17.1-20.3-9.8-8.1-18.2-13-30-17.7-13.8-5.6-28.5-5.4-43-5.4H120.1c-2.3 0-4.7 0-7 .1-39.4 2.1-73.7 27.8-84.8 66.1-4.2 14.4-3.4 29.4-3.4 44.2V840c0 8.2-.1 16.4 0 24.6.2 17.2 4.9 35.6 14.9 49.8 12.2 17.4 27.6 29.1 47.5 36.5 9.8 3.7 20.6 4.7 31 4.7H907.9c20.9 0 41-18.4 40-40-.9-21.4-17.5-39.8-40-39.8z" fill="currentColor"></path>
                            </svg>
                        </i>
                        <span class="item-name">Category<span
                                class="badge rounded-pill bg-success item-name"></span></span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{route('vendor.addon.add')}}">
                        <i class="icon">
                            <svg class="icon-20" xmlns="http://www.w3.org/2000/svg" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="m 7.015625 0 c -1.109375 0 -2 0.890625 -2 2 v 1 h -3.015625 c -1.109375 0 -2 0.890625 -2 2 v 2 h 1.015625 c 1.105469 0 2 0.890625 2 2 s -0.894531 2 -2 2 h -1.015625 v 2.988281 c 0 1.105469 0.890625 2 2 2 h 3.015625 v -0.988281 c 0 -1.109375 0.890625 -2 2 -2 c 1.105469 0 2 0.890625 2 2 v 0.988281 h 2 c 1.105469 0 2 -0.894531 2 -2 v -2.988281 h 1 c 1.105469 0 2 -0.890625 2 -2 s -0.894531 -2 -2 -2 h -1 v -2 c 0 -1.109375 -0.894531 -2 -2 -2 h -2 v -1 c 0 -1.109375 -0.894531 -2 -2 -2 z m 0 0" fill="currentColor"></path> </g></svg>
                        </i>
                        <span class="item-name">Addons<span
                                class="badge rounded-pill bg-success item-name"></span></span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#food" role="button"
                        aria-expanded="false" aria-controls="food">
                        <i class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" class="icon-20" enable-background="new 0 0 64 64" viewBox="0 0 64 64">
                                <path
                                    d="M49.101 49h-1.424l-3.2-8H60.5c1.93 0 3.5-1.57 3.5-3.5S62.43 34 60.5 34h-57C1.57 34 0 35.57 0 37.5S1.57 41 3.5 41h5.17c-.146.379-.221.785-.21 1.206.021.865.387 1.661 1.023 2.236l17.693 16.241C28.101 61.532 29.302 62 30.558 62H49v2h2V50c0-1.654 1.346-3 3-3h10v-2H54C51.586 45 49.566 46.721 49.101 49zM2 37.5C2 36.673 2.673 36 3.5 36h57c.827 0 1.5.673 1.5 1.5S61.327 39 60.5 39H43h-4H3.5C2.673 39 2 38.327 2 37.5zM21.728 47.079l-1.635-4.473c-.133-.311-.124-.648.026-.951.15-.303.414-.515.741-.597.492-.121 1.01.099 1.25.519l3.905 7.185-.012.16.104.008 2.25 4.14L21.728 47.079zM30.311 41.318c.195.205.294.474.281.756L30.094 52.08l-2.039-3.752.494-6.383C28.59 41.415 29.038 41 29.569 41 29.853 41 30.116 41.113 30.311 41.318zM26.555 41.791l-.256 3.306L24.072 41h2.667C26.647 41.251 26.576 41.513 26.555 41.791zM32.414 41H38v8.382l-5.916 2.958.505-10.166C32.609 41.767 32.547 41.37 32.414 41zM18.241 41c-.308.769-.318 1.621-.007 2.343l.315.863L15.003 41H18.241zM30.558 60c-.753 0-1.474-.28-2.029-.79L10.83 42.963c-.231-.209-.363-.496-.371-.808-.008-.312.109-.604.33-.825.423-.421 1.101-.44 1.541-.051l7.69 6.951.041.113.06-.022 8.209 7.421c.306.276.75.338 1.118.152l10-5C39.786 50.725 40 50.379 40 50v-9h2.323l3.749 9.371C46.224 50.751 46.591 51 47 51h2v9H30.558zM3 32h58c.552 0 1-.448 1-1C62 16.112 49.888 4 35 4h-2V2h4V0H27v2h4v2h-2C14.112 4 2 16.112 2 31 2 31.552 2.448 32 3 32zM29 6h6c13.45 0 24.454 10.677 24.98 24H4.02C4.546 16.677 15.55 6 29 6z"
                                    fill="currentColor" ></path>
                                <path
                                    d="M32.95,12.464l-0.707,0.707l-0.707-0.707c-1.949-1.95-5.122-1.95-7.071,0c-1.949,1.949-1.949,5.122,0,7.071l7.071,7.071  c0.195,0.195,0.451,0.293,0.707,0.293s0.512-0.098,0.707-0.293l7.071-7.071c0.945-0.944,1.465-2.2,1.465-3.536  c0-1.336-0.52-2.591-1.465-3.536C38.071,10.515,34.899,10.516,32.95,12.464z M38.606,18.122l-6.364,6.364l-6.364-6.364  c-1.169-1.17-1.169-3.073,0-4.243c0.585-0.585,1.353-0.877,2.122-0.877c0.768,0,1.537,0.292,2.122,0.877l1.414,1.415  c0.375,0.375,1.039,0.375,1.414,0l1.414-1.415c1.169-1.17,3.072-1.171,4.243,0c0.567,0.567,0.879,1.32,0.879,2.122  S39.173,17.555,38.606,18.122z"
                                    fill="currentColor" ></path>
                                <rect width="2" height="2" x="55" y="26" fill="currentColor" ></rect>
                                <rect width="2" height="2" x="51" y="26" fill="currentColor" ></rect>
                                <rect width="2" height="2" x="47" y="26" fill="currentColor" ></rect>
                            </svg>
                        </i>
                        <span class="item-name">Food</span>
                        <i class="right-icon">
                            <svg class="icon-18" xmlns="http://www.w3.org/2000/svg" width="18" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </i>
                    </a>
                    <ul class="sub-nav collapse" id="food" data-bs-parent="#sidebar-menu">
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('vendor.service.add.food')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> H </i>
                                <span class="item-name"> Food Add Request </span>
                            </a>
                        </li>
                        @php($isAdmin = auth('admin')->check())
                        @if ($isAdmin)
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('vendor.food.add')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> H </i>
                                <span class="item-name"> Add </span>
                            </a>
                        </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('vendor.food.list')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> H </i>
                                <span class="item-name"> List </span>
                            </a>
                        </li>
                        @if ($isAdmin)
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('vendor.food.bulk-import')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> L </i>
                                <span class="item-name"> Bulk Import </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('vendor.food.food-export')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> L </i>
                                <span class="item-name"> Bulk Export </span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @if(false)
                <li>
                    <hr class="hr-horizontal">
                </li>
                <li class="nav-item static-item">
                    <a class="nav-link static-item disabled" href="javascript:void(0)" tabindex="-1">
                        <span class="default-icon">Delivery Management</span>
                        <span class="mini-icon">-</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#dlvh-man" role="button"
                        aria-expanded="false" aria-controls="dlvh-man">
                        <i class="icon">
                            <svg fill="currentColor" width="20" class="icon-20"version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 449.505 449.505" xml:space="preserve" stroke="#784545"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <path d="M306.079,223.021c-0.632-7.999-7.672-14.605-15.694-14.728l-53.093-0.814c-3.084-0.047-6.21-2.762-6.689-5.809 l-11.698-74.37c-0.424-2.694-2.936-13.678-16.649-13.678l-66.024,2.875c-8.698,0.378-15.769,4.607-15.769,16.476 c0,0-0.278,165.299-0.616,171.289l-2.31,40.898c-0.309,5.462-2.437,14.303-4.647,19.306l-26.724,60.487 c-1.764,3.991-1.735,8.403,0.08,12.105s5.284,6.428,9.52,7.48l8.897,2.208c1.324,0.329,2.71,0.495,4.118,0.495 c7.182,0,14.052-4.168,17.096-10.372l25.403-51.78c2.806-5.719,6.298-15.412,7.786-21.607l14.334-59.711l34.689,53.049 c2.86,4.374,5.884,12.767,6.471,17.961l6.706,59.392c0.954,8.454,8.654,15.332,17.164,15.332l10.146-0.035 c4.353-0.015,8.311-1.752,11.145-4.893c2.833-3.14,4.158-7.254,3.728-11.585l-7.004-70.612c-0.646-6.512-2.985-16.401-5.325-22.513 l-31.083-81.187l-0.192-17.115l72.241-2.674c4.033-0.149,7.718-1.876,10.376-4.862c2.658-2.985,3.947-6.845,3.629-10.873 L306.079,223.021z M238.43,444.503L238.43,444.503v0.002V444.503z"></path> <path d="M157.338,97.927c5.558,0,11.054-0.948,16.335-2.819c12.327-4.362,22.216-13.264,27.846-25.066 c3.981-8.345,5.483-17.433,4.486-26.398l16.406-1.851c5.717-0.645,11.52-5.205,13.498-10.607l5.495-15.007 c1.173-3.206,0.864-6.45-0.849-8.902c-1.67-2.39-4.484-3.761-7.72-3.761c-0.375,0-0.763,0.018-1.161,0.056l-47.438,4.512 C176.416,2.933,167.116,0,157.333,0c-5.556,0-11.05,0.947-16.333,2.816c-12.326,4.365-22.215,13.268-27.846,25.07 s-6.328,25.089-1.963,37.413C118.102,84.815,136.647,97.927,157.338,97.927z"></path> <path d="M364.605,174.546l-4.72-67.843c-0.561-8.057-7.587-14.611-15.691-14.611l-90.689,0.158 c-4.06,0.007-7.792,1.618-10.509,4.536c-2.716,2.917-4.058,6.754-3.775,10.805l4.72,67.843c0.561,8.057,7.587,14.611,15.664,14.611 l90.716-0.158c4.06-0.007,7.792-1.617,10.509-4.535C363.546,182.434,364.887,178.596,364.605,174.546z M259.604,185.044 L259.604,185.044L259.604,185.044L259.604,185.044z"></path> </g> </g>
                            </svg>
                        </i>
                        <span class="item-name">Delhivery Man </span>
                        <i class="right-icon">
                            <svg class="icon-18" xmlns="http://www.w3.org/2000/svg" width="18" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </i>
                    </a>
                    <ul class="sub-nav collapse" id="dlvh-man" data-bs-parent="#sidebar-menu">
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('vendor.delivery-man.add')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> A </i>
                                <span class="item-name"> Add </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('vendor.delivery-man.list')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> L </i>
                                <span class="item-name"> List </span>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                <li>
                    <hr class="hr-horizontal">
                </li>
                <li class="nav-item static-item">
                    <a class="nav-link static-item disabled" href="javascript:void(0)" tabindex="-1">
                        <span class="default-icon">Employee Management</span>
                        <span class="mini-icon">-</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{route('vendor.administration.roles.permissions')}}">
                        <i class="icon">
                            <svg fill="currentColor" width="20px" viewBox="0 0 1920 1920"
                                xmlns="http://www.w3.org/2000/svg" stroke="currentColor">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <path d="M1600 1066.667c117.653 0 213.333 95.68 213.333 213.333v106.667H1920V1760c0 88.213-71.787 160-160 160h-320c-88.213 0-160-71.787-160-160v-373.333h106.667V1280c0-117.653 95.68-213.333 213.333-213.333ZM800 0c90.667 0 179.2 25.6 254.933 73.6 29.867 18.133 58.667 40.533 84.267 66.133 49.067 49.067 84.8 106.88 108.053 169.814 11.307 30.4 20.8 61.44 25.814 94.08l2.24 14.613 3.626 20.16-.533.32v.213l-52.693 32.427c-44.694 28.907-95.467 61.547-193.067 61.867-.427 0-.747.106-1.173.106-24.534 0-46.08-2.133-65.28-5.653-.64-.107-1.067-.32-1.707-.427-56.32-10.773-93.013-34.24-126.293-55.68-9.6-6.293-18.774-12.16-28.16-17.6-27.947-16-57.92-27.306-108.16-27.306h-.32c-57.814.106-88.747 15.893-121.387 36.266-4.48 2.88-8.853 5.44-13.44 8.427-3.093 2.027-6.72 4.16-9.92 6.187-6.293 4.053-12.693 8.106-19.627 12.16-4.48 2.666-9.493 5.013-14.293 7.573-6.933 3.627-13.973 7.147-21.76 10.453-6.613 2.987-13.76 5.547-21.12 8.107-6.933 2.347-14.507 4.267-22.187 6.293-8.96 2.347-17.813 4.587-27.84 6.187-1.173.213-2.133.533-3.306.747v57.6c0 17.066 1.066 34.133 4.266 50.133C454.4 819.2 611.2 960 800 960c195.2 0 356.267-151.467 371.2-342.4 48-14.933 82.133-37.333 108.8-54.4v23.467c0 165.546-84.373 311.786-212.373 398.08h4.906a1641.19 1641.19 0 0 1 294.08 77.76C1313.28 1119.68 1280 1195.733 1280 1280h-106.667v480c0 1.387.427 2.667.427 4.16-142.933 37.547-272.427 49.173-373.76 49.173-345.493 0-612.053-120.32-774.827-221.333L0 1576.32v-196.373c0-140.054 85.867-263.04 218.667-313.28 100.373-38.08 204.586-64.96 310.186-82.347h4.8C419.52 907.413 339.2 783.787 323.2 640c-2.133-17.067-3.2-35.2-3.2-53.333V480c0-56.533 9.6-109.867 27.733-160C413.867 133.333 592 0 800 0Zm800 1173.333c-58.773 0-106.667 47.894-106.667 106.667v106.667h213.334V1280c0-58.773-47.894-106.667-106.667-106.667Z" fill-rule="evenodd"></path>
                                </g>
                            </svg>
                        </i>
                        <span class="item-name">Permission<span
                                class="badge rounded-pill bg-success item-name"></span></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{route('vendor.roles.add')}}">
                        <i class="icon">
                            <svg class="icon-20" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 31.192 31.192" xml:space="preserve" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <path style="fill:currentColor;" d="M10.898,10.787c0.314,2.613,2.564,5.318,4.604,5.318c2.341,0,4.571-2.845,4.919-5.319 c0.129-0.095,0.348-0.322,0.428-0.838c0,0,0.502-1.792-0.164-1.6c0.232-0.69,1-3.379-0.485-5.052c0,0-0.697-0.947-2.386-1.452 c-0.059-0.051-0.12-0.1-0.189-0.148c0,0,0.037,0.043,0.094,0.12c-0.098-0.027-0.195-0.052-0.301-0.077 c-0.092-0.096-0.193-0.194-0.311-0.301c0,0,0.104,0.106,0.225,0.282c-0.044-0.01-0.086-0.02-0.134-0.03 c-0.076-0.118-0.17-0.237-0.288-0.358c0,0,0.049,0.091,0.117,0.24c-0.312-0.228-0.938-0.758-0.938-1.349 c0,0-0.394,0.184-0.623,0.517c0.092-0.276,0.242-0.531,0.488-0.74c0,0-0.26,0.133-0.496,0.419 c-0.184,0.102-0.604,0.391-0.747,0.903l-0.133-0.066c0.065-0.148,0.158-0.299,0.282-0.455c0,0-0.182,0.164-0.342,0.425 l-0.271-0.138c0.08-0.151,0.188-0.304,0.331-0.459c0,0-0.144,0.113-0.302,0.303c0.045-0.176,0.036-0.377-0.511,0.222 c0,0-2.466,1.071-3.183,3.288c0,0-0.422,1,0.137,3.944c-0.792-0.374-0.251,1.562-0.251,1.562 C10.55,10.466,10.767,10.691,10.898,10.787z M10.851,9.738c0,0.002,0,0.002,0,0.003C10.851,9.74,10.851,9.74,10.851,9.738z M15.384,0.517c-0.118,0.167-0.224,0.376-0.273,0.631l-0.088-0.035C15.091,0.898,15.204,0.694,15.384,0.517z"></path> <path style="fill:currentColor;" d="M25.876,19.226c-0.645-1.43-4.577-2.669-4.577-2.669c-2.095-0.738-2.109-1.476-2.109-1.476 c-4.121,8.125-7.253,0.022-7.253,0.022c-0.286,1.097-4.525,2.381-4.525,2.381c-1.24,0.478-1.765,1.192-1.765,1.192 c-1.834,2.719-2.049,8.769-2.049,8.769c0.024,1.383,0.618,1.525,0.618,1.525c4.218,1.882,10.831,2.215,10.831,2.215 c6.792,0.144,11.733-1.929,11.733-1.929c0.718-0.454,0.738-0.812,0.738-0.812C28.019,24.108,25.876,19.226,25.876,19.226z M17.976,26.946h-4.759V25.14h4.759V26.946z"></path> </g> </g></svg>
                        </i>
                        <span class="item-name">Employee Role<span
                                class="badge rounded-pill bg-success item-name"></span></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#employee" role="button"
                        aria-expanded="false" aria-controls="employee">
                        <i class="icon">
                            <svg class="icon-20" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 31.192 31.192" xml:space="preserve" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <path style="fill:currentColor;" d="M10.898,10.787c0.314,2.613,2.564,5.318,4.604,5.318c2.341,0,4.571-2.845,4.919-5.319 c0.129-0.095,0.348-0.322,0.428-0.838c0,0,0.502-1.792-0.164-1.6c0.232-0.69,1-3.379-0.485-5.052c0,0-0.697-0.947-2.386-1.452 c-0.059-0.051-0.12-0.1-0.189-0.148c0,0,0.037,0.043,0.094,0.12c-0.098-0.027-0.195-0.052-0.301-0.077 c-0.092-0.096-0.193-0.194-0.311-0.301c0,0,0.104,0.106,0.225,0.282c-0.044-0.01-0.086-0.02-0.134-0.03 c-0.076-0.118-0.17-0.237-0.288-0.358c0,0,0.049,0.091,0.117,0.24c-0.312-0.228-0.938-0.758-0.938-1.349 c0,0-0.394,0.184-0.623,0.517c0.092-0.276,0.242-0.531,0.488-0.74c0,0-0.26,0.133-0.496,0.419 c-0.184,0.102-0.604,0.391-0.747,0.903l-0.133-0.066c0.065-0.148,0.158-0.299,0.282-0.455c0,0-0.182,0.164-0.342,0.425 l-0.271-0.138c0.08-0.151,0.188-0.304,0.331-0.459c0,0-0.144,0.113-0.302,0.303c0.045-0.176,0.036-0.377-0.511,0.222 c0,0-2.466,1.071-3.183,3.288c0,0-0.422,1,0.137,3.944c-0.792-0.374-0.251,1.562-0.251,1.562 C10.55,10.466,10.767,10.691,10.898,10.787z M10.851,9.738c0,0.002,0,0.002,0,0.003C10.851,9.74,10.851,9.74,10.851,9.738z M15.384,0.517c-0.118,0.167-0.224,0.376-0.273,0.631l-0.088-0.035C15.091,0.898,15.204,0.694,15.384,0.517z"></path> <path style="fill:currentColor;" d="M25.876,19.226c-0.645-1.43-4.577-2.669-4.577-2.669c-2.095-0.738-2.109-1.476-2.109-1.476 c-4.121,8.125-7.253,0.022-7.253,0.022c-0.286,1.097-4.525,2.381-4.525,2.381c-1.24,0.478-1.765,1.192-1.765,1.192 c-1.834,2.719-2.049,8.769-2.049,8.769c0.024,1.383,0.618,1.525,0.618,1.525c4.218,1.882,10.831,2.215,10.831,2.215 c6.792,0.144,11.733-1.929,11.733-1.929c0.718-0.454,0.738-0.812,0.738-0.812C28.019,24.108,25.876,19.226,25.876,19.226z M17.976,26.946h-4.759V25.14h4.759V26.946z"></path> </g> </g></svg>
                        </i>
                        <span class="item-name">Employee  </span>
                        <i class="right-icon">
                            <svg class="icon-18" xmlns="http://www.w3.org/2000/svg" width="18" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </i>
                    </a>
                    <ul class="sub-nav collapse" id="employee" data-bs-parent="#sidebar-menu">
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('vendor.employee.add-new')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> A </i>
                                <span class="item-name"> Add </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('vendor.employee.list')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor">
                                            </circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> L </i>
                                <span class="item-name"> List </span>
                            </a>
                        </li>
                    </ul>
                </li>




                {{-- <li>
                    <hr class="hr-horizontal">
                </li>
                <li class="nav-item static-item">
                    <a class="nav-link static-item disabled" href="javascript:void(0)" tabindex="-1">
                        <span class="default-icon">Report Management</span>
                        <span class="mini-icon">-</span>
                    </a>
                </li> --}}


                <li>
                    <hr class="hr-horizontal">
                </li>
                <li class="nav-item static-item">
                    <a class="nav-link static-item disabled" href="javascript:void(0)" tabindex="-1">
                        <span class="default-icon">Payments </span>
                        <span class="mini-icon">-</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{route('vendor.wallet.index')}}">
                        <i class="icon">
                            <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M21.9964 8.37513H17.7618C15.7911 8.37859 14.1947 9.93514 14.1911 11.8566C14.1884 13.7823 15.7867 15.3458 17.7618 15.3484H22V15.6543C22 19.0136 19.9636 21 16.5173 21H7.48356C4.03644 21 2 19.0136 2 15.6543V8.33786C2 4.97862 4.03644 3 7.48356 3H16.5138C19.96 3 21.9964 4.97862 21.9964 8.33786V8.37513ZM6.73956 8.36733H12.3796H12.3831H12.3902C12.8124 8.36559 13.1538 8.03019 13.152 7.61765C13.1502 7.20598 12.8053 6.87318 12.3831 6.87491H6.73956C6.32 6.87664 5.97956 7.20858 5.97778 7.61852C5.976 8.03019 6.31733 8.36559 6.73956 8.36733Z"
                                    fill="currentColor"></path>
                                <path opacity="0.4"
                                    d="M16.0374 12.2966C16.2465 13.2478 17.0805 13.917 18.0326 13.8996H21.2825C21.6787 13.8996 22 13.5715 22 13.166V10.6344C21.9991 10.2297 21.6787 9.90077 21.2825 9.8999H17.9561C16.8731 9.90338 15.9983 10.8024 16 11.9102C16 12.0398 16.0128 12.1695 16.0374 12.2966Z"
                                    fill="currentColor"></path>
                                <circle cx="18" cy="11.8999" r="1" fill="currentColor"></circle>
                            </svg>

                        </i>
                        <span class="item-name">My Wallet<span
                                class="badge rounded-pill bg-success item-name"></span></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{route('vendor.banking.add-bank-details')}}">
                        <i class="icon">
                            <svg fill="currentColor"  width="20px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 483.64 483.64" xml:space="preserve" stroke="#7d5454"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <g> <path d="M312.4,238.886l-29.9-5.1c-2.9-11.5-7.4-22.4-13.3-32.4l17.7-24.7c3.3-4.6,2.8-10.8-1.2-14.8l-10.5-10.6l-10.5-10.6 c-2.2-2.2-5.2-3.4-8.1-3.4c-2.3,0-4.6,0.7-6.6,2.1l-24.9,17.4c-10.1-6.1-21.2-10.8-33.1-13.7l-4.9-29.7c-0.9-5.6-5.7-9.6-11.3-9.7 l-15-0.1l-15-0.1l0,0c-5.5,0-10.4,4-11.4,9.6l-5.3,30.2c-11.5,2.9-22.4,7.6-32.3,13.4l-24.3-17.8c-2-1.4-4.4-2.2-6.7-2.2 c-2.9,0-5.9,1.1-8.1,3.3l-10.6,10.5l-10.6,10.5c-3.9,3.9-4.5,10.1-1.3,14.8l17.7,25c-5.9,10-10.4,20.8-13.3,32.3l-29.7,4.9 c-5.6,0.9-9.6,5.7-9.7,11.3l-0.1,15l-0.1,15c0,5.6,4,10.5,9.6,11.4l30.2,5.3c2.9,11.5,7.6,22.4,13.4,32.3l-17.6,24.5 c-3.3,4.6-2.8,10.8,1.2,14.8l10.5,10.6l10.5,10.9c2.2,2.2,5.2,3.4,8.1,3.4c2.3,0,4.6-0.7,6.6-2.1l25-17.7 c9.7,5.7,20.4,10.2,31.5,13.1l4.9,30c0.9,5.6,5.7,9.6,11.3,9.7l15,0.1l15,0.1l0,0c5.5,0,10.4-4,11.4-9.6l5.1-29.9 c11.5-2.9,22.4-7.4,32.4-13.3l24.7,17.7c2,1.4,4.4,2.2,6.7,2.2c2.9,0,5.9-1.1,8.1-3.3l10.6-10.5l10.6-10.5 c3.9-3.9,4.5-10.1,1.3-14.8l-17.5-24.9c6-10,10.5-20.8,13.5-32.3l30-4.9c5.6-0.9,9.6-5.7,9.7-11.3l0.1-15l0.1-15 C321.9,244.586,317.9,239.886,312.4,238.886z M182.7,359.186c-7.3,1.7-14.6,2.5-21.8,2.5c-44.2,0-84.2-30.4-94.6-75.4 c-12-52.3,20.6-104.4,72.9-116.4c7.3-1.7,14.6-2.5,21.8-2.5c44.2,0,84.2,30.4,94.6,75.4 C267.6,295.086,234.9,347.186,182.7,359.186z"></path> <path d="M364,229.486l8,1l0,0c2.9,0.3,5.8-1.5,6.6-4.4l4.6-15.5c6.3-0.8,12.3-2.5,18-5l12,10.9c1,0.9,2.2,1.4,3.4,1.6 c1.6,0.2,3.2-0.2,4.5-1.2l6.3-4.9l6.3-4.9c2.3-1.8,3-5.1,1.6-7.7l-7.7-14.3c3.8-4.9,6.9-10.4,9.2-16.3l16.2-0.7 c3-0.1,5.5-2.4,5.8-5.4l1-8l1-8c0.2-2.9-1.6-5.7-4.4-6.5l-15.5-4.6c-0.8-6.3-2.5-12.3-5-18l10.9-12c2-2.2,2.1-5.6,0.3-7.9 l-4.9-6.3l-4.9-6.3c-1-1.3-2.5-2.1-4.1-2.3c-1.2-0.1-2.5,0.1-3.6,0.7l-14.3,7.7c-5-3.9-10.6-7-16.7-9.3l-0.8-16.1 c-0.1-3-2.4-5.5-5.4-5.8l-8-1l-8-1l0,0c-2.9-0.3-5.8,1.5-6.6,4.4l-4.7,15.7c-6.3,0.8-12.3,2.6-18,5.1l-11.8-11 c-1-0.9-2.2-1.4-3.4-1.6c-1.6-0.2-3.2,0.2-4.5,1.2l-6.3,4.9l-6.3,4.9c-2.3,1.8-3,5.1-1.6,7.7l7.8,14.4c-3.7,4.9-6.8,10.4-9.1,16.3 l-16,0.7c-3,0.1-5.5,2.4-5.8,5.4l-1,8l-1,8c-0.4,2.9,1.5,5.8,4.4,6.7l15.7,4.7c0.8,6.3,2.6,12.3,5.1,18l-10.8,11.9 c-2,2.2-2.1,5.6-0.3,7.9l4.9,6.3l4.9,6.3c1,1.3,2.5,2.1,4.1,2.3c1.2,0.1,2.5-0.1,3.6-0.7l14.4-7.8c4.8,3.6,10.2,6.7,15.9,8.9 l0.7,16.2c0.1,3,2.4,5.5,5.4,5.8L364,229.486z M339.4,142.886c0.7-19.4,17-34.5,36.4-33.8s34.5,17,33.8,36.4 c-0.7,19.4-17,34.5-36.4,33.8C353.8,178.586,338.7,162.286,339.4,142.886z"></path> <path d="M483.6,293.386l-0.7-6l-0.7-6c-0.4-2.1-2.2-3.8-4.4-4l-12.1-0.6c-1.7-4.4-4-8.5-6.8-12.2l5.8-10.6c1.1-2,0.6-4.4-1.2-5.8 l-4.7-3.7l-4.7-3.7c-1-0.8-2.2-1.1-3.4-1c-0.9,0.1-1.8,0.5-2.5,1.1l-9,8.1c-4.3-2-8.9-3.3-13.7-3.9l-3.3-11.5 c-0.6-2.2-2.7-3.5-4.9-3.3l-6,0.7l-6,0.7l0,0c-2.2,0.3-3.9,2.1-4,4.3l-0.7,12.2c-4.4,1.7-8.5,4.1-12.1,6.8l-10.5-5.9 c-0.9-0.5-1.8-0.6-2.8-0.5c-1.2,0.1-2.3,0.7-3,1.7l-3.7,4.7l-3.7,4.7c-1.4,1.7-1.3,4.2,0.2,5.9l8.2,9.1c-1.9,4.2-3.1,8.7-3.7,13.4 l-11.5,3.3c-2.2,0.6-3.5,2.7-3.3,4.9l0.7,6l0.7,6c0.3,2.2,2.1,3.9,4.3,4.1l12.2,0.7c1.7,4.4,4.1,8.5,6.8,12.1l-5.8,10.5 c-1.1,2-0.6,4.4,1.2,5.8l4.7,3.7l4.7,3.7c1,0.8,2.2,1.1,3.4,1c0.9-0.1,1.8-0.5,2.5-1.1l9.1-8.2c4.1,1.8,8.6,3.1,13.1,3.7l3.3,11.6 c0.6,2.2,2.7,3.5,4.9,3.3l6-0.7l6-0.7l0,0c2.2-0.3,3.9-2.1,4-4.3l0.6-12.1c4.4-1.7,8.5-4,12.2-6.8l10.6,5.8 c0.9,0.5,1.8,0.6,2.8,0.5c1.2-0.1,2.3-0.7,3-1.7l3.7-4.7l3.7-4.7c1.4-1.7,1.3-4.2-0.2-5.9l-8.1-9c1.9-4.2,3.2-8.7,3.8-13.4 l11.6-3.3C482.5,297.686,483.9,295.586,483.6,293.386z M424.4,320.486c-14.2,2.9-28-6.3-30.9-20.5s6.3-28,20.5-30.9 s28,6.3,30.9,20.5S438.6,317.586,424.4,320.486z"></path> <path d="M216.7,294.486c-17.3-4.4-31.3-14.3-31.3-14.3l-11,34.7l-2.1,6.5v-0.1l-1.8,5.5l-5.8-16.4c14.6-20.4-3.9-19.6-3.9-19.6 s-18.5-0.8-3.9,19.6l-5.8,16.5l-1.8-5.6l-13-41.2c0,0-14.1,9.9-31.3,14.3c-8.6,2.2-11.7,9.6-12.7,16.4 c15.5,21.3,40.5,34.7,68.4,34.7c6.4,0,12.8-0.7,19.1-2.2c20.2-4.6,37.7-16.3,49.7-32.9C228.3,303.786,225.1,296.686,216.7,294.486 z"></path> <path d="M131.8,257.286c1.5,9.7,8.9,22,21.2,26.3c5,1.8,10.5,1.8,15.6,0c12.1-4.3,19.8-16.6,21.3-26.2c1.6-0.1,3.8-2.4,6.1-10.5 c3.1-11.1-0.2-12.8-3-12.5c0.5-1.5,0.9-3.1,1.2-4.6c4.8-28.8-9.4-29.8-9.4-29.8s-2.3-4.5-8.5-7.9c-4.1-2.5-9.9-4.4-17.5-3.7 c-2.5,0.1-4.8,0.6-7,1.3l0,0c-2.8,0.9-5.4,2.3-7.7,3.9c-2.8,1.8-5.5,4-7.9,6.5c-3.8,3.8-7.1,8.8-8.5,15c-1.2,4.6-0.9,9.5,0.1,14.6 l0,0c0.3,1.5,0.7,3,1.2,4.6c-2.8-0.3-6.2,1.4-3,12.5C128,254.786,130.2,257.086,131.8,257.286z"></path> </g> </g> </g></svg>
                        </i>
                        <span class="item-name">Bank Details<span
                                class="badge rounded-pill bg-success item-name"></span></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{route('vendor.banking.add-payment-request')}}">
                        <i class="icon">
                            <svg fill="currentColor"  width="20px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 483.64 483.64" xml:space="preserve" stroke="#7d5454"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <g> <path d="M312.4,238.886l-29.9-5.1c-2.9-11.5-7.4-22.4-13.3-32.4l17.7-24.7c3.3-4.6,2.8-10.8-1.2-14.8l-10.5-10.6l-10.5-10.6 c-2.2-2.2-5.2-3.4-8.1-3.4c-2.3,0-4.6,0.7-6.6,2.1l-24.9,17.4c-10.1-6.1-21.2-10.8-33.1-13.7l-4.9-29.7c-0.9-5.6-5.7-9.6-11.3-9.7 l-15-0.1l-15-0.1l0,0c-5.5,0-10.4,4-11.4,9.6l-5.3,30.2c-11.5,2.9-22.4,7.6-32.3,13.4l-24.3-17.8c-2-1.4-4.4-2.2-6.7-2.2 c-2.9,0-5.9,1.1-8.1,3.3l-10.6,10.5l-10.6,10.5c-3.9,3.9-4.5,10.1-1.3,14.8l17.7,25c-5.9,10-10.4,20.8-13.3,32.3l-29.7,4.9 c-5.6,0.9-9.6,5.7-9.7,11.3l-0.1,15l-0.1,15c0,5.6,4,10.5,9.6,11.4l30.2,5.3c2.9,11.5,7.6,22.4,13.4,32.3l-17.6,24.5 c-3.3,4.6-2.8,10.8,1.2,14.8l10.5,10.6l10.5,10.9c2.2,2.2,5.2,3.4,8.1,3.4c2.3,0,4.6-0.7,6.6-2.1l25-17.7 c9.7,5.7,20.4,10.2,31.5,13.1l4.9,30c0.9,5.6,5.7,9.6,11.3,9.7l15,0.1l15,0.1l0,0c5.5,0,10.4-4,11.4-9.6l5.1-29.9 c11.5-2.9,22.4-7.4,32.4-13.3l24.7,17.7c2,1.4,4.4,2.2,6.7,2.2c2.9,0,5.9-1.1,8.1-3.3l10.6-10.5l10.6-10.5 c3.9-3.9,4.5-10.1,1.3-14.8l-17.5-24.9c6-10,10.5-20.8,13.5-32.3l30-4.9c5.6-0.9,9.6-5.7,9.7-11.3l0.1-15l0.1-15 C321.9,244.586,317.9,239.886,312.4,238.886z M182.7,359.186c-7.3,1.7-14.6,2.5-21.8,2.5c-44.2,0-84.2-30.4-94.6-75.4 c-12-52.3,20.6-104.4,72.9-116.4c7.3-1.7,14.6-2.5,21.8-2.5c44.2,0,84.2,30.4,94.6,75.4 C267.6,295.086,234.9,347.186,182.7,359.186z"></path> <path d="M364,229.486l8,1l0,0c2.9,0.3,5.8-1.5,6.6-4.4l4.6-15.5c6.3-0.8,12.3-2.5,18-5l12,10.9c1,0.9,2.2,1.4,3.4,1.6 c1.6,0.2,3.2-0.2,4.5-1.2l6.3-4.9l6.3-4.9c2.3-1.8,3-5.1,1.6-7.7l-7.7-14.3c3.8-4.9,6.9-10.4,9.2-16.3l16.2-0.7 c3-0.1,5.5-2.4,5.8-5.4l1-8l1-8c0.2-2.9-1.6-5.7-4.4-6.5l-15.5-4.6c-0.8-6.3-2.5-12.3-5-18l10.9-12c2-2.2,2.1-5.6,0.3-7.9 l-4.9-6.3l-4.9-6.3c-1-1.3-2.5-2.1-4.1-2.3c-1.2-0.1-2.5,0.1-3.6,0.7l-14.3,7.7c-5-3.9-10.6-7-16.7-9.3l-0.8-16.1 c-0.1-3-2.4-5.5-5.4-5.8l-8-1l-8-1l0,0c-2.9-0.3-5.8,1.5-6.6,4.4l-4.7,15.7c-6.3,0.8-12.3,2.6-18,5.1l-11.8-11 c-1-0.9-2.2-1.4-3.4-1.6c-1.6-0.2-3.2,0.2-4.5,1.2l-6.3,4.9l-6.3,4.9c-2.3,1.8-3,5.1-1.6,7.7l7.8,14.4c-3.7,4.9-6.8,10.4-9.1,16.3 l-16,0.7c-3,0.1-5.5,2.4-5.8,5.4l-1,8l-1,8c-0.4,2.9,1.5,5.8,4.4,6.7l15.7,4.7c0.8,6.3,2.6,12.3,5.1,18l-10.8,11.9 c-2,2.2-2.1,5.6-0.3,7.9l4.9,6.3l4.9,6.3c1,1.3,2.5,2.1,4.1,2.3c1.2,0.1,2.5-0.1,3.6-0.7l14.4-7.8c4.8,3.6,10.2,6.7,15.9,8.9 l0.7,16.2c0.1,3,2.4,5.5,5.4,5.8L364,229.486z M339.4,142.886c0.7-19.4,17-34.5,36.4-33.8s34.5,17,33.8,36.4 c-0.7,19.4-17,34.5-36.4,33.8C353.8,178.586,338.7,162.286,339.4,142.886z"></path> <path d="M483.6,293.386l-0.7-6l-0.7-6c-0.4-2.1-2.2-3.8-4.4-4l-12.1-0.6c-1.7-4.4-4-8.5-6.8-12.2l5.8-10.6c1.1-2,0.6-4.4-1.2-5.8 l-4.7-3.7l-4.7-3.7c-1-0.8-2.2-1.1-3.4-1c-0.9,0.1-1.8,0.5-2.5,1.1l-9,8.1c-4.3-2-8.9-3.3-13.7-3.9l-3.3-11.5 c-0.6-2.2-2.7-3.5-4.9-3.3l-6,0.7l-6,0.7l0,0c-2.2,0.3-3.9,2.1-4,4.3l-0.7,12.2c-4.4,1.7-8.5,4.1-12.1,6.8l-10.5-5.9 c-0.9-0.5-1.8-0.6-2.8-0.5c-1.2,0.1-2.3,0.7-3,1.7l-3.7,4.7l-3.7,4.7c-1.4,1.7-1.3,4.2,0.2,5.9l8.2,9.1c-1.9,4.2-3.1,8.7-3.7,13.4 l-11.5,3.3c-2.2,0.6-3.5,2.7-3.3,4.9l0.7,6l0.7,6c0.3,2.2,2.1,3.9,4.3,4.1l12.2,0.7c1.7,4.4,4.1,8.5,6.8,12.1l-5.8,10.5 c-1.1,2-0.6,4.4,1.2,5.8l4.7,3.7l4.7,3.7c1,0.8,2.2,1.1,3.4,1c0.9-0.1,1.8-0.5,2.5-1.1l9.1-8.2c4.1,1.8,8.6,3.1,13.1,3.7l3.3,11.6 c0.6,2.2,2.7,3.5,4.9,3.3l6-0.7l6-0.7l0,0c2.2-0.3,3.9-2.1,4-4.3l0.6-12.1c4.4-1.7,8.5-4,12.2-6.8l10.6,5.8 c0.9,0.5,1.8,0.6,2.8,0.5c1.2-0.1,2.3-0.7,3-1.7l3.7-4.7l3.7-4.7c1.4-1.7,1.3-4.2-0.2-5.9l-8.1-9c1.9-4.2,3.2-8.7,3.8-13.4 l11.6-3.3C482.5,297.686,483.9,295.586,483.6,293.386z M424.4,320.486c-14.2,2.9-28-6.3-30.9-20.5s6.3-28,20.5-30.9 s28,6.3,30.9,20.5S438.6,317.586,424.4,320.486z"></path> <path d="M216.7,294.486c-17.3-4.4-31.3-14.3-31.3-14.3l-11,34.7l-2.1,6.5v-0.1l-1.8,5.5l-5.8-16.4c14.6-20.4-3.9-19.6-3.9-19.6 s-18.5-0.8-3.9,19.6l-5.8,16.5l-1.8-5.6l-13-41.2c0,0-14.1,9.9-31.3,14.3c-8.6,2.2-11.7,9.6-12.7,16.4 c15.5,21.3,40.5,34.7,68.4,34.7c6.4,0,12.8-0.7,19.1-2.2c20.2-4.6,37.7-16.3,49.7-32.9C228.3,303.786,225.1,296.686,216.7,294.486 z"></path> <path d="M131.8,257.286c1.5,9.7,8.9,22,21.2,26.3c5,1.8,10.5,1.8,15.6,0c12.1-4.3,19.8-16.6,21.3-26.2c1.6-0.1,3.8-2.4,6.1-10.5 c3.1-11.1-0.2-12.8-3-12.5c0.5-1.5,0.9-3.1,1.2-4.6c4.8-28.8-9.4-29.8-9.4-29.8s-2.3-4.5-8.5-7.9c-4.1-2.5-9.9-4.4-17.5-3.7 c-2.5,0.1-4.8,0.6-7,1.3l0,0c-2.8,0.9-5.4,2.3-7.7,3.9c-2.8,1.8-5.5,4-7.9,6.5c-3.8,3.8-7.1,8.8-8.5,15c-1.2,4.6-0.9,9.5,0.1,14.6 l0,0c0.3,1.5,0.7,3,1.2,4.6c-2.8-0.3-6.2,1.4-3,12.5C128,254.786,130.2,257.086,131.8,257.286z"></path> </g> </g> </g></svg>
                        </i>
                        <span class="item-name">Payout Request<span
                                class="badge rounded-pill bg-success item-name"></span></span>
                    </a>
                </li>
                <li>
                    <hr class="hr-horizontal">
                </li>
                <li class="nav-item static-item">
                    <a class="nav-link static-item disabled" href="javascript:void(0)" tabindex="-1">
                        <span class="default-icon">Reports</span>
                        <span class="mini-icon">-</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{route('vendor.report.order')}}">
                        <i class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" class="icon-20" width="20">
                                <path
                                    d="M57.64423,53.41349l-4.995-34.96809a5.79886,5.79886,0,0,0-5.71139-4.953h-4.6807l-.9398-2.821C38.35577,1.78071,25.64643,1.77149,22.682,10.672L21.7422,13.4924H17.06151a5.79885,5.79885,0,0,0-5.71139,4.953l-4.995,34.96809a5.80619,5.80619,0,0,0,5.71146,6.585l39.86629.00006A5.8063,5.8063,0,0,0,57.64423,53.41349ZM24.57994,11.3047C26.9,4.233,37.10036,4.23455,39.41939,11.30433l.72907,2.18807H23.85093ZM54.77876,56.69822a3.76656,3.76656,0,0,1-2.84592,1.29885H12.06648a3.7913,3.7913,0,0,1-3.72934-4.30022l4.995-34.968a3.787,3.787,0,0,1,3.72941-3.23489c.00391-.0025,24.47393.00177,24.47772,0h5.39859a3.78623,3.78623,0,0,1,3.72941,3.23489l4.995,34.96809A3.765,3.765,0,0,1,54.77876,56.69822Z"
                                    fill="currentColor" ></path>
                                <path
                                    d="M37.54686,32.79815,31.3048,39.0412l-2.76774-2.76774A1.00083,1.00083,0,0,0,27.122,37.6886l3.47525,3.47531a1.00637,1.00637,0,0,0,1.41514,0L38.962,34.21329A1.00085,1.00085,0,0,0,37.54686,32.79815Z"
                                    fill="currentColor" ></path>
                                <path
                                    d="M31.99966,23.47072A13.5254,13.5254,0,0,0,18.48935,36.981c.74154,17.92334,26.28184,17.91815,27.02062-.00012A13.52537,13.52537,0,0,0,31.99966,23.47072Zm0,25.01909A11.5222,11.5222,0,0,1,20.49088,36.981c.63226-15.26794,22.38769-15.26354,23.01756.00006A11.52218,11.52218,0,0,1,31.99966,48.48981Z"
                                    fill="currentColor" ></path>
                            </svg>

                        </i>
                        <span class="item-name">Order Report<span
                                class="badge rounded-pill bg-success item-name"></span></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{route('vendor.report.product')}}">
                        <i class="icon">

                            <svg xmlns="http://www.w3.org/2000/svg" class="icon-20" width="20" viewBox="0 0 64 64" fill="currentColor">
                                <path
                                    d="m60.93 41.62-4-10c-.19-.23-.25-.57-.93-.63H40a1 1 0 1 0 0 2h.23l-4.8 8H5.77l4.8-8H24a1 1 0 0 0 0-2H10a1.14 1.14 0 0 0-.86.49l-6 10A1 1 0 0 0 4 43h5v17a1 1 0 0 0 1 1h46a1 1 0 0 0 1-1V43h3a1 1 0 0 0 .83-.44 1 1 0 0 0 .1-.94ZM11 52h12v3H11Zm0 5h13a1 1 0 0 0 1-1v-5a1 1 0 0 0-1-1H11v-7h25a1 1 0 0 0 .86-.48L41 35.6V59H11Zm44 2H43V37.19l2.07 5.18A1 1 0 0 0 46 43h9Zm-8.32-18-3.2-8h11.84l3.2 8Z" />
                                <path
                                    d="M28 51a1 1 0 0 0-1 1v4a1 1 0 1 0 2 0v-4a1 1 0 0 0-1-1zm4 0a1 1 0 0 0-1 1v4a1 1 0 1 0 2 0v-4a1 1 0 0 0-1-1zM20.82 29.05a1.13 1.13 0 0 0 1.11-.4s1.57-2.25 3.5-2.39a3.42 3.42 0 0 1 2.75 1.34 1 1 0 0 0 .76.39h6.12a1 1 0 0 0 .75-.39 3.52 3.52 0 0 1 2.75-1.34c1.95.13 3.5 2.37 3.51 2.39a1.1 1.1 0 0 0 1.11.4 1 1 0 0 0 .72-.93c.16-6.38-3.18-8.33-5-8.92a14 14 0 0 0 .1-1.71c0-7.77-6-13.94-6.29-14.2a1 1 0 0 0-1.42 0C31 3.55 25 9.72 25 17.49a14 14 0 0 0 .11 1.71c-1.83.59-5.17 2.54-5 8.92a1 1 0 0 0 .71.93zm20.89-3.58a5.85 5.85 0 0 0-3-1.21 4.36 4.36 0 0 0-1.2.07 19.79 19.79 0 0 0 1-3.13c1.15.42 2.66 1.5 3.2 4.27zM32 5.5c1.53 1.82 5 6.56 5 12a17.42 17.42 0 0 1-2.54 8.5h-4.92A17.42 17.42 0 0 1 27 17.49c0-5.43 3.47-10.17 5-11.99zm-6.56 15.7a19.79 19.79 0 0 0 1 3.13 4.36 4.36 0 0 0-1.2-.07 5.85 5.85 0 0 0-3 1.21c.59-2.77 2.1-3.85 3.2-4.27z" />
                                <path
                                    d="M34 19a1 1 0 0 0 1-1v-4a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v4a1 1 0 0 0 1 1zm-3-4h2v2h-2zm0 16v5a1 1 0 1 0 2 0v-5a1 1 0 1 0-2 0zm4-1v3a1 1 0 1 0 2 0v-3a1 1 0 1 0-2 0zm-7-1a1 1 0 0 0-1 1v3a1 1 0 0 0 2 0v-3a1 1 0 0 0-1-1z" />
                            </svg>

                        </i>
                        <span class="item-name">Product Report<span
                                class="badge rounded-pill bg-success item-name"></span></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{route('vendor.report.tax')}}">
                        <i class="icon">
                            <svg fill="currentColor" class="icon-20" width="20" version="1.1"
                                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                viewBox="0 0 512 512" xml:space="preserve"><g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <g> <path d="M482.728,147.271c-24.16-28.793-59.946-44.65-100.766-44.65c-40.728,0-81.742,15.798-115.487,44.484 c-33.716,28.662-56.232,66.84-63.403,107.503c-7.339,41.623,2.201,80.731,26.861,110.121c24.16,28.793,59.946,44.65,100.766,44.65 c40.728,0,81.742-15.798,115.487-44.484c33.716-28.662,56.232-66.84,63.403-107.502 C516.927,215.769,507.389,176.661,482.728,147.271z M493.8,254.608c-13.489,76.501-86.656,138.74-163.1,138.74 c-35.998,0-67.422-13.823-88.483-38.924c-21.562-25.696-29.856-60.156-23.353-97.03c13.489-76.501,86.656-138.74,163.1-138.74 c35.998,0,67.422,13.823,88.483,38.924C492.007,183.274,500.301,217.734,493.8,254.608z"></path> </g> </g>
                                <g> <g> <path d="M454.432,171.643c-18.841-22.453-46.719-34.819-78.5-34.819c-31.592,0-63.39,12.24-89.535,34.467 c-26.117,22.201-43.561,51.791-49.121,83.317c-5.711,32.388,1.73,62.842,20.954,85.75c18.839,22.453,46.718,34.819,78.5,34.819 c31.592,0,63.39-12.24,89.535-34.467c26.117-22.201,43.561-51.791,49.121-83.317C481.096,225.005,473.655,194.553,454.432,171.643 z M459.597,254.608c-10.164,57.642-65.281,104.536-122.866,104.536c-26.961,0-50.478-10.331-66.219-29.092 c-16.124-19.216-22.32-45.02-17.446-72.659c10.164-57.642,65.281-104.536,122.866-104.536c26.961,0,50.477,10.331,66.219,29.092 C458.274,201.164,464.471,226.969,459.597,254.608z"></path> </g> </g>
                                <g> <g> <path d="M398.233,209.557c-5.176-6.17-13.59-10.429-23.441-12.093l1.495-8.478c0.768-4.361-2.143-8.519-6.503-9.286 c-4.356-0.773-8.518,2.143-9.286,6.502l-1.914,10.857c-20.031,2.217-36.675,14.02-39.262,28.69 c-3.791,21.505,13.698,30.466,27.923,35.615l-6.596,37.404c-7.003-1.131-11.69-3.948-13.941-6.631 c-1.564-1.864-2.144-3.789-1.774-5.886c0.768-4.361-2.143-8.519-6.503-9.286c-4.356-0.774-8.518,2.142-9.286,6.502 c-1.192,6.757,0.685,13.497,5.282,18.976c5.177,6.172,13.591,10.43,23.442,12.093l-1.495,8.478 c-0.768,4.361,2.143,8.519,6.503,9.286c0.47,0.083,0.938,0.123,1.401,0.123c3.817,0,7.2-2.737,7.885-6.625l1.914-10.857 c20.032-2.217,36.675-14.02,39.262-28.69c3.792-21.505-13.698-30.466-27.924-35.615l6.596-37.404 c7.003,1.131,11.69,3.948,13.941,6.631c1.564,1.864,2.144,3.789,1.774,5.886c-0.768,4.361,2.143,8.519,6.503,9.286 c4.359,0.776,8.518-2.143,9.286-6.502C404.707,221.776,402.83,215.036,398.233,209.557z M350.089,245.231 c-14.093-5.63-16.151-10.043-14.978-16.698c1.04-5.901,9.192-12.329,20.529-14.786L350.089,245.231z M377.551,283.467 c-1.04,5.901-9.192,12.329-20.53,14.786l5.552-31.485C376.666,272.4,378.724,276.812,377.551,283.467z"></path> </g> </g>
                                <g> <g> <path d="M170.481,247.984H8.016c-4.427,0-8.016,3.588-8.016,8.016s3.589,8.016,8.016,8.016h162.465 c4.427,0,8.016-3.589,8.016-8.016C178.497,251.573,174.908,247.984,170.481,247.984z"></path> </g> </g>
                                <g> <g> <path d="M187.583,205.231H59.321c-4.427,0-8.016,3.588-8.016,8.016c0,4.428,3.589,8.016,8.016,8.016h128.262 c4.427,0,8.016-3.588,8.016-8.016C195.599,208.819,192.01,205.231,187.583,205.231z"></path> </g> </g>
                                <g> <g> <path d="M213.235,162.477h-85.508c-4.427,0-8.016,3.588-8.016,8.016s3.589,8.016,8.016,8.016h85.508 c4.427,0,8.016-3.588,8.016-8.016S217.662,162.477,213.235,162.477z"></path> </g> </g>
                                <g> <g> <path d="M238.887,128.274h-25.652c-4.427,0-8.016,3.588-8.016,8.016s3.589,8.016,8.016,8.016h25.652 c4.427,0,8.016-3.588,8.016-8.016C246.904,131.862,243.314,128.274,238.887,128.274z"></path> </g> </g>
                                <g> <g> <path d="M204.684,367.695h-17.102c-4.427,0-8.016,3.588-8.016,8.016s3.589,8.016,8.016,8.016h17.102 c4.427,0,8.016-3.588,8.016-8.016S209.111,367.695,204.684,367.695z"></path> </g> </g>
                                <g> <g> <path d="M187.583,333.492h-59.855c-4.427,0-8.016,3.588-8.016,8.016c0,4.428,3.589,8.016,8.016,8.016h59.855 c4.427,0,8.016-3.589,8.016-8.016C195.599,337.08,192.01,333.492,187.583,333.492z"></path> </g> </g>
                                <g> <g> <path d="M170.481,290.738H59.321c-4.427,0-8.016,3.588-8.016,8.016s3.589,8.016,8.016,8.016h111.16 c4.427,0,8.016-3.589,8.016-8.016C178.497,294.326,174.908,290.738,170.481,290.738z"></path> </g> </g> </g>
                            </svg>
                        </i>
                        <span class="item-name">Tax Report<span
                                class="badge rounded-pill bg-success item-name"></span></span>
                    </a>
                </li>

                <li>
                    <hr class="hr-horizontal">
                </li>
                <li class="nav-item static-item">
                    <a class="nav-link static-item disabled" href="javascript:void(0)" tabindex="-1">
                        <span class="default-icon">Business Setting</span>
                        <span class="mini-icon">-</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{route('vendor.business-settings.restaurant-setup')}}">
                        <i class="icon">
                            <svg fill="currentColor"  width="20px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 483.64 483.64" xml:space="preserve" stroke="#7d5454"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <g> <path d="M312.4,238.886l-29.9-5.1c-2.9-11.5-7.4-22.4-13.3-32.4l17.7-24.7c3.3-4.6,2.8-10.8-1.2-14.8l-10.5-10.6l-10.5-10.6 c-2.2-2.2-5.2-3.4-8.1-3.4c-2.3,0-4.6,0.7-6.6,2.1l-24.9,17.4c-10.1-6.1-21.2-10.8-33.1-13.7l-4.9-29.7c-0.9-5.6-5.7-9.6-11.3-9.7 l-15-0.1l-15-0.1l0,0c-5.5,0-10.4,4-11.4,9.6l-5.3,30.2c-11.5,2.9-22.4,7.6-32.3,13.4l-24.3-17.8c-2-1.4-4.4-2.2-6.7-2.2 c-2.9,0-5.9,1.1-8.1,3.3l-10.6,10.5l-10.6,10.5c-3.9,3.9-4.5,10.1-1.3,14.8l17.7,25c-5.9,10-10.4,20.8-13.3,32.3l-29.7,4.9 c-5.6,0.9-9.6,5.7-9.7,11.3l-0.1,15l-0.1,15c0,5.6,4,10.5,9.6,11.4l30.2,5.3c2.9,11.5,7.6,22.4,13.4,32.3l-17.6,24.5 c-3.3,4.6-2.8,10.8,1.2,14.8l10.5,10.6l10.5,10.9c2.2,2.2,5.2,3.4,8.1,3.4c2.3,0,4.6-0.7,6.6-2.1l25-17.7 c9.7,5.7,20.4,10.2,31.5,13.1l4.9,30c0.9,5.6,5.7,9.6,11.3,9.7l15,0.1l15,0.1l0,0c5.5,0,10.4-4,11.4-9.6l5.1-29.9 c11.5-2.9,22.4-7.4,32.4-13.3l24.7,17.7c2,1.4,4.4,2.2,6.7,2.2c2.9,0,5.9-1.1,8.1-3.3l10.6-10.5l10.6-10.5 c3.9-3.9,4.5-10.1,1.3-14.8l-17.5-24.9c6-10,10.5-20.8,13.5-32.3l30-4.9c5.6-0.9,9.6-5.7,9.7-11.3l0.1-15l0.1-15 C321.9,244.586,317.9,239.886,312.4,238.886z M182.7,359.186c-7.3,1.7-14.6,2.5-21.8,2.5c-44.2,0-84.2-30.4-94.6-75.4 c-12-52.3,20.6-104.4,72.9-116.4c7.3-1.7,14.6-2.5,21.8-2.5c44.2,0,84.2,30.4,94.6,75.4 C267.6,295.086,234.9,347.186,182.7,359.186z"></path> <path d="M364,229.486l8,1l0,0c2.9,0.3,5.8-1.5,6.6-4.4l4.6-15.5c6.3-0.8,12.3-2.5,18-5l12,10.9c1,0.9,2.2,1.4,3.4,1.6 c1.6,0.2,3.2-0.2,4.5-1.2l6.3-4.9l6.3-4.9c2.3-1.8,3-5.1,1.6-7.7l-7.7-14.3c3.8-4.9,6.9-10.4,9.2-16.3l16.2-0.7 c3-0.1,5.5-2.4,5.8-5.4l1-8l1-8c0.2-2.9-1.6-5.7-4.4-6.5l-15.5-4.6c-0.8-6.3-2.5-12.3-5-18l10.9-12c2-2.2,2.1-5.6,0.3-7.9 l-4.9-6.3l-4.9-6.3c-1-1.3-2.5-2.1-4.1-2.3c-1.2-0.1-2.5,0.1-3.6,0.7l-14.3,7.7c-5-3.9-10.6-7-16.7-9.3l-0.8-16.1 c-0.1-3-2.4-5.5-5.4-5.8l-8-1l-8-1l0,0c-2.9-0.3-5.8,1.5-6.6,4.4l-4.7,15.7c-6.3,0.8-12.3,2.6-18,5.1l-11.8-11 c-1-0.9-2.2-1.4-3.4-1.6c-1.6-0.2-3.2,0.2-4.5,1.2l-6.3,4.9l-6.3,4.9c-2.3,1.8-3,5.1-1.6,7.7l7.8,14.4c-3.7,4.9-6.8,10.4-9.1,16.3 l-16,0.7c-3,0.1-5.5,2.4-5.8,5.4l-1,8l-1,8c-0.4,2.9,1.5,5.8,4.4,6.7l15.7,4.7c0.8,6.3,2.6,12.3,5.1,18l-10.8,11.9 c-2,2.2-2.1,5.6-0.3,7.9l4.9,6.3l4.9,6.3c1,1.3,2.5,2.1,4.1,2.3c1.2,0.1,2.5-0.1,3.6-0.7l14.4-7.8c4.8,3.6,10.2,6.7,15.9,8.9 l0.7,16.2c0.1,3,2.4,5.5,5.4,5.8L364,229.486z M339.4,142.886c0.7-19.4,17-34.5,36.4-33.8s34.5,17,33.8,36.4 c-0.7,19.4-17,34.5-36.4,33.8C353.8,178.586,338.7,162.286,339.4,142.886z"></path> <path d="M483.6,293.386l-0.7-6l-0.7-6c-0.4-2.1-2.2-3.8-4.4-4l-12.1-0.6c-1.7-4.4-4-8.5-6.8-12.2l5.8-10.6c1.1-2,0.6-4.4-1.2-5.8 l-4.7-3.7l-4.7-3.7c-1-0.8-2.2-1.1-3.4-1c-0.9,0.1-1.8,0.5-2.5,1.1l-9,8.1c-4.3-2-8.9-3.3-13.7-3.9l-3.3-11.5 c-0.6-2.2-2.7-3.5-4.9-3.3l-6,0.7l-6,0.7l0,0c-2.2,0.3-3.9,2.1-4,4.3l-0.7,12.2c-4.4,1.7-8.5,4.1-12.1,6.8l-10.5-5.9 c-0.9-0.5-1.8-0.6-2.8-0.5c-1.2,0.1-2.3,0.7-3,1.7l-3.7,4.7l-3.7,4.7c-1.4,1.7-1.3,4.2,0.2,5.9l8.2,9.1c-1.9,4.2-3.1,8.7-3.7,13.4 l-11.5,3.3c-2.2,0.6-3.5,2.7-3.3,4.9l0.7,6l0.7,6c0.3,2.2,2.1,3.9,4.3,4.1l12.2,0.7c1.7,4.4,4.1,8.5,6.8,12.1l-5.8,10.5 c-1.1,2-0.6,4.4,1.2,5.8l4.7,3.7l4.7,3.7c1,0.8,2.2,1.1,3.4,1c0.9-0.1,1.8-0.5,2.5-1.1l9.1-8.2c4.1,1.8,8.6,3.1,13.1,3.7l3.3,11.6 c0.6,2.2,2.7,3.5,4.9,3.3l6-0.7l6-0.7l0,0c2.2-0.3,3.9-2.1,4-4.3l0.6-12.1c4.4-1.7,8.5-4,12.2-6.8l10.6,5.8 c0.9,0.5,1.8,0.6,2.8,0.5c1.2-0.1,2.3-0.7,3-1.7l3.7-4.7l3.7-4.7c1.4-1.7,1.3-4.2-0.2-5.9l-8.1-9c1.9-4.2,3.2-8.7,3.8-13.4 l11.6-3.3C482.5,297.686,483.9,295.586,483.6,293.386z M424.4,320.486c-14.2,2.9-28-6.3-30.9-20.5s6.3-28,20.5-30.9 s28,6.3,30.9,20.5S438.6,317.586,424.4,320.486z"></path> <path d="M216.7,294.486c-17.3-4.4-31.3-14.3-31.3-14.3l-11,34.7l-2.1,6.5v-0.1l-1.8,5.5l-5.8-16.4c14.6-20.4-3.9-19.6-3.9-19.6 s-18.5-0.8-3.9,19.6l-5.8,16.5l-1.8-5.6l-13-41.2c0,0-14.1,9.9-31.3,14.3c-8.6,2.2-11.7,9.6-12.7,16.4 c15.5,21.3,40.5,34.7,68.4,34.7c6.4,0,12.8-0.7,19.1-2.2c20.2-4.6,37.7-16.3,49.7-32.9C228.3,303.786,225.1,296.686,216.7,294.486 z"></path> <path d="M131.8,257.286c1.5,9.7,8.9,22,21.2,26.3c5,1.8,10.5,1.8,15.6,0c12.1-4.3,19.8-16.6,21.3-26.2c1.6-0.1,3.8-2.4,6.1-10.5 c3.1-11.1-0.2-12.8-3-12.5c0.5-1.5,0.9-3.1,1.2-4.6c4.8-28.8-9.4-29.8-9.4-29.8s-2.3-4.5-8.5-7.9c-4.1-2.5-9.9-4.4-17.5-3.7 c-2.5,0.1-4.8,0.6-7,1.3l0,0c-2.8,0.9-5.4,2.3-7.7,3.9c-2.8,1.8-5.5,4-7.9,6.5c-3.8,3.8-7.1,8.8-8.5,15c-1.2,4.6-0.9,9.5,0.1,14.6 l0,0c0.3,1.5,0.7,3,1.2,4.6c-2.8-0.3-6.2,1.4-3,12.5C128,254.786,130.2,257.086,131.8,257.286z"></path> </g> </g> </g></svg>
                        </i>
                        <span class="item-name">Business Setup<span
                                class="badge rounded-pill bg-success item-name"></span></span>
                    </a>
                </li>


            </ul>
            <!-- Sidebar Menu End -->
        </div>
    </div>
    <div class="sidebar-footer"></div>
</aside>
