@extends('user-views.restaurant.layouts.main')

@section('containt')
<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Online Payments History Information</h4>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-responsive">
                        <thead>
                            <tr>
                                <th>##</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th class="text-break">Transaction Id</th>
                                <th>Gateway</th>
                                {{-- <th>Remarks</th> --}}
                                
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($txns as $item)
                                <tr> 
                                    <td>{{$loop->index + 1}}</td>
                                    <td>{{App\CentralLogics\Helpers::format_currency($item->amount)}}</td>
                                    <td>{{App\CentralLogics\Helpers::format_date($item->created_at)}}</td>
                                    <td>
                                        @if($item->payment_status == 'success')
                                        <span class="badge py-1 px-1 bg-success">
                                            {{Str::ucfirst($item->payment_status) }}</td>
                                        </span>
                                        @elseif ($item->payment_status == 'pending')
                                        <span class="badge py-1 px-1 bg-info">
                                            {{Str::ucfirst($item->payment_status) }}</td>
                                        </span>
                                        @elseif ($item->payment_status == 'failed')
                                        <span class="badge py-1 px-1 bg-primary">
                                            {{Str::ucfirst($item->payment_status) }}</td>
                                        </span>
                                        @endif
                                        
                                    <td><span class="text-break">{{Str::ucfirst($item->txn_id)}}</td></span>
                                    <td>
                                        <span class="badge py-1 px-1 bg-primary">
                                            {{Str::ucfirst($item->gateway) }}</td>
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $txns->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


