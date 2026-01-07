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
</style>
@endpush

@section('content')
<div class="container-fluid content-inner mt-n5 py-0">
    <!-- Summary Statistics -->
    <div class="row mb-4">
        <div class="col-lg-3 col-sm-6">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h3 class="mb-1">{{$totalPayouts}}</h3>
                    <p class="mb-0">{{__('Total Payouts')}}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h3 class="mb-1">{{\App\CentralLogics\Helpers::format_currency($totalAmount)}}</h3>
                    <p class="mb-0">{{__('Total Amount')}}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-sm-4">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h3 class="mb-1">{{$completedPayouts}}</h3>
                    <p class="mb-0 small">{{__('Completed')}}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-sm-4">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h3 class="mb-1">{{$pendingPayouts}}</h3>
                    <p class="mb-0 small">{{__('Pending')}}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-sm-4">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <h3 class="mb-1">{{$failedPayouts}}</h3>
                    <p class="mb-0 small">{{__('Failed')}}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Admin Activity -->
    @if($recentAdmins->count() > 0)
    <div class="row mb-4">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">{{__('Recent Admin Activity')}}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($recentAdmins as $adminActivity)
                        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                <div class="avatar-sm me-3">
                                    <div class="avatar-title bg-primary text-white rounded-circle">
                                        {{strtoupper(substr($adminActivity->admin->f_name ?? 'A', 0, 1))}}
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <a href="{{route('admin.earning.payouts-by-admin', $adminActivity->admin_id)}}" class="text-decoration-none">
                                        <h6 class="mb-0 text-primary">{{$adminActivity->admin->f_name}}</h6>
                                        <small class="text-muted">{{$adminActivity->payout_count}} payouts</small><br>
                                        <small class="text-success">{{\App\CentralLogics\Helpers::format_currency($adminActivity->total_amount)}}</small>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">{{__('Delivery Man Payouts')}}</h4>
                    </div>
                    {{-- <div>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPayoutModal">
                            {{__('Create New Payout')}}
                        </button>
                    </div> --}}
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
                            <div class="col-lg-3 col-sm-6">
                                <label class="input-label">{{__('Search')}}</label>
                                <div class="d-flex">
                                    <input type="text" name="search" value="{{request('search')}}" class="form-control" placeholder="{{__('Search by name or phone')}}">
                                    <button type="submit" class="btn btn-outline-primary ml-2">{{__('Filter')}}</button>
                                </div>
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
                                    <th>{{__('Created By')}}</th>
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
                                        </td>
                                        <td>
                                            @if($payout->admin)
                                                <a href="{{route('admin.earning.payouts-by-admin', $payout->admin->id)}}" class="text-decoration-none">
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm me-2">
                                                            <div class="avatar-title bg-soft-primary text-primary rounded-circle">
                                                                {{strtoupper(substr($payout->admin->f_name ?? 'A', 0, 1))}}
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0 text-sm text-primary">{{$payout->admin->f_name}} {{$payout->admin->l_name}}</h6>
                                                            <small class="text-muted">{{$payout->admin->email}}</small>
                                                            @if($payout->updated_by && $payout->updated_by != $payout->admin_id)
                                                                <br>
                                                                <small class="text-warning">
                                                                    <i class="fas fa-edit"></i> Updated by: {{$payout->updatedBy->f_name}}
                                                                </small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </a>
                                            @else
                                                <span class="text-muted">{{__('System')}}</span>
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
                                        <td colspan="13" class="text-center py-4">
                                            <div class="empty--data">
                                                <img src="{{asset('/public/assets/admin/img/empty.png')}}" alt="empty">
                                                <h5>{{__('No payouts found')}}</h5>
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

<!-- Create Payout Modal -->
<div class="modal fade" id="createPayoutModal" tabindex="-1" aria-labelledby="createPayoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createPayoutModalLabel">{{__('Create New Payout')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createPayoutForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label class="input-label">{{__('Zone')}} <span class="text-danger">*</span></label>
                                <select id="zone_select" class="form-control js-select2-custom" required>
                                    <option value="">{{__('Select Zone')}}</option>
                                    @foreach($zones as $zone)
                                        <option value="{{$zone->id}}">{{$zone->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label class="input-label">{{__('Delivery Man')}} <span class="text-danger">*</span></label>
                                <select name="delivery_man_id" id="delivery_man_select" class="form-control js-select2-custom" required>
                                    <option value="">{{__('Select Delivery Man')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label class="input-label">{{__('Amount')}} <span class="text-danger">*</span></label>
                                <input type="number" name="amount" class="form-control" step="0.01" min="0.01" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label class="input-label">{{__('Payment Method')}} <span class="text-danger">*</span></label>
                                <select name="method" class="form-control" required>
                                    <option value="cash">{{__('Cash')}}</option>
                                    <option value="upi">{{__('UPI')}}</option>
                                    <option value="bank_transfer">{{__('Bank Transfer')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label class="input-label">{{__('Payout Type')}} <span class="text-danger">*</span></label>
                                <select name="payout_type" class="form-control" required>
                                    <option value="cash_collection">{{__('Cash Collection')}}</option>
                                    <option value="wallet_payout">{{__('Wallet Payout')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label class="input-label">{{__('Reference Number')}}</label>
                                <input type="text" name="reference_no" class="form-control" maxlength="100">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group mb-3">
                                <label class="input-label">{{__('Notes')}}</label>
                                <textarea name="notes" class="form-control" rows="3" maxlength="500"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                    <button type="submit" class="btn btn-primary">{{__('Create Payout')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('javascript')
<script>
    /*
    // Load delivery men when zone is selected
    $('#zone_select').on('change', function() {
        const zoneId = $(this).val();
        const deliveryManSelect = $('#delivery_man_select');
        
        deliveryManSelect.empty().append('<option value="">{{__("Loading...")}}</option>');
        
        if (zoneId) {
            fetch(`{{ route('admin.delivery-man.get-deliverymen')}}?zone_id=${zoneId}`)
                .then(response => response.json())
                .then(data => {
                    deliveryManSelect.empty().append('<option value="">{{__("Select Delivery Man")}}</option>');
                    data.forEach(dm => {
                        deliveryManSelect.append(`<option value="${dm.id}">${dm.name} (${dm.phone})</option>`);
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    toastr.error('Failed to load delivery men');
                    deliveryManSelect.empty().append('<option value="">{{__("Select Delivery Man")}}</option>');
                });
        } else {
            deliveryManSelect.empty().append('<option value="">{{__("Select Delivery Man")}}</option>');
        }
    });

    // Handle create payout form submission
    $('#createPayoutForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = $(this).find('button[type="submit"]');
        
        submitBtn.prop('disabled', true).text('{{__("Creating...")}}');
        
        fetch('{{ route("admin.earning.create-payout") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                toastr.success(data.message);
                $('#createPayoutModal').modal('hide');
                location.reload();
            } else {
                toastr.error(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            toastr.error('An error occurred while creating the payout');
        })
        .finally(() => {
            submitBtn.prop('disabled', false).text('{{__("Create Payout")}}');
        });
    });

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

    */
</script>
@endpush
