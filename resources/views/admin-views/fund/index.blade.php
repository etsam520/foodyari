@extends('layouts.dashboard-main')

@push('css')
<style>
    .fund-dashboard {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
        padding: 20px 0;
    }
    .stat-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        border: none;
        text-align: center;
    }
    .stat-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 8px 40px rgba(0,0,0,0.15);
    }
    .stat-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 2rem;
        color: white;
    }
    .balance-icon {
        background: linear-gradient(45deg, #4CAF50, #45a049);
    }
    .time-icon {
        background: linear-gradient(45deg, #FF9800, #f57c00);
    }
    .stat-value {
        font-size: 2.5rem;
        font-weight: bold;
        color: #333;
        margin-bottom: 10px;
    }
    .stat-label {
        font-size: 1.1rem;
        color: #666;
        margin-bottom: 0;
    }
    .history-section {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        margin-top: 30px;
    }
    .history-link {
        display: inline-flex;
        align-items: center;
        text-decoration: none;
        color: #4CAF50;
        font-weight: bold;
        font-size: 1.2rem;
        transition: all 0.3s ease;
    }
    .history-link:hover {
        color: #45a049;
        transform: scale(1.05);
    }
    .history-link i {
        margin-right: 10px;
        font-size: 1.5rem;
    }
</style>
@endpush

@section('content')
<div class="fund-dashboard">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <h1 class="text-center mb-5 text-dark font-weight-bold">
                    <i class="fas fa-chart-line me-3"></i>Transaction Dashboard
                </h1>
            </div>
        </div>

        <div class="row g-4">
            <!-- Balance Card -->
            <div class="col-lg-6 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon balance-icon">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div class="stat-value">{{ App\CentralLogics\Helpers::format_currency($fund->balance) }}</div>
                    <p class="stat-label">Current Balance</p>
                </div>
            </div>

            <!-- Last Updated Card -->
            <div class="col-lg-6 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon time-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-value">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $fund->updated_at)->format('h:i A') }}</div>
                    <p class="stat-label">Last Updated: {{ App\CentralLogics\Helpers::format_date($fund->updated_at) }}</p>
                </div>
            </div>
        </div>

        <!-- History Section -->
        <div class="row justify-content-center mt-4">
            <div class="col-lg-8">
                <div class="history-section text-center">
                    <h3 class="mb-4 text-dark">Transaction History</h3>
                    <p class="text-muted mb-4">View detailed transaction history and manage your funds</p>
                    <a href="{{ route('admin.fund.histories') }}" class="history-link">
                        <i class="fas fa-history"></i>
                        View Full History
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection