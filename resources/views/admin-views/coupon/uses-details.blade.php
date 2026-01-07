@extends('layouts.dashboard-main')

@section('content')
<div class="container-fluid content-inner mt-n5 py-0">
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="header-title">
                        <h4 class="card-title">Coupon Uses Details</h4>
                        <p class="mb-0">Coupon: <strong>{{$coupon->title}} ({{$coupon->code}})</strong></p>
                    </div>
                    <div>
                        <a href="{{route('admin.coupon.add-new')}}" class="btn btn-primary">
                            <i class="fas fa-arrow-left me-2"></i>Back
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h3 class="mb-1">{{$coupon->total_uses}}</h3>
                                    <p class="mb-0">Total Uses</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h3 class="mb-1">{{$coupon->discount}}{{$coupon->discount_type == 'percent' ? '%' : ''}}</h3>
                                    <p class="mb-0">Discount</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h3 class="mb-1">{{$coupon->limit ?: 'Unlimited'}}</h3>
                                    <p class="mb-0">Limit</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header py-2">
                    <div class="search-button-wrapper">
                        <h5 class="card-title">Uses History<span class="badge badge-primary ml-2">{{$usesDetails->total()}}</span></h5>
                    </div>
                </div>
                
                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th>S.I.</th>
                                <th>Order ID</th>
                                <th>Order Amount</th>
                                <th>Order Date</th>
                                <th>Customer Details</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                        @forelse($usesDetails as $key => $useDetail)
                            <tr>
                                <td>{{$key + $usesDetails->firstItem()}}</td>
                                <td>
                                    <a href="{{route('admin.order.details', $useDetail->order_id)}}"><span class="badge bg-secondary">
                                        #{{$useDetail->order_id}}
                                    </span></a>
                                </td>
                                {{-- @dd($useDetail) --}}
                                <td>
                                    @if(floatval($useDetail->order_amount) > 0)
                                        <div class="text-right">
                                            {{Helpers::format_currency($useDetail->order_amount ?? 0)}}
                                        </div>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>{{$useDetail->used_at ? \Carbon\Carbon::parse($useDetail->used_at)->format('M d, Y h:i A') : 'N/A'}}</td>
                                <td>
                                    <div class="mb-1">
                                        <h6 class="text-muted mb-1">{{$useDetail->user_name}}</h6>

                                        <p class="text-muted mb-1">{{$useDetail->email}}</p>

                                        <p class="text-muted mb-1">{{$useDetail->phone}}</p>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if($useDetail->order_id)
                                        <a href="{{route('admin.order.details', $useDetail->order_id)}}" 
                                           class="btn btn-sm btn-outline-primary"
                                           title="View Order">
                                            <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M22.4541 11.3918C22.7819 11.7385 22.7819 12.2615 22.4541 12.6082C20.7124 14.4335 16.9577 18 12 18C7.04234 18 3.28756 14.4335 1.54586 12.6082C1.21811 12.2615 1.21811 11.7385 1.54586 11.3918C3.28756 9.56647 7.04234 6 12 6C16.9577 6 20.7124 9.56647 22.4541 11.3918Z" stroke="currentColor" stroke-width="1.5"/>
                                                <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.5"/>
                                            </svg>
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="empty--data">
                                        <img src="{{asset('/public/assets/admin/img/empty.png')}}" alt="No data" width="100">
                                        <h5 class="mt-3">No Data Found</h5>
                                        <p class="text-muted">This coupon has not been used yet</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                    
                    @if($usesDetails->hasPages())
                        <div class="page-area px-4 pb-3">
                            <div class="d-flex align-items-center justify-content-end">
                                <div>
                                    {!! $usesDetails->links() !!}
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
