@extends('layouts.dashboard-main')
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/flatpickr/dist/flatpickr.min.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .top-store-box {
            width: 60px;
            height: 60px;
            text-align: center;
            align-self: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .hover-bg-light:hover {
            background-color: #f8f9fa !important;
            transition: all 0.3s ease;
        }
        
        .bg-gradient-primary {
            background: linear-gradient(91deg, #007bff00 0%, #0557af 100%);
        }
        
        .bg-gradient-warning {
            background: linear-gradient(91deg, #ffc10700 0%, #e0a800 100%);
        }
        
        .bg-gradient-info {
            background: linear-gradient(91deg, #17a2b800 0%, #117a8b 100%);
        }
        
        .blockquote-sm {
            border-left: 4px solid #e9ecef;
            padding-left: 1rem;
            margin-left: 0;
        }
        
        .card {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
        }
        
        .list-group-item {
            border: none !important;
            transition: all 0.3s ease;
        }
        
        .rounded-4 {
            border-radius: 1rem !important;
        }
        
        .rounded-top-4 {
            border-top-left-radius: 1rem !important;
            border-top-right-radius: 1rem !important;
        }
        
        .shadow-lg {
            box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
        }
        
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }
        
        .chart-container canvas {
            position: relative;
            z-index: 5;
        }
        
        @media (max-width: 768px) {
            .chart-container {
                height: 250px;
            }
            .stats-number {
                font-size: 1.8rem;
            }
        }
        
        .chart-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .chart-header-orders {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        
        .chart-header-payments {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        
        .chart-header-ratings {
            background: linear-gradient(135deg, #fdbb2d 0%, #22c1c3 100%);
        }
        
        .chart-header-earnings {
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
        }
        
        .stats-number {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
     <!-- Pusher CDN -->


@endpush

@section('content')
    <div class="conatiner-fluid content-inner mt-1 py-0">
        <div class="d-flex justify-content-between mb-3">
            {{-- @dd(auth('admin')->user()->can('dashboard')) --}}
            @can('wallet')
                <h4 class="">Dashboard</h4>
            @endcan
            {{-- @dd(auth('admin')->user()->permissions) --}}
            <div class="d-flex">
                <p class="mb-md-0 mb-2 d-flex align-items-center me-4">
                    <svg width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="me-2 icon-20">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M4.56517 3C3.70108 3 3 3.71286 3 4.5904V5.52644C3 6.17647 3.24719 6.80158 3.68936 7.27177L8.5351 12.4243L8.53723 12.4211C9.47271 13.3788 9.99905 14.6734 9.99905 16.0233V20.5952C9.99905 20.9007 10.3187 21.0957 10.584 20.9516L13.3436 19.4479C13.7602 19.2204 14.0201 18.7784 14.0201 18.2984V16.0114C14.0201 14.6691 14.539 13.3799 15.466 12.4243L20.3117 7.27177C20.7528 6.80158 21 6.17647 21 5.52644V4.5904C21 3.71286 20.3 3 19.4359 3H4.56517Z"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                    Filter :: {{ Str::ucfirst($filter) }}
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
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M16.2383 3.5791H7.77096C4.83427 3.5791 3 5.21504 3 8.22213V17.2718C3 20.3261 4.83427 21.9999 7.77096 21.9999H16.229C19.175 21.9999 21 20.3545 21 17.3474V8.22213C21.0092 5.21504 19.1842 3.5791 16.2383 3.5791Z" stroke="currentColor"
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>

                            @if ($filter == 'today')
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
                            <li><a class="dropdown-item" href="{!! route('admin.dashboard') . '?filter=this_week' !!}">This Week</a></li>
                            <li><a class="dropdown-item" href="{!! route('admin.dashboard') . '?filter=this_month' !!}">This Month</a></li>
                            <li><a class="dropdown-item" href="{!! route('admin.dashboard') . '?filter=this_year' !!}">This Year</a></li>
                            <li><a class="dropdown-item" href="{!! route('admin.dashboard') . '?filter=previous_year' !!}">Previous Year</a></li>
                            <li><a class="dropdown-item" href="{!! route('admin.dashboard') . '?filter=today' !!}">Today</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0)">
                                    {{-- <form action="">
                                        <div class="m-0 d-flex flex-column align-items-center justify-content-center">

                                            <input type="text" name="date_range" class="form-control range_flatpicker d-flex flatpickr-input active" placeholder="Date Range" readonly="readonly" required>
                                            <input type="hidden" name="filter" value="custom">
                                            <button class="badge rounded-pill bg-success ms-1 mb-1 px-3 py-2" type="submit">Go</button>
                                        </div>
                                    </form> --}}
                                    <form action="">
                                        <div class="m-0 d-flex flex-column align-items-center justify-content-center gap-2">
                                            <div class="d-flex gap-2 w-100">
                                                <input type="text" name="start_date" class="form-control" placeholder="Start Date" readonly required>
                                                <input type="text" name="end_date" class="form-control" placeholder="End Date" readonly required>
                                            </div>
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
        <div class="row">

            {{-- NEW UI FOR DASHBOARD --}}
            <div class="col-md-12">
                <div class=" rounded-4">
                    <div class="rounded-4">
                        <div class="accordion" id="accordionExample">
                            <div class="accordion-item border-0">
                                <div class="card d-flex justify-content-between accordion-header mb-3" id="headingOne">
                                    <div class="card-body">
                                        <button class="accordion-button bg-white rounded-4 p-0 fs-5 fw-bolder" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            Quick View
                                        </button>
                                    </div>
                                </div>
                                <div id="collapseOne" class="row accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                    <div class="col-6">
                                        <a href="{{route('admin.order.list', ['status' => 'all'])}}" class="card">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="text-start">
                                                        <h2 class="counter" style="visibility: visible;">{{ $count['currentOrder'] }}</h2>
                                                        Live Orders
                                                    </div>
                                                    <div class="bg-primary text-white rounded-circle p-1">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="50" class="card-slie-arrow icon-50" fill-rule="evenodd" clip-rule="evenodd" text-rendering="geometricPrecision" viewBox="0 0 2048 2048">
                                                            <path fill="none" d="M0 0h2048v2048H0z" />
                                                            <path fill="none" d="M255.999 255.999h1536v1536h-1536z" />
                                                            <path fill="none" d="M256 255.999h1536v1536H256z" />
                                                            <path fill="#fff"
                                                                d="M1783.77 501.991c-8.633-14.418-22.84-24.522-39.23-27.81l-870.058-209.76c-28.676-5.564-56.966 10.281-66.887 37.686L475.96 1144.612l110.708 40.342L900.95 383.877l755.976 192.9c-47.213 146.36-159.552 451.86-267.253 736.308-93.883 248.175-147.913 333.16-250.611 350.512-.114-.123-.227-.228-.227-.41-209.875 29.411-206.116-238.14-206.116-238.14l-670.28-270.759c-4.461 28.3-6.468 54.592-6.442 78.991.28 235.896 190.786 295.536 190.786 295.536l490.755 222.793c.105.07 76.722 36.564 159.98 32.804 224.59-5.276 303.51-168.35 402.405-429.618 142.872-377.593 287.875-800.656 289.295-805.143 5.11-16.012 3.093-33.286-5.45-47.659zm-423.72 406.944c5.11 1.394 10.403 2.139 15.626 2.139 25.846 0 49.492-17.24 56.739-43.374 8.615-31.42-9.99-63.864-41.35-72.418l-255.36-78.816c-31.006-8.58-63.819 9.85-72.39 41.244-8.545 31.402 9.877 63.847 41.367 72.444l255.369 78.781zM929.276 647.747c36.073 0 65.354 28.466 65.354 63.53 0 35.084-29.28 63.514-65.354 63.514-36.1 0-65.362-28.422-65.362-63.514 0-35.064 29.263-63.53 65.362-63.53zm-109.745 566.609c-25.24-10.08-54.25 2.27-64.311 27.484-10.132 25.355 2.393 54.285 27.633 64.363 25.241 10.08 54.102-2.33 64.224-27.685 10.07-25.215-2.305-54.092-27.546-64.162zm8.914-422.607l-18.37 45.995 45.993 18.36 12.955 207.517-54.215 42.34c-4.97 5.942-11 14.189-13.725 21.016-10.123 25.354 2.392 54.294 27.633 64.372l275.561 110.034 18.37-46.003-266.377-106.362c-2.357-.946-3.698-4.075-2.76-6.432l.894-2.235 36.23-30.771 170.033 67.896c18.265 7.292 35.758 3.584 48.212-7.424l142.269-116.274c6.432-2.76 7.37-5.117 9.22-9.737 5.45-13.663-.062-26.52-13.875-32.04L906.524 876.251l-2.244-54.224-75.835-30.28zm220.794 514.323c-25.24-10.078-54.241 2.278-64.311 27.493-10.124 25.354 2.393 54.285 27.633 64.364 25.24 10.078 54.092-2.332 64.223-27.686 10.061-25.215-2.313-54.093-27.545-64.171zm398.216-581.018c5.11 1.394 10.402 2.138 15.626 2.138 25.845 0 49.49-17.238 56.739-43.374 8.615-31.418-9.991-63.863-41.35-72.417l-255.36-78.816c-31.007-8.58-63.82 9.85-72.392 41.244-8.544 31.402 9.878 63.847 41.367 72.444l255.37 78.781zM1016.68 463.865c36.072 0 65.354 28.467 65.354 63.532 0 35.083-29.282 63.513-65.354 63.513-36.1 0-65.364-28.422-65.364-63.513 0-35.065 29.263-63.532 65.364-63.532z" />
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-6">
                                        <a href="{{route('admin.order.list' ,['status' => 'all'])}}" class="card">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="text-start">
                                                        <h2 class="counter" style="visibility: visible;">{{ $count['totalOrders'] }}</h2>
                                                        Total Orders
                                                    </div>
                                                    <div class="bg-primary text-white rounded-circle p-1">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="50" class="card-slie-arrow icon-50" fill-rule="evenodd" clip-rule="evenodd" text-rendering="geometricPrecision" viewBox="0 0 2048 2048">
                                                            <path fill="none" d="M0 0h2048v2048H0z" />
                                                            <path fill="none" d="M255.999 255.999h1536v1536h-1536z" />
                                                            <path fill="none" d="M256 255.999h1536v1536H256z" />
                                                            <path fill="#fff"
                                                                d="M1783.77 501.991c-8.633-14.418-22.84-24.522-39.23-27.81l-870.058-209.76c-28.676-5.564-56.966 10.281-66.887 37.686L475.96 1144.612l110.708 40.342L900.95 383.877l755.976 192.9c-47.213 146.36-159.552 451.86-267.253 736.308-93.883 248.175-147.913 333.16-250.611 350.512-.114-.123-.227-.228-.227-.41-209.875 29.411-206.116-238.14-206.116-238.14l-670.28-270.759c-4.461 28.3-6.468 54.592-6.442 78.991.28 235.896 190.786 295.536 190.786 295.536l490.755 222.793c.105.07 76.722 36.564 159.98 32.804 224.59-5.276 303.51-168.35 402.405-429.618 142.872-377.593 287.875-800.656 289.295-805.143 5.11-16.012 3.093-33.286-5.45-47.659zm-423.72 406.944c5.11 1.394 10.403 2.139 15.626 2.139 25.846 0 49.492-17.24 56.739-43.374 8.615-31.42-9.99-63.864-41.35-72.418l-255.36-78.816c-31.006-8.58-63.819 9.85-72.39 41.244-8.545 31.402 9.877 63.847 41.367 72.444l255.369 78.781zM929.276 647.747c36.073 0 65.354 28.466 65.354 63.53 0 35.084-29.28 63.514-65.354 63.514-36.1 0-65.362-28.422-65.362-63.514 0-35.064 29.263-63.53 65.362-63.53zm-109.745 566.609c-25.24-10.08-54.25 2.27-64.311 27.484-10.132 25.355 2.393 54.285 27.633 64.363 25.241 10.08 54.102-2.33 64.224-27.685 10.07-25.215-2.305-54.092-27.546-64.162zm8.914-422.607l-18.37 45.995 45.993 18.36 12.955 207.517-54.215 42.34c-4.97 5.942-11 14.189-13.725 21.016-10.123 25.354 2.392 54.294 27.633 64.372l275.561 110.034 18.37-46.003-266.377-106.362c-2.357-.946-3.698-4.075-2.76-6.432l.894-2.235 36.23-30.771 170.033 67.896c18.265 7.292 35.758 3.584 48.212-7.424l142.269-116.274c6.432-2.76 7.37-5.117 9.22-9.737 5.45-13.663-.062-26.52-13.875-32.04L906.524 876.251l-2.244-54.224-75.835-30.28zm220.794 514.323c-25.24-10.078-54.241 2.278-64.311 27.493-10.124 25.354 2.393 54.285 27.633 64.364 25.24 10.078 54.092-2.332 64.223-27.686 10.061-25.215-2.313-54.093-27.545-64.171zm398.216-581.018c5.11 1.394 10.402 2.138 15.626 2.138 25.845 0 49.49-17.238 56.739-43.374 8.615-31.418-9.991-63.863-41.35-72.417l-255.36-78.816c-31.007-8.58-63.82 9.85-72.392 41.244-8.544 31.402 9.878 63.847 41.367 72.444l255.37 78.781zM1016.68 463.865c36.072 0 65.354 28.467 65.354 63.532 0 35.083-29.282 63.513-65.354 63.513-36.1 0-65.364-28.422-65.364-63.513 0-35.065 29.263-63.532 65.364-63.532z" />
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-6">
                                        <a href="{{route('admin.order.list', 'scheduled')}}" class="card">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="text-start">
                                                        <h2 class="counter" style="visibility: visible;">{{ $count['scheduledOrders'] }}</h2>
                                                        Scheduled Orders
                                                    </div>
                                                    <div class="bg-warning text-white rounded-circle p-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="40" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="icon-40">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-6">
                                        <a href="{{route('admin.customer.list')}}" class="card">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="text-start">
                                                        <h2 class="counter" style="visibility: visible;">{{ $count['customers'] }}</h2>
                                                        Customers
                                                    </div>
                                                    <div class="bg-primary text-white rounded-circle p-3">
                                                        <svg class="icon-30" xmlns="http://www.w3.org/2000/svg" width="80" fill="none" viewBox="0 0 24 24" stroke="#fff">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                                            </path>
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-6">
                                        <a href="{{route('admin.restaurant.list')}}" class="card">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="text-start">
                                                        <h2 class="counter" style="visibility: visible;">{{ $count['restaurants'] }}</h2>
                                                        Restaurants
                                                    </div>
                                                    <div class="bg-primary text-white rounded-circle p-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="40" fill="#fff" class="card-slie-arrow icon-50" viewBox="0 0 64 64">
                                                            <path
                                                                style="font-feature-settings:normal;font-variant-alternates:normal;font-variant-caps:normal;font-variant-east-asian:normal;font-variant-ligatures:normal;font-variant-numeric:normal;font-variant-position:normal;font-variation-settings:normal;inline-size:0;isolation:auto;mix-blend-mode:normal;shape-margin:0;shape-padding:0;text-decoration-color:#fff;text-decoration-line:none;text-decoration-style:solid;text-indent:0;text-orientation:mixed;text-transform:none"
                                                                d="M11 4C9.355 4 8 5.355 8 7v2.068a13.74 13.74 0 0 1-.465 3.541L4.531 23.875A15.741 15.741 0 0 0 4 27.932V30.5a7.502 7.502 0 0 0 4 6.63V57c0 1.645 1.355 3 3 3h42c1.645 0 3-1.355 3-3V37.13a7.502 7.502 0 0 0 4-6.629v-2.568c0-1.37-.178-2.733-.531-4.057L56.465 12.61A13.74 13.74 0 0 1 56 9.07V7c0-1.645-1.355-3-3-3H11zm0 2h42c.564 0 1 .436 1 1v2.068c0 .312.01.622.03.932H9.97c.019-.31.03-.62.03-.932V7c0-.564.435-1 1-1zm-1.277 6h44.555c.071.377.154.753.254 1.125l3.004 11.266c.053.202.098.406.142.61H6.323c.045-.204.089-.408.143-.61L9.47 13.125c.099-.372.182-.748.253-1.125zm-3.69 15h51.934c.021.31.033.62.033.932V30.5c0 3.064-2.435 5.5-5.5 5.5S47 33.564 47 30.5V30a1 1 0 0 0-2 0c0 3.341-2.658 6-6 6s-6-2.659-6-6a1 1 0 0 0-2 0c0 3.341-2.658 6-6 6s-6-2.659-6-6a1 1 0 0 0-2 0v.5c0 3.064-2.435 5.5-5.5 5.5S6 33.564 6 30.5v-2.568c0-.312.012-.622.034-.932zM32 33.664C33.34 36.208 35.928 38 39 38c2.994 0 5.508-1.718 6.88-4.156C47.117 36.28 49.581 38 52.5 38a7.51 7.51 0 0 0 1.5-.15V57c0 .564-.436 1-1 1H30v-8c0-4.415-3.585-8-8-8s-8 3.585-8 8v8h-3c-.564 0-1-.436-1-1V37.85c.485.098.986.15 1.5.15 2.918 0 5.382-1.719 6.621-4.156C19.493 36.282 22.006 38 25.001 38c3.071 0 5.659-1.792 7-4.336zM22 44c3.341 0 6 2.659 6 6v8H16v-8c0-3.341 2.659-6 6-6z"
                                                                color="#fff" paint-order="fill markers stroke" />
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-6">
                                        <a href="{{route('admin.report.tax')}}" class="card">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="text-start">
                                                        <h2 class="counter" style="visibility: visible;">{{ Helpers::format_currency($count['earning']) }}</h2>
                                                        EARNINGS
                                                    </div>
                                                    <div class="bg-primary text-white rounded-circle p-3">
                                                        <svg class="icon-30" xmlns="http://www.w3.org/2000/svg" width="20px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                                            </path>
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-6">
                                        <a href="{{route('admin.report.tax')}}" class="card">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="text-start">
                                                        <h2 class="counter" style="visibility: visible;">{{ Helpers::format_currency($count['collection']) }}</h2>
                                                        Collection
                                                    </div>
                                                    <div class="bg-primary text-white rounded-circle p-3">
                                                        <svg class="icon-30" xmlns="http://www.w3.org/2000/svg" width="20px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                                            </path>
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card mb-3">
                                    <div class="card-header d-flex justify-content-between">
                                        <div class="header-title d-flex">
                                            <h4 class="card-title">Recent Orders</h4>
                                            @if(Helpers::isAdmin())
                                            <div class="dropstart ms-2">
                                                <span class="badge bg-soft-warning py-2 px-3  dropdown-toggle me-2" type="button" id="dropstartMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Zone
                                                </span>
                                                @php($zones = App\Models\Zone::isActive()->get())
                                                <ul class="dropdown-menu" aria-labelledby="dropstartMenuButton">
                                                    <li>
                                                        <h6 type="button" class="dropdown-header zone-changer" zone-id="all">All</h6>
                                                    </li>
                                                    @foreach ($zones as $zone)
                                                        <li><a class="dropdown-item zone-changer" zone-id="{{ $zone->id }}" href="javascript:void(0)">{{ Str::ucfirst($zone->name) }}</a></li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="card-body px-0 " id="new-orders">
                                        <div class="table-responsive" top-latest-orders="zone-wise">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                              
                        {{-- Modern Charts Section --}}

                        <div class="row mb-4">
                            {{-- Orders Chart --}}
                            <div class="col-lg-6 col-md-12 mb-4">
                                <div class="card shadow-lg border-0 rounded-4 h-100">
                                    <div class="card-header chart-header-orders text-white d-flex justify-content-between align-items-center rounded-top-4 py-3">
                                        <div class="header-title d-flex align-items-center">
                                            <div class="bg-white bg-opacity-20 rounded-circle p-2 me-3">
                                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M19,7H22V9H19V7M19,10H22V12H19V10M19,13H22V15H19V13M13,7H16V9H13V7M13,10H16V12H13V10M13,13H16V15H13V13M7,7H10V9H7V7M7,10H10V12H7V10M7,13H10V15H7V13M19,3H5C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5C21,3.89 20.1,3 19,3Z"/>
                                                </svg>
                                            </div>
                                            <h5 class="card-title mb-0 fw-bold">Orders Trend</h5>
                                        </div>
                                        <div class="stats-number text-white">{{ number_format($count['totalOrders']) }}</div>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-container">
                                            <canvas id="ordersChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Payments Chart --}}
                            <div class="col-lg-6 col-md-12 mb-4">
                                <div class="card shadow-lg border-0 rounded-4 h-100">
                                    <div class="card-header chart-header-payments text-white d-flex justify-content-between align-items-center rounded-top-4 py-3">
                                        <div class="header-title d-flex align-items-center">
                                            <div class="bg-white bg-opacity-20 rounded-circle p-2 me-3">
                                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M20,4H4C2.89,4 2,4.89 2,6V18A2,2 0 0,0 4,20H20A2,2 0 0,0 22,18V6C22,4.89 21.1,4 20,4M20,18H4V8H20V18M7,13V15H9V13H7M11,13V15H17V13H11Z"/>
                                                </svg>
                                            </div>
                                            <h5 class="card-title mb-0 fw-bold">Payments Volume</h5>
                                        </div>
                                        <div class="stats-number text-white">{{ Helpers::format_currency($count['collection']) }}</div>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-container">
                                            <canvas id="paymentsChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            {{-- Ratings Chart --}}
                            <div class="col-lg-6 col-md-12 mb-4">
                                <div class="card shadow-lg border-0 rounded-4 h-100">
                                    <div class="card-header chart-header-ratings text-white d-flex justify-content-between align-items-center rounded-top-4 py-3">
                                        <div class="header-title d-flex align-items-center">
                                            <div class="bg-white bg-opacity-20 rounded-circle p-2 me-3">
                                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12,17.27L18.18,21L16.54,13.97L22,9.24L14.81,8.62L12,2L9.19,8.62L2,9.24L7.45,13.97L5.82,21L12,17.27Z"/>
                                                </svg>
                                            </div>
                                            <h5 class="card-title mb-0 fw-bold">Ratings Distribution</h5>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-white text-warning px-3 py-2 rounded-pill">⭐ Reviews</span>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-container">
                                            <canvas id="ratingsChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Earnings Chart --}}
                            <div class="col-lg-6 col-md-12 mb-4">
                                <div class="card shadow-lg border-0 rounded-4 h-100">
                                    <div class="card-header chart-header-earnings text-white d-flex justify-content-between align-items-center rounded-top-4 py-3">
                                        <div class="header-title d-flex align-items-center">
                                            <div class="bg-white bg-opacity-20 rounded-circle p-2 me-3">
                                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M7,15H9C9,16.08 10.37,17 12,17C13.63,17 15,16.08 15,15C15,13.9 13.96,13.5 11.76,12.97C9.64,12.44 7,11.78 7,9C7,7.21 8.47,5.69 10.5,5.18V3H13.5V5.18C15.53,5.69 17,7.21 17,9H15C15,7.92 13.63,7 12,7C10.37,7 9,7.92 9,9C9,10.1 10.04,10.5 12.24,11.03C14.36,11.56 17,12.22 17,15C17,16.79 15.53,18.31 13.5,18.82V21H10.5V18.82C8.47,18.31 7,16.79 7,15Z"/>
                                                </svg>
                                            </div>
                                            <h5 class="card-title mb-0 fw-bold">Earnings Trend</h5>
                                        </div>
                                        <div class="stats-number text-white">{{ Helpers::format_currency($count['earning']) }}</div>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-container">
                                            <canvas id="earningsChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card shadow-lg mb-4 border-0 rounded-4">
                                    <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center rounded-top-4 py-3">
                                        <div class="header-title d-flex align-items-center">
                                            <div class="bg-white bg-opacity-20 rounded-circle p-2 me-3">
                                                <svg width="24" height="24" fill="#0557af" viewBox="0 0 24 24">
                                                    <path d="M12 2C13.1 2 14 2.9 14 4C14 5.1 13.1 6 12 6C10.9 6 10 5.1 10 4C10 2.9 10.9 2 12 2ZM21 9V7L15 7.5L14 8.5V17H12V11H10V17H8V8.5L7 7.5L1 7V9L7 8.5V20H9V22H15V20H17V8.5L21 9Z"/>
                                                </svg>
                                            </div>
                                            <h4 class="card-title mb-0 fw-bold">New Signups</h4>
                                        </div>
                                        <span class="badge bg-white text-primary px-3 py-2 rounded-pill">
                                            {{ count($restaurantSignUp) + count($deliveryBoySignUp) }} Total
                                        </span>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="list-group list-group-flush">
                                            @foreach ($restaurantSignUp as $signUpForm)
                                            <div class="list-group-item border-0 py-3 px-4 hover-bg-light">
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                                                        <svg width="24" height="24" fill="currentColor" class="text-success" viewBox="0 0 24 24">
                                                            <path d="M12,2A3,3 0 0,1 15,5V7H19A1,1 0 0,1 20,8V20A1,1 0 0,1 19,21H5A1,1 0 0,1 4,20V8A1,1 0 0,1 5,7H9V5A3,3 0 0,1 12,2M12,4A1,1 0 0,0 11,5V7H13V5A1,1 0 0,0 12,4M6,9V19H18V9H6M8,11H16V13H8V11M8,15H13V17H8V15Z"/>
                                                        </svg>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex justify-content-between align-items-start">
                                                            <div>
                                                                <h6 class="mb-1 fw-bold text-dark">{{Str::ucfirst($signUpForm['restaurant_name'])}}</h6>
                                                                <div class="d-flex align-items-center mb-1">
                                                                    <svg width="16" height="16" fill="currentColor" class="text-muted me-1" viewBox="0 0 24 24">
                                                                        <path d="M6.62,10.79C8.06,13.62 10.38,15.94 13.21,17.38L15.41,15.18C15.69,14.9 16.08,14.82 16.43,14.93C17.55,15.3 18.75,15.5 20,15.5A1,1 0 0,1 21,16.5V20A1,1 0 0,1 20,21A17,17 0 0,1 3,4A1,1 0 0,1 4,3H7.5A1,1 0 0,1 8.5,4C8.5,5.25 8.7,6.45 9.07,7.57C9.18,7.92 9.1,8.31 8.82,8.59L6.62,10.79Z"/>
                                                                    </svg>
                                                                    <span class="text-muted small">{{$signUpForm['restaurant_phone']}}</span>
                                                                </div>
                                                                <div class="d-flex align-items-center">
                                                                    <svg width="14" height="14" fill="currentColor" class="text-muted me-1" viewBox="0 0 24 24">
                                                                        <path d="M12,20A8,8 0 0,0 20,12A8,8 0 0,0 12,4A8,8 0 0,0 4,12A8,8 0 0,0 12,20M12,2A10,10 0 0,1 22,12A10,10 0 0,1 12,22C6.47,22 2,17.5 2,12A10,10 0 0,1 12,2M12.5,7V12.25L17,14.92L16.25,16.15L11,13V7H12.5Z"/>
                                                                    </svg>
                                                                    <span class="text-muted small">{{Helpers::timeAgo($signUpForm['created_at'])}}</span>
                                                                </div>
                                                            </div>
                                                            <a href="{{route('admin.joinas.restaurant-show', ['id' => $signUpForm['id']])}}" class="btn btn-outline-success btn-sm rounded-pill px-3">
                                                                <svg width="14" height="14" fill="currentColor" class="me-1" viewBox="0 0 24 24">
                                                                    <path d="M12,2A3,3 0 0,1 15,5V7H19A1,1 0 0,1 20,8V20A1,1 0 0,1 19,21H5A1,1 0 0,1 4,20V8A1,1 0 0,1 5,7H9V5A3,3 0 0,1 12,2Z"/>
                                                                </svg>
                                                                Restaurant
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                            @foreach ($deliveryBoySignUp as $signUpForm)
                                            <div class="list-group-item border-0 py-3 px-4 hover-bg-light">
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-info bg-opacity-10 rounded-circle p-3 me-3">
                                                        <svg width="24" height="24" fill="currentColor" class="text-info" viewBox="0 0 24 24">
                                                            <path d="M5,4A1,1 0 0,0 4,5V17A1,1 0 0,0 5,18H6V20A1,1 0 0,0 7,21H17A1,1 0 0,0 18,20V18H19A1,1 0 0,0 20,17V5A1,1 0 0,0 19,4H5M6,6H18V16H6V6M7,8V10H17V8H7M7,12V14H15V12H7Z"/>
                                                        </svg>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex justify-content-between align-items-start">
                                                            <div>
                                                                <h6 class="mb-1 fw-bold text-dark">{{Str::ucfirst($signUpForm['deliveryman_name'])}}</h6>
                                                                <div class="d-flex align-items-center mb-1">
                                                                    <svg width="16" height="16" fill="currentColor" class="text-muted me-1" viewBox="0 0 24 24">
                                                                        <path d="M6.62,10.79C8.06,13.62 10.38,15.94 13.21,17.38L15.41,15.18C15.69,14.9 16.08,14.82 16.43,14.93C17.55,15.3 18.75,15.5 20,15.5A1,1 0 0,1 21,16.5V20A1,1 0 0,1 20,21A17,17 0 0,1 3,4A1,1 0 0,1 4,3H7.5A1,1 0 0,1 8.5,4C8.5,5.25 8.7,6.45 9.07,7.57C9.18,7.92 9.1,8.31 8.82,8.59L6.62,10.79Z"/>
                                                                    </svg>
                                                                    <span class="text-muted small">{{$signUpForm['deliveryman_phone']}}</span>
                                                                </div>
                                                                <div class="d-flex align-items-center">
                                                                    <svg width="14" height="14" fill="currentColor" class="text-muted me-1" viewBox="0 0 24 24">
                                                                        <path d="M12,20A8,8 0 0,0 20,12A8,8 0 0,0 12,4A8,8 0 0,0 4,12A8,8 0 0,0 12,20M12,2A10,10 0 0,1 22,12A10,10 0 0,1 12,22C6.47,22 2,17.5 2,12A10,10 0 0,1 12,2M12.5,7V12.25L17,14.92L16.25,16.15L11,13V7H12.5Z"/>
                                                                    </svg>
                                                                    <span class="text-muted small">{{Helpers::timeAgo($signUpForm['created_at'])}}</span>
                                                                </div>
                                                            </div>
                                                            <a href="{{route('admin.joinas.deliveryman-show', ['id' => $signUpForm['id']])}}" class="btn btn-outline-info btn-sm rounded-pill px-3">
                                                                <svg width="14" height="14" fill="currentColor" class="me-1" viewBox="0 0 24 24">
                                                                    <path d="M5,4A1,1 0 0,0 4,5V17A1,1 0 0,0 5,18H6V20A1,1 0 0,0 7,21H17A1,1 0 0,0 18,20V18H19A1,1 0 0,0 20,17V5A1,1 0 0,0 19,4H5Z"/>
                                                                </svg>
                                                                Deliveryman
                                                            </a>
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
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card shadow-lg mb-4 border-0 rounded-4">
                                    <div class="card-header bg-gradient-warning text-white d-flex justify-content-between align-items-center rounded-top-4 py-3">
                                        <div class="header-title d-flex align-items-center">
                                            <div class="bg-white bg-opacity-20 rounded-circle p-2 me-3">
                                                <svg width="24" height="24" fill="#e0a800" viewBox="0 0 24 24">
                                                    <path d="M5,16L3,5H1V3H4L6,14H18V16H5M7,18A2,2 0 0,0 5,20A2,2 0 0,0 7,22A2,2 0 0,0 9,20A2,2 0 0,0 7,18M17,18A2,2 0 0,0 15,20A2,2 0 0,0 17,22A2,2 0 0,0 19,20A2,2 0 0,0 17,18M7.2,14L5.2,5H20V7H7.4L8.2,10H20V12H8.6L9.2,14H7.2Z"/>
                                                </svg>
                                            </div>
                                            <h4 class="card-title mb-0 fw-bold">Top Restaurants</h4>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <svg width="20" height="20" fill="#e0a800" class="me-2" viewBox="0 0 24 24">
                                                <path d="M12,17.27L18.18,21L16.54,13.97L22,9.24L14.81,8.62L12,2L9.19,8.62L2,9.24L7.45,13.97L5.82,21L12,17.27Z"/>
                                            </svg>
                                            <span class="badge bg-white text-warning px-3 py-2 rounded-pill">Top Performers</span>
                                        </div>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="list-group list-group-flush">
                                            <?php
                                            $counter = 0;
                                            $medalColors = ['text-warning', 'text-secondary', 'text-warning'];
                                            $bgColors = ['border border-warning', 'border border-secondary', 'border border-warning'];
                                            foreach ($topRestaurant as $key => $restaurant) : ?>
                                            <?php
                                            $counter++;
                                            if ($counter == 6) {
                                                break;
                                            }
                                            $medalColor = $counter <= 3 ? $medalColors[$counter-1] : 'text-primary';
                                            $bgColor = $counter <= 3 ? $bgColors[$counter-1] : 'bg-primary';
                                            ?>
                                            <div class="list-group-item border-0 py-3 px-4 hover-bg-light position-relative">
                                                @if($counter <= 3)
                                                <div class="position-absolute top-0 end-0 mt-2 me-2">
                                                    <svg width="20" height="20" fill="currentColor" class="{{ $medalColor }}" viewBox="0 0 24 24">
                                                        <path d="M5,16L3,5H1V3H4L6,14H18V16H5M7,18A2,2 0 0,0 5,20A2,2 0 0,0 7,22A2,2 0 0,0 9,20A2,2 0 0,0 7,18M17,18A2,2 0 0,0 15,20A2,2 0 0,0 17,22A2,2 0 0,0 19,20A2,2 0 0,0 17,18Z"/>
                                                    </svg>
                                                </div>
                                                @endif
                                                <div class="d-flex align-items-center">
                                                    <div class="{{ $bgColor }} bg-opacity-15 rounded-circle p-3 me-3 position-relative">
                                                        <div class="fw-bold {{ $medalColor }} fs-4">{{ $counter }}</div>
                                                        @if($counter == 1)
                                                        <div class="position-absolute top-0 start-0 translate-middle">
                                                            <svg width="16" height="16" fill="currentColor" class="text-warning" viewBox="0 0 24 24">
                                                                <path d="M12,17.27L18.18,21L16.54,13.97L22,9.24L14.81,8.62L12,2L9.19,8.62L2,9.24L7.45,13.97L5.82,21L12,17.27Z"/>
                                                            </svg>
                                                        </div>
                                                        @endif
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex justify-content-between align-items-start">
                                                            <div>
                                                                <h6 class="mb-2 fw-bold text-dark">{{ Str::ucfirst($restaurant['name']) }}</h6>
                                                                <div class="row g-0">
                                                                    <div class="col-auto">
                                                                        <div class="d-flex align-items-center mb-1">
                                                                            <div class="bg-success bg-opacity-10 rounded p-1 me-2">
                                                                                <svg width="14" height="14" fill="currentColor" class="text-success" viewBox="0 0 24 24">
                                                                                    <path d="M7,15H9C9,16.08 10.37,17 12,17C13.63,17 15,16.08 15,15C15,13.9 13.96,13.5 11.76,12.97C9.64,12.44 7,11.78 7,9C7,7.21 8.47,5.69 10.5,5.18V3H13.5V5.18C15.53,5.69 17,7.21 17,9H15C15,7.92 13.63,7 12,7C10.37,7 9,7.92 9,9C9,10.1 10.04,10.5 12.24,11.03C14.36,11.56 17,12.22 17,15C17,16.79 15.53,18.31 13.5,18.82V21H10.5V18.82C8.47,18.31 7,16.79 7,15Z"/>
                                                                                </svg>
                                                                            </div>
                                                                            <span class="fw-bold text-success">{{ Helpers::format_currency($restaurant['revenue']) }}</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex align-items-center text-muted small">
                                                                    <svg width="14" height="14" fill="currentColor" class="me-1" viewBox="0 0 24 24">
                                                                        <path d="M19,7H22V9H19V12H17V9H14V7H17V4H19V7M7,15H9C9,16.08 10.37,17 12,17C13.63,17 15,16.08 15,15C15,13.9 13.96,13.5 11.76,12.97C9.64,12.44 7,11.78 7,9C7,7.21 8.47,5.69 10.5,5.18V3H13.5V5.18C15.53,5.69 17,7.21 17,9H15C15,7.92 13.63,7 12,7C10.37,7 9,7.92 9,9C9,10.1 10.04,10.5 12.24,11.03C14.36,11.56 17,12.22 17,15C17,16.79 15.53,18.31 13.5,18.82V21H10.5V18.82C8.47,18.31 7,16.79 7,15Z"/>
                                                                    </svg>
                                                                    <span>{{ $restaurant['orders'] }} orders</span>
                                                                </div>
                                                            </div>
                                                            <div class="text-end">
                                                                <div class="badge {{ $bgColor }} bg-opacity-20 {{ $medalColor }} px-2 py-1 rounded-pill">
                                                                    #{{ $counter }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card shadow-lg mb-4 border-0 rounded-4">
                                    <div class="card-header bg-gradient-info text-white d-flex justify-content-between align-items-center rounded-top-4 py-3">
                                        <div class="header-title d-flex align-items-center">
                                            <div class="bg-white bg-opacity-20 rounded-circle p-2 me-3">
                                                <svg width="24" height="24" fill="#117a8b" viewBox="0 0 24 24">
                                                    <path d="M12,17.27L18.18,21L16.54,13.97L22,9.24L14.81,8.62L12,2L9.19,8.62L2,9.24L7.45,13.97L5.82,21L12,17.27Z"/>
                                                </svg>
                                            </div>
                                            <h4 class="card-title mb-0 fw-bold">Latest Reviews</h4>
                                        </div>
                                        <a href="{{route('admin.customer.rating')}}" class="btn btn-light btn-sm rounded-pill px-3 py-2 fw-semibold">
                                            <svg width="16" height="16" fill="currentColor" class="me-1" viewBox="0 0 24 24">
                                                <path d="M13,9H18.5L13,3.5V9M6,2H14L20,8V20A2,2 0 0,1 18,22H6C4.89,22 4,21.1 4,20V4C4,2.89 4.89,2 6,2M15,18V16H6V18H15M18,14V12H6V14H18Z"/>
                                            </svg>
                                            See All
                                        </a>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="list-group list-group-flush">
                                            @foreach ($reviews as $review)
                                            <div class="list-group-item border-0 py-3 px-4 hover-bg-light">
                                                <div class="d-flex">
                                                    <div class="border border-primary bg-opacity-10 rounded-circle p-1 me-3 d-flex align-items-center justify-content-center" style="width:40px!important; height:40px!important;">
                                                        <svg width="24" height="24" fill="currentColor" class="text-primary" viewBox="0 0 24 24">
                                                            <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M7.07,18.28C7.5,17.38 8.12,16.5 8.91,15.77C9.71,15.04 10.75,14.5 11.83,14.16C9.68,13.65 8,11.75 8,9.5C8,6.96 10.04,5 12.5,5C14.96,5 17,6.96 17,9.5A4.5,4.5 0 0,1 12.5,14C12.73,14 12.96,14.03 13.18,14.07C13.1,14.23 13,14.39 12.91,14.54C12.64,15 12.26,15.65 11.97,16.31C11.68,16.97 11.47,17.66 11.35,18.28C11.32,18.34 11.28,18.4 11.25,18.46C10.98,18.54 10.71,18.59 10.43,18.61C10.1,18.64 9.77,18.64 9.43,18.61C8.75,18.54 8.14,18.28 7.58,17.93C7.41,17.82 7.25,17.69 7.07,17.56V18.28M16.93,18.28C16.5,17.38 15.88,16.5 15.09,15.77C14.29,15.04 13.25,14.5 12.17,14.16C14.32,13.65 16,11.75 16,9.5C16,6.96 13.96,5 11.5,5C9.04,5 7,6.96 7,9.5A4.5,4.5 0 0,0 11.5,14C11.27,14 11.04,14.03 10.82,14.07C10.9,14.23 11,14.39 11.09,14.54C11.36,15 11.74,15.65 12.03,16.31C12.32,16.97 12.53,17.66 12.65,18.28C12.68,18.34 12.72,18.4 12.75,18.46C13.02,18.54 13.29,18.59 13.57,18.61C13.9,18.64 14.23,18.64 14.57,18.61C15.25,18.54 15.86,18.28 16.42,17.93C16.59,17.82 16.75,17.69 16.93,17.56V18.28Z"/>
                                                        </svg>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                                            <div>
                                                                <h6 class="mb-1 fw-bold text-dark">{{Str::ucfirst($review->customer->f_name)}} {{Str::ucfirst($review->customer->l_name)}}</h6>
                                                                <div class="small text-muted">
                                                                    Order: <a href="{{route('admin.order.details', $review->order_id)}}" class="text-primary text-decoration-none fw-semibold">#{{$review->order_id}}</a>
                                                                </div>
                                                            </div>
                                                            <div class="text-end">
                                                                <div class="d-flex align-items-center mb-1">
                                                                    @for($i = 1; $i <= 5; $i++)
                                                                        @if($i <= $review->rating)
                                                                            <svg width="14" height="14" fill="currentColor" class="text-warning" viewBox="0 0 24 24">
                                                                                <path d="M12,17.27L18.18,21L16.54,13.97L22,9.24L14.81,8.62L12,2L9.19,8.62L2,9.24L7.45,13.97L5.82,21L12,17.27Z"/>
                                                                            </svg>
                                                                        @else
                                                                            <svg width="14" height="14" fill="currentColor" class="text-muted" viewBox="0 0 24 24">
                                                                                <path d="M12,15.39L8.24,17.66L9.23,13.38L5.91,10.5L10.29,10.13L12,6.09L13.71,10.13L18.09,10.5L14.77,13.38L15.76,17.66M22,9.24L14.81,8.63L12,2L9.19,8.63L2,9.24L7.45,13.97L5.82,21L12,17.27L18.18,21L16.54,13.97L22,9.24Z"/>
                                                                            </svg>
                                                                        @endif
                                                                    @endfor
                                                                    <span class="ms-2 fw-bold text-warning">{{$review->rating}}</span>
                                                                </div>
                                                                <small class="text-muted">{{Helpers::timeAgo($review->created_at)}}</small>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="mb-2">
                                                            <blockquote class="blockquote-sm mb-2">
                                                                <p class="mb-0 text-dark fst-italic">"{{Str::ucfirst($review->review)}}"</p>
                                                            </blockquote>
                                                        </div>

                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <div class="d-flex align-items-center">
                                                                @if($review->review_to == 'restaurant')
                                                                <div class="bg-success bg-opacity-10 rounded px-2 py-1 me-2">
                                                                    <svg width="14" height="14" fill="currentColor" class="text-success me-1" viewBox="0 0 24 24">
                                                                        <path d="M12,2A3,3 0 0,1 15,5V7H19A1,1 0 0,1 20,8V20A1,1 0 0,1 19,21H5A1,1 0 0,1 4,20V8A1,1 0 0,1 5,7H9V5A3,3 0 0,1 12,2Z"/>
                                                                    </svg>
                                                                    <small class="text-success fw-semibold">{{Str::ucfirst($review->restaurant->name)}}</small>
                                                                </div>
                                                                @elseif ($review->review_to == 'deliveryman')
                                                                <div class="border border-info bg-opacity-10 rounded px-2 py-1 me-2">
                                                                    <svg width="14" height="14" fill="currentColor" class="text-info me-1" viewBox="0 0 24 24">
                                                                    </svg>
                                                                    <small class="text-info fw-semibold">{{Str::ucfirst($review->deliveryman->f_name)." ".Str::ucfirst($review->deliveryman->l_name)}}</small>
                                                                </div>
                                                                @endif
                                                                <span class="badge bg-light text-dark rounded-pill px-2 py-1">
                                                                    <small>{{Str::ucfirst($review->review_to)}}</small>
                                                                </span>
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
                </div>
            </div>

        </div>
    </div>
@endsection
@vite(['resources/js/app.js'])
@push('javascript')
    <script src="{{ asset('assets/vendor/flatpickr/dist/flatpickr.min.js') }}"></script>
    <script>
        /*===========// zone change //=================*/
        document.querySelectorAll('.zone-changer').forEach(zone => {
            zone.addEventListener('click', async () => {
                try {
                    const zoneId = zone.getAttribute('zone-id');
                    let url = "{{ route('admin.zone.set-order-zone') }}?zone_id=" + zoneId;
                    const resp = await fetch(url);
                    if (resp.ok) {
                        const result = await resp.json();
                        toastr.success(result.message);
                    }
                    getLatestOrders();
                } catch (error) {
                    toastr.error(error.message);
                }

            })
        })

        async function getLatestOrders() {
            try {
                const resp = await fetch("{{ route('admin.order.top-orders') }}");
                if (!resp.ok) {
                    const error = await resp.json();
                    throw new Error(error.message);
                }

                const result = await resp.json();
                const container = document.querySelector('[top-latest-orders="zone-wise"]');
                container.innerHTML = result.view;

                // Reinitialize Select2 for dynamically loaded content
                if (typeof window.reinitializeSelect2 === 'function') {
                    window.reinitializeSelect2(container);
                }

                change_order_state();

            } catch (error) {
                //toastr.error(error.message);
                console.warn(error.message);
            }
        }
        window.addEventListener('load', getLatestOrders);


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
                eventButton.addEventListener('click', function(event) {
                    event.stopPropagation();
                    let route = "{{ route('admin.order.order-status-update') }}?";
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
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.Echo.private('order')
            .listen('.order.updated', (e) => {
                console.log("✅ Received message from Laravel:", e.message);
            });

            window.Echo.private('admin')
            .listen('.order.placed', (e) => {
                console.log('New Order Received:');
                //reload orders
                getLatestOrders();
                console.log('Order ID:', e.order_id);
                console.log('Instructions:', e.instructions);
                console.log('Amount:', e.amount);
                console.log('Placed at:', e.placed_at);
            });
        });

    </script>

    <script>
    flatpickr('input[name="start_date"]', {
        dateFormat: "d-m-Y"
    });
    flatpickr('input[name="end_date"]', {
        dateFormat: "d-m-Y"
    });
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chart.js default configuration
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.font.size = 12;
    Chart.defaults.color = '#64748b';
    Chart.defaults.borderColor = '#e2e8f0';
    Chart.defaults.backgroundColor = '#f8fafc';

    // Chart data from Laravel
    const chartData = @json($chartData ?? []);
    
    // Provide default data if empty
    const defaultChartData = {
        orders: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            data: [12, 19, 3, 5, 2, 3]
        },
        payments: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            data: [1200, 1900, 300, 500, 200, 300]
        },
        ratings: {
            labels: ['1 Star', '2 Stars', '3 Stars', '4 Stars', '5 Stars'],
            data: [5, 10, 15, 25, 45]
        },
        earnings: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            data: [120, 190, 30, 50, 20, 30]
        }
    };
    
    // Merge with default data if needed
    const finalChartData = {
        orders: chartData.orders || defaultChartData.orders,
        payments: chartData.payments || defaultChartData.payments,
        ratings: chartData.ratings || defaultChartData.ratings,
        earnings: chartData.earnings || defaultChartData.earnings
    };

    console.log('Chart data loaded:', finalChartData);

    // Orders Chart
    const ordersCanvas = document.getElementById('ordersChart');
    if (!ordersCanvas) {
        console.error('Orders chart canvas not found');
        return;
    }
    
    try {
        const ordersCtx = ordersCanvas.getContext('2d');
        console.log('Orders chart data:', finalChartData.orders);
        
        const ordersChart = new Chart(ordersCtx, {
        type: 'line',
        data: {
            labels: finalChartData.orders.labels,
            datasets: [{
                label: 'Orders',
                data: finalChartData.orders.data,
                borderColor: '#f093fb',
                backgroundColor: 'rgba(240, 147, 251, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#f5576c',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    borderColor: '#f093fb',
                    borderWidth: 1,
                    cornerRadius: 8,
                    displayColors: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(226, 232, 240, 0.5)',
                        drawBorder: false
                    },
                    ticks: {
                        color: '#64748b',
                        font: {
                            size: 11
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#64748b',
                        font: {
                            size: 11
                        }
                    }
                }
            },
            interaction: {
                intersect: false
            },
            onHover: (event, activeElements) => {
                event.native.target.style.cursor = activeElements.length > 0 ? 'pointer' : 'default';
            }
        },
        plugins: [{
            id: 'chartLoaded',
            afterRender: function(chart) {
                console.log('Orders chart rendered successfully');
            }
        }]
    });
    } catch (error) {
        console.error('Error creating orders chart:', error);
        const loader = document.getElementById('ordersLoading');
        if (loader) loader.style.display = 'none';
    }

    // Payments Chart
    const paymentsCanvas = document.getElementById('paymentsChart');
    if (!paymentsCanvas) {
        console.error('Payments chart canvas not found');
        return;
    }
    const paymentsCtx = paymentsCanvas.getContext('2d');
    const paymentsChart = new Chart(paymentsCtx, {
        type: 'bar',
        data: {
            labels: finalChartData.payments.labels,
            datasets: [{
                label: 'Payments',
                data: finalChartData.payments.data,
                backgroundColor: 'rgba(79, 172, 254, 0.8)',
                borderColor: '#4facfe',
                borderWidth: 2,
                hoverBackgroundColor: 'rgba(79, 172, 254, 1)',
                borderRadius: 8,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    borderColor: '#4facfe',
                    borderWidth: 1,
                    cornerRadius: 8,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return 'Amount: ₹' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(226, 232, 240, 0.5)',
                        drawBorder: false
                    },
                    ticks: {
                        color: '#64748b',
                        font: {
                            size: 11
                        },
                        callback: function(value) {
                            return '₹' + value.toLocaleString();
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#64748b',
                        font: {
                            size: 11
                        }
                    }
                }
            },
            onHover: (event, activeElements) => {
                event.native.target.style.cursor = activeElements.length > 0 ? 'pointer' : 'default';
            }
        },
        plugins: [{
            id: 'chartLoaded',
            afterRender: function(chart) {
                console.log('Payments chart rendered successfully');
            }
        }]
    });

    // Ratings Chart (Doughnut)
    const ratingsCanvas = document.getElementById('ratingsChart');
    if (!ratingsCanvas) {
        console.error('Ratings chart canvas not found');
        return;
    }
    const ratingsCtx = ratingsCanvas.getContext('2d');
    const ratingsChart = new Chart(ratingsCtx, {
        type: 'doughnut',
        data: {
            labels: finalChartData.ratings.labels,
            datasets: [{
                data: finalChartData.ratings.data,
                backgroundColor: [
                    '#ef4444',
                    '#f97316',
                    '#eab308',
                    '#22c55e',
                    '#06b6d4'
                ],
                borderColor: '#ffffff',
                borderWidth: 3,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        font: {
                            size: 11
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    borderColor: '#fdbb2d',
                    borderWidth: 1,
                    cornerRadius: 8,
                    displayColors: true
                }
            },
            cutout: '60%',
            onHover: (event, activeElements) => {
                event.native.target.style.cursor = activeElements.length > 0 ? 'pointer' : 'default';
            }
        },
        plugins: [{
            id: 'chartLoaded',
            afterRender: function(chart) {
                console.log('Ratings chart rendered successfully');
            }
        }]
    });

    // Earnings Chart
    const earningsCanvas = document.getElementById('earningsChart');
    if (!earningsCanvas) {
        console.error('Earnings chart canvas not found');
        return;
    }
    const earningsCtx = earningsCanvas.getContext('2d');
    const earningsChart = new Chart(earningsCtx, {
        type: 'line',
        data: {
            labels: finalChartData.earnings.labels,
            datasets: [{
                label: 'Earnings',
                data: finalChartData.earnings.data,
                borderColor: '#a8edea',
                backgroundColor: 'rgba(168, 237, 234, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#fed6e3',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    borderColor: '#a8edea',
                    borderWidth: 1,
                    cornerRadius: 8,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return 'Earnings: ₹' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(226, 232, 240, 0.5)',
                        drawBorder: false
                    },
                    ticks: {
                        color: '#64748b',
                        font: {
                            size: 11
                        },
                        callback: function(value) {
                            return '₹' + value.toLocaleString();
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#64748b',
                        font: {
                            size: 11
                        }
                    }
                }
            },
            interaction: {
                intersect: false
            },
            onHover: (event, activeElements) => {
                event.native.target.style.cursor = activeElements.length > 0 ? 'pointer' : 'default';
            }
        },
        plugins: [{
            id: 'chartLoaded',
            afterRender: function(chart) {
                console.log('Earnings chart rendered successfully');
            }
        }]
    });

    // Add chart refresh functionality when filter changes
    const refreshCharts = () => {
        [ordersChart, paymentsChart, ratingsChart, earningsChart].forEach(chart => {
            if (chart) {
                chart.update('active');
            }
        });
    };

    // Listen for filter changes
    document.querySelectorAll('.dropdown-item').forEach(item => {
        item.addEventListener('click', function() {
            console.log('Filter changed, page will reload with new data');
        });
    });
});
</script>
@endpush
