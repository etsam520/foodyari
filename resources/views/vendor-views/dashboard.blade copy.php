@extends('vendor-views.layouts.dashboard-main')
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/flatpickr/dist/flatpickr.min.css') }}">
    <style>
        . .card-slie-arrow {
            left: 35px;
            right: 0px;
            width: 35px;
            height: 35px;
            position: absolute;
            top: 35px;
            -o-object-fit: cover;
            object-fit: cover;
        }

        . {
            text-align: center;
            font-size: 18px;
            background: #fff;
        }

        .swiper . {
            height: 300px;
            line-height: 300px;
        }
    </style>
@endpush
@section('content')
    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div class="row">
            <!-- filter -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                            <p class="mb-md-0 mb-2 d-flex align-items-center">
                                <svg width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="me-2 icon-20">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M4.56517 3C3.70108 3 3 3.71286 3 4.5904V5.52644C3 6.17647 3.24719 6.80158 3.68936 7.27177L8.5351 12.4243L8.53723 12.4211C9.47271 13.3788 9.99905 14.6734 9.99905 16.0233V20.5952C9.99905 20.9007 10.3187 21.0957 10.584 20.9516L13.3436 19.4479C13.7602 19.2204 14.0201 18.7784 14.0201 18.2984V16.0114C14.0201 14.6691 14.539 13.3799 15.466 12.4243L20.3117 7.27177C20.7528 6.80158 21 6.17647 21 5.52644V4.5904C21 3.71286 20.3 3 19.4359 3H4.56517Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                Filter  ::  {{Str::ucfirst($filter)}}
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

                                           @if($filter == 'today')
                                           Today :
                                           @elseif ($filter == 'this_week')
                                           Week:
                                           @elseif ($filter == 'this_month')
                                           Month :
                                           @elseif ($filter == 'this_year')
                                           Year
                                           @elseif ($filter == 'previous_year')
                                            Previous Year
                                            @else
                                            Select
                                           @endif
                                    </span>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton22" style="min-width:275px;">
                                       <li><a class="dropdown-item" href="{!! route('vendor.dashboard').'?filter=this_week' !!}">This Week</a></li>
                                       <li><a class="dropdown-item" href="{!! route('vendor.dashboard').'?filter=this_month' !!}">This Month</a></li>
                                       <li><a class="dropdown-item" href="{!! route('vendor.dashboard').'?filter=this_year' !!}">This Year</a></li>
                                       <li><a class="dropdown-item" href="{!! route('vendor.dashboard').'?filter=previous_year' !!}">Previous Year</a></li>
                                       <li><a class="dropdown-item" href="{!! route('vendor.dashboard').'?filter=today' !!}">Today</a></li>
                                       <li><a class="dropdown-item" href="javascript:void(0)">
                                        <form action="">
                                        <div class="m-0 d-flex flex-column align-items-center justify-content-center">

                                                <input type="text" name="date_range" class="form-control range_flatpicker d-flex flatpickr-input active" placeholder="Date Range" readonly="readonly" required>
                                                <input type="hidden" name="filter" value="custom">
                                                <button class="badge rounded-pill bg-success ms-1 mb-1 px-3 py-2" type="submit">Go</button>
                                            </div>
                                        </form>

                                        </a></li>
                                    </ul>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- filter end -->


            <div class="col-md-12 col-lg-12">
                <div class="row">
                    <div class="col-md-7">
                        <div class="row">
                            <div class="col-6 ">
                                <div class="card" style="box-shadow: 0 0.5rem 1rem rgb(0 0 0 / 15%), inset 5px 8px 8px rgba(0, 0, 0, .2), inset -2px -2px 10px hsla(0, 0%, 100%, .2) !important;
                                    background: linear-gradient(230deg, #00b7ff00, var(--bs-primary));">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between">
                                            {{-- <h6 class="text-pink mb-0">Open Projects 05</h6> --}}
                                            <div class="progress-widget">

                                                <div class="progress-detail">
                                                    <h4 class="counter text-white mb-2">{{ $count['totalOrders'] }}</h4>
                                                    <p class="text-white mb-0">Orders</p>
                                                </div>
                                            </div>
                                            <div style="border:3px dashed  var(--bs-primary);border-radius:50%;padding:5px;background-color: #fff;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="50" fill-rule="evenodd" clip-rule="evenodd" text-rendering="geometricPrecision" viewBox="0 0 2048 2048">
                                                    <path fill="none" d="M0 0h2048v2048H0z" />
                                                    <path fill="none" d="M255.999 255.999h1536v1536h-1536z" />
                                                    <path fill="none" d="M256 255.999h1536v1536H256z" />
                                                    <path fill="var(--bs-primary)"
                                                        d="M1783.77 501.991c-8.633-14.418-22.84-24.522-39.23-27.81l-870.058-209.76c-28.676-5.564-56.966 10.281-66.887 37.686L475.96 1144.612l110.708 40.342L900.95 383.877l755.976 192.9c-47.213 146.36-159.552 451.86-267.253 736.308-93.883 248.175-147.913 333.16-250.611 350.512-.114-.123-.227-.228-.227-.41-209.875 29.411-206.116-238.14-206.116-238.14l-670.28-270.759c-4.461 28.3-6.468 54.592-6.442 78.991.28 235.896 190.786 295.536 190.786 295.536l490.755 222.793c.105.07 76.722 36.564 159.98 32.804 224.59-5.276 303.51-168.35 402.405-429.618 142.872-377.593 287.875-800.656 289.295-805.143 5.11-16.012 3.093-33.286-5.45-47.659zm-423.72 406.944c5.11 1.394 10.403 2.139 15.626 2.139 25.846 0 49.492-17.24 56.739-43.374 8.615-31.42-9.99-63.864-41.35-72.418l-255.36-78.816c-31.006-8.58-63.819 9.85-72.39 41.244-8.545 31.402 9.877 63.847 41.367 72.444l255.369 78.781zM929.276 647.747c36.073 0 65.354 28.466 65.354 63.53 0 35.084-29.28 63.514-65.354 63.514-36.1 0-65.362-28.422-65.362-63.514 0-35.064 29.263-63.53 65.362-63.53zm-109.745 566.609c-25.24-10.08-54.25 2.27-64.311 27.484-10.132 25.355 2.393 54.285 27.633 64.363 25.241 10.08 54.102-2.33 64.224-27.685 10.07-25.215-2.305-54.092-27.546-64.162zm8.914-422.607l-18.37 45.995 45.993 18.36 12.955 207.517-54.215 42.34c-4.97 5.942-11 14.189-13.725 21.016-10.123 25.354 2.392 54.294 27.633 64.372l275.561 110.034 18.37-46.003-266.377-106.362c-2.357-.946-3.698-4.075-2.76-6.432l.894-2.235 36.23-30.771 170.033 67.896c18.265 7.292 35.758 3.584 48.212-7.424l142.269-116.274c6.432-2.76 7.37-5.117 9.22-9.737 5.45-13.663-.062-26.52-13.875-32.04L906.524 876.251l-2.244-54.224-75.835-30.28zm220.794 514.323c-25.24-10.078-54.241 2.278-64.311 27.493-10.124 25.354 2.393 54.285 27.633 64.364 25.24 10.078 54.092-2.332 64.223-27.686 10.061-25.215-2.313-54.093-27.545-64.171zm398.216-581.018c5.11 1.394 10.402 2.138 15.626 2.138 25.845 0 49.49-17.238 56.739-43.374 8.615-31.418-9.991-63.863-41.35-72.417l-255.36-78.816c-31.007-8.58-63.82 9.85-72.392 41.244-8.544 31.402 9.878 63.847 41.367 72.444l255.37 78.781zM1016.68 463.865c36.072 0 65.354 28.467 65.354 63.532 0 35.083-29.282 63.513-65.354 63.513-36.1 0-65.364-28.422-65.364-63.513 0-35.065 29.263-63.532 65.364-63.532z" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 ">
                                <div class="card" style="box-shadow: 0 0.5rem 1rem rgb(0 0 0 / 15%), inset 5px 8px 8px rgba(0, 0, 0, .2), inset -2px -2px 10px hsla(0, 0%, 100%, .2) !important;
                                    background: linear-gradient(230deg, #00b7ff00, var(--bs-primary));">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between">
                                            {{-- <h6 class="text-pink mb-0">Open Projects 05</h6> --}}
                                            <div class="progress-widget">
                                                <div class="progress-detail">
                                                    <h4 class="counter text-white mb-2">{{ $count['customers'] }}</h4>
                                                    <p class="text-white mb-0">Customers</p>
                                                </div>
                                            </div>
                                            <div style="border:3px dashed  var(--bs-primary);border-radius:50%;padding:10px;background-color: #fff;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="40"  viewBox="0 0 128 128">
                                                    <path fill="var(--bs-primary)"
                                                        d="M120.44 96.015c-1.345-20.979-28.914-25.387-28.914-25.387-7.042-1.257-9.912-7.303-11.049-11.217 5.414-6.063 9.202-13.898 11.295-21.534A34.065 34.065 0 0 0 93 28.91a28.52 28.52 0 0 0-.464-5.028c3.514-5.89 2.48-12.458 1.646-15.614-.253-.958-1.21-1.432-2.194-1.432-.106 0-.212.006-.317.017a11.64 11.64 0 0 1-1.186.056c-6.293 0-16.7-4.18-16.7-4.18C70.61 1.36 66.7.557 63.277.161c-2.995-.347-6.024-.159-8.9.78-3.995 1.305-7.674 3.874-10.655 6.813a12.962 12.962 0 0 0-1.614 1.932c-6.343 2.123-7.417 10.293-7.04 17.447A28.78 28.78 0 0 0 35 28.91c0 10.753 4.568 23.087 11.687 31.215.208.237.425.465.641.693-1.387 3.82-4.35 8.697-10.59 9.811 0 0-27.57 4.408-28.914 25.387 0 0-1.66 10.964-.267 24.041.467 4.394 4.556 7.944 8.999 7.944h95.153c4.442 0 8.43-3.562 8.836-7.962 1.082-11.718-.105-24.023-.105-24.023zM80.804 69.531l1.465 1.618.352.39.44.284 1.286.833-9.874 14.118-3.87-6.355-.158-.26-.196-.233c-.307-.365-.33-1.086-.08-1.52l10.635-8.875zM66.506 86h-4.818l-1.448-2.56.573-.941c.375-.446.651-.959.849-1.5h4.676c.198.541.473 1.054.848 1.5l.652 1.07-1.332 2.43zm3.485 25.343c.058.223-.117.766-.344.939l-4.78 3.638c-.139.106-.54.106-.678 0l-4.78-3.637c-.255-.194-.457-.783-.397-1.055L63.408 90h1.277l5.306 21.343zm-2.71-35.736c-.366.413-.64.89-.849 1.393h-4.864a4.92 4.92 0 0 0-.85-1.393l-11.424-9.533c.475-.82.88-1.643 1.226-2.447 3.88 2.834 8.488 4.372 13.089 4.372 4.459 0 8.907-1.44 12.673-4.536.35-.289.69-.591 1.031-.894.409 1.079.928 2.22 1.574 3.354L67.28 75.606zM43.377 13.48a4.002 4.002 0 0 0 2.031-1.533c.055-.08 5.566-7.949 16.13-7.95h.001c3.37 0 6.958.81 10.665 2.405l.09.037c1.138.457 11.323 4.47 18.191 4.47.065 0 .13 0 .194-.002.992 5.329.414 13.376-10.113 17.592-.048.019-4.921 1.897-10.91 1.897-5.939 0-10.834-1.807-14.551-5.372a4 4 0 0 0-6.738 2.396l-.257 2.072-1.266-.891a3.994 3.994 0 0 0-4.823.165c-.781.633-1.693 1.588-2.53 2.813-1.007-7.39-.864-16.509 3.886-18.099zm-2.28 28.15c-1.099-6.054 3.444-9.759 3.444-9.759l5.383 3.79c.3.241.58.357.818.357.437 0 .727-.391.727-1.103l.867-7.003c5.295 5.077 11.769 6.484 17.32 6.484 6.903 0 12.378-2.176 12.378-2.176 2.882-1.154 5.152-2.557 6.933-4.104.01.264.033.527.033.793 0 2.623-.365 5.284-1.085 7.91-2.678 9.765-7.844 18.35-14.173 23.553A15.78 15.78 0 0 1 63.61 64c-5.217 0-10.288-2.374-13.913-6.511-3.727-4.255-6.735-9.928-8.6-15.859zm3.843 30.193.44-.285.351-.389 1.465-1.618 10.634 8.875c.251.434.228 1.155-.079 1.52l-.196.233-.158.26-3.87 6.355-9.874-14.118 1.287-.833zm71.621 47.848c-.211 2.306-2.48 4.329-4.852 4.329H16.556c-2.428 0-4.774-2.041-5.022-4.368-1.317-12.363.23-22.915.245-23.018l.026-.17.01-.173c1.123-17.503 25.317-21.653 25.555-21.692l.036-.006.036-.007a17.77 17.77 0 0 0 2.267-.57l11.247 16.082c.588 1.268 1.52 1.921 2.508 1.921.792 0 1.62-.418 2.34-1.274l2.054-3.373 1.474 2.605-4.233 20.438c-.393 1.805.4 3.937 1.886 5.07l4.781 3.638c.788.6 1.774.899 2.762.899.987 0 1.974-.3 2.761-.9l4.782-3.639c1.487-1.132 2.257-3.337 1.796-5.108L68.807 90h.068l1.364-2.488 1.957 3.214c.72.856 1.548 1.274 2.34 1.274.989 0 1.92-.653 2.508-1.921l11.293-16.15c.783.26 1.604.481 2.486.638l.036.007.036.006c.244.039 24.425 4.106 25.554 21.692l.004.064.006.064c.011.12 1.138 12.058.102 23.272z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 ">
                                <div class="card" style="box-shadow: 0 0.5rem 1rem rgb(0 0 0 / 15%), inset 5px 8px 8px rgba(0, 0, 0, .2), inset -2px -2px 10px hsla(0, 0%, 100%, .2) !important;
                                    background: linear-gradient(230deg, #00b7ff00, var(--bs-primary));">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between">
                                            {{-- <h6 class="text-pink mb-0">Open Projects 05</h6> --}}
                                            <div class="progress-widget">
                                                <div class="progress-detail">
                                                    <h4 class="counter text-white mb-2">{{ $count['sold'] }}</h4>
                                                    <p class="text-white mb-0">Sold</p>
                                                </div>
                                            </div>
                                            <div style="border:3px dashed  var(--bs-primary);border-radius:50%;padding:10px;background-color: #fff;">

                                                <svg xmlns="http://www.w3.org/2000/svg" width="40" viewBox="0 0 64 64">
                                                    <path fill="var(--bs-primary)"
                                                        d="M21 49h-1a10 10 0 0 1-10-10v-4a1 1 0 0 1 1-1h19a1 1 0 0 1 1 1v4a10 10 0 0 1-10 10zm-9-13v3a8 8 0 0 0 8 8h1a8 8 0 0 0 8-8v-3zm6.12-7.12a1 1 0 0 1-1-1c0-1.67 1.47-2.45 2.53-3s1.47-.81 1.47-1.24-.55-.76-1.47-1.25-2.53-1.34-2.53-3 1.47-2.44 2.53-3 1.47-.81 1.47-1.24a1 1 0 0 1 2 0c0 1.66-1.46 2.44-2.53 3-.91.48-1.47.81-1.47 1.24s.56.76 1.47 1.25c1.07.57 2.53 1.34 2.53 3s-1.46 2.44-2.53 3c-.91.49-1.47.81-1.47 1.25a1 1 0 0 1-1 .99z" />
                                                    <path fill="var(--bs-primary)"
                                                        d="M11.35 43H7.7a3.51 3.51 0 0 1-3.26-2.21A3.52 3.52 0 0 1 7.67 36h3.32a1 1 0 0 1 0 2h-3.3a1.51 1.51 0 0 0-1.25.68A1.51 1.51 0 0 0 7.7 41h3.65a1 1 0 1 1 0 2zM60 52H4a1 1 0 0 1 0-2h56a1 1 0 0 1 0 2zm-6 5H16.1a1 1 0 0 1 0-2H54a1 1 0 0 1 0 2zm-42 0h-2a1 1 0 0 1 0-2h2a1 1 0 0 1 0 2z" />
                                                    <path fill="var(--bs-primary)"
                                                        d="M8.27 52a1 1 0 0 1-.92-.61l-1.27-3A1 1 0 0 1 7 47h13a1 1 0 0 1 0 2H8.51l.68 1.61a1 1 0 0 1-.53 1.31 1.09 1.09 0 0 1-.39.08Z" />
                                                    <path fill="var(--bs-primary)"
                                                        d="M31.73 52a1.09 1.09 0 0 1-.39-.08 1 1 0 0 1-.53-1.31l.68-1.61H20a1 1 0 0 1 0-2h13a1 1 0 0 1 .83.45 1 1 0 0 1 .09.94l-1.27 3a1 1 0 0 1-.92.61zM58 52a1 1 0 0 1-1-1 23 23 0 0 0-37.65-17.73 1 1 0 0 1-1.28-1.54A25 25 0 0 1 59 51a1 1 0 0 1-1 1z" />
                                                    <path fill="var(--bs-primary)"
                                                        d="M47.06 35.57a1 1 0 0 1-.62-.22A20 20 0 0 0 37 31.23a1 1 0 0 1-.84-1.14 1 1 0 0 1 1.14-.84 22 22 0 0 1 10.37 4.53 1 1 0 0 1-.62 1.79zm2.8 2.68a1 1 0 0 1-.75-.34l-.47-.53A1 1 0 1 1 50.1 36c.17.19.35.38.52.58a1 1 0 0 1-.1 1.41 1 1 0 0 1-.66.26zM56 40a1 1 0 0 1-1-1V25h-2v6a1 1 0 0 1-2 0v-6a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v14a1 1 0 0 1-1 1z" />
                                                    <path fill="var(--bs-primary)" d="M54 25a1 1 0 0 1-1-1V9a1 1 0 0 1 2 0v15a1 1 0 0 1-1 1Z" />
                                                    <path fill="var(--bs-primary)"
                                                        d="M56 18h-4a2 2 0 0 1-2-2V9a1 1 0 0 1 2 0v7h4V9a1 1 0 0 1 2 0v7a2 2 0 0 1-2 2zM45 28a1 1 0 0 1-1-1v-6a1 1 0 0 1 .29-.71c2.72-2.71 1.07-8.44.23-10.77a.78.78 0 0 0-1.52.26V25a1 1 0 0 1-2 0V9.78a2.78 2.78 0 0 1 5.4-.93c.94 2.61 2.72 9-.4 12.54V27a1 1 0 0 1-1 1zm-30-3a1 1 0 0 1-1-1c0-.3-.82-.69-1.42-1-1.09-.48-2.58-1.18-2.58-2.75s1.49-2.28 2.58-2.79c.6-.28 1.42-.66 1.42-1s-.82-.68-1.42-1C11.49 15 10 14.32 10 12.75s1.49-2.27 2.58-2.78C13.18 9.69 14 9.3 14 9a1 1 0 0 1 2 0c0 1.57-1.49 2.27-2.58 2.78-.6.28-1.42.67-1.42 1s.82.68 1.42 1c1.09.51 2.58 1.21 2.58 2.78s-1.49 2.28-2.58 2.79c-.6.28-1.42.66-1.42 1s.82.69 1.42 1C14.51 21.73 16 22.43 16 24a1 1 0 0 1-1 1zm21 3h-4a1 1 0 0 1-1-1v-2a3 3 0 0 1 6 0v2a1 1 0 0 1-1 1zm-3-2h2v-1a1 1 0 0 0-2 0z" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 ">
                                <div class="card" style="box-shadow: 0 0.5rem 1rem rgb(0 0 0 / 15%), inset 5px 8px 8px rgba(0, 0, 0, .2), inset -2px -2px 10px hsla(0, 0%, 100%, .2) !important;
                                    background: linear-gradient(230deg, #00b7ff00, var(--bs-primary));">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between">
                                            {{-- <h6 class="text-pink mb-0">Open Projects 05</h6> --}}
                                            <div class="progress-widget">
                                                <div class="progress-detail">
                                                    <h4 class="counter text-white mb-2">{{ $count['customers'] }}</h4>
                                                    <p class="text-white mb-0">Staff</p>
                                                </div>
                                            </div>
                                            <div style="border:3px dashed  var(--bs-primary);border-radius:50%;padding:10px;background-color: #fff;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="40" viewBox="0 0 64 64" >
                                                    <path fill="var(--bs-primary)"
                                                        d="M32 29a13 13 0 1 0-13-13 13.015 13.015 0 0 0 13 13zm0-24a11 11 0 1 1-11 11A11.013 11.013 0 0 1 32 5zm11.993 26H20.007A9.018 9.018 0 0 0 11 40.007V60a1 1 0 0 0 1 1h40a1 1 0 0 0 1-1V40.007A9.018 9.018 0 0 0 43.993 31zM32 44.487l3.1 11.129-3.1 2.368-3.1-2.368zm.061-4.72c-.021 0-.04-.01-.061-.01s-.04.009-.061.01L29.171 37 32 34.171 34.829 37zM13 40.007A7.015 7.015 0 0 1 20.007 33h10.336l-3.293 3.293a1 1 0 0 0 0 1.414l3.724 3.724-3.98 14.3a1 1 0 0 0 .356 1.062L30.036 59H19.169V46a1 1 0 0 0-2 0v13H13zM51 59h-3.831V46a1 1 0 0 0-2 0v13h-11.2l2.886-2.206a1 1 0 0 0 .356-1.062l-3.98-14.3 3.724-3.724a1 1 0 0 0 0-1.414L33.657 33h10.336A7.015 7.015 0 0 1 51 40.007z" />
                                                    <path d="M43 42h-4a1 1 0 0 0-1 1v6a1 1 0 0 0 2 0v-5h2v5a1 1 0 0 0 2 0v-6a1 1 0 0 0-1-1Z" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 ">
                                <div class="card" style="box-shadow: 0 0.5rem 1rem rgb(0 0 0 / 15%), inset 5px 8px 8px rgba(0, 0, 0, .2), inset -2px -2px 10px hsla(0, 0%, 100%, .2) !important;
                                    background: linear-gradient(230deg, #00b7ff00, var(--bs-primary));">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between">
                                            {{-- <h6 class="text-pink mb-0">Open Projects 05</h6> --}}
                                            <div class="progress-widget">
                                                <div class="progress-detail">
                                                    <h4 class="counter text-white mb-2">{{ $count['collection'] }}</h4>
                                                    <p class="text-white mb-0">Collection</p>
                                                </div>
                                            </div>
                                            <div style="border:3px dashed  var(--bs-primary);border-radius:50%;padding:10px;background-color: #fff;">

                                                <svg fill="var(--bs-primary)" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" width="40"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 464.544 464.544" xml:space="preserve">
                                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                                    <g id="SVGRepo_iconCarrier"> <g> <g> <g> <path d="M226.246,246.141c-7.002-8.572-16.13-13.281-25.793-13.281c-9.662,0-18.79,4.708-25.792,13.281 c-19.308,6.842-31.26,20.493-31.26,35.784s11.952,28.941,31.26,35.784c7.001,8.572,16.129,13.28,25.792,13.28 c9.664,0,18.792-4.708,25.793-13.28c19.309-6.841,31.261-20.492,31.261-35.784C257.506,266.634,245.555,252.982,226.246,246.141z "></path> <path d="M303.514,260.889c-16.02,0-29.051,9.438-29.051,21.036s13.031,21.036,29.051,21.036s29.051-9.438,29.051-21.036 S319.533,260.889,303.514,260.889z"></path> <path d="M97.392,260.889c-16.019,0-29.052,9.438-29.052,21.036s13.033,21.036,29.052,21.036s29.051-9.438,29.051-21.036 S113.411,260.889,97.392,260.889z"></path> </g> <path d="M455.811,149.25L85.665,68.983c-5.979-1.297-11.878,2.5-13.175,8.479l-19.64,90.571H11.079 C4.96,168.033,0,172.992,0,179.112v205.623c0,6.119,4.96,11.079,11.079,11.079h378.747c6.119,0,11.08-4.959,11.08-11.079v-14.317 l6.631,1.438c5.98,1.297,11.879-2.5,13.176-8.479l43.576-200.954C465.588,156.444,461.789,150.546,455.811,149.25z M378.748,335.281c-4.227-2.439-9.131-3.844-14.361-3.844c-15.895,0-28.777,12.885-28.777,28.778 c0,4.855,1.207,9.429,3.332,13.441H61.966c2.124-4.013,3.332-8.585,3.332-13.441c0-15.894-12.885-28.778-28.778-28.778 c-5.232,0-10.135,1.404-14.362,3.844V228.568c4.227,2.44,9.129,3.845,14.362,3.845c15.893,0,28.778-12.886,28.778-28.779 c0-4.856-1.208-9.429-3.332-13.442h276.976c-2.125,4.013-3.332,8.585-3.332,13.442c0,15.894,12.883,28.779,28.777,28.779 c5.23,0,10.135-1.405,14.361-3.845V335.281L378.748,335.281z M75.522,168.034l8.142-37.544c3.614,3.281,8.107,5.692,13.222,6.8 c15.532,3.369,30.854-6.494,34.223-22.026c1.029-4.746,0.818-9.471-0.409-13.843l270.685,58.698 c-2.371,2.812-4.221,6.117-5.389,9.791l0.002,0.001c-1.764-1.185-3.887-1.878-6.172-1.878L75.522,168.034L75.522,168.034z M409.539,310.351c-2.486-2.258-5.398-4.09-8.633-5.383V196.591c3.988,5.005,9.658,8.72,16.398,10.182 c5.113,1.109,10.201,0.776,14.85-0.712L409.539,310.351z"></path> </g> </g> </g>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 ">
                                <div class="card" style="box-shadow: 0 0.5rem 1rem rgb(0 0 0 / 15%), inset 5px 8px 8px rgba(0, 0, 0, .2), inset -2px -2px 10px hsla(0, 0%, 100%, .2) !important;
                                    background: linear-gradient(230deg, #00b7ff00, var(--bs-primary));">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between">
                                            {{-- <h6 class="text-pink mb-0">Open Projects 05</h6> --}}
                                            <div class="progress-widget">
                                                <div class="progress-detail">
                                                    <h4 class="counter text-white mb-2">{{ $count['earning'] }}</h4>
                                                    <p class="text-white mb-0">Earning</p>
                                                </div>
                                            </div>
                                            <div style="border:3px dashed  var(--bs-primary);border-radius:50%;padding:10px;background-color: #fff;">

                                                <svg fill="var(--bs-primary)" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" width="40"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 464.544 464.544" xml:space="preserve">
                                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                                    <g id="SVGRepo_iconCarrier"> <g> <g> <g> <path d="M226.246,246.141c-7.002-8.572-16.13-13.281-25.793-13.281c-9.662,0-18.79,4.708-25.792,13.281 c-19.308,6.842-31.26,20.493-31.26,35.784s11.952,28.941,31.26,35.784c7.001,8.572,16.129,13.28,25.792,13.28 c9.664,0,18.792-4.708,25.793-13.28c19.309-6.841,31.261-20.492,31.261-35.784C257.506,266.634,245.555,252.982,226.246,246.141z "></path> <path d="M303.514,260.889c-16.02,0-29.051,9.438-29.051,21.036s13.031,21.036,29.051,21.036s29.051-9.438,29.051-21.036 S319.533,260.889,303.514,260.889z"></path> <path d="M97.392,260.889c-16.019,0-29.052,9.438-29.052,21.036s13.033,21.036,29.052,21.036s29.051-9.438,29.051-21.036 S113.411,260.889,97.392,260.889z"></path> </g> <path d="M455.811,149.25L85.665,68.983c-5.979-1.297-11.878,2.5-13.175,8.479l-19.64,90.571H11.079 C4.96,168.033,0,172.992,0,179.112v205.623c0,6.119,4.96,11.079,11.079,11.079h378.747c6.119,0,11.08-4.959,11.08-11.079v-14.317 l6.631,1.438c5.98,1.297,11.879-2.5,13.176-8.479l43.576-200.954C465.588,156.444,461.789,150.546,455.811,149.25z M378.748,335.281c-4.227-2.439-9.131-3.844-14.361-3.844c-15.895,0-28.777,12.885-28.777,28.778 c0,4.855,1.207,9.429,3.332,13.441H61.966c2.124-4.013,3.332-8.585,3.332-13.441c0-15.894-12.885-28.778-28.778-28.778 c-5.232,0-10.135,1.404-14.362,3.844V228.568c4.227,2.44,9.129,3.845,14.362,3.845c15.893,0,28.778-12.886,28.778-28.779 c0-4.856-1.208-9.429-3.332-13.442h276.976c-2.125,4.013-3.332,8.585-3.332,13.442c0,15.894,12.883,28.779,28.777,28.779 c5.23,0,10.135-1.405,14.361-3.845V335.281L378.748,335.281z M75.522,168.034l8.142-37.544c3.614,3.281,8.107,5.692,13.222,6.8 c15.532,3.369,30.854-6.494,34.223-22.026c1.029-4.746,0.818-9.471-0.409-13.843l270.685,58.698 c-2.371,2.812-4.221,6.117-5.389,9.791l0.002,0.001c-1.764-1.185-3.887-1.878-6.172-1.878L75.522,168.034L75.522,168.034z M409.539,310.351c-2.486-2.258-5.398-4.09-8.633-5.383V196.591c3.988,5.005,9.658,8.72,16.398,10.182 c5.113,1.109,10.201,0.776,14.85-0.712L409.539,310.351z"></path> </g> </g> </g>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="row">
                            <div class="col-md-12 col-lg-12">
                                <div class="card aos-init aos-animate" data-aos="fade-up" data-aos-delay="600">
                                    <div class="flex-wrap card-header d-flex justify-content-between">
                                        <div class="header-title">
                                            <h4 class="mb-2 card-title">Most Sold</h4>
                                            {{-- <p class="mb-0">
                                                <svg class="me-2 icon-24" width="24" height="24" viewBox="0 0 24 24">
                                                    <path fill="#17904b" d="M13,20H11V8L5.5,13.5L4.08,12.08L12,4.16L19.92,12.08L18.5,13.5L13,8V20Z"></path>
                                                </svg>
                                                16% this month
                                            </p> --}}
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        @foreach ($productSold as $item)
                                        <div class="mb-2  d-flex profile-media align-items-top">
                                            <div class="mt-1 profile-dots-pills border-primary"></div>
                                            <div class="ms-4">
                                                <h6 class="mb-1 ">{{$item['foodname']}}</h6>
                                                <span class="mb-0">Quantity : {{$item['quantity']}}</span>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="col-md-12" id="new-orders">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="header-title d-flex justify-content-between">
                                            <h4 class="card-title">New Orders</h4>
                                            <a href="{{ route('vendor.order.list', ['status', 'all']) }}" class="mb-0 fs-6 text-warning">All</a>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-inline m-0 p-0">
                                            @php($orders = App\Models\Order::whereDate('created_at', Carbon\Carbon::now()->toDateString())->where('order_status', '!=', 'delivered')->where('restaurant_id', Session::get('restaurant')->id)->latest()->limit(5)->get())
                                            @foreach ($orders as $order)
                                                @php($deliveryAddress = json_decode($order->delivery_address))
                                                <li class="d-flex mb-4 align-items-center border-bottom shadow p-2 rounded ">
                                                    <div class="img-fluid "><img src="{{ asset('product/default-food.png') }}" alt="story-img" class="avatar-80"></div>
                                                    <div class="ms-3 flex-grow-1">
                                                        <h6>#{{ $order->id }}</h6>
                                                        <p class="mb-0">
                                                            {{ isset($deliveryAddress->contact_person_name) ? $deliveryAddress->contact_person_name : Str::ucfirst($order->customer->f_name) . ' ' . Str::ucfirst($order->customer->l_name) }}
                                                        </p>
                                                        <div class="d-md-flex">
                                                            <p class="mb-0"><i class="pt-1 me-1 fa fa-location"></i>{{ isset($deliveryAddress->distance) ? App\CentralLogics\Helpers::formatDistance($deliveryAddress->distance) : null }}</p>
                                                            <p class="mb-0 ms-md-2"><i class="pt-1 me-1 fa fa-phone"></i>{{ isset($deliveryAddress->contact_person_number) ? $deliveryAddress->contact_person_number : $order->customer->phone }}</p>
                                                        </div>

                                                    </div>
                                                    <div class="w-25">

                                                        <p class="mb-0 small"><i class="fa-regular fa-clock"></i> {{ App\CentralLogics\Helpers::format_time($order->updated_at->toTimeString()) }}</p>
                                                        <div class="d-flex flex-column flex-md-row">
                                                            @if ($order['order_status'] == 'pending')
                                                                <button class="btn p-1 me-md-2 my-2 my-md-0 btn-soft-success" data-order-state="confirmed" orderId="{{ $order->id }}">Confirm</button>
                                                                <button class="btn p-1 btn-soft-warning" data-order-state="canceled" orderId="{{ $order->id }}">Cancel</button>
                                                            @elseif($order['order_status'] == 'confirmed')
                                                                <span class="badge bg-soft-info ml-2 ml-sm-3">
                                                                    {{ __('messages.confirmed') }}
                                                                </span>
                                                            @elseif($order['order_status'] == 'processing')
                                                                <span class="badge bg-soft-warning ml-2 ml-sm-3">
                                                                    {{ __('messages.cooking') }}
                                                                </span>
                                                            @elseif($order['order_status'] == 'picked_up')
                                                                <span class="badge bg-soft-warning ml-2 ml-sm-3">
                                                                    {{ __('messages.out_for_delivery') }}
                                                                </span>
                                                            @elseif($order['order_status'] == 'delivered')
                                                                <span class="badge bg-soft-success ml-2 ml-sm-3">
                                                                    {{ __('messages.delivered') }}
                                                                </span>
                                                            @elseif($order['order_status'] == 'canceled')
                                                                <span class="badge bg-soft-danger ml-2 ml-sm-3">
                                                                    {{ __('messages.canceled') }}
                                                                </span>
                                                            @endif

                                                        </div>

                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection

@push('javascript')
    <script src="{{ asset('assets/vendor/flatpickr/dist/flatpickr.min.js') }}"></script>
    <script>
        function order_status_change_alert(route, message, option = {}) {
            if (option.processing == "canceled") {
                Swal.fire({
                    //text: message,
                    title: message,
                    type: 'warning',
                    showCancelButton: true,
                    cancelButtonColor: 'default',
                    confirmButtonColor: '#FC6A57',
                    cancelButtonText: 'Cancel',
                    confirmButtonText: 'Submit',
                    // inputPlaceholder: "Enter processing time",
                    input: 'text',
                    html: '<br/>' + '<label>Enter Cancle reason</label>',
                    // inputValue: ,
                    preConfirm: (cancelReason) => {

                        location.href = route + '&cancel_reason=' + cancelReason;
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                })
            } else {
                Swal.fire({
                    title: 'Are you sure  ',
                    text: message,
                    type: 'warning',
                    showCancelButton: true,
                    cancelButtonColor: 'default',
                    confirmButtonColor: '#FC6A57',
                    cancelButtonText: 'No',
                    confirmButtonText: 'Yes',
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        location.href = route;
                    }
                })
            }
        }

        function change_order_state() {
            document.querySelectorAll('#new-orders [data-order-state]').forEach(eventButton => {
                eventButton.addEventListener('click', function() {
                    let route = "{{ route('vendor.order.order-status-update') }}?";
                    route += `id=${eventButton.getAttribute('orderId')}&order_status=${eventButton.dataset.orderState}`;

                    let message = '';
                    if (eventButton.dataset.orderState === 'confirmed') {
                        message = "Change status to confirmed?";
                    } else {
                        message = "Are you sure you want to cancel the order?";
                    }
                    order_status_change_alert(route, message, {
                        processing: eventButton.dataset.orderState
                    });
                });
            });
        }
        change_order_state();
    </script>

    {{-- <script>
    $(".range_flatpicker").flatpickr({
        mode: "range",
        minDate: "today",
        dateFormat: "Y-m-d",
    });
</script> --}}
@endpush
