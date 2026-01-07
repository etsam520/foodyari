@extends('vendor-views.layouts.dashboard-main')
@section('content')



    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <div class="row align-items-center ">
                                <div class="col-auto mb-2 mb-sm-0">
                                    <h3 class="page-header-title">{{ __('messages.customer') }} {{ __('messages.id') }} #{{ $customer['id'] }}</h3>
                                    <span class="d-block">
                                        <i class="tio-date-range"></i> {{ __('messages.joined_at') }} : {{ date('d M Y ' . config('timeformat'), strtotime($customer['created_at'])) }}
                                    </span>
                                </div>
                                <div class="col-auto ml-auto">
                                    <a class="btn btn-soft-info rounded-circle mr-1" href="{{ route('admin.customer.view', [$customer['id'] - 1]) }}" data-toggle="tooltip" data-placement="top" title="{{ __('Previous customer') }}">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                    <a class="btn btn-soft-info rounded-circle mr-1" href="{{ route('admin.customer.view', [$customer['id'] + 1]) }}" data-toggle="tooltip" data-placement="top" title="{{ __('Next customer') }}">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </div>
                                <div class="col-12 mt-3">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="resturant-card bg-soft-success d-flex p-2">
                                                <img class="theme-color-default-img img-fluid avatar avatar-100 " src="{{ asset('assets/images/icons/wallet.svg') }}" alt="dashboard">
                                                <div class="for-card-text font-weight-bold  text-uppercase mb-1">
                                                    {{ __('messages.wallet') }} {{ __('messages.balance') }}
                                                    <i class="for-card-count">{{ $customer->wallet_balance ?? 0 }}</i>
                                                </div>
                                            </div>
                                        </div>
                            
                                        <!-- Pending Requests Card Example -->
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="resturant-card bg-soft-success d-flex p-2">
                                                <img class="theme-color-default-img img-fluid avatar avatar-100 " src="{{ asset('assets/images/icons/loyaltipoint.svg') }}" alt="dashboard">
                                                <div class="for-card-text font-weight-bold  text-uppercase mb-1">
                                                    {{ __('messages.loyalty_point') }} {{ __('messages.balance') }}
                                                    <i class="for-card-count">{{ $customer->loyalty_point ?? 0 }}</i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Collected Cash Card Example -->
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row" id="printableArea">
                            <div class="col-lg-8 mb-3 mb-lg-0">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-header-title">{{ __('messages.Order List') }} <span class="badge badge-soft-secondary" id="itemCount">{{ $orders->total() }}</span></h5>
                                        <div class="float-end mb-2">
                                            <form action="javascript:" id="search-form">
                                                @csrf
                                                <!-- Search -->
                                                <input type="hidden" name="id" value="{{ $customer->id }}" id="">
                                                <div class="input--group input-group input-group-merge input-group-flush">
                                                    <input id="datatableSearch_" type="search" name="search" class="form-control" value="{{ request()->get('search') }}" placeholder="{{ __('Ex: Search Here by ID...') }}" aria-label="Search" required>
                                                    <button type="submit" class="btn btn-soft-secondary">
                                                        <i class="fa fa-search"></i>
                                                    </button>

                                                </div>
                                                <!-- End Search -->
                                            </form>
                                        </div>
                                    </div>
                                    <!-- Table -->
                                    <div class="table-responsive datatable-custom">
                                        <table id="columnSearchDatatable" class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>{{ __('messages.sl') }}</th>
                                                    <th class="text-center w-50p">{{ __('messages.order') }} {{ __('messages.id') }}</th>
                                                    <th class="w-50p text-center">{{ __('messages.total') }} {{ __('messages.amount') }}</th>
                                                    <th class="text-center w-100px">{{ __('messages.action') }}</th>
                                                </tr>
                                            </thead>


                                            <tbody id="set-rows">
                                                @include('vendor-views.customer.partial._tableList')
                                            </tbody>

                                        </table>
                                        @if (count($orders) === 0)
                                            <div class="empty--data">
                                                <img src="{{ asset('/public/assets/admin/img/empty.png') }}" alt="public">
                                                <h5>
                                                    {{ __('no_data_found') }}
                                                </h5>
                                            </div>
                                        @endif
                                        <!-- Pagination -->
                                        <div class="page-area px-4 pb-3">
                                            <div class="d-flex align-items-center justify-content-end">
                                                {{-- <div>
                                                    1-15 of 380
                                                </div> --}}
                                                <div class="hide-page">
                                                    {!! $orders->links() !!}
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Pagination -->
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <!-- Card -->
                                <div class="card">
                                    <!-- Header -->
                                    <div class="card-header">
                                        <h4 class="card-header-title">
                                            <span class="card-header-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="30">
                                                    <circle cx="12" cy="8" r="4" fill="#464646" ></circle>
                                                    <path fill="currentColor"
                                                        d="M20 19v1a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-1a6 6 0 0 1 6-6h4a6 6 0 0 1 6 6Z"></path>
                                                </svg>
                                            </span>
                                            <span>
                                                @if ($customer)
                                                    {{ $customer['f_name'] . ' ' . $customer['l_name'] }}
                                                @else
                                                    {{ __('messages.Customer') }}
                                                @endif
                                            </span>
                                        </h4>
                                    </div>
                                    <!-- End Header -->

                                    <!-- Body -->
                                    @if ($customer)
                                        <div class="card-body">
                                            <div class="media d-flex align-items-center customer-information-single " href="javascript:">
                                                <div class="avatar avatar-circle">
                                                    <img class="theme-color-default-img img-fluid avatar avatar-50 avatar-rounded" src="{{ asset('customers/' . $customer->image) }}" alt="Image Description">
                                                </div>
                                                <div class="media-body mx-2">
                                                    <ul class="list-unstyled m-0">
                                                        <li class="pb-1">
                                                            <i class="tio-email mr-2"></i>
                                                            {{ $customer['email'] }}
                                                        </li>
                                                        <li class="pb-1">
                                                            <i class="tio-call-talking-quiet mr-2"></i>
                                                            {{ $customer['phone'] }}
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h5>{{ __('messages.contact') }} {{ __('messages.info') }}</h5>
                                            </div>
                                            {{-- @foreach ($customer->addresses as $address)
                                                <ul class="list-unstyled list-unstyled-py-2">
                                                    @if ($address['contact_person_umber'])
                                                        <li>
                                                            <i class="tio-call-talking-quiet mr-2"></i>
                                                            {{ $address['contact_person_umber'] }}
                                                        </li>
                                                    @endif
                                                    <li class="quick--address-bar">
                                                        <div class="quick-icon badge-soft-secondary">
                                                            <i class="tio-home"></i>
                                                        </div>
                                                        <div class="info">
                                                            <h6>{{ $address['address_type'] }}</h6>
                                                            <a target="_blank" href="http://maps.google.com/maps?z=12&t=m&q=loc:{{ $address['latitude'] }}+{{ $address['longitude'] }}" class="text--title">
                                                                {{ $address['address'] }}
                                                            </a>
                                                        </div>
                                                    </li>
                                                </ul>
                                            @endforeach --}}

                                        </div>
                                    @endif
                                    <!-- End Body -->
                                </div>
                                <!-- End Card -->

                                <div class="card mt-4">
                                    <!-- Header -->
                                    <div class="card-header">
                                        <h4 class="card-header-title">
                                            <span class="card-header-icon">
                                                <i class="tio-wallet"></i>
                                            </span>
                                            <span>
                                                {{ __('messages.Wallet') }}
                                            </span>
                                        </h4>
                                    </div>
                                    <!-- End Header -->

                                    <!-- Body -->
                                    @if ($customer)
                                        <div class="card-body">

                                            <form 
                                            {{-- action="{{ route('admin.customer.wallet.add-fund') }}" --}}
                                            method="post" enctype="multipart/form-data" id="add_fund" class="js-validate">
                                                @csrf
                                                <div class="row">
                                                    <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                                                    <input type="hidden" id="customer_name" value="{{ $customer->f_name }} {{ $customer->l_name }}">
                                                    <input type="hidden" id="customer_phone" value="{{ $customer->phone }}">
                                                    <div class="col-md-12 col-12">
                                                        <span class="input-label">
                                                            {{ __('messages.Transaction') }}
                                                            {{ __('messages.Type') }}
                                                        </span>
                                                    </div>
                                                    <div class="col-md-6 col-12">
                                                        <label class="form-check form--check mr-2 mr-md-4">
                                                            <input class="form-check-input transaction_type" type="radio" value="Credit" name="transaction_type" id="credit">
                                                            <span class="form-check-label">
                                                                {{ __('messages.Credit') }}
                                                            </span>
                                                        </label>
                                                    </div>
                                                    <div class="col-md-6 col-12">
                                                        <label class="form-check form--check mr-2 mr-md-4">
                                                            <input class="form-check-input transaction_type" type="radio" value="Debit" name="transaction_type" id="debit">
                                                            <span class="form-check-label">
                                                                {{ __('messages.Debit') }}
                                                            </span>
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-12 col-12 mt-3">
                                                        <div class="form-group">
                                                            <label class="form-label" for="amount">{{ __('messages.amount') }}</label>

                                                            <input type="number" class="form-control h--45px" placeholder="{{ __('messages.Enter Amount') }}" name="amount" id="amount" step=".01" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label class="form-label" for="referance">{{ __('messages.reference') }} <small>({{ __('messages.optional') }})</small></label>

                                                            <input type="text" class="form-control h--45px" placeholder="{{ __('messages.Reference') }}" name="referance" id="referance">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="btn--container justify-content-end">
                                                    <button type="reset" id="reset" class="btn btn--reset">{{ __('messages.reset') }}</button>
                                                    <button type="submit" id="submit" class="btn btn--primary">{{ __('messages.submit') }}</button>
                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                    <!-- End Body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Row -->
    </div>
