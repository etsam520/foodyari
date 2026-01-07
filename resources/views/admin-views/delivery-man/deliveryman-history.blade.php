@extends('layouts.dashboard-main')

@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="header-title d-flex align-items-center">
                        <div class="card-header-icon d-inline-flex mr-2 img">
                            <!-- existing SVG -->
                        </div>
                        <h4 class="mb-0">{{ __('Deliveryman History') }} - {{ $dm->name ?? ('#'.$dm->id) }}</h4>
                    </div>
                    <div>
                        <a href="{{ url()->previous() }}" class="btn btn-sm btn-outline-secondary">{{ __('Back') }}</a>
                    </div>
                </div>

                <div class="card-body">
                    @if($orders->count())
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>{{ __('Order No') }}</th>
                                        <th>{{ __('Customer') }}</th>
                                        <th>{{ __('Amount') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Date') }}</th>
                                        <th class="text-end">{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td> <a href="{{route('admin.order.details', $order->id)}}">#{{ $order->id }}</a></td>
                                            <td>{{ optional($order->customer)->f_name ?? ($order->customer_name ?? '-') }}</td>
                                            <td>{{ number_format($order->order_amount ?? ($order->total ?? 0), 2) }}</td>
                                            <td>{{ ucfirst($order->order_status ?? ($order->status ?? '-')) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, h:i A') }}</td>
                                            <td class="text-end">
                                                <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.order.details', ['id' => $order->id]) ?? '#' }}" title="{{ __('View') }}">
                                                    <!-- small eye SVG -->
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.2"/></svg>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $orders->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="mb-0">{{ __('No history found for this deliveryman.') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
