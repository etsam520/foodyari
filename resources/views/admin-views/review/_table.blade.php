@extends('layouts.dashboard-main')

@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Review List</h4>
                    </div>
                </div>
                <div class="card-body px-0">
                    <div class="table-responsive">
                        <table id="user-list-table" class="table " role="grid" data-toggle="data-table">
                            <thead>
                                <tr class="ligth">
                                    <th>S.I.</th>
                                    <th>Time</th>
                                    <th>Customer</th>
                                     <th>Order No</th>
                                    <th>Rating</th>
                                    <th>To</th>

                                    <th style="min-width: 100px">Review</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reviews as $rev )
                                <tr>
                                    <td class="text-center">{{$loop->index + 1}}</td>
                                    <td>{{Helpers::timeAgo($rev->created_at)}}</td>
                                    <td><a href="{{route('admin.customer.view',['id'=> $rev->customer->id])}}">{{Str::ucfirst($rev->customer->f_name)}} {{Str::ucfirst($rev->customer->l_name)}}</a></td>
                                    <td><a href="{{route('admin.order.details', $rev->order_id)}}" class="text-primary">#{{$rev->order_id}}</a></td>
                                    <td><div class="badge bg-success text-white p-2 mt-1">
                                        {{$rev->rating}} <i class="fas fa-star ms-1"></i>
                                    </div></td>
                                    <td>
                                        @if($rev->review_to == 'restaurant')
                                        <div>Restaurant : <span class="text-muted">{{Str::ucfirst($rev->restaurant->name)}}</span></div>
                                        @elseif ($rev->review_to == 'deliveryman')
                                        <div>Deliveryman : <span class="text-muted">{{Str::ucfirst($rev->deliveryman->f_name)." ".Str::ucfirst($rev->deliveryman->l_name)}}</span></div>
                                        @endif
                                    </td>

                                    <td><span class="text-dark">{{Str::ucfirst($rev->review)}}</span></td>
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
