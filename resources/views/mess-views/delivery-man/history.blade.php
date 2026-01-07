
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <div class="header-title">
            <h4 class="card-title">My Wallet History Information</h4>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-responsive">
            <thead>
                <tr>
                    <th>##</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($walletTransactions as $history)
                    <tr> 
                        <td>{{$loop->index + 1}}</td>
                        <td>{{App\CentralLogics\Helpers::format_currency($history->amount)}}</td>
                        <td>{{App\CentralLogics\Helpers::format_date($history->created_at)}}</td>
                        <td>{{Str::ucfirst($history->type) }}</td>
                        <td>{{Str::ucfirst($history->remarks)}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $walletTransactions->links() }}
    </div>
</div>
       