@extends('vendor-views.layouts.dashboard-main')

@section('title', __('Refund_Requests'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="content container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm mb-2 mb-sm-0">
                <h1 class="page-header-title">{{ __('Refund_Requests') }}</h1>
            </div>
        </div>
    </div>
    <!-- End Page Header -->

    <!-- Stats Cards -->
    <div class="row gx-2 gx-lg-3 mb-3">
        <div class="col-sm-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <span class="h5 text-dark">{{ $stats['total'] }}</span>
                            <div class="text-muted text-uppercase font-size-sm">{{ __('Total_Refunds') }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="bg-soft-info rounded p-2">
                                <i class="tio-receipt text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

       

        {{-- <div class="col-sm-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <span class="h5 text-success">{{ $stats['processed'] }}</span>
                            <div class="text-muted text-uppercase font-size-sm">{{ __('Processed') }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="bg-soft-success rounded p-2">
                                <i class="tio-checkmark-circle text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

        {{-- <div class="col-sm-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <span class="h5 text-danger">{{ $stats['rejected'] }}</span>
                            <div class="text-muted text-uppercase font-size-sm">{{ __('Rejected') }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="bg-soft-danger rounded p-2">
                                <i class="tio-clear text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
    <!-- End Stats Cards -->

    <!-- Filters -->
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('vendor.refund.index') }}">
                <div class="row">
                    <div class="col-md-4">
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="all" {{ $status === 'all' ? 'selected' : '' }}>{{ __('All_Refunds') }}</option>
                            <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                            <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>{{ __('Approved') }}</option>
                            <option value="processed" {{ $status === 'processed' ? 'selected' : '' }}>{{ __('Processed') }}</option>
                            <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>{{ __('Rejected') }}</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="search" name="search" class="form-control" placeholder="{{ __('Search_by_order_ID_or_customer') }}" value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary">
                                <i class="tio-search"></i> {{ __('Search') }}
                            </button>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('vendor.refund.index') }}" class="btn btn-outline-secondary">
                            <i class="tio-clear"></i> {{ __('Clear') }}
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Refunds Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-header-title">
                {{ __('Refund_Requests') }}
                <span class="badge badge-soft-secondary">{{ $refunds->total() }}</span>
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-thead-bordered table-align-middle">
                    <thead class="thead-light">
                        <tr>
                            <th>{{ __('Refund_ID') }}</th>
                            <th>{{ __('Order_ID') }}</th>
                            <th>{{ __('Refund_Amount') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Request_Date') }}</th>
                            <th class="text-center">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($refunds as $refund)
                        <tr>
                            <td>#{{ $refund->id }}</td>
                            <td>
                                <a href="{{ route('vendor.order.details', $refund->order_id) }}" class="text-decoration-none">
                                    #{{ $refund->order_id }}
                                </a>
                            </td>
                            <td>
                                @if($refund->restaurant_deduction_amount > 0)
                                    <span class="text-danger fw-bold">
                                        {{ \App\CentralLogics\Helpers::format_currency($refund->restaurant_deduction_amount) }}
                                    </span>
                                @else
                                    <span class="text-muted">{{ __('No_Penalty') }}</span>
                                @endif
                            </td>
                            <td>
                                @if($refund->refund_status === 'pending')
                                    <span class="badge bg-warning">{{ __('Pending') }}</span>
                                @elseif($refund->refund_status === 'approved')
                                    <span class="badge bg-info">{{ __('Approved') }}</span>
                                @elseif($refund->refund_status === 'processed')
                                    <span class="badge bg-success">{{ __('Processed') }}</span>
                                @elseif($refund->refund_status === 'rejected')
                                    <span class="badge bg-danger">{{ __('Rejected') }}</span>
                                @endif
                            </td>
                            <td>{{ $refund->created_at->format('d M Y, h:i A') }}</td>
                            <td class="text-center">
                                <a href="{{ route('vendor.refund.show',['id' => $refund->id]) }}" 
                                   class="btn btn-outline-primary btn-sm">
                                    <i class="tio-visible"></i> View
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="text-center">
                                    <img src="{{ asset('public/assets/admin/img/no-data.jpg') }}" alt="No refunds" class="mb-3" style="width: 100px;">
                                    <h5>{{ __('No_Refund_Requests_Found') }}</h5>
                                    <p class="text-muted">{{ __('There_are_no_refund_requests_for_your_restaurant_yet') }}</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        @if($refunds->hasPages())
        <div class="card-footer">
            {{ $refunds->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
