@extends('user-views.restaurant.layouts.main')
@section('containt')
<div class="osahan-home-page">
    
    <div class="main">
      
        <div class="container position-relative">
            <div class="row mt-3">
                <div class="col-md-12">
                    <!-- Loyalty Points Dashboard -->
                    <section class="bg-white osahan-main-body rounded shadow-sm overflow-hidden">
                        <div class="container p-0">
                            <div class="row">
                                <div class="col-lg-12">
                                    <!-- Header -->
                                    <div class="bg-primary text-white p-4 text-center">
                                        <h3 class="mb-2">
                                            <i class="fas fa-star me-2"></i>
                                            Loyalty Points
                                        </h3>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="text-center">
                                                    <h4 class="fw-bold mb-1">{{ number_format($customer->loyalty_points, 2) }}</h4>
                                                    <small>Available Points</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="text-center">
                                                    <h4 class="fw-bold mb-1">{{ App\CentralLogics\Helpers::format_currency($currencyValue) }}</h4>
                                                    <small>Wallet Value</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="text-center">
                                                    <h4 class="fw-bold mb-1">{{ $loyaltySettings['loyalty_percent'] }}%</h4>
                                                    <small>Earning Rate</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Redeem Section -->
                                    @if($customer->loyalty_points > 0)
                                    <div class="p-4 border-bottom">
                                        <h5 class="mb-3">
                                            <i class="fas fa-exchange-alt me-2"></i>
                                            Redeem Points
                                        </h5>
                                        
                                        <form action="{{ route('user.loyalty.redeem') }}" method="POST" id="redeemForm">
                                            @csrf
                                            <div class="row align-items-end">
                                                <div class="col-md-4">
                                                    <label for="points" class="form-label">Points to Redeem</label>
                                                    <input type="number" 
                                                           class="form-control" 
                                                           id="points" 
                                                           name="points" 
                                                           min="1" 
                                                           max="{{ $customer->loyalty_points }}"
                                                           step="0.01"
                                                           required>
                                                    <small class="text-muted">Max: {{ number_format($customer->loyalty_points, 2) }} points</small>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Wallet Value</label>
                                                    <div class="form-control-plaintext fw-bold" id="walletValue">
                                                        {{ App\CentralLogics\Helpers::format_currency(0) }}
                                                    </div>
                                                    <small class="text-muted">1 point = {{ App\CentralLogics\Helpers::format_currency($loyaltySettings['loyalty_value']) }}</small>
                                                </div>
                                                <div class="col-md-4">
                                                    <button type="submit" class="btn btn-primary w-100" id="redeemBtn">
                                                        <i class="fas fa-exchange-alt me-2"></i>
                                                        Redeem to Wallet
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <small class="text-info">
                                                    <i class="fas fa-info-circle me-1"></i>
                                                    Minimum redeem value: {{ App\CentralLogics\Helpers::format_currency($loyaltySettings['minimum_redeem_value']) }}
                                                </small>
                                            </div>
                                        </form>
                                    </div>
                                    @endif

                                    <!-- How it Works -->
                                    <div class="p-4 border-bottom">
                                        <h5 class="mb-3">
                                            <i class="fas fa-question-circle me-2"></i>
                                            How Loyalty Points Work
                                        </h5>
                                        <div class="row">
                                            <div class="col-md-4 text-center mb-3">
                                                <div class="bg-light rounded p-3">
                                                    <i class="fas fa-shopping-cart fa-2x text-primary mb-2"></i>
                                                    <h6>Order & Earn</h6>
                                                    <p class="small mb-0">Earn {{ $loyaltySettings['loyalty_percent'] }}% of your order value as loyalty points</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4 text-center mb-3">
                                                <div class="bg-light rounded p-3">
                                                    <i class="fas fa-star fa-2x text-warning mb-2"></i>
                                                    <h6>Collect Points</h6>
                                                    <p class="small mb-0">Points are automatically added when your order is delivered</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4 text-center mb-3">
                                                <div class="bg-light rounded p-3">
                                                    <i class="fas fa-wallet fa-2x text-success mb-2"></i>
                                                    <h6>Redeem to Wallet</h6>
                                                    <p class="small mb-0">Convert points to wallet balance and use for future orders</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Transaction History -->
                                    <div class="p-4">
                                        <h5 class="mb-3">
                                            <i class="fas fa-history me-2"></i>
                                            Transaction History
                                        </h5>
                                        
                                        @if($transactions->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Type</th>
                                                        <th>Points</th>
                                                        <th>Description</th>
                                                        <th>Value</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($transactions as $transaction)
                                                    <tr>
                                                        <td>
                                                            <small>{{ $transaction->created_at->format('M d, Y') }}</small><br>
                                                            <small class="text-muted">{{ $transaction->created_at->format('h:i A') }}</small>
                                                        </td>
                                                        <td>
                                                            @if($transaction->type == 'earned')
                                                                <span class="badge bg-success">
                                                                    <i class="fas fa-plus me-1"></i>Earned
                                                                </span>
                                                            @elseif($transaction->type == 'redeemed')
                                                                <span class="badge bg-primary">
                                                                    <i class="fas fa-minus me-1"></i>Redeemed
                                                                </span>
                                                            @else
                                                                <span class="badge bg-warning">
                                                                    <i class="fas fa-clock me-1"></i>Expired
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($transaction->type == 'earned')
                                                                <span class="text-success">+{{ number_format($transaction->points, 2) }}</span>
                                                            @else
                                                                <span class="text-danger">-{{ number_format($transaction->points, 2) }}</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <small>{{ $transaction->description }}</small>
                                                            @if($transaction->order)
                                                                <br><small class="text-muted">Order #{{ $transaction->order->id }}</small>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($transaction->amount)
                                                                <small class="fw-bold">{{ App\CentralLogics\Helpers::format_currency($transaction->amount) }}</small>
                                                            @else
                                                                <small class="text-muted">-</small>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        
                                        <!-- Pagination -->
                                        @if($transactions->hasPages())
                                        <div class="d-flex justify-content-center mt-3">
                                            {{ $transactions->links() }}
                                        </div>
                                        @endif
                                        
                                        @else
                                        <div class="text-center py-5">
                                            <i class="fas fa-star fa-3x text-muted mb-3"></i>
                                            <h6 class="text-muted">No loyalty point transactions yet</h6>
                                            <p class="text-muted">Start ordering to earn loyalty points!</p>
                                            <a href="{{ route('userHome') }}" class="btn btn-primary">
                                                <i class="fas fa-utensils me-2"></i>Start Ordering
                                            </a>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('javascript')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const pointsInput = document.getElementById('points');
    const walletValueDiv = document.getElementById('walletValue');
    const redeemBtn = document.getElementById('redeemBtn');
    const loyaltyValue = {{ $loyaltySettings['loyalty_value'] }};
    const minimumRedeemValue = {{ $loyaltySettings['minimum_redeem_value'] }};
    const maxPoints = {{ $customer->loyalty_points }};

    if (pointsInput) {
        pointsInput.addEventListener('input', function() {
            const points = parseFloat(this.value) || 0;
            const walletValue = points * loyaltyValue;
            
            // Update wallet value display
            walletValueDiv.textContent = new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'INR'
            }).format(walletValue);
            
            // Enable/disable redeem button based on minimum value
            if (walletValue >= minimumRedeemValue && points <= maxPoints && points > 0) {
                redeemBtn.disabled = false;
                redeemBtn.classList.remove('btn-secondary');
                redeemBtn.classList.add('btn-primary');
            } else {
                redeemBtn.disabled = true;
                redeemBtn.classList.remove('btn-primary');
                redeemBtn.classList.add('btn-secondary');
            }
        });
    }

    // Form validation
    const redeemForm = document.getElementById('redeemForm');
    if (redeemForm) {
        redeemForm.addEventListener('submit', function(e) {
            const points = parseFloat(pointsInput.value) || 0;
            const walletValue = points * loyaltyValue;
            
            if (walletValue < minimumRedeemValue) {
                e.preventDefault();
                alert(`Minimum redeem value is ${new Intl.NumberFormat('en-US', {
                    style: 'currency',
                    currency: 'INR'
                }).format(minimumRedeemValue)}`);
                return false;
            }
            
            if (points > maxPoints) {
                e.preventDefault();
                alert(`You only have ${maxPoints} loyalty points available`);
                return false;
            }
            
            // Confirm redemption
            if (!confirm(`Are you sure you want to redeem ${points} points for ${new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'INR'
            }).format(walletValue)}?`)) {
                e.preventDefault();
                return false;
            }
        });
    }
});
</script>
@endpush
