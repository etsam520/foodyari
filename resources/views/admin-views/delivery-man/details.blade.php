@extends('layouts.dashboard-main')

@section('content')
    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                            <p class="mb-md-0 mb-2 d-flex align-items-center">
                                <svg width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="me-2 icon-20">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M4.56517 3C3.70108 3 3 3.71286 3 4.5904V5.52644C3 6.17647 3.24719 6.80158 3.68936 7.27177L8.5351 12.4243L8.53723 12.4211C9.47271 13.3788 9.99905 14.6734 9.99905 16.0233V20.5952C9.99905 20.9007 10.3187 21.0957 10.584 20.9516L13.3436 19.4479C13.7602 19.2204 14.0201 18.7784 14.0201 18.2984V16.0114C14.0201 14.6691 14.539 13.3799 15.466 12.4243L20.3117 7.27177C20.7528 6.80158 21 6.17647 21 5.52644V4.5904C21 3.71286 20.3 3 19.4359 3H4.56517Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                {{-- Filter  ::  {{Str::ucfirst($filter)}} --}}
                            </p>
                            <div class="d-flex align-items-center flex-wrap">

                                <div class="dropdown me-3">
                                    <span class="dropdown-toggle align-items-center d-flex" id="dropdownMenuButton04" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <svg width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="me-2 icon-20">
                                            <path d="M3.09277 9.40421H20.9167" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M16.442 13.3097H16.4512" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M12.0045 13.3097H12.0137" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M7.55818 13.3097H7.56744" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M16.442 17.1962H16.4512" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M12.0045 17.1962H12.0137" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M7.55818 17.1962H7.56744" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M16.0433 2V5.29078" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M7.96515 2V5.29078" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M16.2383 3.5791H7.77096C4.83427 3.5791 3 5.21504 3 8.22213V17.2718C3 20.3261 4.83427 21.9999 7.77096 21.9999H16.229C19.175 21.9999 21 20.3545 21 17.3474V8.22213C21.0092 5.21504 19.1842 3.5791 16.2383 3.5791Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>


                                    </span>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton22" style="">
                                        @for ($i = 0; $i < 6; $i++)
                                            <?php
                                                $month = $now->copy()->subMonths($i);
                                            ?>
                                            <li>
                                                <a class="dropdown-item" href="{{ url()->current() }}?filter={{ $month->format('Y-m') }}">
                                                    {{ $month->format('F Y') }}
                                                </a>
                                            </li>
                                        @endfor
                                    </ul>
                                </div>
                                <form action="{{ url()->current()}}">
                                <div class="me-3 d-flex align-items-center justify-content-center">

                                        <input type="text" name="date_range" class="form-control range_flatpicker d-flex flatpickr-input active" placeholder="Date Range" readonly="readonly" required>
                                        <input type="hidden" name="filter" value="custom">
                                        <button class="badge rounded-pill bg-success ms-1 mb-1 px-3 py-2" type="submit">Go</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                            <div class="d-flex flex-wrap align-items-center">
                                <div class="profile-img position-relative me-3 mb-3 mb-lg-0 profile-logo profile-logo1">
                                    <img onerror="this.src='{{asset('assets/user/img/user2.png')}}'"
                                    src="{{asset("delivery-man/".$dm['image'])}}"alt="User-Profile" class="theme-color-default-img img-fluid rounded-pill avatar-100">
                                    {{-- <img src="../../assets/images/avatars/01.png" >
                                    <img src="../../assets/images/avatars/avtar_1.png" alt="User-Profile" class="theme-color-purple-img img-fluid rounded-pill avatar-100">
                                    <img src="../../assets/images/avatars/avtar_2.png" alt="User-Profile" class="theme-color-blue-img img-fluid rounded-pill avatar-100">
                                    <img src="../../assets/images/avatars/avtar_4.png" alt="User-Profile" class="theme-color-green-img img-fluid rounded-pill avatar-100">
                                    <img src="../../assets/images/avatars/avtar_5.png" alt="User-Profile" class="theme-color-yellow-img img-fluid rounded-pill avatar-100">
                                    <img src="../../assets/images/avatars/avtar_3.png" alt="User-Profile" class="theme-color-pink-img img-fluid rounded-pill avatar-100"> --}}
                                </div>
                                <div class="d-flex flex-wrap align-items-center mb-3 mb-sm-0">
                                    <h4 class="me-2 h4">{{$dm['f_name'].' '.$dm['l_name']}}</h4>
                                    {{-- <span> - Web Developer</span> --}}
                                </div>
                            </div>
                            <ul class="d-flex nav nav-pills mb-0 text-center profile-tab nav-slider" data-toggle="slider-tab" id="profile-pills-tab" role="tablist">

                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active show" data-bs-toggle="tab" href="#profile-attendance" role="tab" aria-selected="false" tabindex="-1">Attendance</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#profile-fuel" role="tab" aria-selected="false" tabindex="-1">Fuel</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#profile-cash" role="tab" aria-selected="false" tabindex="-1">Cash</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#profile-wallet" role="tab" aria-selected="false" tabindex="-1">Wallet</a>
                                </li>
                                {{-- <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#profile-profile" role="tab" aria-selected="false" tabindex="-1">Orders</a>
                                </li> --}}
                                <div class="nav-slider-thumb position-absolute nav-link" style="padding: 0px; width: 60px; height: 40px; transform: translate3d(0px, 0px, 0px); transition: 300ms ease-in-out;" aria-selected="false" tabindex="-1"
                                    role="tab"><a class="nav-link active show" data-bs-toggle="tab" href="#profile-feed" role="tab" aria-selected="true"></a></div>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card">
                    <div class="row">
                        <div class="col-lg-12">
                            <a href="void(0)">
                                <div class="text-center bg-body-secondary shadow-sm py-2 rounded-4">
                                    <img src="{{asset('assets/images/working-hours.png')}}" alt="" style="width: 30px; height: 30px;">
                                    <br><span class="text-muted"><small>Total Working Days</small></span>
                                    <div class="fw-bolder">{{Helpers::half_whole_day_display($dm['working_days'])}} Days</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-12">
                            <a href="void(0)">
                                <div class="text-center bg-body-secondary shadow-sm py-2 rounded-4">
                                    <img src="{{asset('assets/images/working-hours.png')}}" alt="" style="width: 30px; height: 30px;">
                                    <br><span class="text-muted"><small>Total Half Working Days</small></span>
                                    <div class="fw-bolder">{{$dm['half_days']}} Days</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-12">
                            <a href="void(0)">
                                <div class="text-center bg-body-secondary shadow-sm py-2 rounded-4">
                                    <img src="{{asset('assets/images/working-hours.png')}}" alt="" style="width: 30px; height: 30px;">
                                    <br><span class="text-muted"><small>Total Full Working Days</small></span>
                                    <div class="fw-bolder">{{$dm['full_days']}} Days</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-12 mt-2 mt-lg-0">
                            <a href="void(0)">
                                <div class="text-center bg-body-secondary shadow-sm py-2 rounded-4">
                                    <img src="{{asset('assets/images/distance.png')}}" alt="" style="width: 30px; height: 30px;">
                                    <br><span class="text-muted"><small>Total Distance</small></span>
                                    <div class="fw-bolder">{{$dm['total_distance']}} KM</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-12 mt-2 mt-lg-0">
                            <a href="void(0)">
                                <div class="text-center bg-body-secondary shadow-sm py-2 rounded-4">
                                    <img src="{{asset('assets/images/fuel-credit.png')}}" alt="" style="width: 30px; height: 30px;">
                                    <br><span class="text-muted"><small>Fuel Credit</small></span>
                                    <div class="fw-bolder"> {{Helpers::format_currency($dm['fuel_balance'])}}</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="header-title">
                            <h4 class="card-title">Feul Rate per KM</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="list-inline m-0 p-0">
                            <li class="d-flex mb-2">
                                <div class="news-icon me-3">
                                    <svg class="icon-20" width="20" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="M20,2H4A2,2 0 0,0 2,4V22L6,18H20A2,2 0 0,0 22,16V4C22,2.89 21.1,2 20,2Z"></path>
                                    </svg>
                                </div>
                                <p class="news-detail mb-0">{{Helpers::format_currency($dm['fuel_rate'])}}  <a href="#" class="text-primary" id="fuel-rate-changer">change</a></p>
                            </li>
                            <li class="d-flex mb-2 d-none" id="fuel-rate-li">
                                <form action="{{route('admin.delivery-man.update-fuel-rate')}}" class="d-flex" method="POST" >
                                    @csrf
                                    <input type="hidden" name="dm_id" value="{{$dm['id']}}">
                                    <input type="number" name="fuel_rate" class="form-control" placeholder="Enter Rate">
                                    <button class="btn btn-primary">Update</button>
                                </form>
                            </li>
                            <script>
                                document.getElementById("fuel-rate-changer").addEventListener("click", () => {
                                    document.getElementById("fuel-rate-li").classList.toggle("d-none");
                                });
                            </script>
                        </ul>
                    </div>
                </div>


                <div class="card mt-4">
                    <!-- Header -->
                    <div class="card-header">
                        <h4 class="card-header-title">
                            <span class="card-header-icon">
                                <i class="tio-wallet"></i>
                            </span>
                            <span>
                                Add Fuel Balance
                            </span>
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="{{route('admin.delivery-man.add-fuel-balance')}}" method="post">
                            @csrf
                            <div class="row">
                                <input type="hidden" name="dm_id" value="{{$dm['id']}}">

                                <div class="col-sm-12 col-12 mt-3">
                                    <div class="form-group">
                                        <label class="form-label" for="amount">Amount</label>

                                        <input type="number" class="form-control h--45px" placeholder="Enter Amount" name="amount"
                                            id="amount" step=".01" required="">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label" for="referance">Note: <small>(Optional)</small></label>

                                        <input type="text" class="form-control h--45px"  name="note"
                                            id="referance">
                                    </div>
                                </div>
                            </div>
                            <div class="btn--container justify-content-end">
                                {{-- <button type="reset" id="reset" class="btn btn-soft-reset">Reset</button> --}}
                                <button type="submit" id="submit" class="btn btn-soft-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card mt-4">
                    <!-- Header -->
                    <div class="card-header">
                        <h4 class="card-header-title">
                            <span class="card-header-icon">
                                <i class="tio-lock"></i>
                            </span>
                            <span>
                                Change Password
                            </span>
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="{{route('admin.delivery-man.change-password')}}" method="post" onsubmit="return validatePasswordForm()">
                            @csrf
                            <div class="row">
                                <input type="hidden" name="dm_id" value="{{$dm['id']}}">

                                <div class="col-sm-12 col-12 mt-3">
                                    <div class="form-group">
                                        <label class="form-label" for="new_password">New Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control h--45px" placeholder="Enter New Password" name="new_password"
                                                id="new_password" minlength="6" required>
                                            <span class="input-group-text" onclick="togglePasswordVisibility('new_password')" style="cursor: pointer;">
                                                <i class="fa fa-eye" id="new_password_eye"></i>
                                            </span>
                                        </div>
                                        <small class="text-muted">Password must be at least 6 characters long</small>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-12 mt-3">
                                    <div class="form-group">
                                        <label class="form-label" for="confirm_password">Confirm Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control h--45px" placeholder="Confirm New Password" name="confirm_password"
                                                id="confirm_password" minlength="6" required>
                                            <span class="input-group-text" onclick="togglePasswordVisibility('confirm_password')" style="cursor: pointer;">
                                                <i class="fa fa-eye" id="confirm_password_eye"></i>
                                            </span>
                                        </div>
                                        <div id="password_match_message" class="mt-2"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="btn--container justify-content-end">
                                <button type="submit" id="submit_password" class="btn btn-soft-primary">Change Password</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card d-none">
                    <div class="card-header">
                        <div class="header-title">
                            <h4 class="card-title">News</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="list-inline m-0 p-0">
                            <li class="d-flex mb-2">
                                <div class="news-icon me-3">
                                    <svg class="icon-20" width="20" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="M20,2H4A2,2 0 0,0 2,4V22L6,18H20A2,2 0 0,0 22,16V4C22,2.89 21.1,2 20,2Z"></path>
                                    </svg>
                                </div>
                                <p class="news-detail mb-0">there is a meetup in your city on fryday at 19:00. <a href="#">see details</a></p>
                            </li>
                            <li class="d-flex">
                                <div class="news-icon me-3">
                                    <svg class="icon-20" width="20" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="M20,2H4A2,2 0 0,0 2,4V22L6,18H20A2,2 0 0,0 22,16V4C22,2.89 21.1,2 20,2Z"></path>
                                    </svg>
                                </div>
                                <p class="news-detail mb-0">20% off coupon on selected items at pharmaprix </p>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card d-none">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Gallery</h4>
                        </div>
                        <span>132 pics</span>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-card grid-cols-3">
                            <a data-fslightbox="gallery" href="../../assets/images/icons/04.png">
                                <img src="../../assets/images/icons/04.png" class="img-fluid bg-info-subtle rounded" alt="profile-image">
                            </a>
                            <a data-fslightbox="gallery" href="../../assets/images/shapes/02.png">
                                <img src="../../assets/images/shapes/02.png" class="img-fluid bg-primary-subtle rounded" alt="profile-image">
                            </a>
                            <a data-fslightbox="gallery" href="../../assets/images/icons/08.png">
                                <img src="../../assets/images/icons/08.png" class="img-fluid bg-info-subtle rounded" alt="profile-image">
                            </a>
                            <a data-fslightbox="gallery" href="../../assets/images/shapes/04.png">
                                <img src="../../assets/images/shapes/04.png" class="img-fluid bg-primary-subtle rounded" alt="profile-image">
                            </a>
                            <a data-fslightbox="gallery" href="../../assets/images/icons/02.png">
                                <img src="../../assets/images/icons/02.png" class="img-fluid bg-warning-subtle rounded" alt="profile-image">
                            </a>
                            <a data-fslightbox="gallery" href="../../assets/images/shapes/06.png">
                                <img src="../../assets/images/shapes/06.png" class="img-fluid bg-primary-subtle rounded" alt="profile-image">
                            </a>
                            <a data-fslightbox="gallery" href="../../assets/images/icons/05.png">
                                <img src="../../assets/images/icons/05.png" class="img-fluid  bg-danger-subtle rounded" alt="profile-image">
                            </a>
                            <a data-fslightbox="gallery" href="../../assets/images/shapes/04.png">
                                <img src="../../assets/images/shapes/04.png" class="img-fluid bg-primary-subtle rounded" alt="profile-image">
                            </a>
                            <a data-fslightbox="gallery" href="../../assets/images/icons/01.png">
                                <img src="../../assets/images/icons/01.png" class="img-fluid  bg-success-subtle rounded" alt="profile-image">
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card d-none">
                    <div class="card-header">
                        <div class="header-title">
                            <h4 class="card-title">Twitter Feeds</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="twit-feed">
                            <div class="d-flex align-items-center mb-2">
                                <img class="rounded-pill img-fluid avatar-50 me-3 p-1 bg-danger-subtle  ps-2" src="../../assets/images/icons/03.png" alt="">
                                <div class="media-support-info">
                                    <h6 class="mb-0">Figma Community</h6>
                                    <p class="mb-0">@figma20
                                        <span class="text-primary">
                                            <svg class="icon-15" width="15" viewBox="0 0 24 24">
                                                <path fill="currentColor" d="M10,17L5,12L6.41,10.58L10,14.17L17.59,6.58L19,8M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z"></path>
                                            </svg>
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="media-support-body">
                                <p class="mb-0">Lorem Ipsum is simply dummy text of the printing and typesetting industry</p>
                                <div class="d-flex flex-wrap">
                                    <a href="#" class="twit-meta-tag pe-2">#Html</a>
                                    <a href="#" class="twit-meta-tag pe-2">#Bootstrap</a>
                                </div>
                                <div class="twit-date">07 Jan 2021</div>
                            </div>
                        </div>
                        <hr class="my-4">
                        <div class="twit-feed">
                            <div class="d-flex align-items-center mb-2">
                                <img class="rounded-pill img-fluid avatar-50 me-3 p-1 bg-primary-subtle" src="../../assets/images/icons/04.png" alt="">
                                <div class="media-support-info">
                                    <h6 class="mb-0">Flutter</h6>
                                    <p class="mb-0">@jane59
                                        <span class="text-primary">
                                            <svg class="icon-15" width="15" viewBox="0 0 24 24">
                                                <path fill="currentColor" d="M10,17L5,12L6.41,10.58L10,14.17L17.59,6.58L19,8M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z"></path>
                                            </svg>
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="media-support-body">
                                <p class="mb-0">Lorem Ipsum is simply dummy text of the printing and typesetting industry</p>
                                <div class="d-flex flex-wrap">
                                    <a href="#" class="twit-meta-tag pe-2">#Js</a>
                                    <a href="#" class="twit-meta-tag pe-2">#Bootstrap</a>
                                </div>
                                <div class="twit-date">18 Feb 2021</div>
                            </div>
                        </div>
                        <hr class="my-4">
                        <div class="twit-feed">
                            <div class="d-flex align-items-center mb-2">
                                <img class="rounded-pill img-fluid avatar-50 me-3 p-1 bg-warning-subtle pt-2" src="../../assets/images/icons/02.png" alt="">
                                <div class="mt-2">
                                    <h6 class="mb-0">Blender</h6>
                                    <p class="mb-0">@blender59
                                        <span class="text-primary">
                                            <svg class="icon-15" width="15" viewBox="0 0 24 24">
                                                <path fill="currentColor" d="M10,17L5,12L6.41,10.58L10,14.17L17.59,6.58L19,8M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z"></path>
                                            </svg>
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="media-support-body">
                                <p class="mb-0">Lorem Ipsum is simply dummy text of the printing and typesetting industry</p>
                                <div class="d-flex flex-wrap">
                                    <a href="#" class="twit-meta-tag pe-2">#Html</a>
                                    <a href="#" class="twit-meta-tag pe-2">#CSS</a>
                                </div>
                                <div class="twit-date">15 Mar 2021</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="profile-content tab-content">
                    {{-- <div id="profile-feed" class="tab-pane fade active show" role="tabpanel">
                        <div class="card">
                            <div class="card-header d-flex align-items-center justify-content-between pb-4">
                                <div class="header-title">
                                    <div class="d-flex flex-wrap">
                                        <div class="media-support-user-img me-3">
                                            <img class="rounded-pill img-fluid avatar-60 bg-danger-subtle p-1 ps-2" src="../../assets/images/avatars/02.png" alt="">
                                        </div>
                                        <div class="media-support-info mt-2">
                                            <h5 class="mb-0">Anna Sthesia</h5>
                                            <p class="mb-0 text-primary">colleages</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="dropdown mt-5 mt-md-0">
                                    <span class="dropdown-toggle" id="dropdownMenuButton7" data-bs-toggle="dropdown" aria-expanded="false" role="button">
                                        29 mins
                                    </span>
                                    <div class="dropdown-menu dropdown-menu-end custom-dropdown-menu-profile" aria-labelledby="dropdownMenuButton7">
                                        <a class="dropdown-item " href="javascript:void(0);">Action</a>
                                        <a class="dropdown-item " href="javascript:void(0);">Another action</a>
                                        <a class="dropdown-item " href="javascript:void(0);">Something else here</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="user-post">
                                    <a href="javascript:void(0);"><img src="../../assets/images/pages/02-page.png" alt="post-image" class="img-fluid"></a>
                                </div>
                                <div class="comment-area p-3">
                                    <div class="d-flex flex-wrap justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <div class="d-flex align-items-center message-icon me-3">
                                                <svg class="icon-20" width="20" viewBox="0 0 24 24">
                                                    <path fill="currentColor"
                                                        d="M12.1,18.55L12,18.65L11.89,18.55C7.14,14.24 4,11.39 4,8.5C4,6.5 5.5,5 7.5,5C9.04,5 10.54,6 11.07,7.36H12.93C13.46,6 14.96,5 16.5,5C18.5,5 20,6.5 20,8.5C20,11.39 16.86,14.24 12.1,18.55M16.5,3C14.76,3 13.09,3.81 12,5.08C10.91,3.81 9.24,3 7.5,3C4.42,3 2,5.41 2,8.5C2,12.27 5.4,15.36 10.55,20.03L12,21.35L13.45,20.03C18.6,15.36 22,12.27 22,8.5C22,5.41 19.58,3 16.5,3Z">
                                                    </path>
                                                </svg>
                                                <span class="ms-1">140</span>
                                            </div>
                                            <div class="d-flex align-items-center feather-icon">
                                                <svg class="icon-20" width="20" viewBox="0 0 24 24">
                                                    <path fill="currentColor"
                                                        d="M9,22A1,1 0 0,1 8,21V18H4A2,2 0 0,1 2,16V4C2,2.89 2.9,2 4,2H20A2,2 0 0,1 22,4V16A2,2 0 0,1 20,18H13.9L10.2,21.71C10,21.9 9.75,22 9.5,22V22H9M10,16V19.08L13.08,16H20V4H4V16H10Z"></path>
                                                </svg>
                                                <span class="ms-1">140</span>
                                            </div>
                                        </div>
                                        <div class="share-block d-flex align-items-center feather-icon">
                                            <a href="javascript:void(0);" data-bs-toggle="offcanvas" data-bs-target="#share-btn" aria-controls="share-btn">
                                                <span class="ms-1">
                                                    <svg width="18" class="me-1 icon-18" viewBox="0 0 24 24">
                                                        <path fill="currentColor"
                                                            d="M18 16.08C17.24 16.08 16.56 16.38 16.04 16.85L8.91 12.7C8.96 12.47 9 12.24 9 12S8.96 11.53 8.91 11.3L15.96 7.19C16.5 7.69 17.21 8 18 8C19.66 8 21 6.66 21 5S19.66 2 18 2 15 3.34 15 5C15 5.24 15.04 5.47 15.09 5.7L8.04 9.81C7.5 9.31 6.79 9 6 9C4.34 9 3 10.34 3 12S4.34 15 6 15C6.79 15 7.5 14.69 8.04 14.19L15.16 18.34C15.11 18.55 15.08 18.77 15.08 19C15.08 20.61 16.39 21.91 18 21.91S20.92 20.61 20.92 19C20.92 17.39 19.61 16.08 18 16.08M18 4C18.55 4 19 4.45 19 5S18.55 6 18 6 17 5.55 17 5 17.45 4 18 4M6 13C5.45 13 5 12.55 5 12S5.45 11 6 11 7 11.45 7 12 6.55 13 6 13M18 20C17.45 20 17 19.55 17 19S17.45 18 18 18 19 18.45 19 19 18.55 20 18 20Z">
                                                        </path>
                                                    </svg>
                                                    99 Share</span></a>
                                        </div>
                                    </div>
                                    <hr>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi nulla dolor, ornare at commodo non, feugiat non nisi. Phasellus faucibus mollis pharetra. Proin blandit ac massa sed rhoncus</p>
                                    <hr>
                                    <ul class="list-inline p-0 m-0">
                                        <li class="mb-2">
                                            <div class="d-flex">
                                                <img src="../../assets/images/avatars/03.png" alt="userimg" class="avatar-50 p-1 pt-2 bg-primary-subtle rounded-pill img-fluid">
                                                <div class="ms-3">
                                                    <h6 class="mb-1">Monty Carlo</h6>
                                                    <p class="mb-1">Lorem ipsum dolor sit amet</p>
                                                    <div class="d-flex flex-wrap align-items-center mb-1">
                                                        <a href="javascript:void(0);" class="me-3">
                                                            <svg width="20" class="text-body me-1 icon-20" viewBox="0 0 24 24">
                                                                <path fill="currentColor"
                                                                    d="M12.1,18.55L12,18.65L11.89,18.55C7.14,14.24 4,11.39 4,8.5C4,6.5 5.5,5 7.5,5C9.04,5 10.54,6 11.07,7.36H12.93C13.46,6 14.96,5 16.5,5C18.5,5 20,6.5 20,8.5C20,11.39 16.86,14.24 12.1,18.55M16.5,3C14.76,3 13.09,3.81 12,5.08C10.91,3.81 9.24,3 7.5,3C4.42,3 2,5.41 2,8.5C2,12.27 5.4,15.36 10.55,20.03L12,21.35L13.45,20.03C18.6,15.36 22,12.27 22,8.5C22,5.41 19.58,3 16.5,3Z">
                                                                </path>
                                                            </svg>
                                                            like
                                                        </a>
                                                        <a href="javascript:void(0);" class="me-3">
                                                            <svg width="20" class="me-1 icon-20" viewBox="0 0 24 24">
                                                                <path fill="currentColor" d="M8,9.8V10.7L9.7,11C12.3,11.4 14.2,12.4 15.6,13.7C13.9,13.2 12.1,12.9 10,12.9H8V14.2L5.8,12L8,9.8M10,5L3,12L10,19V14.9C15,14.9 18.5,16.5 21,20C20,15 17,10 10,9">
                                                                </path>
                                                            </svg>
                                                            reply
                                                        </a>
                                                        <a href="javascript:void(0);" class="me-3">translate</a>
                                                        <span> 5 min </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex">
                                                <img src="../../assets/images/avatars/04.png" alt="userimg" class="avatar-50 p-1 bg-danger-subtle rounded-pill img-fluid">
                                                <div class="ms-3">
                                                    <h6 class="mb-1">Paul Molive</h6>
                                                    <p class="mb-1">Lorem ipsum dolor sit amet</p>
                                                    <div class="d-flex flex-wrap align-items-center">
                                                        <a href="javascript:void(0);" class="me-3">
                                                            <svg width="20" class="text-body me-1 icon-20" viewBox="0 0 24 24">
                                                                <path fill="currentColor"
                                                                    d="M12.1,18.55L12,18.65L11.89,18.55C7.14,14.24 4,11.39 4,8.5C4,6.5 5.5,5 7.5,5C9.04,5 10.54,6 11.07,7.36H12.93C13.46,6 14.96,5 16.5,5C18.5,5 20,6.5 20,8.5C20,11.39 16.86,14.24 12.1,18.55M16.5,3C14.76,3 13.09,3.81 12,5.08C10.91,3.81 9.24,3 7.5,3C4.42,3 2,5.41 2,8.5C2,12.27 5.4,15.36 10.55,20.03L12,21.35L13.45,20.03C18.6,15.36 22,12.27 22,8.5C22,5.41 19.58,3 16.5,3Z">
                                                                </path>
                                                            </svg>
                                                            like
                                                        </a>
                                                        <a href="javascript:void(0);" class="me-3">
                                                            <svg width="20" class="me-1 icon-20" viewBox="0 0 24 24">
                                                                <path fill="currentColor" d="M8,9.8V10.7L9.7,11C12.3,11.4 14.2,12.4 15.6,13.7C13.9,13.2 12.1,12.9 10,12.9H8V14.2L5.8,12L8,9.8M10,5L3,12L10,19V14.9C15,14.9 18.5,16.5 21,20C20,15 17,10 10,9">
                                                                </path>
                                                            </svg>
                                                            reply
                                                        </a>
                                                        <a href="javascript:void(0);" class="me-3">translate</a>
                                                        <span> 5 min </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                    <form class="comment-text d-flex align-items-center mt-3" action="javascript:void(0);">
                                        <input type="text" class="form-control rounded" placeholder="Lovely!">
                                        <div class="comment-attagement d-flex">
                                            <a href="javascript:void(0);" class="me-2 text-body">
                                                <svg class="icon-20" width="20" viewBox="0 0 24 24">
                                                    <path fill="currentColor"
                                                        d="M20,12A8,8 0 0,0 12,4A8,8 0 0,0 4,12A8,8 0 0,0 12,20A8,8 0 0,0 20,12M22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2A10,10 0 0,1 22,12M10,9.5C10,10.3 9.3,11 8.5,11C7.7,11 7,10.3 7,9.5C7,8.7 7.7,8 8.5,8C9.3,8 10,8.7 10,9.5M17,9.5C17,10.3 16.3,11 15.5,11C14.7,11 14,10.3 14,9.5C14,8.7 14.7,8 15.5,8C16.3,8 17,8.7 17,9.5M12,17.23C10.25,17.23 8.71,16.5 7.81,15.42L9.23,14C9.68,14.72 10.75,15.23 12,15.23C13.25,15.23 14.32,14.72 14.77,14L16.19,15.42C15.29,16.5 13.75,17.23 12,17.23Z">
                                                    </path>
                                                </svg>
                                            </a>
                                            <a href="javascript:void(0);" class="text-body">
                                                <svg class="icon-20" width="20" viewBox="0 0 24 24">
                                                    <path fill="currentColor"
                                                        d="M20,4H16.83L15,2H9L7.17,4H4A2,2 0 0,0 2,6V18A2,2 0 0,0 4,20H20A2,2 0 0,0 22,18V6A2,2 0 0,0 20,4M20,18H4V6H8.05L9.88,4H14.12L15.95,6H20V18M12,7A5,5 0 0,0 7,12A5,5 0 0,0 12,17A5,5 0 0,0 17,12A5,5 0 0,0 12,7M12,15A3,3 0 0,1 9,12A3,3 0 0,1 12,9A3,3 0 0,1 15,12A3,3 0 0,1 12,15Z">
                                                    </path>
                                                </svg>
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header d-flex align-items-center justify-content-between pb-4">
                                <div class="header-title">
                                    <div class="d-flex flex-wrap">
                                        <div class="media-support-user-img me-3">
                                            <img class="rounded-pill img-fluid avatar-60 p-1 bg-info-subtle" src="../../assets/images/avatars/05.png" alt="">
                                        </div>
                                        <div class="media-support-info mt-2">
                                            <h5 class="mb-0">Wade Warren</h5>
                                            <p class="mb-0 text-primary">colleages</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="dropdown">
                                    <span class="dropdown-toggle" id="dropdownMenuButton07" data-bs-toggle="dropdown" aria-expanded="false" role="button">
                                        1 Hr
                                    </span>
                                    <div class="dropdown-menu dropdown-menu-end custom-dropdown-menu-friends" aria-labelledby="dropdownMenuButton07">
                                        <a class="dropdown-item " href="javascript:void(0);">Action</a>
                                        <a class="dropdown-item " href="javascript:void(0);">Another action</a>
                                        <a class="dropdown-item " href="javascript:void(0);">Something else here</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <p class="p-3 mb-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi nulla dolor, ornare at commodo non, feugiat non nisi. Phasellus faucibus mollis pharetra. Proin blandit ac massa sed rhoncus</p>
                                <div class="comment-area p-3">
                                    <hr class="mt-0">
                                    <div class="d-flex flex-wrap justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <div class="d-flex align-items-center message-icon me-3">
                                                <svg class="icon-20" width="20" viewBox="0 0 24 24">
                                                    <path fill="currentColor"
                                                        d="M12.1,18.55L12,18.65L11.89,18.55C7.14,14.24 4,11.39 4,8.5C4,6.5 5.5,5 7.5,5C9.04,5 10.54,6 11.07,7.36H12.93C13.46,6 14.96,5 16.5,5C18.5,5 20,6.5 20,8.5C20,11.39 16.86,14.24 12.1,18.55M16.5,3C14.76,3 13.09,3.81 12,5.08C10.91,3.81 9.24,3 7.5,3C4.42,3 2,5.41 2,8.5C2,12.27 5.4,15.36 10.55,20.03L12,21.35L13.45,20.03C18.6,15.36 22,12.27 22,8.5C22,5.41 19.58,3 16.5,3Z">
                                                    </path>
                                                </svg>
                                                <span class="ms-1">140</span>
                                            </div>
                                            <div class="d-flex align-items-center feather-icon">
                                                <svg class="icon-20" width="20" viewBox="0 0 24 24">
                                                    <path fill="currentColor"
                                                        d="M9,22A1,1 0 0,1 8,21V18H4A2,2 0 0,1 2,16V4C2,2.89 2.9,2 4,2H20A2,2 0 0,1 22,4V16A2,2 0 0,1 20,18H13.9L10.2,21.71C10,21.9 9.75,22 9.5,22V22H9M10,16V19.08L13.08,16H20V4H4V16H10Z"></path>
                                                </svg>
                                                <span class="ms-1">140</span>
                                            </div>
                                        </div>
                                        <div class="share-block d-flex align-items-center feather-icon">
                                            <a href="javascript:void(0);" data-bs-toggle="offcanvas" data-bs-target="#share-btn" aria-controls="share-btn">
                                                <span class="ms-1">
                                                    <svg width="18" class="me-1 icon-18" viewBox="0 0 24 24">
                                                        <path fill="currentColor"
                                                            d="M18 16.08C17.24 16.08 16.56 16.38 16.04 16.85L8.91 12.7C8.96 12.47 9 12.24 9 12S8.96 11.53 8.91 11.3L15.96 7.19C16.5 7.69 17.21 8 18 8C19.66 8 21 6.66 21 5S19.66 2 18 2 15 3.34 15 5C15 5.24 15.04 5.47 15.09 5.7L8.04 9.81C7.5 9.31 6.79 9 6 9C4.34 9 3 10.34 3 12S4.34 15 6 15C6.79 15 7.5 14.69 8.04 14.19L15.16 18.34C15.11 18.55 15.08 18.77 15.08 19C15.08 20.61 16.39 21.91 18 21.91S20.92 20.61 20.92 19C20.92 17.39 19.61 16.08 18 16.08M18 4C18.55 4 19 4.45 19 5S18.55 6 18 6 17 5.55 17 5 17.45 4 18 4M6 13C5.45 13 5 12.55 5 12S5.45 11 6 11 7 11.45 7 12 6.55 13 6 13M18 20C17.45 20 17 19.55 17 19S17.45 18 18 18 19 18.45 19 19 18.55 20 18 20Z">
                                                        </path>
                                                    </svg>
                                                    99 Share
                                                </span>
                                            </a>
                                        </div>
                                    </div>
                                    <form class="comment-text d-flex align-items-center mt-3" action="javascript:void(0);">
                                        <input type="text" class="form-control rounded" placeholder="Lovely!">
                                        <div class="comment-attagement d-flex">
                                            <a href="javascript:void(0);" class="me-2 text-body">
                                                <svg class="icon-20" width="20" viewBox="0 0 24 24">
                                                    <path fill="currentColor"
                                                        d="M20,12A8,8 0 0,0 12,4A8,8 0 0,0 4,12A8,8 0 0,0 12,20A8,8 0 0,0 20,12M22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2A10,10 0 0,1 22,12M10,9.5C10,10.3 9.3,11 8.5,11C7.7,11 7,10.3 7,9.5C7,8.7 7.7,8 8.5,8C9.3,8 10,8.7 10,9.5M17,9.5C17,10.3 16.3,11 15.5,11C14.7,11 14,10.3 14,9.5C14,8.7 14.7,8 15.5,8C16.3,8 17,8.7 17,9.5M12,17.23C10.25,17.23 8.71,16.5 7.81,15.42L9.23,14C9.68,14.72 10.75,15.23 12,15.23C13.25,15.23 14.32,14.72 14.77,14L16.19,15.42C15.29,16.5 13.75,17.23 12,17.23Z">
                                                    </path>
                                                </svg>
                                            </a>
                                            <a href="javascript:void(0);" class="text-body">
                                                <svg class="icon-20" width="20" viewBox="0 0 24 24">
                                                    <path fill="currentColor"
                                                        d="M20,4H16.83L15,2H9L7.17,4H4A2,2 0 0,0 2,6V18A2,2 0 0,0 4,20H20A2,2 0 0,0 22,18V6A2,2 0 0,0 20,4M20,18H4V6H8.05L9.88,4H14.12L15.95,6H20V18M12,7A5,5 0 0,0 7,12A5,5 0 0,0 12,17A5,5 0 0,0 17,12A5,5 0 0,0 12,7M12,15A3,3 0 0,1 9,12A3,3 0 0,1 12,9A3,3 0 0,1 15,12A3,3 0 0,1 12,15Z">
                                                    </path>
                                                </svg>
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    <div id="profile-attendance" class="tab-pane active show" role="tabpanel">
                        <div class="card">
                            <div class="bg-white px-0">
                                <div class="p-3">
                                    <div class="text-center bg-body-secondary shadows-m rounded-4 py-3">
                                        <img src="{{ asset('assets/images/distance.png') }}" alt=""
                                            style="width: 30px; height: 30px;">
                                        <div class="fs-5">Total Distance</div>
                                        <h2 class="fw-bolder">{{$dm['total_distance']}} KM</h2>
                                    </div>
                                </div>
                                <hr>
                                <div class="m-3 table-responsive" style="overflow-x: auto;">
                                    <table class="table table-success table-striped mb-0">
                                        <thead style="position: sticky; top: 0; z-index: 1;">
                                            <tr class="text-nowrap text-center">
                                                <th style="position: sticky; left: 0; z-index: 2;">Date</th>
                                                <th>Check-In Meter</th>
                                                <th>Check-Out Meter</th>
                                                <th>Travelled</th>
                                                <th>Status</th>
                                                <th>Address(Check In)</th>
                                                <th>Address(Check Out)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($dm['attendences'] as $attendance)
                                            <tr class="text-nowrap">
                                                <td style="position: sticky; left: 0; z-index: 2;">{{$attendance['date']}}</td>
                                                <td>
                                                    <div class="d-flex justify-content-between " style="min-width: 160px;">
                                                        <div>{{$attendance['check_in']}}</div>
                                                        <div>{{$attendance['check_in_meter']}}</div>
                                                        <a href="javascirpt:void(0)" data-bs-toggle="modal" data-meter-image="{{$attendance['check_in_image']}}" data-bs-target="#meter-image">View</a>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-between" style="min-width: 160px;">
                                                        <div>{{$attendance['check_out']}}</div>
                                                        <div>{{$attendance['check_out_meter']}}</div>
                                                        <a href="javascirpt:void(0)" data-bs-toggle="modal" data-meter-image="{{$attendance['check_out_image']}}" data-bs-target="#meter-image">View</a>
                                                    </div>
                                                </td>
                                                <td>{{$attendance['distance']}}</td>
                                                <td>{{$attendance['status']}}</td>
                                                <td>{{$attendance['check_in_address']}}</td>
                                                <td>{{$attendance['check_out_address']}}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="profile-fuel" class="tab-pane fade" role="tabpanel">
                        <div class="card">
                            <div class="bg-white px-0">
                                <div class="pb-3">
                                    <div class="p-3">

                                        <hr>
                                        <div class="text-center bg-body-secondary shadows-m rounded-4 py-3">
                                            <img src="{{ asset('assets/images/fuel-credit.png') }}" alt=""
                                                style="width: 30px; height: 30px;">
                                            <div class="fs-5">Fuel Credit</div>
                                            <h2 class="fw-bolder">{{Helpers::format_currency($dm['fuel_balance'])}}</h2>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="m-3 table-responsive" style="overflow-x: auto;">
                                        <div class="order-body">
                                            <div class="pb-3">
                                                <div class="p-3 rounded">
                                                    @foreach ($dm['fuel_transactions'] as $data)
                                                        <div class="d-flex justify-content-between fw-bolder py-2 px-3 mt-2"
                                                            style="background-color:#ff810a36;">
                                                            <div>{{ \Carbon\Carbon::parse($data['date'])->format('M d, Y') }}</div>
                                                            <div>₹{{ number_format($data['total'], 2) }}</div>
                                                        </div>
                                                        @foreach ($data['transactions'] as $transaction)
                                                            <div class="d-flex justify-content-between align-item-center fs-6 px-3 py-2 border-bottom">
                                                                <div>
                                                                    <div><b>
                                                                        {{ $transaction->note }}
                                                                        </b></div>
                                                                    <div>{{ \Carbon\Carbon::parse($transaction->created_at)->format('M d, g:i A') }}</div>
                                                                </div>
                                                                <div class="align-self-center ">
                                                                    @if($transaction->type === 'add')
                                                                        <span class="text-success text-nowrap">+ ₹{{ number_format($transaction->amount, 2) }}</span>
                                                                    @else
                                                                        <span class="text-danger text-nowra">- ₹{{ number_format($transaction->amount, 2) }}</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="profile-cash" class="tab-pane fade" role="tabpanel">
                        <div class="card">
                            <div class="p-3">
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex">
                                        <div class="fw-bolder"><i class="feather-refresh-cw me-2"></i></div>
                                        <div class="fw-bolder"><i class="feather-download"></i></div>
                                    </div>
                                </div>
                                <hr>
                                <div class="text-center">
                                    <div>Received since last settlement</div>
                                    <h2 class="fw-bolder">₹ {{$dm['cash_histories']['cashInHand']}}</h2>
                                </div>
                            </div>
                            <ul class="nav nav-tabs w-100 flex-nowrap custom-tabs border-0 bg-white rounded justify-content-around"
                                id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link border-0 text-dark py-3 d-lg-flex justify-content-center text-center active"
                                        id="transactions-tab" href="#transactions" role="tab" aria-controls="transactions"
                                        aria-selected="true" data-bs-toggle="tab">
                                        <span><b>Transactions</b></span>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link border-0 text-dark py-3 d-lg-flex justify-content-center text-center"
                                        id="settlements-tab" href="#settlements" role="tab" aria-controls="settlements"
                                        aria-selected="false" data-bs-toggle="tab">
                                        <span><b>Settlements</b></span>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content border-top" id="myTabContent">
                                <div class="tab-pane fade show active" id="transactions" role="tabpanel"
                                    aria-labelledby="transactions-tab">
                                    <div class="order-body">
                                        <div class="pb-3">
                                            <div class="p-3 rounded">
                                                @foreach ($dm['cash_histories']['formattedDataTxns']  as $data)
                                                    <div class="d-flex justify-content-between fw-bolder py-2 px-3 mt-2"
                                                        style="background-color:#ff810a36;">
                                                        <div>{{ \Carbon\Carbon::parse($data['date'])->format('M d, Y') }}</div>
                                                        <div>₹{{ number_format($data['total'], 2) }}</div>
                                                    </div>

                                                    @foreach ($data['transactions'] as $transaction)
                                                        <div class="d-flex justify-content-between align-item-center fs-6 px-3 py-2 border-bottom">
                                                            <div>
                                                                <div><b>
                                                                        @if($transaction->txn_type === 'received')
                                                                            {{ $transaction->remarks }}
                                                                        @else
                                                                            Paid to {{ $transaction->paid_to }}
                                                                        @endif
                                                                    </b></div>
                                                                <div>{{ \Carbon\Carbon::parse($transaction->created_at)->format('M d, g:i A') }}</div>
                                                            </div>
                                                            <div class="align-self-center ">
                                                                @if($transaction->txn_type === 'received')
                                                                    <span class="text-success text-nowrap">+ ₹{{ number_format($transaction->amount, 2) }}</span>
                                                                @else
                                                                    <span class="text-danger text-nowra">- ₹{{ number_format($transaction->amount, 2) }}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="settlements" role="tabpanel" aria-labelledby="settlements-tab">
                                    <div class="order-body">
                                        <div class="pb-3">
                                            <div class="p-3 rounded">
                                                @foreach ($dm['cash_histories']['fomattedSettlementTxns']  as $data)
                                                    <div class="d-flex justify-content-between fw-bolder py-2 px-3 mt-2"
                                                        style="background-color:#ff810a36;">
                                                        <div>{{ \Carbon\Carbon::parse($data['date'])->format('M d, Y') }}</div>
                                                        <div>₹{{ number_format($data['total'], 2) }}</div>
                                                    </div>

                                                    @foreach ($data['transactions'] as $transaction)
                                                        <div class="d-flex justify-content-between align-item-center fs-6 px-3 py-2 border-bottom">
                                                            <div>
                                                                <div><b>
                                                                        @if($transaction['txn_type'] === 'received')
                                                                            {{ $transaction['remarks'] }}
                                                                        @else
                                                                            Paid to {{ $transaction['paid_to'] }}
                                                                        @endif
                                                                    </b></div>
                                                                <div>{{ \Carbon\Carbon::parse($transaction['created_at'])->format('M d, g:i A') }}</div>
                                                            </div>
                                                            <div class="align-self-center ">
                                                                @if($transaction['txn_type'] === 'received')
                                                                    <span class="text-success text-nowrap">+ ₹{{ number_format($transaction['amount'], 2) }}</span>
                                                                @else
                                                                    <span class="text-danger text-nowra">- ₹{{ number_format($transaction['amount'], 2) }}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="profile-wallet" class="tab-pane fade" role="tabpanel">
                        <div class="card">
                            <div class="bg-white px-0">
                                <div class="p-3">
                                    <div class="d-flex justify-content-between">
                                        <div class="d-flex">
                                            <div class="fw-bolder"><i class="feather-download"></i></div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="text-center">
                                        <div>Remaining since last settlement</div>
                                        <h2 class="fw-bolder">₹ {{$dm['wallet_histories']['balance']}}</h2>
                                    </div>
                                </div>
                                @if((!is_string($dm['wallet_histories']['formattedData'])) && (!is_int($dm['wallet_histories']['formattedData'])))
                                    @foreach ($dm['wallet_histories']['formattedData'] as $data)
                                        <div class="d-flex justify-content-between fw-bolder py-2 px-3 mt-2" style="background-color:#ff810a36;">
                                            <div>{{ \Carbon\Carbon::parse($data['date'])->format('M d, Y') }}</div>
                                            <div>₹{{ number_format($data['total'], 2) }}</div>
                                        </div>

                                        @foreach ($data['transactions'] as $transaction)
                                            <div class="d-flex justify-content-between align-item-center fs-6 px-3 py-2 border-bottom">
                                                <div>
                                                    <div><b>{{ $transaction->remarks ?? 'N/A' }}</b></div>
                                                    <div>{{ \Carbon\Carbon::parse($transaction->created_at)->format('M d, g:i A') }}</div>
                                                </div>
                                                <div class="align-self-center">
                                                    @if($transaction->type === 'received')
                                                        <span class="text-success">+ ₹{{ number_format($transaction->amount, 2) }}</span>
                                                    @else
                                                        <span class="text-danger">- ₹{{ number_format($transaction->amount, 2) }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                    {{-- <div id="profile-friends" class="tab-pane fade" role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <div class="header-title">
                                    <h4 class="card-title">Friends</h4>
                                </div>
                            </div>
                            <div class="card-body">
                                <ul class="list-inline m-0 p-0">
                                    <li class="d-flex mb-4 align-items-center">
                                        <img src="../../assets/images/avatars/01.png" alt="story-img" class="rounded-pill avatar-40">
                                        <div class="ms-3 flex-grow-1">
                                            <h6>Paul Molive</h6>
                                            <p class="mb-0">Web Designer</p>
                                        </div>
                                        <div class="dropdown">
                                            <span class="dropdown-toggle" id="dropdownMenuButton9" data-bs-toggle="dropdown" aria-expanded="false" role="button">
                                            </span>
                                            <div class="dropdown-menu dropdown-menu-end custom-dropdown-menu-friends" aria-labelledby="dropdownMenuButton9">
                                                <a class="dropdown-item " href="javascript:void(0);">Unfollow</a>
                                                <a class="dropdown-item " href="javascript:void(0);">Unfriend</a>
                                                <a class="dropdown-item " href="javascript:void(0);">Block</a>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="d-flex mb-4 align-items-center">
                                        <img src="../../assets/images/avatars/05.png" alt="story-img" class="rounded-pill avatar-40">
                                        <div class="ms-3 flex-grow-1">
                                            <h6>Paul Molive</h6>
                                            <p class="mb-0">trainee</p>
                                        </div>
                                        <div class="dropdown">
                                            <span class="dropdown-toggle" id="dropdownMenuButton10" data-bs-toggle="dropdown" aria-expanded="false" role="button">
                                            </span>
                                            <div class="dropdown-menu dropdown-menu-end custom-dropdown-menu-friends" aria-labelledby="dropdownMenuButton10">
                                                <a class="dropdown-item " href="javascript:void(0);">Unfollow</a>
                                                <a class="dropdown-item " href="javascript:void(0);">Unfriend</a>
                                                <a class="dropdown-item " href="javascript:void(0);">Block</a>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="d-flex mb-4 align-items-center">
                                        <img src="../../assets/images/avatars/02.png" alt="story-img" class="rounded-pill avatar-40">
                                        <div class="ms-3 flex-grow-1">
                                            <h6>Anna Mull</h6>
                                            <p class="mb-0">Web Developer</p>
                                        </div>
                                        <div class="dropdown">
                                            <span class="dropdown-toggle" id="dropdownMenuButton11" data-bs-toggle="dropdown" aria-expanded="false" role="button">
                                            </span>
                                            <div class="dropdown-menu dropdown-menu-end custom-dropdown-menu-friends" aria-labelledby="dropdownMenuButton11">
                                                <a class="dropdown-item " href="javascript:void(0);">Unfollow</a>
                                                <a class="dropdown-item " href="javascript:void(0);">Unfriend</a>
                                                <a class="dropdown-item " href="javascript:void(0);">Block</a>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="d-flex mb-4 align-items-center">
                                        <img src="../../assets/images/avatars/03.png" alt="story-img" class="rounded-pill avatar-40">
                                        <div class="ms-3 flex-grow-1">
                                            <h6>Paige Turner</h6>
                                            <p class="mb-0">trainee</p>
                                        </div>
                                        <div class="dropdown">
                                            <span class="dropdown-toggle" id="dropdownMenuButton12" data-bs-toggle="dropdown" aria-expanded="false" role="button">
                                            </span>
                                            <div class="dropdown-menu dropdown-menu-end custom-dropdown-menu-friends" aria-labelledby="dropdownMenuButton12">
                                                <a class="dropdown-item " href="javascript:void(0);">Unfollow</a>
                                                <a class="dropdown-item " href="javascript:void(0);">Unfriend</a>
                                                <a class="dropdown-item " href="javascript:void(0);">Block</a>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="d-flex mb-4 align-items-center">
                                        <img src="../../assets/images/avatars/04.png" alt="story-img" class="rounded-pill avatar-40">
                                        <div class="ms-3 flex-grow-1">
                                            <h6>Barb Ackue</h6>
                                            <p class="mb-0">Web Designer</p>
                                        </div>
                                        <div class="dropdown">
                                            <span class="dropdown-toggle" id="dropdownMenuButton13" data-bs-toggle="dropdown" aria-expanded="false" role="button">
                                            </span>
                                            <div class="dropdown-menu dropdown-menu-end custom-dropdown-menu-friends" aria-labelledby="dropdownMenuButton13">
                                                <a class="dropdown-item " href="javascript:void(0);">Unfollow</a>
                                                <a class="dropdown-item " href="javascript:void(0);">Unfriend</a>
                                                <a class="dropdown-item " href="javascript:void(0);">Block</a>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="d-flex mb-4 align-items-center">
                                        <img src="../../assets/images/avatars/05.png" alt="story-img" class="rounded-pill avatar-40">
                                        <div class="ms-3 flex-grow-1">
                                            <h6>Greta Life</h6>
                                            <p class="mb-0">Tester</p>
                                        </div>
                                        <div class="dropdown">
                                            <span class="dropdown-toggle" id="dropdownMenuButton14" data-bs-toggle="dropdown" aria-expanded="false" role="button">
                                            </span>
                                            <div class="dropdown-menu dropdown-menu-end custom-dropdown-menu-friends" aria-labelledby="dropdownMenuButton14">
                                                <a class="dropdown-item " href="javascript:void(0);">Unfollow</a>
                                                <a class="dropdown-item " href="javascript:void(0);">Unfriend</a>
                                                <a class="dropdown-item " href="javascript:void(0);">Block</a>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="d-flex mb-4 align-items-center">
                                        <img src="../../assets/images/avatars/03.png" alt="story-img" class="rounded-pill avatar-40">
                                        <div class="ms-3 flex-grow-1">
                                            <h6>Ira Membrit</h6>
                                            <p class="mb-0">Android Developer</p>
                                        </div>
                                        <div class="dropdown">
                                            <span class="dropdown-toggle" id="dropdownMenuButton15" data-bs-toggle="dropdown" aria-expanded="false" role="button">
                                            </span>
                                            <div class="dropdown-menu dropdown-menu-end custom-dropdown-menu-friends" aria-labelledby="dropdownMenuButton15">
                                                <a class="dropdown-item " href="javascript:void(0);">Unfollow</a>
                                                <a class="dropdown-item " href="javascript:void(0);">Unfriend</a>
                                                <a class="dropdown-item " href="javascript:void(0);">Block</a>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="d-flex mb-4 align-items-center">
                                        <img src="../../assets/images/avatars/02.png" alt="story-img" class="rounded-pill avatar-40">
                                        <div class="ms-3 flex-grow-1">
                                            <h6>Pete Sariya</h6>
                                            <p class="mb-0">Web Designer</p>
                                        </div>
                                        <div class="dropdown">
                                            <span class="dropdown-toggle" id="dropdownMenuButton16" data-bs-toggle="dropdown" aria-expanded="false" role="button">
                                            </span>
                                            <div class="dropdown-menu dropdown-menu-end custom-dropdown-menu-friends" aria-labelledby="dropdownMenuButton16">
                                                <a class="dropdown-item " href="javascript:void(0);">Unfollow</a>
                                                <a class="dropdown-item " href="javascript:void(0);">Unfriend</a>
                                                <a class="dropdown-item " href="javascript:void(0);">Block</a>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div id="profile-profile" class="tab-pane fade" role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <div class="header-title">
                                    <h4 class="card-title">Profile</h4>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="text-center">
                                    <div class="user-profile">
                                        <img src="../../assets/images/avatars/01.png" alt="profile-img" class="rounded-pill avatar-130 img-fluid">
                                    </div>
                                    <div class="mt-3">
                                        <h3 class="d-inline-block">Austin Robertson</h3>
                                        <p class="d-inline-block pl-3"> - Web developer</p>
                                        <p class="mb-0">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <div class="header-title">
                                    <h4 class="card-title">About User</h4>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="user-bio">
                                    <p>Tart I love sugar plum I love oat cake. Sweet roll caramels I love jujubes. Topping cake wafer.</p>
                                </div>
                                <div class="mt-2">
                                    <h6 class="mb-1">Joined:</h6>
                                    <p>Feb 15, 2021</p>
                                </div>
                                <div class="mt-2">
                                    <h6 class="mb-1">Lives:</h6>
                                    <p>United States of America</p>
                                </div>
                                <div class="mt-2">
                                    <h6 class="mb-1">Email:</h6>
                                    <p><a href="#" class="text-body"> austin@gmail.com</a></p>
                                </div>
                                <div class="mt-2">
                                    <h6 class="mb-1">Url:</h6>
                                    <p><a href="#" class="text-body" target="_blank"> www.bootstrap.com </a></p>
                                </div>
                                <div class="mt-2">
                                    <h6 class="mb-1">Contact:</h6>
                                    <p><a href="#" class="text-body">(001) 4544 565 456</a></p>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-header">
                        <div class="header-title">
                            <h4 class="card-title">About</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        {{-- <p>Lorem ipsum dolor sit amet, contur adipiscing elit.</p> --}}
                        <div class="mb-1">Email: <a href="javascript:void(0)" class="ms-3">{{ $dm['email'] }}</a></div>
                        <div class="mb-1">Phone: <a href="javascript:void(0)" class="ms-3">{{ $dm['phone'] }}</a></div>
                        <div class="mb-1">Status: <a href="javascript:void(0)" class="ms-3">{{ $dm['is_online']? 'Online' : 'Offline'}}</a></div>
                        <div class="mb-1">Live Orders: <a href="javascript:void(0)" class="ms-3">{{ $dm['live_orders']}}</a></div>
                        <div class="mb-1">Delivered Orders: <a href="javascript:void(0)" class="ms-3">{{ $dm['delivered_orders']}}</a></div>

                        {{-- <div>Location: <span class="ms-3">USA</span></div> --}}
                        {{-- @dd($dm) --}}
                    </div>
                </div>
                <div class="card d-none">
                    <div class="card-header">
                        <div class="header-title">
                            <h4 class="card-title">Stories</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="list-inline m-0 p-0 ">
                            <li class="d-flex mb-4 align-items-center active">
                                <img src="../../assets/images/icons/06.png" alt="story-img" class="rounded-pill avatar-70 p-1 profile-story-img border  bg-light-subtle img-fluid">
                                <div class="ms-3">
                                    <h5>Web Design</h5>
                                    <p class="mb-0">1 hour ago</p>
                                </div>
                            </li>
                            <li class="d-flex mb-4 align-items-center">
                                <img src="../../assets/images/icons/03.png" alt="story-img" class="rounded-pill avatar-70 p-1 border  img-fluid bg-danger-subtle">
                                <div class="ms-3">
                                    <h5>App Design</h5>
                                    <p class="mb-0">4 hour ago</p>
                                </div>
                            </li>
                            <li class="d-flex align-items-center">
                                <img src="../../assets/images/icons/07.png" alt="story-img" class="rounded-pill avatar-70 p-1 border bg-primary-subtle img-fluid">
                                <div class="ms-3">
                                    <h5>Abstract Design</h5>
                                    <p class="mb-0">9 hour ago</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card d-none">
                    <div class="card-header">
                        <div class="header-title">
                            <h4 class="card-title">Suggestions</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="list-inline m-0 p-0">
                            <li class="d-flex mb-4 align-items-center">
                                <div class="img-fluid bg-warning-subtle rounded-pill"><img src="../../assets/images/icons/05.png" alt="story-img" class="rounded-pill avatar-40"></div>
                                <div class="ms-3 flex-grow-1">
                                    <h6>Paul Molive</h6>
                                    <p class="mb-0">4 mutual friends</p>
                                </div>
                                <a href="javascript:void(0);" class="btn btn-outline-primary rounded-circle btn-icon btn-sm p-2">
                                    <span class="btn-inner">
                                        <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M9.87651 15.2063C6.03251 15.2063 2.74951 15.7873 2.74951 18.1153C2.74951 20.4433 6.01251 21.0453 9.87651 21.0453C13.7215 21.0453 17.0035 20.4633 17.0035 18.1363C17.0035 15.8093 13.7415 15.2063 9.87651 15.2063Z"
                                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M9.8766 11.886C12.3996 11.886 14.4446 9.841 14.4446 7.318C14.4446 4.795 12.3996 2.75 9.8766 2.75C7.3546 2.75 5.3096 4.795 5.3096 7.318C5.3006 9.832 7.3306 11.877 9.8456 11.886H9.8766Z"
                                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M19.2036 8.66919V12.6792" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M21.2497 10.6741H17.1597" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </span>
                                </a>
                            </li>
                            <li class="d-flex mb-4 align-items-center">
                                <div class="img-fluid bg-danger-subtle rounded-pill"><img src="../../assets/images/icons/03.png" alt="story-img" class="rounded-pill avatar-40"></div>
                                <div class="ms-3 flex-grow-1">
                                    <h6>Robert Fox</h6>
                                    <p class="mb-0">4 mutual friends</p>
                                </div>
                                <a href="javascript:void(0);" class="btn btn-outline-primary rounded-circle btn-icon btn-sm p-2">
                                    <span class="btn-inner">
                                        <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M9.87651 15.2063C6.03251 15.2063 2.74951 15.7873 2.74951 18.1153C2.74951 20.4433 6.01251 21.0453 9.87651 21.0453C13.7215 21.0453 17.0035 20.4633 17.0035 18.1363C17.0035 15.8093 13.7415 15.2063 9.87651 15.2063Z"
                                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M9.8766 11.886C12.3996 11.886 14.4446 9.841 14.4446 7.318C14.4446 4.795 12.3996 2.75 9.8766 2.75C7.3546 2.75 5.3096 4.795 5.3096 7.318C5.3006 9.832 7.3306 11.877 9.8456 11.886H9.8766Z"
                                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M19.2036 8.66919V12.6792" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M21.2497 10.6741H17.1597" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </span>
                                </a>
                            </li>
                            <li class="d-flex mb-4 align-items-center">
                                <div class="img-fluid  bg-dark-subtle  rounded-pill"><img src="../../assets/images/icons/06.png" alt="story-img" class="rounded-pill  profile-story-img avatar-40"></div>
                                <div class="ms-3 flex-grow-1">
                                    <h6>Jenny Wilson</h6>
                                    <p class="mb-0">6 mutual friends</p>
                                </div>
                                <a href="javascript:void(0);" class="btn btn-outline-primary rounded-circle btn-icon btn-sm p-2">
                                    <span class="btn-inner">
                                        <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M9.87651 15.2063C6.03251 15.2063 2.74951 15.7873 2.74951 18.1153C2.74951 20.4433 6.01251 21.0453 9.87651 21.0453C13.7215 21.0453 17.0035 20.4633 17.0035 18.1363C17.0035 15.8093 13.7415 15.2063 9.87651 15.2063Z"
                                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M9.8766 11.886C12.3996 11.886 14.4446 9.841 14.4446 7.318C14.4446 4.795 12.3996 2.75 9.8766 2.75C7.3546 2.75 5.3096 4.795 5.3096 7.318C5.3006 9.832 7.3306 11.877 9.8456 11.886H9.8766Z"
                                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M19.2036 8.66919V12.6792" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M21.2497 10.6741H17.1597" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </span>
                                </a>
                            </li>
                            <li class="d-flex mb-4 align-items-center">
                                <div class="img-fluid bg-primary-subtle rounded-pill"><img src="../../assets/images/icons/07.png" alt="story-img" class="rounded-pill avatar-40"></div>
                                <div class="ms-3 flex-grow-1">
                                    <h6>Cody Fisher</h6>
                                    <p class="mb-0">8 mutual friends</p>
                                </div>
                                <a href="javascript:void(0);" class="btn btn-outline-primary rounded-circle btn-icon btn-sm p-2">
                                    <span class="btn-inner">
                                        <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M9.87651 15.2063C6.03251 15.2063 2.74951 15.7873 2.74951 18.1153C2.74951 20.4433 6.01251 21.0453 9.87651 21.0453C13.7215 21.0453 17.0035 20.4633 17.0035 18.1363C17.0035 15.8093 13.7415 15.2063 9.87651 15.2063Z"
                                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M9.8766 11.886C12.3996 11.886 14.4446 9.841 14.4446 7.318C14.4446 4.795 12.3996 2.75 9.8766 2.75C7.3546 2.75 5.3096 4.795 5.3096 7.318C5.3006 9.832 7.3306 11.877 9.8456 11.886H9.8766Z"
                                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M19.2036 8.66919V12.6792" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M21.2497 10.6741H17.1597" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </span>
                                </a>
                            </li>
                            <li class="d-flex mb-4 align-items-center">
                                <div class="img-fluid bg-info-subtle rounded-pill"><img src="../../assets/images/icons/04.png" alt="story-img" class="rounded-pill avatar-40"></div>
                                <div class="ms-3 flex-grow-1">
                                    <h6>Bessie Cooper</h6>
                                    <p class="mb-0">1 mutual friends</p>
                                </div>
                                <a href="javascript:void(0);" class="btn btn-outline-primary rounded-circle btn-icon btn-sm p-2">
                                    <span class="btn-inner">
                                        <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M9.87651 15.2063C6.03251 15.2063 2.74951 15.7873 2.74951 18.1153C2.74951 20.4433 6.01251 21.0453 9.87651 21.0453C13.7215 21.0453 17.0035 20.4633 17.0035 18.1363C17.0035 15.8093 13.7415 15.2063 9.87651 15.2063Z"
                                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M9.8766 11.886C12.3996 11.886 14.4446 9.841 14.4446 7.318C14.4446 4.795 12.3996 2.75 9.8766 2.75C7.3546 2.75 5.3096 4.795 5.3096 7.318C5.3006 9.832 7.3306 11.877 9.8456 11.886H9.8766Z"
                                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M19.2036 8.66919V12.6792" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M21.2497 10.6741H17.1597" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </span>
                                </a>
                            </li>
                            <li class="d-flex mb-4 align-items-center">
                                <div class="img-fluid bg-warning-subtle rounded-pill"><img src="../../assets/images/icons/02.png" alt="story-img" class="rounded-pill avatar-40"></div>
                                <div class="ms-3 flex-grow-1">
                                    <h6>Wade Warren</h6>
                                    <p class="mb-0">3 mutual friends</p>
                                </div>
                                <a href="javascript:void(0);" class="btn btn-outline-primary rounded-circle btn-icon btn-sm p-2">
                                    <span class="btn-inner">
                                        <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M9.87651 15.2063C6.03251 15.2063 2.74951 15.7873 2.74951 18.1153C2.74951 20.4433 6.01251 21.0453 9.87651 21.0453C13.7215 21.0453 17.0035 20.4633 17.0035 18.1363C17.0035 15.8093 13.7415 15.2063 9.87651 15.2063Z"
                                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M9.8766 11.886C12.3996 11.886 14.4446 9.841 14.4446 7.318C14.4446 4.795 12.3996 2.75 9.8766 2.75C7.3546 2.75 5.3096 4.795 5.3096 7.318C5.3006 9.832 7.3306 11.877 9.8456 11.886H9.8766Z"
                                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M19.2036 8.66919V12.6792" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M21.2497 10.6741H17.1597" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </span>
                                </a>
                            </li>
                            <li class="d-flex mb-4 align-items-center">
                                <div class="img-fluid  bg-success-subtle rounded-pill"><img src="../../assets/images/icons/01.png" alt="story-img" class="rounded-pill avatar-40"></div>
                                <div class="ms-3 flex-grow-1">
                                    <h6>Guy Hawkins</h6>
                                    <p class="mb-0">12 mutual friends</p>
                                </div>
                                <a href="javascript:void(0);" class="btn btn-outline-primary rounded-circle btn-icon btn-sm p-2">
                                    <span class="btn-inner">
                                        <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M9.87651 15.2063C6.03251 15.2063 2.74951 15.7873 2.74951 18.1153C2.74951 20.4433 6.01251 21.0453 9.87651 21.0453C13.7215 21.0453 17.0035 20.4633 17.0035 18.1363C17.0035 15.8093 13.7415 15.2063 9.87651 15.2063Z"
                                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M9.8766 11.886C12.3996 11.886 14.4446 9.841 14.4446 7.318C14.4446 4.795 12.3996 2.75 9.8766 2.75C7.3546 2.75 5.3096 4.795 5.3096 7.318C5.3006 9.832 7.3306 11.877 9.8456 11.886H9.8766Z"
                                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M19.2036 8.66919V12.6792" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M21.2497 10.6741H17.1597" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </span>
                                </a>
                            </li>
                            <li class="d-flex align-items-center">
                                <div class="img-fluid bg-info-subtle rounded-pill"><img src="../../assets/images/icons/08.png" alt="story-img" class="rounded-pill avatar-40"></div>
                                <div class="ms-3 flex-grow-1">
                                    <h6>Floyd Miles</h6>
                                    <p class="mb-0">2 mutual friends</p>
                                </div>
                                <a href="javascript:void(0);" class="btn btn-outline-primary rounded-circle btn-icon btn-sm p-2">
                                    <span class="btn-inner">
                                        <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M9.87651 15.2063C6.03251 15.2063 2.74951 15.7873 2.74951 18.1153C2.74951 20.4433 6.01251 21.0453 9.87651 21.0453C13.7215 21.0453 17.0035 20.4633 17.0035 18.1363C17.0035 15.8093 13.7415 15.2063 9.87651 15.2063Z"
                                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M9.8766 11.886C12.3996 11.886 14.4446 9.841 14.4446 7.318C14.4446 4.795 12.3996 2.75 9.8766 2.75C7.3546 2.75 5.3096 4.795 5.3096 7.318C5.3006 9.832 7.3306 11.877 9.8456 11.886H9.8766Z"
                                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M19.2036 8.66919V12.6792" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M21.2497 10.6741H17.1597" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="offcanvas offcanvas-bottom share-offcanvas" tabindex="-1" id="share-btn" aria-labelledby="shareBottomLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="shareBottomLabel">Share</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body small">
                <div class="d-flex flex-wrap align-items-center">
                    <div class="text-center me-3 mb-3">
                        <img src="../../assets/images/brands/08.png" class="img-fluid rounded mb-2" alt="">
                        <h6>Facebook</h6>
                    </div>
                    <div class="text-center me-3 mb-3">
                        <img src="../../assets/images/brands/09.png" class="img-fluid rounded mb-2" alt="">
                        <h6>Twitter</h6>
                    </div>
                    <div class="text-center me-3 mb-3">
                        <img src="../../assets/images/brands/10.png" class="img-fluid rounded mb-2" alt="">
                        <h6>Instagram</h6>
                    </div>
                    <div class="text-center me-3 mb-3">
                        <img src="../../assets/images/brands/11.png" class="img-fluid rounded mb-2" alt="">
                        <h6>Google Plus</h6>
                    </div>
                    <div class="text-center me-3 mb-3">
                        <img src="../../assets/images/brands/13.png" class="img-fluid rounded mb-2" alt="">
                        <h6>In</h6>
                    </div>
                    <div class="text-center me-3 mb-3">
                        <img src="../../assets/images/brands/12.png" class="img-fluid rounded mb-2" alt="">
                        <h6>YouTube</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL --}}

    <div class="modal fade" id="meter-image" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bolder" id="exampleModalLongTitle">Check In Image</h5>
                    <button type="button" class="close border-0 bg-transparent" data-bs-dismiss="modal"
                        aria-label="Close">
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
{{-- <script src="{{asset('assets/vendor/flatpickr/dist/flatpickr.min.js')}}"></script> --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
      const modalImage = document.getElementById('profile-pic');

      document.querySelectorAll('[data-bs-toggle="modal"]').forEach(function (button) {
          button.addEventListener('click', function () {
              const imageUrl = this.getAttribute('data-meter-image');
              modalImage.src = imageUrl;
          });
      });
  });

  // Password visibility toggle function
  function togglePasswordVisibility(fieldId) {
      const field = document.getElementById(fieldId);
      const eye = document.getElementById(fieldId + '_eye');
      
      if (field.type === 'password') {
          field.type = 'text';
          eye.classList.remove('fa-eye');
          eye.classList.add('fa-eye-slash');
      } else {
          field.type = 'password';
          eye.classList.remove('fa-eye-slash');
          eye.classList.add('fa-eye');
      }
  }

  // Password validation function
  function validatePasswordForm() {
      const newPassword = document.getElementById('new_password').value;
      const confirmPassword = document.getElementById('confirm_password').value;
      const messageDiv = document.getElementById('password_match_message');
      
      if (newPassword !== confirmPassword) {
          messageDiv.innerHTML = '<small class="text-danger">Passwords do not match!</small>';
          return false;
      }
      
      if (newPassword.length < 6) {
          messageDiv.innerHTML = '<small class="text-danger">Password must be at least 6 characters long!</small>';
          return false;
      }
      
      messageDiv.innerHTML = '';
      return true;
  }

  // Real-time password matching feedback
  document.getElementById('confirm_password').addEventListener('input', function() {
      const newPassword = document.getElementById('new_password').value;
      const confirmPassword = this.value;
      const messageDiv = document.getElementById('password_match_message');
      
      if (confirmPassword.length > 0) {
          if (newPassword === confirmPassword) {
              messageDiv.innerHTML = '<small class="text-success">✓ Passwords match</small>';
          } else {
              messageDiv.innerHTML = '<small class="text-danger">✗ Passwords do not match</small>';
          }
      } else {
          messageDiv.innerHTML = '';
      }
  });
</script>
@endpush


