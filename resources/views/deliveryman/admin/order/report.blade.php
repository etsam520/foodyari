@php( $deliveryman = Session::get('deliveryMan'))

@extends('deliveryman.admin.layouts.main')

@push('css')
    <link rel="stylesheet" href="{{asset('assets/vendor/flatpickr/dist/flatpickr.min.css')}}">
@endpush
@section('content')
    <style>
        @media only screen and (max-width: 767px) {
            .dropdown-menu {
                right: 25px;
            }
        }
    </style>
    <div class="osahan-home-page">
        <div class="res-section">
            <div class="container mt-3">
                <div class="row d-flex justify-content-center">
                    <div class="col-lg-8 bg-white px-0">
                        <div class="p-3">
                            <div class="d-flex justify-content-between">
                                <div class="">
                                    <button type="button" class="btn btn-white border-0 dropdown-toggle"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        Filter:
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="{{ url()->current() }}?filter=day">By Day</a>
                                        </li>
                                        <li><a class="dropdown-item" href="{{ url()->current() }}?filter=month">By Month</a>
                                        </li>
                                        <li><a class="dropdown-item" href="{{ url()->current() }}?filter=year">By Year</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="d-flex">
                                    <div class="fw-bolder"><i class="feather-refresh-cw me-2"></i></div>
                                    <div class="fw-bolder"><i class="feather-download"></i></div>
                                </div>
                            </div>
                            <hr>
                            <div class="text-center">
                                <div>Order History</div>
                                <h2 class="fw-bolder">₹ 13700</h2>
                                <h6>No. of Delivered Order : 29</h6>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between fw-bolder py-2 px-3 mt-2"
                            style="background-color:#ff810a36;">
                            <div>Oct 10, 2023</div>
                            <div>₹1,500.00</div>
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

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
