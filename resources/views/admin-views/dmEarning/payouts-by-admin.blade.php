@extends('layouts.dashboard-main')
@push('css')
<style>
    span.select2.select2-container{
        width: 100% !important;
    }
    .badge-pending {
        background-color: #ffc107;
        color: #000;
    }
    .badge-completed {
        background-color: #28a745;
        color: #fff;
    }
    .badge-failed {
        background-color: #dc3545;
        color: #fff;
    }
    .payout-type-cash {
        background-color: #17a2b8;
        color: #fff;
    }
    .payout-type-wallet {
        background-color: #6c757d;
        color: #fff;
    }
    .admin-info-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        margin-bottom: 20px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid content-inner mt-n5 py-0">
    <div class="row">
        <!-- Admin Info Card -->
        <div class="col-xl-12 col-lg-12">
            <div class="card admin-info-card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-lg-2">
                            <div class="text-center">
                                <div class="avatar-lg mx-auto">
                                    <div class="avatar-title bg-white text-primary rounded-circle fs-1">
                                        {{strtoupper(substr($admin->f_name ?? 'A', 0, 1))}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <h3 class="mb-1">{{$admin->f_name}} {{$admin->l_name}}</h3>
                            <p class="mb-0">{{$admin->email}}</p>
                            <p class="mb-0"><small>{{__('Role')}}: {{ucfirst($admin->role_name ?? 'Admin')}}</small></p>
                        </div>
                        <div class="col-lg-4">
                            <div class="row text-center">
                                <div class="col-4">
                                    <h4 class="mb-0">{{$payouts->total()}}</h4>
                                    <small>{{__('Total Payouts')}}</small>
                                </div>
                                <div class="col-4">
                                    <h4 class="mb-0">{{\App\CentralLogics\Helpers::format_currency($payouts->sum('amount'))}}</h4>
                                    <small>{{__('Total Amount')}}</small>
                                </div>
                                <div class="col-4">
                                    <h4 class="mb-0">{{$payouts->where('status', 'completed')->count()}}</h4>
                                    <small>{{__('Completed')}}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">{{__('Payouts Created by')}} {{$admin->f_name}} {{$admin->l_name}}</h4>
                    </div>
                    <div>
                        <a href="{{route('admin.earning.payouts')}}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left"></i> {{__('Back to All Payouts')}}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <form method="GET">
                        <div class="row mb-3">
                            <div class="col-lg-3 col-sm-6">
                                <label class="input-label">{{__('Zone')}}</label>
                                <select name="zone_id" class="form-control js-select2-custom">
                                    <option value="all">{{__('All Zones')}}</option>
                                    @foreach($zones as $zone)
                                        <option value="{{$zone->id}}" {{request('zone_id') == $zone->id ? 'selected' : ''}}>
                                            {{$zone->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3 col-sm-6">
                                <label class="input-label">{{__('Status')}}</label>
                                <select name="status" class="form-control">
                                    <option value="all">{{__('All Status')}}</option>
                                    <option value="pending" {{request('status') == 'pending' ? 'selected' : ''}}>{{__('Pending')}}</option>
                                    <option value="completed" {{request('status') == 'completed' ? 'selected' : ''}}>{{__('Completed')}}</option>
                                    <option value="failed" {{request('status') == 'failed' ? 'selected' : ''}}>{{__('Failed')}}</option>
                                </select>
                            </div>
                            <div class="col-lg-3 col-sm-6">
                                <label class="input-label">{{__('Payout Type')}}</label>
                                <select name="payout_type" class="form-control">
                                    <option value="all">{{__('All Types')}}</option>
                                    <option value="cash_collection" {{request('payout_type') == 'cash_collection' ? 'selected' : ''}}>{{__('Cash Collection')}}</option>
                                    <option value="wallet_payout" {{request('payout_type') == 'wallet_payout' ? 'selected' : ''}}>{{__('Wallet Payout')}}</option>
                                </select>
                            </div>
                            <div class="col-lg-3 col-sm-6 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">{{__('Filter')}}</button>
                                <a href="{{route('admin.earning.payouts-by-admin', $admin->id)}}" class="btn btn-secondary">{{__('Reset')}}</a>
                            </div>
                        </div>
                    </form>

                    <!-- Search -->
                    <form method="GET" class="mb-3">
                        <div class="row">
                            <div class="col-lg-8 col-sm-8">
                                <input type="search" name="search" class="form-control" placeholder="{{__('Search delivery man...')}}" value="{{request('search')}}">
                            </div>
                            <div class="col-lg-2 col-sm-2">
                                <button type="submit" class="btn btn-primary w-100">{{__('Search')}}</button>
                            </div>
                            <div class="col-lg-2 col-sm-2">
                                <a href="{{route('admin.earning.payouts-by-admin', $admin->id)}}" class="btn btn-secondary w-100">{{__('Clear')}}</a>
                            </div>
                        </div>
                    </form>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{__('ID')}}</th>
                                    <th>{{__('Delivery Man')}}</th>
                                    <th>{{__('Phone')}}</th>
                                    <th>{{__('Zone')}}</th>
                                    <th>{{__('Amount')}}</th>
                                    <th>{{__('Method')}}</th>
                                    <th>{{__('Type')}}</th>
                                    <th>{{__('Reference No')}}</th>
                                    <th>{{__('Status')}}</th>
                                    <th>{{__('Notes')}}</th>
                                    <th>{{__('Date')}}</th>
                                    <th>{{__('Action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payouts as $payout)
                                    <tr>
                                        <td>{{$payout->id}}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <h6 class="mb-0">{{$payout->deliveryMan->f_name}} {{$payout->deliveryMan->l_name}}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{$payout->deliveryMan->phone}}</td>
                                        <td>
                                            @if($payout->deliveryMan->zone)
                                                <span class="badge badge-soft-info">{{$payout->deliveryMan->zone->name}}</span>
                                            @else
                                                <span class="text-muted">{{__('No Zone')}}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="font-weight-bold text-primary">
                                                {{\App\CentralLogics\Helpers::format_currency($payout->amount)}}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-soft-secondary">{{ucfirst($payout->method)}}</span>
                                        </td>
                                        <td>
                                            <span class="badge payout-type-{{$payout->payout_type == 'cash_collection' ? 'cash' : 'wallet'}}">
                                                {{$payout->payout_type == 'cash_collection' ? 'Cash Collection' : 'Wallet Payout'}}
                                            </span>
                                        </td>
                                        <td>{{$payout->reference_no ?? '-'}}</td>
                                        <td>
                                            <span class="badge badge-{{$payout->status}}">
                                                {{ucfirst($payout->status)}}
                                            </span>
                                            @if($payout->updated_by && $payout->updated_by != $payout->admin_id)
                                                <br>
                                                <small class="text-warning">
                                                    <i class="fas fa-edit"></i> Updated by: {{$payout->updatedBy->f_name}}
                                                </small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($payout->notes)
                                                <span class="text-truncate" title="{{$payout->notes}}" style="max-width: 150px; display: inline-block;">
                                                    {{Str::limit($payout->notes, 30)}}
                                                </span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{$payout->created_at->format('M d, Y H:i')}}</td>
                                        <td>
                                            @if($payout->status == 'pending')
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-success" onclick="updatePayoutStatus({{$payout->id}}, 'completed')">
                                                        {{__('Complete')}}
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-danger" onclick="updatePayoutStatus({{$payout->id}}, 'failed')">
                                                        {{__('Fail')}}
                                                    </button>
                                                </div>
                                            @else
                                                <span class="text-muted">{{__('No Action')}}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="text-center py-4">
                                            <div class="empty--data">
                                                <img src="{{asset('/public/assets/admin/img/empty.png')}}" alt="empty">
                                                <h5>{{__('No payouts found for this admin')}}</h5>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($payouts->hasPages())
                        <div class="page-area px-4 pb-3">
                            <div class="d-flex align-items-center justify-content-end">
                                <div>
                                    {!! $payouts->links() !!}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('javascript')
<script>
    // Update payout status
    function updatePayoutStatus(payoutId, status) {
        if (!confirm('{{__("Are you sure you want to update the payout status?")}}')) {
            return;
        }

        fetch(`{{ url('admin/earning/payouts') }}/${payoutId}/status`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                toastr.success(data.message);
                location.reload();
            } else {
                toastr.error(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            toastr.error('An error occurred while updating the payout status');
        });
    }

    // Initialize Select2
    $(document).ready(function() {
        $('.js-select2-custom').select2({
            width: '100%'
        });
    });
</script>
@endpush