@php( $deliveryman = Session::get('deliveryMan'))

@extends('deliveryman.admin.layouts.main')

@push('css')
    <link rel="stylesheet" href="{{asset('assets/vendor/flatpickr/dist/flatpickr.min.css')}}">
    <style>
        @media only screen and (max-width: 767px) {
            .dropdown-menu {
                right: 25px;
            }
        }

        @media only screen and (max-width: 767px) {
            .table-responsive::-webkit-scrollbar {
                width: 5px;
                height: 5px;
            }

            .table-responsive::-webkit-scrollbar-thumb {
                background-color: #888;
                border-radius: 10px;
            }

            .table-responsive::-webkit-scrollbar-thumb:hover {
                background-color: #555;
            }
        }

        .summary-card {
            background: linear-gradient(135deg, #fdeedf, #ffffff);
            border: none;
            border-radius: 28px 28px 10px 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .summary-card:hover {
            transform: translateY(-5px);
        }

        .summary-icon {
            font-size: 30px;
        }

        .summary-title {
            font-size: 1.1rem;
            font-weight: 600;
        }

        @media only screen and (max-width: 767px) {
            .summary-title {
                font-size: 0.9rem;
                font-weight: 600;
            }
        }

        .summary-value {
            font-size: 1.8rem;
            font-weight: bold;
        }

        @media only screen and (max-width: 767px) {
            .summary-value {
                font-size: 1.2rem;
                font-weight: bold;
            }
        }

        .bg-orange {
            background-color: #f57c00;
        }

        .text-white {
            color: white !important;
        }

        .summary-container {
            padding-top: 40px;
            padding-bottom: 40px;
        }
    </style>
@endpush
@section('content')

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

                                <div class="d-flex">
                                    <div class="fw-bolder"><i class="feather-refresh-cw me-2"></i></div>
                                    <div class="fw-bolder"><i class="feather-download"></i></div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="container summary-container">
                            <h2 class="text-center mb-5 fw-bold">Employee Summary</h2>
                            <div class="row g-4">

                                <!-- Total Working Days -->
                                <div class="col-4 px-2">
                                    <div class="card summary-card text-center">
                                        <div class="bg-orange text-white py-2">
                                            <div class="summary-icon"><i class="fas fa-calendar-check"></i></div>
                                        </div>
                                        <div class="p-lg-4 p-2">
                                            <div class="summary-title">Total Working</div>
                                            <div class="summary-value text-dark">{{Helpers::half_whole_day_display($workings->total_working_days)}}</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Total Leave -->



                                <div class="col-4 px-2">
                                    <div class="card summary-card text-center">
                                        <div class="bg-orange text-white py-2">
                                            <div class="summary-icon"><i class="fas fa-calendar-times"></i></div>
                                        </div>
                                        <div class="p-lg-4 p-2">
                                            <div class="summary-title">Total Leave</div>
                                            <div class="summary-value text-dark">
                                                @if ($now->greaterThanOrEqualTo($endDate))
                                                    {{Helpers::half_whole_day_display($leave_days)}}
                                                @else
                                                {{ '0' }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Salary Amount -->
                                <div class="col-4 px-2">
                                    <div class="card summary-card text-center">
                                        <div class="bg-orange text-white py-2">
                                            <div class="summary-icon"><i class="fas fa-sack-dollar"></i></div>
                                        </div>
                                        <div class="p-lg-4 p-2">
                                            <div class="summary-title">Salary</div>
                                            <div class="summary-value text-dark">{{Helpers::format_currency($total_salary)}}</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Total Travel Distance -->
                                <div class="col-6 px-2">
                                    <div class="card summary-card text-center">
                                        <div class="bg-orange text-white py-2">
                                            <div class="summary-icon"><i class="fas fa-route"></i></div>
                                        </div>
                                        <div class="p-lg-4 p-2">
                                            <div class="summary-title">Total Travel Distance</div>
                                            <div class="summary-value text-dark">{{$total_distance}}</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Total Fuel Price -->
                                <div class="col-6 px-2">
                                    <div class="card summary-card text-center">
                                        <div class="bg-orange text-white py-2">
                                            <div class="summary-icon"><i class="fas fa-gas-pump"></i></div>
                                        </div>
                                        <div class="p-lg-4 p-2">
                                            <div class="summary-title">Total Fuel Price</div>
                                            <div class="summary-value text-dark">{{Helpers::format_currency($fuel_price)}}</div>
                                        </div>
                                    </div>
                                </div>

                            </>
                        </div>
                        <div class="m-3 table-responsive" style="overflow-x: auto;">
                            <table class="table table-success table-striped mb-0">
                                <thead style="position: sticky; top: 0; z-index: 1;">
                                    <tr class="text-nowrap text-center">
                                        <th style="position: sticky; left: 0; z-index: 2;">Date</th>
                                        <th>Check-In Time</th>
                                        <th>Check-In Meter</th>
                                        <th>Check-Out Time</th>
                                        <th>Check-Out Meter</th>
                                        <th>Distance</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($attendances_formatted as $attendance)
                                    <tr class="text-nowrap">
                                        <td style="position: sticky; left: 0; z-index: 2;">{{$attendance['date']}}</td>
                                        <td>{{$attendance['check_in']}}</td>
                                        <td>{{$attendance['check_in_meter']}}</td>
                                        <td>{{$attendance['check_out']}}</td>
                                        <td>{{$attendance['check_out_meter']}}</td>
                                        <td>{{$attendance['distance']}}</td>
                                        <td>{{$attendance['status']}}</td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>



                    </div>
                </div>
            </div>
        </div>

@endsection
