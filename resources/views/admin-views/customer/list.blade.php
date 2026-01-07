@extends('layouts.dashboard-main')

@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Customer List</h4>
                    </div>
                    <div class="d-flex gap-2">
                        <!-- Search Box -->
                        <form method="GET" action="{{ route('admin.customer.list') }}" class="d-flex gap-2">
                            <input type="text" name="search" class="form-control" placeholder="Search by name, email, phone..." value="{{ request('search') }}">
                            <select name="status" class="form-select" style="width: 120px;">
                                <option value="">All Status</option>
                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                            @if(request('search') || request('status') !== null)
                                <a href="{{ route('admin.customer.list') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i>
                                </a>
                            @endif
                        </form>
                    </div>
                </div>
                <div class="card-body px-0">
                    <div class="table-responsive">
                        <table id="user-list-table" class="table " role="grid" data-toggle="data-table">
                            <thead>
                                <tr class="ligth">
                                    <th>Profile</th>
                                    <th>Name</th>
                                    <th>Contact</th>
                                    <th>Orders</th>
                                    <th>Wallet</th>
                                    <th>Loyalty Points</th>
                                    <th>Status</th>
                                    <th style="min-width: 100px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($customers as $customer )
                                <tr>
                                    <td class="text-center"><a href="{{route('admin.customer.view',['id'=> $customer->id])}}"><img class="bg-soft-primary rounded img-fluid avatar-40 me-3" src="{{asset("customers/$customer->image")}}" alt="profile"></a></td>
                                    <td> 
                                        <a href="{{route('admin.customer.view',['id'=> $customer->id])}}">
                                            {{$customer->f_name}} {{$customer->l_name}} 
                                            <br><small class="text-muted">{{$customer->email}}</small>
                                            @if($customer->referral_code)
                                                <br><span class="badge bg-info">Ref: {{$customer->referral_code}}</span>
                                            @endif
                                        </a>
                                    </td>
                                    <td>{{$customer->phone}}</td>
                                    <td>
                                        <span class="badge bg-success">{{$customer->delivered_orders_count}}</span> / 
                                        <span class="badge bg-secondary">{{$customer->total_orders_count}}</span>
                                    </td>
                                    <td>
                                        <span class="text-success fw-bold">
                                            ₹{{ number_format($customer->wallet->balance ?? 0, 2) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning text-dark">
                                            {{$customer->loyalty_points ?? 0}} pts
                                        </span>
                                    </td>
                                    <td>
                                        <div class="form-check w-100 form-switch px-3">
                                            <input type="checkbox"  data-toggle="toggle" name="customer-status-{{$customer->id}}" {{$customer->status ==1?'checked':''}} onchange="location.href='{{ route('admin.customer.status')}}?id={{$customer->id}}&status='+this.checked"
                                             class="form-check-input mx-auto form-control fs-4" id="customer-status-{{$customer->id}}">
                                        </div>
                                    </td>
                                    <td><div class="flex align-items-center ">
                                        <a href="{{ route('admin.customer.edit', $customer->id) }}" class="btn btn-sm btn-icon btn-primary me-1">Edit</a>
                                        <a href="{{route('admin.customer.view',['id'=> $customer->id])}}" class="btn btn-sm btn-icon btn-primary me-1">View</a>
                                        <a href="{{route('admin.customer.access',$customer->id)}}" target="_blank" class="btn btn-sm btn-icon btn-warning">Access</a>
                                    </div></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                        {{-- <!-- Pagination -->
                        <div class="card-footer d-flex justify-content-between align-items-center">
                            <div>
                                Showing {{ $customers->firstItem() }} to {{ $customers->lastItem() }} of {{ $customers->total() }} customers
                            </div>
                            <div>
                                {{ $customers->links() }}
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
