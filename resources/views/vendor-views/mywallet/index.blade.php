@extends('vendor-views.layouts.dashboard-main')
@push('css')
    <link rel="stylesheet" href="{{asset('assets/vendor/flatpickr/dist/flatpickr.min.css')}}">
@endpush
@section('content')
    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="d-flex align-items-center justify-content-between flex-wrap">
                    <div class="d-flex">
                        <div class="mb-0 d-flex align-items-center fw-bolder">
                            <svg width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                                class="me-2 icon-20">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M4.56517 3C3.70108 3 3 3.71286 3 4.5904V5.52644C3 6.17647 3.24719 6.80158 3.68936 7.27177L8.5351 12.4243L8.53723 12.4211C9.47271 13.3788 9.99905 14.6734 9.99905 16.0233V20.5952C9.99905 20.9007 10.3187 21.0957 10.584 20.9516L13.3436 19.4479C13.7602 19.2204 14.0201 18.7784 14.0201 18.2984V16.0114C14.0201 14.6691 14.539 13.3799 15.466 12.4243L20.3117 7.27177C20.7528 6.80158 21 6.17647 21 5.52644V4.5904C21 3.71286 20.3 3 19.4359 3H4.56517Z"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                </path>
                            </svg>
                            Filter ::
                        </div>
                        <div class="d-flex align-items-center flex-wrap ms-3">
                            <div class="dropdown me-3 fw-bolder">
                                <span class="dropdown-toggle align-items-center d-flex" id="dropdownMenuButton04"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <svg width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                                        class="me-2 icon-20">
                                        <path d="M3.09277 9.40421H20.9167" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M16.442 13.3097H16.4512" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M12.0045 13.3097H12.0137" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M7.55818 13.3097H7.56744" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M16.442 17.1962H16.4512" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M12.0045 17.1962H12.0137" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M7.55818 17.1962H7.56744" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M16.0433 2V5.29078" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M7.96515 2V5.29078" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M16.2383 3.5791H7.77096C4.83427 3.5791 3 5.21504 3 8.22213V17.2718C3 20.3261 4.83427 21.9999 7.77096 21.9999H16.229C19.175 21.9999 21 20.3545 21 17.3474V8.22213C21.0092 5.21504 19.1842 3.5791 16.2383 3.5791Z"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                    </svg>

                                    {{-- @if($filter == 'today')
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
                                    @endif --}}
                                </span>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton22"
                                    style="min-width:275px;">
                                    <li><a class="dropdown-item"
                                            href="{!! route('vendor.dashboard') . '?filter=this_week' !!}">This Week</a>
                                    </li>
                                    <li><a class="dropdown-item"
                                            href="{!! route('vendor.dashboard') . '?filter=this_month' !!}">This
                                            Month</a>
                                    </li>
                                    <li><a class="dropdown-item"
                                            href="{!! route('vendor.dashboard') . '?filter=this_year' !!}">This Year</a>
                                    </li>
                                    <li><a class="dropdown-item"
                                            href="{!! route('vendor.dashboard') . '?filter=previous_year' !!}">Previous
                                            Year</a></li>
                                    <li><a class="dropdown-item"
                                            href="{!! route('vendor.dashboard') . '?filter=today' !!}">Today</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0)">
                                            <form action="">
                                                <div
                                                    class="m-0 d-flex flex-column align-items-center justify-content-center">

                                                    <input type="text" name="date_range"
                                                        class="form-control range_flatpicker d-flex flatpickr-input active"
                                                        placeholder="Date Range" readonly="readonly" required>
                                                    <input type="hidden" name="filter" value="custom">
                                                    <button class="badge rounded-pill bg-success ms-1 mb-1 px-3 py-2"
                                                        type="submit">Go</button>
                                                </div>
                                            </form>

                                        </a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="fw-bolder"><i class="feather-refresh-cw me-2"></i></div>
                        <div class="fw-bolder"><i class="feather-download"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="page-header-title">My Wallet</h4>
                        </div>

                    </div>
                    <div class="card-body p-0">
                        <div class="new-user-info">
                            {{-- <h3 class="h-3 text-start" style="color: #38c54a">
                                <svg xmlns="http://www.w3.org/2000/svg" data-name="Layer 1" viewBox="0 0 512 512"
                                    width="50">
                                    <path
                                        d="M217 100h35.5v35.5a7.5 7.5 0 0 0 15 0V100H302a7.5 7.5 0 0 0 0-15h-34.5V50.5a7.5 7.5 0 0 0-15 0V85H217a7.5 7.5 0 0 0 0 15Zm224 106h-22.5v-52.5A37.542 37.542 0 0 0 381 116h-31.502a92.5 92.5 0 1 0-178.996 0H86a52.5 52.5 0 0 0-52.5 52.5v276A67.576 67.576 0 0 0 101 512h340a37.542 37.542 0 0 0 37.5-37.5v-231A37.542 37.542 0 0 0 441 206ZM260 15a77.602 77.602 0 0 1 72.405 105.259 7.434 7.434 0 0 0-.454 1.192A77.17 77.17 0 0 1 296.237 161h-72.474a77.17 77.17 0 0 1-35.714-39.549 7.434 7.434 0 0 0-.454-1.192A77.602 77.602 0 0 1 260 15ZM59.484 141.983A37.25 37.25 0 0 1 86 131h89.824a91.7 91.7 0 0 0 21.959 30H146a7.5 7.5 0 0 0 0 15h152.065l.02.001.027-.001H373a7.5 7.5 0 0 0 0-15h-50.783a91.7 91.7 0 0 0 21.959-30H381a22.525 22.525 0 0 1 22.5 22.5V206H86a37.5 37.5 0 0 1-26.516-64.017ZM463.5 396h-82a37 37 0 0 1 0-74h82Zm0-89h-82a52 52 0 0 0 0 104h82v63.5A22.525 22.525 0 0 1 441 497H101a52.56 52.56 0 0 1-52.5-52.5V205.192A52.335 52.335 0 0 0 86 221h355a22.525 22.525 0 0 1 22.5 22.5Zm-89 52a7.5 7.5 0 1 0 7.5-7.5 7.5 7.5 0 0 0-7.5 7.5Z"
                                        fill="#38c54a"></path>
                                </svg>
                                &nbsp;{{App\CentralLogics\Helpers::format_currency($mywallet->balance)}}
                            </h3> --}}

                            <div class="border">
                                <div class="p-3" style="background: #eaf0f1;">

                                    <div class="text-center">
                                        <h2 class="fw-bolder">
                                            {{App\CentralLogics\Helpers::format_currency($mywallet->balance)}}
                                        </h2>
                                        <h6>No. of Delivered Order : 29</h6>
                                        <button class="btn btn-sm btn-soft-primary mt-4 mb-3" data-bs-toggle="offcanvas"
                                            href="#withdraw-request">Withdraw Request</button>
                                    </div>
                                </div>

                                <div class="card mb-0">
                                    <div class="card-header d-flex justify-content-between fw-bolder py-2 px-3 rounded-0">
                                        <div>Oct 10, 2023</div>
                                        <div>₹1,500.00</div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-item-center fs-6 px-3 py-2 border-bottom">
                                    <div>
                                        <div><b>#orderid</b></div>
                                        <div>Oct 10, 2023, 10:30 AM</div>
                                    </div>
                                    <div class="align-self-center">
                                        <span class="text-success">+ ₹1,000.00</span>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-item-center fs-6 px-3 py-2 border-bottom">
                                    <div>
                                        <div><b>#orderid</b></div>
                                        <div>Oct 10, 2023, 11:00 AM</div>
                                    </div>
                                    <div class="align-self-center">
                                        <span class="text-danger">- ₹500.00</span>
                                    </div>
                                </div>
                                <div class="card mb-0">
                                    <div class="card-header d-flex justify-content-between fw-bolder py-2 px-3 rounded-0">
                                        <div>Mar 24, 2023</div>
                                        <div>₹1,500.00</div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-item-center fs-6 px-3 py-2 border-bottom">
                                    <div>
                                        <div><b>#orderid</b></div>
                                        <div>Mar 10, 2023, 10:30 AM</div>
                                    </div>
                                    <div class="align-self-center">
                                        <span class="text-success">+ ₹1,000.00</span>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-item-center fs-6 px-3 py-2 border-bottom">
                                    <div>
                                        <div><b>#orderid</b></div>
                                        <div>Mar 10, 2023, 11:00 AM</div>
                                    </div>
                                    <div class="align-self-center">
                                        <span class="text-danger">- ₹500.00</span>
                                    </div>
                                </div>

                            </div>

                            {{-- <div class="row mt-5">

                                <div class="col-12 mt-5">
                                    <h4 style="color: #fc6603">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="50">
                                            <g data-name="Layer 2">
                                                <path
                                                    d="M87.88,29.84A29.68,29.68,0,0,0,48.4,27.66h-21a1.5,1.5,0,0,0,0,3h17.8a29.56,29.56,0,0,0-4.27,5.91H17.34a1.5,1.5,0,0,0,0,3H39.49a29.83,29.83,0,0,0-2.07,14.08h-10a1.5,1.5,0,0,0,0,3H37.87A29.41,29.41,0,0,0,39.6,62.3H17.34a1.5,1.5,0,0,0,0,3H41.07A29,29,0,0,0,45.19,71H13.7a1.5,1.5,0,0,0,0,3H47.41a1.58,1.58,0,0,0,.75-.2A29.65,29.65,0,0,0,87.88,29.84ZM85.76,69.65a26.64,26.64,0,1,1,0-37.69A26.69,26.69,0,0,1,85.76,69.65Z"
                                                    fill="#fc6603"></path>
                                                <path
                                                    d="M66.92,28.49A22.31,22.31,0,1,0,89.23,50.8,22.34,22.34,0,0,0,66.92,28.49ZM68.42,70V68.25a1.5,1.5,0,0,0-3,0V70A19.32,19.32,0,0,1,47.69,52.3h1.78a1.5,1.5,0,0,0,0-3H47.69A19.31,19.31,0,0,1,65.42,31.57v1.78a1.5,1.5,0,1,0,3,0V31.57A19.31,19.31,0,0,1,86.15,49.3H84.37a1.5,1.5,0,0,0,0,3h1.78A19.32,19.32,0,0,1,68.42,70Z"
                                                    fill="#fc6603"></path>
                                                <path
                                                    d="M75.16 54.3l-6.29-4 6.53-13A1.5 1.5 0 0072.72 36L65.58 50.13a1.5 1.5 0 00.53 1.94l7.43 4.76a1.48 1.48 0 00.8.23 1.5 1.5 0 00.82-2.76zM22.48 55.14a1.5 1.5 0 00-1.5-1.5H5.07a1.5 1.5 0 000 3H21A1.5 1.5 0 0022.48 55.14zM12.42 30.65H21a1.5 1.5 0 000-3H12.42a1.5 1.5 0 000 3zM10.79 48H30.56a1.5 1.5 0 100-3H10.79a1.5 1.5 0 000 3z"
                                                    fill="#fc6603"></path>
                                            </g>
                                        </svg>
                                        &nbsp;Last Updated :
                                        <small>{{App\CentralLogics\Helpers::format_date($mywallet->updated_at)}},
                                            {{Carbon\Carbon::createFromFormat('Y-m-d H:i:s',
                                            $mywallet->updated_at)->format('H:s:i A')}}
                                        </small>
                                    </h4>
                                </div>
                            </div> --}}

                        </div>
                    </div>
                </div>
                <a href="{{route('vendor.wallet.histories')}}" style="color: #38c54a">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="50" height="50">
                        <path
                            d="M13.975 4.242h-7.95a1.783 1.783 0 0 0-1.78 1.78v4.467a1.507 1.507 0 0 0 1.478 1.5l1.083.016v2.253a1.502 1.502 0 0 0 1.5 1.5h5.949a1.502 1.502 0 0 0 1.5-1.5V6.022a1.783 1.783 0 0 0-1.78-1.78Zm-7.17 6.763-1.067-.016a.502.502 0 0 1-.493-.5V6.022a.78.78 0 0 1 1.56 0Zm7.95 3.253a.5.5 0 0 1-.5.5h-5.95a.5.5 0 0 1-.5-.5V6.022a1.772 1.772 0 0 0-.18-.78h6.35a.781.781 0 0 1 .78.78Zm-1.207-7.516a.5.5 0 0 1-.5.5h-2.554a.5.5 0 0 1 0-1h2.554a.5.5 0 0 1 .5.5Zm0 2a.5.5 0 0 1-.5.5H9.494a.5.5 0 0 1 0-1h3.554a.5.5 0 0 1 .5.5Zm0 2a.5.5 0 0 1-.5.5H9.494a.5.5 0 0 1 0-1h3.554a.5.5 0 0 1 .5.5Z"
                            fill="#38c54a"></path>
                    </svg>
                    See History</a>
            </div>
        </div>
    </div>
    <!-- withdraw request -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="withdraw-request" aria-labelledby="withdraw-request-label">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="withdraw-request-label">Withdreaw Request</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="row  mt-5">
                <div class="form-group col-12 mx-auto">
                    <form action="#" class="p-3 d-flex flex-column justify-content-around" method="post">
                        <input type="hidden" name="_token" value="LRWIvitPyiGo2lGQZ1tghKpKwyt7J2yJM66cEQUK"
                            autocomplete="off"> <span class="mb-3">
                            <label class="form-label mx-2" for="add-amount ">Requested Amount:</label>
                            <input type="number" class="form-control " name="add_amount" value="" id="add-amount"
                                placeholder="0">
                        </span>
                        <button type="submit d-block mt-4" class="btn btn-primary ">Request</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
