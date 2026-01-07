@extends('vendor-views.layouts.dashboard-main')

@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Customer List</h4>
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
                                    <th>Address</th>
                                    <th>Status</th>
                                    <th style="min-width: 100px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($customers as $customer ) 
                                <tr> 
                                    <td class="text-center"><a href="{{route('vendor.customer.view', $customer->id)}}"><img class="bg-soft-primary rounded img-fluid avatar-40 me-3" src="{{asset("customers/$customer->image")}}" alt="profile"></a></td>
                                    <td> <a href="{{route('vendor.customer.view', $customer->id)}}">{{$customer->f_name}} {{$customer->l_name}} <br>{{$customer->email}}</a></td>
                                    <td>{{$customer->phone}}</td>
                                    @php($address = json_decode($customer->address, true))
                                    <td>{{$address['street']}}, {{$address['city']}}-{{$address['pincode']}}</td>
                                    <td>Active</td>
                                    <td>action</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
