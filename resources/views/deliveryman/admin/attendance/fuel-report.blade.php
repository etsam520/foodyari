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
                                <img src="{{ asset('assets/images/fuel-credit.png') }}" alt=""
                                    style="width: 30px; height: 30px;">
                                <div class="fs-5">Fuel Credit</div>
                                <h2 class="fw-bolder">{{Helpers::format_currency($balance)}}</h2>
                            </div>
                        </div>
                        <hr>
                        <div class="m-3 table-responsive" style="overflow-x: auto;">
                            <div class="order-body">
                                <div class="pb-3">
                                    <div class="p-3 rounded">
                                        @foreach ($formattedData as $data)
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
        
@endsection
