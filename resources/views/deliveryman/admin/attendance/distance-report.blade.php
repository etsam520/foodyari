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
    <style>
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
                            <div class="text-center bg-body-secondary shadows-m rounded-4 py-3">
                                <img src="{{ asset('assets/images/distance.png') }}" alt=""
                                    style="width: 30px; height: 30px;">
                                <div class="fs-5">Total Distance</div>
                                <h2 class="fw-bolder">{{$total_distance}} KM</h2>
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
                                        <th>Total Travel</th>
                                        <th>Address(Check In)</th>
                                        <th>Address(Check Out)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dm_attendances_formatted as $attendance)
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
        </div>
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
</script>

@endpush
