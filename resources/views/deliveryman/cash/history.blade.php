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
    {{-- <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">My Cash History Information</h4>
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table ">
                            <thead>
                                <tr>
                                    <th>##</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Paid TO</th>
                                    <th>Recieved From</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cashTxns as $txn)
                                <tr>
                                    <td>{{$loop->index + 1}}</td>
                                    <td>{{App\CentralLogics\Helpers::format_currency($txn->amount)}}</td>
                                    <td>{{App\CentralLogics\Helpers::format_date($txn->created_at)}}</td>
                                    <td>{{Str::ucfirst($txn->txn_type) }}</td>
                                    <td>{{Str::ucfirst($txn->paid_to) }}</td>
                                    <td>{{Str::ucfirst($txn->received_from) }}</td>
                                    <td>{{Str::ucfirst($txn->remarks)}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $cashTxns->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
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
                                        Filter: {{ ucfirst($filterType) }}
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
                                <div>Received since last settlement</div>
                                <h2 class="fw-bolder">₹ {{$cashInHand->balance}}</h2>
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
                                            @foreach ($formattedDataTxns as $data)
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
                                            @foreach ($fomattedSettlementTxns as $data)
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
            </div>
        </div>
    </div>
@endsection