@endsection

@push('script_2')
    <script>
        $(document).on('ready', function() {
            // INITIALIZATION OF DATATABLES
            // =======================================================
            var datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));

            $('#column1_search').on('keyup', function() {
                datatable
                    .columns(1)
                    .search(this.value)
                    .draw();
            });


            $('#column3_search').on('change', function() {
                datatable
                    .columns(2)
                    .search(this.value)
                    .draw();
            });


            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function() {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
    </script>

    <script>
        $('#add_fund').on('submit', function(e) {

            e.preventDefault();
            var formData = new FormData(this);
            var transactionType = $('input[name="transaction_type"]:checked').val();

            Swal.fire({
                title: '{{ __('messages.are_you_sure') }}',
                text: '{{ __('messages.you_want_to ') }}' + (transactionType === 'Credit' ? '{{ __('messages.credit_fund ') }}' : '{{ __('messages.debit_fund ') }}') + $('#amount').val() + ' {{ \App\CentralLogics\Helpers::currency_symbol() . ' ' . __('messages.to') }} ' + $('#customer_name').val() + '(' +$('#customer_phone').val() + ')'+'{{ __('messages.to_wallet') }}',
                type: 'info',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: 'primary',
                cancelButtonText: '{{ __('messages.no') }}',
                confirmButtonText: '{{ __('messages.yes') }}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.post({
                        // url: '{{-- route('admin.customer.wallet.add-fund') --}}',
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function(data) {
                            if (data.errors) {
                                for (var i = 0; i < data.errors.length; i++) {
                                    toastr.error(data.errors[i].message, {
                                        CloseButton: true,
                                        ProgressBar: true
                                    });
                                }
                            } else {
                                $('#customer').val(null).trigger('change');
                                $('#amount').val(null).trigger('change');
                                $('#referance').val(null).trigger('change');
                                toastr.success('{{ __('messages.fund_transaction_success') }}', {
                                    CloseButton: true,
                                    ProgressBar: true
                                });
                                // window.location.href = "{{-- route('admin.customer.wallet.report') --}}"
                            }
                        }
                    });
                }
            })
        })
        $('#search-form').on('submit', function() {
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{-- route('admin.customer.order_search') --}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(data) {
                    $('#set-rows').html(data.view);
                    $('.hide-page').hide();
                    $('#itemCount').html(data.total);
                },
                complete: function() {
                    $('#loading').hide();
                },
            });
        });
    </script>
@endpush
