@extends('vendor-views.layouts.dashboard-main')
@push('css')
<link rel="stylesheet" href="{{asset('assets/vendor/flatpickr/dist/flatpickr.min.css')}}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
@endpush
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
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton22" style="">
                                   <li><a class="dropdown-item" href="{!! route('vendor.wallet.histories').'?filter=this_week' !!}">This Week</a></li>
                                   <li><a class="dropdown-item" href="{!! route('vendor.wallet.histories').'?filter=this_month' !!}">This Month</a></li>
                                   <li><a class="dropdown-item" href="{!! route('vendor.wallet.histories').'?filter=this_year' !!}">This Year</a></li>
                                   <li><a class="dropdown-item" href="{!! route('vendor.wallet.histories').'?filter=previous_year' !!}">Previous Year</a></li>
                                   <li><a class="dropdown-item" href="{!! route('vendor.wallet.histories').'?filter=today' !!}">Today</a></li>
                                </ul>
                            </div>
                            <form action="{{route('vendor.wallet.histories')}}">
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
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h5 class="page-header-title">My Wallet History Information</h5>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-responsive"id="datatable-export" data-toggle="data-table-export" aria-describedby="datatable_info">
                        <thead>
                            <tr>
                                <th>##</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($walletTransactions as $history)
                                <tr>
                                    <td>{{$loop->index + 1}}</td>
                                    <td>{{App\CentralLogics\Helpers::format_currency($history->amount)}}</td>
                                    <td>{{App\CentralLogics\Helpers::format_date($history->created_at)}}</td>
                                    <td>{{Str::ucfirst($history->type) }}</td>
                                    <td>{{Str::ucfirst($history->remarks)}}</td>
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
@push('javascript')
    <script src="{{asset('assets/vendor/flatpickr/dist/flatpickr.min.js')}}"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- Buttons Extension -->
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>

    <!-- HTML5 Export Buttons -->
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

    <!-- Dependencies for Excel and PDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script>
        $(document).ready(function () {
            $('#datatable-export').DataTable({
                dom: 'Bfrtip', // Define placement for Buttons
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print' // List of export buttons
                ],
                responsive: true, // Makes the table responsive
                paging: true, // Enables pagination
                searching: true, // Enables the search box
                ordering: true // Enables column sorting
            });
        });
    </script>
@endpush


