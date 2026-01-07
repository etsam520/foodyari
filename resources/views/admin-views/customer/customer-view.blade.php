@extends('layouts.dashboard-main')

@push('css')
    <style>
    .for-card-count {
        font-size: 1.2rem;
        font-weight: bold;
        color: #28a745;
    }
    .resturant-card {
        border-radius: 8px;
        border: 1px solid #e3e6f0;
        transition: all 0.3s ease;
    }
    .resturant-card:hover {
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }
    .loyalty-stats .card {
        border: none;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }
    .loyalty-stats .card-body h5 {
        margin-bottom: 0.5rem;
        font-weight: bold;
    }

    /* Enhanced referral section styles */
    .alert {
        border-left: 4px solid;
    }
    .alert-info {
        border-left-color: #17a2b8;
    }
    .alert-secondary {
        border-left-color: #6c757d;
    }

    .nav-tabs .nav-link {
        border-radius: 0.5rem 0.5rem 0 0;
        margin-right: 0.25rem;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0,0,0,.02);
    }

    .badge {
        font-size: 0.75em;
    }

    .tab-content {
        min-height: 200px;
    }

    .text-center.py-4 i {
        opacity: 0.5;
    }

    .fw-bold {
        font-weight: 600 !important;
    }

    /* Order table improvements */
    .order-table-container {
        min-height: 400px;
    }
    
    .loved-one-info {
        border-radius: 6px;
        padding: 8px 12px;
        margin: 2px 0;
    }
    
    .customer-info {
        border-radius: 6px;
        padding: 8px 12px;
        margin: 2px 0;
    }
    
    .search-highlight {
        background-color: #fff3cd;
        font-weight: bold;
        padding: 1px 3px;
        border-radius: 3px;
    }

    /* Responsive table improvements */
    @media (max-width: 768px) {
        .table-responsive {
            font-size: 0.875rem;
        }
        
        .nav-tabs {
            flex-wrap: nowrap;
            overflow-x: auto;
        }
        
        .nav-tabs .nav-link {
            white-space: nowrap;
        }
        
        .order-table-container .table td {
            padding: 0.5rem 0.25rem;
        }
        
        .btn--container .btn {
            padding: 0.25rem 0.5rem;
            margin: 0 1px;
        }
    }
    </style>
@endpush

@section('content')



    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <div class="row align-items-center ">
                                <div class="col-auto mb-2 mb-sm-0">
                                    <h3 class="page-header-title">{{ __('messages.customer') }} {{ __('messages.id') }} #{{ $customer['id'] }}</h3>
                                    <span class="d-block">
                                        <i class="tio-date-range"></i> {{ __('messages.joined_at') }} : {{ date('d M Y ' . config('timeformat'), strtotime($customer['created_at'])) }}
                                    </span>
                                </div>
                                <div class="col-auto ml-auto">
                                    <a class="btn btn-soft-info rounded-circle mr-1" href="{{ route('admin.customer.view', [$customer['id'] - 1]) }}" data-toggle="tooltip" data-placement="top" title="{{ __('Previous customer') }}">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                    <a class="btn btn-soft-info rounded-circle mr-1" href="{{ route('admin.customer.view', [$customer['id'] + 1]) }}" data-toggle="tooltip" data-placement="top" title="{{ __('Next customer') }}">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </div>
                                <div class="col-12 mt-3">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="resturant-card bg-soft-success d-flex p-2">
                                                <img class="theme-color-default-img img-fluid avatar avatar-100 " src="{{ asset('assets/images/icons/wallet.svg') }}" alt="dashboard">
                                                <div class="for-card-text font-weight-bold  text-uppercase mb-1">
                                                    {{ __('messages.wallet') }} {{ __('messages.balance') }}:
                                                    <i class="for-card-count">{{ App\CentralLogics\Helpers::format_currency($customer->wallet->balance ?? 0) }}</i>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Pending Requests Card Example -->
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="resturant-card bg-soft-success d-flex p-2">
                                                <img class="theme-color-default-img img-fluid avatar avatar-100 " src="{{ asset('assets/images/icons/loyaltipoint.svg') }}" alt="dashboard">
                                                <div class="for-card-text font-weight-bold  text-uppercase mb-1">
                                                    {{ __('messages.loyalty_point') }} {{ __('messages.balance') }}
                                                    <i class="for-card-count">{{ $customer->loyalty_points ?? 0 }}</i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Loyalty and Referral Stats -->
                                    <div class="row mt-3 loyalty-stats">
                                        <div class="col-lg-3 col-md-6 col-sm-6 mb-2">
                                            <div class="card bg-light border-left-success">
                                                <div class="card-body text-center">
                                                    <div class="text-success font-weight-bold h5">{{ $loyaltyStats['total_earned'] ?? 0 }}</div>
                                                    <small class="text-muted">Loyalty Earned</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6 mb-2">
                                            <div class="card bg-light border-left-primary">
                                                <div class="card-body text-center">
                                                    <div class="text-primary font-weight-bold h5">{{ $loyaltyStats['total_redeemed'] ?? 0 }}</div>
                                                    <small class="text-muted">Loyalty Redeemed</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6 mb-2">
                                            <div class="card bg-light border-left-info">
                                                <div class="card-body text-center">
                                                    <div class="text-info font-weight-bold h5">{{ $referralStats['total_referrals'] ?? 0 }}</div>
                                                    <small class="text-muted">Total Referrals</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6 mb-2">
                                            <div class="card bg-light border-left-warning">
                                                <div class="card-body text-center">
                                                    <div class="text-warning font-weight-bold h5">₹{{ number_format($referralStats['total_rewards_earned'] ?? 0, 2) }}</div>
                                                    <small class="text-muted">Referral Rewards</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Collected Cash Card Example -->
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row" id="printableArea">
                            <div class="col-lg-8 mb-3 mb-lg-0">
                                <div class="accordion" id="customerAccordian">
                                    <!-- Loyalty Points Section -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#loyalty-ac" aria-expanded="false" aria-controls="loyalty-ac">
                                                <i class="fas fa-star me-2"></i> Loyalty Points Details
                                            </button>
                                        </h2>
                                        <div id="loyalty-ac" class="accordion-collapse collapse" data-bs-parent="#customerAccordian">
                                            <div class="accordion-body">
                                                <div class="row mb-3">
                                                    <div class="col-md-4">
                                                        <div class="card text-center">
                                                            <div class="card-body">
                                                                <h5 class="text-success">{{ $loyaltyStats['total_earned'] ?? 0 }}</h5>
                                                                <small>Total Earned</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="card text-center">
                                                            <div class="card-body">
                                                                <h5 class="text-primary">{{ $loyaltyStats['total_redeemed'] ?? 0 }}</h5>
                                                                <small>Total Redeemed</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="card text-center">
                                                            <div class="card-body">
                                                                <h5 class="text-warning">{{ $customer->loyalty_points ?? 0 }}</h5>
                                                                <small>Current Balance</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                @if($customer->loyaltyPointTransactions && $customer->loyaltyPointTransactions->count() > 0)
                                                <h6>Recent Transactions</h6>
                                                <div class="table-responsive">
                                                    <table class="table table-sm">
                                                        <thead>
                                                            <tr>
                                                                <th>Date</th>
                                                                <th>Type</th>
                                                                <th>Points</th>
                                                                <th>Description</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($customer->loyaltyPointTransactions as $transaction)
                                                            <tr>
                                                                <td>{{ $transaction->created_at->format('M d, Y') }}</td>
                                                                <td>
                                                                    <span class="badge bg-{{ $transaction->type == 'earned' ? 'success' : ($transaction->type == 'redeemed' ? 'primary' : 'warning') }}">
                                                                        {{ ucfirst($transaction->type) }}
                                                                    </span>
                                                                </td>
                                                                <td>{{ $transaction->points }}</td>
                                                                <td>{{ $transaction->description ?? 'N/A' }}</td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                                @else
                                                <p class="text-muted">No loyalty transactions found.</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Referral Section -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#referral-ac" aria-expanded="false" aria-controls="referral-ac">
                                                <i class="fas fa-users me-2"></i> Referral Details
                                            </button>
                                        </h2>
                                        <div id="referral-ac" class="accordion-collapse collapse" data-bs-parent="#customerAccordian">
                                            <div class="accordion-body">
                                                <div class="row mb-3">
                                                    <div class="col-md-3">
                                                        <div class="card text-center">
                                                            <div class="card-body">
                                                                <h5 class="text-info">{{ $referralStats['total_referrals'] ?? 0 }}</h5>
                                                                <small>Total Referrals</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="card text-center">
                                                            <div class="card-body">
                                                                <h5 class="text-success">{{ $referralStats['successful_referrals'] ?? 0 }}</h5>
                                                                <small>Successful</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="card text-center">
                                                            <div class="card-body">
                                                                <h5 class="text-primary">₹{{ number_format($referralStats['claimed_rewards'] ?? 0, 2) }}</h5>
                                                                <small>Claimed Rewards</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="card text-center">
                                                            <div class="card-body">
                                                                <h5 class="text-warning">₹{{ number_format($referralStats['pending_rewards'] ?? 0, 2) }}</h5>
                                                                <small>Pending Rewards</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Referral Code Section -->
                                                @if($customer->referral_code)
                                                <div class="alert alert-info d-flex align-items-center">
                                                    <i class="fas fa-link me-2"></i>
                                                    <div>
                                                        <strong>Customer's Referral Code:</strong> 
                                                        <span class="badge bg-primary fs-6 ms-2">{{ $customer->referral_code }}</span>
                                                    </div>
                                                </div>
                                                @endif

                                                <!-- If customer was referred by someone -->
                                                @if($customer->referrer)
                                                <div class="alert alert-secondary d-flex align-items-center">
                                                    <i class="fas fa-user-friends me-2"></i>
                                                    <div>
                                                        <strong>Referred By:</strong> 
                                                        <a href="{{ route('admin.customer.view', $customer->referrer->id) }}" class="text-decoration-none">
                                                            {{ $customer->referrer->f_name }} {{ $customer->referrer->l_name }}
                                                        </a>
                                                        <small class="text-muted">(ID: {{ $customer->referrer->id }})</small>
                                                    </div>
                                                </div>
                                                @endif

                                                <!-- Referral Activity Tabs -->
                                                <ul class="nav nav-tabs mb-3" id="referralTabs" role="tablist">
                                                    <li class="nav-item" role="presentation">
                                                        <button class="nav-link active" id="referred-users-tab" data-bs-toggle="tab" data-bs-target="#referred-users" type="button" role="tab">
                                                            <i class="fas fa-users me-1"></i> Referred Users
                                                        </button>
                                                    </li>
                                                    <li class="nav-item" role="presentation">
                                                        <button class="nav-link" id="sponsor-rewards-tab" data-bs-toggle="tab" data-bs-target="#sponsor-rewards" type="button" role="tab">
                                                            <i class="fas fa-gift me-1"></i> Sponsor Rewards
                                                        </button>
                                                    </li>
                                                    <li class="nav-item" role="presentation">
                                                        <button class="nav-link" id="user-rewards-tab" data-bs-toggle="tab" data-bs-target="#user-rewards" type="button" role="tab">
                                                            <i class="fas fa-coins me-1"></i> User Rewards
                                                        </button>
                                                    </li>
                                                </ul>

                                                <div class="tab-content" id="referralTabContent">
                                                    <!-- Referred Users Tab -->
                                                    <div class="tab-pane fade show active" id="referred-users" role="tabpanel">
                                                        @if($customer->referredUsers && $customer->referredUsers->count() > 0)
                                                        <div class="table-responsive">
                                                            <table class="table table-sm table-hover">
                                                                <thead class="table-light">
                                                                    <tr>
                                                                        <th>User</th>
                                                                        <th>Email</th>
                                                                        <th>Phone</th>
                                                                        <th>Joined Date</th>
                                                                        <th>Orders</th>
                                                                        <th>Status</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($customer->referredUsers as $referredUser)
                                                                    <tr>
                                                                        <td>
                                                                            <div class="d-flex align-items-center">
                                                                                <img src="{{ $referredUser->image ? asset('storage/app/public/customers/'.$referredUser->image) : asset('public/assets/admin/img/160x160/img1.jpg') }}" 
                                                                                     class="rounded-circle me-2" width="30" height="30" alt="User">
                                                                                <div>
                                                                                    <strong>{{ $referredUser->f_name }} {{ $referredUser->l_name }}</strong>
                                                                                    <br><small class="text-muted">ID: {{ $referredUser->id }}</small>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td>{{ $referredUser->email }}</td>
                                                                        <td>{{ $referredUser->phone }}</td>
                                                                        <td>{{ $referredUser->created_at->format('M d, Y') }}</td>
                                                                        <td>
                                                                            <span class="badge bg-info">{{ $referredUser->successful_orders ?? 0 }}</span>
                                                                        </td>
                                                                        <td>
                                                                            <span class="badge bg-{{ $referredUser->status ? 'success' : 'danger' }}">
                                                                                {{ $referredUser->status ? 'Active' : 'Inactive' }}
                                                                            </span>
                                                                        </td>
                                                                        <td>
                                                                            <a href="{{ route('admin.customer.view', $referredUser->id) }}" 
                                                                               class="btn btn-sm btn-outline-primary" title="View Details">
                                                                                <i class="fas fa-eye"></i>
                                                                            </a>
                                                                        </td>
                                                                    </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        @else
                                                        <div class="text-center py-4">
                                                            <i class="fas fa-user-friends fa-3x text-muted mb-3"></i>
                                                            <p class="text-muted">No users referred yet.</p>
                                                        </div>
                                                        @endif
                                                    </div>

                                                    <!-- Sponsor Rewards Tab -->
                                                    <div class="tab-pane fade" id="sponsor-rewards" role="tabpanel">
                                                        @if($customer->sponsorRewards && $customer->sponsorRewards->count() > 0)
                                                        <div class="table-responsive">
                                                            <table class="table table-sm table-hover">
                                                                <thead class="table-light">
                                                                    <tr>
                                                                        <th>Date</th>
                                                                        <th>Referred User</th>
                                                                        <th>Reward Amount</th>
                                                                        <th>Orders Required</th>
                                                                        <th>Current Orders</th>
                                                                        <th>Status</th>
                                                                        <th>Claimed</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($customer->sponsorRewards as $reward)
                                                                    <tr>
                                                                        <td>{{ $reward->created_at->format('M d, Y') }}</td>
                                                                        <td>
                                                                            @if($reward->user)
                                                                            <a href="{{ route('admin.customer.view', $reward->user->id) }}" class="text-decoration-none">
                                                                                {{ $reward->user->f_name }} {{ $reward->user->l_name }}
                                                                            </a>
                                                                            @else
                                                                            <span class="text-muted">N/A</span>
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            <span class="fw-bold text-success">₹{{ number_format($reward->sponsor_reward_value, 2) }}</span>
                                                                        </td>
                                                                        <td>{{ $reward->required_orders }}</td>
                                                                        <td>{{ $reward->user_current_orders }}</td>
                                                                        <td>
                                                                            <span class="badge bg-{{ $reward->is_unlocked ? 'success' : 'warning' }}">
                                                                                {{ $reward->is_unlocked ? 'Unlocked' : 'Locked' }}
                                                                            </span>
                                                                        </td>
                                                                        <td>
                                                                            <span class="badge bg-{{ $reward->is_sponsor_claimed ? 'success' : 'secondary' }}">
                                                                                {{ $reward->is_sponsor_claimed ? 'Claimed' : 'Unclaimed' }}
                                                                            </span>
                                                                        </td>
                                                                    </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        @else
                                                        <div class="text-center py-4">
                                                            <i class="fas fa-gift fa-3x text-muted mb-3"></i>
                                                            <p class="text-muted">No sponsor rewards earned yet.</p>
                                                        </div>
                                                        @endif
                                                    </div>

                                                    <!-- User Rewards Tab -->
                                                    <div class="tab-pane fade" id="user-rewards" role="tabpanel">
                                                        @if($customer->referralRewards && $customer->referralRewards->count() > 0)
                                                        <div class="table-responsive">
                                                            <table class="table table-sm table-hover">
                                                                <thead class="table-light">
                                                                    <tr>
                                                                        <th>Date</th>
                                                                        <th>Sponsor</th>
                                                                        <th>Reward Amount</th>
                                                                        <th>Orders Required</th>
                                                                        <th>Current Orders</th>
                                                                        <th>Status</th>
                                                                        <th>Claimed</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($customer->referralRewards as $reward)
                                                                    <tr>
                                                                        <td>{{ $reward->created_at->format('M d, Y') }}</td>
                                                                        <td>
                                                                            @if($reward->sponsor)
                                                                            <a href="{{ route('admin.customer.view', $reward->sponsor->id) }}" class="text-decoration-none">
                                                                                {{ $reward->sponsor->f_name }} {{ $reward->sponsor->l_name }}
                                                                            </a>
                                                                            @else
                                                                            <span class="text-muted">N/A</span>
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            <span class="fw-bold text-primary">₹{{ number_format($reward->user_reward_value, 2) }}</span>
                                                                        </td>
                                                                        <td>{{ $reward->required_orders }}</td>
                                                                        <td>{{ $reward->user_current_orders }}</td>
                                                                        <td>
                                                                            <span class="badge bg-{{ $reward->is_unlocked ? 'success' : 'warning' }}">
                                                                                {{ $reward->is_unlocked ? 'Unlocked' : 'Locked' }}
                                                                            </span>
                                                                        </td>
                                                                        <td>
                                                                            <span class="badge bg-{{ $reward->is_user_claimed ? 'success' : 'secondary' }}">
                                                                                {{ $reward->is_user_claimed ? 'Claimed' : 'Unclaimed' }}
                                                                            </span>
                                                                        </td>
                                                                    </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        @else
                                                        <div class="text-center py-4">
                                                            <i class="fas fa-coins fa-3x text-muted mb-3"></i>
                                                            <p class="text-muted">No user rewards earned yet.</p>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Order Transactions Section -->
                                    <div class="accordion-item">
                                      <h2 class="accordion-header">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#transactions-ac" aria-expanded="true" aria-controls="collapseOne">
                                          <i class="fas fa-shopping-cart me-2"></i> Order Transactions
                                        </button>
                                      </h2>
                                      <div id="transactions-ac" class="accordion-collapse collapse show" data-bs-parent="#customerAccordian">
                                        <div class="accordion-body">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5 class="card-header-title">{{ __('messages.Order List') }} <span class="badge badge-soft-secondary" id="itemCount">{{ $orders->total() }}</span></h5>
                                                    <div class="float-end mb-2">
                                                        <form action="javascript:" id="search-form">
                                                            @csrf
                                                            <!-- Search -->
                                                            <input type="hidden" name="id" value="{{ $customer->id }}" id="">
                                                            <div class="input--group input-group input-group-merge input-group-flush">
                                                                <input id="datatableSearch_" type="search" name="search" class="form-control" value="{{ request()->get('search') }}" placeholder="{{ __('Search by Order ID, Customer Name, or Loved One Name...') }}" aria-label="Search">
                                                                <button type="submit" class="btn btn-soft-secondary">
                                                                    <i class="fa fa-search"></i>
                                                                </button>

                                                            </div>
                                                            <!-- End Search -->
                                                        </form>
                                                    </div>
                                                </div>
                                                <!-- Table -->
                                                <div class="table-responsive datatable-custom order-table-container">
                                                    <!-- Loading indicator -->
                                                    <div id="loading-indicator" class="text-center py-4" style="display: none;">
                                                        <div class="spinner-border text-primary" role="status">
                                                            <span class="visually-hidden">Loading...</span>
                                                        </div>
                                                        <p class="mt-2 text-muted">{{ __('Searching orders...') }}</p>
                                                    </div>
                                                    
                                                    <table id="columnSearchDatatable" class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th>{{ __('messages.sl') }}</th>
                                                                <th class="text-center w-50p">{{ __('messages.order') }} {{ __('messages.id') }}</th>
                                                                <th class="text-center">{{ __('messages.customer') }} / {{ __('Loved One') }}</th>
                                                                <th class="w-50p text-center">{{ __('messages.total') }} {{ __('messages.amount') }}</th>
                                                                <th class="text-center w-100px">{{ __('messages.action') }}</th>
                                                            </tr>
                                                        </thead>


                                                        <tbody id="set-rows">
                                                            @include('admin-views.customer.partial._order-table-list')
                                                        </tbody>

                                                    </table>
                                                    @if (count($orders) === 0)
                                                        <div class="empty--data">
                                                            <img src="{{ asset('/public/assets/admin/img/empty.png') }}" alt="public">
                                                            <h5>
                                                                {{ __('no_data_found') }}
                                                            </h5>
                                                        </div>
                                                    @endif
                                                    <!-- Pagination -->
                                                    <div class="page-area px-4 pb-3">
                                                        <div class="d-flex align-items-center justify-content-end">
                                                            {{-- <div>
                                                                1-15 of 380
                                                            </div> --}}
                                                            <div class="hide-page">
                                                                {!! $orders->links() !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Pagination -->
                                                </div>
                                            </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="accordion-item">
                                      <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                          Amount Transactions
                                        </button>
                                      </h2>
                                      <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#customerAccordian">
                                        <div class="accordion-body">
                                            <div class="mt-4">
                                                <div class="btn-group w-100 bg-white" role="group" aria-label="Basic radio toggle button group" id="txn_method">
                                                    <input type="radio" class="btn-check" name="txn_method" value="all" id="txn_method_all" checked>
                                                    <label class="btn" for="txn_method_all">All</label>
                                                    <input type="radio" class="btn-check" name="txn_method" value="wallet" id="txn_method_wallet">
                                                    <label class="btn" for="txn_method_wallet">Wallet</label>
                                                    <input type="radio" class="btn-check" name="txn_method" value="cash" id="txn_method_cash">
                                                    <label class="btn" for="txn_method_cash">Cash</label>
                                                    <input type="radio" class="btn-check" name="txn_method" value="online" id="txn_method_online">
                                                    <label class="btn" for="txn_method_online">Online</label>
                                                    <input type="radio" class="btn-check" name="txn_method" value="referal" id="txn_method_referral">
                                                    <label class="btn ml-0" for="txn_method_referral">Referral</label>
                                                </div>
                                                <div class="mt-2">
                                                    <div class="btn-group bg-white w-100" role="group" aria-label="Basic radio toggle button group" id="txn_type">
                                                        <input type="radio" class="btn-check" name="txn_type" value="all" id="txn_type_all" checked>
                                                        <label class="btn btndays" for="txn_type_all">All Transaction</label>
                                                        <input type="radio" class="btn-check" name="txn_type" value="paid" id="txn_type_paid">
                                                        <label class="btn btndays" for="txn_type_paid">Paid</label>
                                                        <input type="radio" class="btn-check" name="txn_type" value="received" id="txn_type_received">
                                                        <label class="btn btndays" for="txn_type_received">Received</label>
                                                        <input type="radio" class="btn-check" name="txn_type" value="refund" id="txn_type_refund">
                                                        <label class="btn btndays" for="txn_type_refund">Refunded</label>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="w-100 mt-3" >
                                                        <div class="row">
                                                            <div class="col-lg-12 col-12 mt-2 ">
                                                                <div class="rounded shadow-sm px-4 py-lg-5 py-4 bg-white" id="txn_history">
                                                                    <div class="bg-light rounded-end-2 p-3 d-flex justify-content-between" style="border-left:3px solid #ff810a;">
                                                                        <h6 class="mb-0">Promotional Credit Expired</h6>
                                                                        <div>₹ 100</div>
                                                                    </div>
                                                                    <div class="bg-light rounded-end-2 p-3 d-flex justify-content-between mt-3" style="border-left:3px solid #ff810a;">
                                                                        <h6 class="mb-0">Promotional Credit Expired</h6>
                                                                        <div>₹ 100</div>
                                                                    </div>
                                                                    <div class="bg-light rounded-end-2 p-3 d-flex justify-content-between mt-3" style="border-left:3px solid #ff810a;">
                                                                        <h6 class="mb-0">Refund for Order 1001165</h6>
                                                                        <div>₹ 100</div>
                                                                    </div>
                                                                    <div class="bg-light rounded-end-2 p-3 d-flex justify-content-between mt-3" style="border-left:3px solid #ff810a;">
                                                                        <h6 class="mb-0">Paid for order (Order number)</h6>
                                                                        <div>₹ 100</div>
                                                                    </div>
                                                                    <div class="mt-4">
                                                                       <span class="fw-bolder text-success"> Note : </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                      </div>
                                    </div>
                                </div>

                            </div>

                            <div class="col-lg-4">
                                <!-- Card -->
                                <div class="card">
                                    <!-- Header -->
                                    <div class="card-header">
                                        <h4 class="card-header-title">
                                            <span class="card-header-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="30">
                                                    <circle cx="12" cy="8" r="4" fill="#464646" ></circle>
                                                    <path fill="currentColor"
                                                        d="M20 19v1a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-1a6 6 0 0 1 6-6h4a6 6 0 0 1 6 6Z"></path>
                                                </svg>
                                            </span>
                                            <span>
                                                @if ($customer)
                                                    {{ $customer['f_name'] . ' ' . $customer['l_name'] }}
                                                @else
                                                    {{ __('messages.Customer') }}
                                                @endif
                                            </span>
                                        </h4>
                                    </div>
                                    <!-- End Header -->

                                    <!-- Body -->
                                    @if ($customer)
                                        <div class="card-body">
                                            <div class="media d-flex align-items-center customer-information-single " href="javascript:">
                                                <div class="avatar avatar-circle">
                                                    <img class="theme-color-default-img img-fluid avatar avatar-50 avatar-rounded" src="{{ asset('customers/' . $customer->image) }}" alt="Image Description">
                                                </div>
                                                <div class="media-body mx-2">
                                                    <ul class="list-unstyled m-0">
                                                        <li class="pb-1">
                                                            <i class="tio-email mr-2"></i>
                                                            {{ $customer['email'] }}
                                                        </li>
                                                        <li class="pb-1">
                                                            <i class="tio-call-talking-quiet mr-2"></i>
                                                            {{ $customer['phone'] }}
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h5>{{ __('messages.contact') }} {{ __('messages.info') }}</h5>
                                            </div>
                                            {{-- @foreach ($customer->addresses as $address)
                                                <ul class="list-unstyled list-unstyled-py-2">
                                                    @if ($address['contact_person_umber'])
                                                        <li>
                                                            <i class="tio-call-talking-quiet mr-2"></i>
                                                            {{ $address['contact_person_umber'] }}
                                                        </li>
                                                    @endif
                                                    <li class="quick--address-bar">
                                                        <div class="quick-icon badge-soft-secondary">
                                                            <i class="tio-home"></i>
                                                        </div>
                                                        <div class="info">
                                                            <h6>{{ $address['address_type'] }}</h6>
                                                            <a target="_blank" href="http://maps.google.com/maps?z=12&t=m&q=loc:{{ $address['latitude'] }}+{{ $address['longitude'] }}" class="text--title">
                                                                {{ $address['address'] }}
                                                            </a>
                                                        </div>
                                                    </li>
                                                </ul>
                                            @endforeach --}}

                                        </div>
                                    @endif
                                    <!-- End Body -->
                                </div>
                                <!-- End Card -->

                                <div class="card mt-4">
                                    <!-- Header -->
                                    <div class="card-header">
                                        <h4 class="card-header-title">
                                            <span class="card-header-icon">
                                                <i class="tio-wallet"></i>
                                            </span>
                                            <span>
                                                {{ __('messages.Wallet') }}
                                            </span>
                                        </h4>
                                    </div>
                                    <!-- End Header -->

                                    <!-- Body -->
                                    @if ($customer)
                                        <div class="card-body">

                                            <form
                                            action="{{ route('admin.customer.add-wallet-fund') }}"
                                            method="post" enctype="multipart/form-data" id="add_fund" class="js-validate">
                                                @csrf
                                                <div class="row">
                                                    <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                                                    <input type="hidden" id="customer_name" value="{{ $customer->f_name }} {{ $customer->l_name }}">
                                                    <input type="hidden" id="customer_phone" value="{{ $customer->phone }}">
                                                    <div class="col-md-12 col-12">
                                                        <span class="input-label">
                                                            {{ __('messages.Transaction') }}
                                                            {{ __('messages.Type') }}
                                                        </span>
                                                    </div>
                                                    <div class="col-md-6 col-12">
                                                        <label class="form-check form--check mr-2 mr-md-4">
                                                            <input class="form-check-input transaction_type" type="radio" value="credit" name="transaction_type" id="credit">
                                                            <span class="form-check-label">
                                                                {{ __('messages.Credit') }}
                                                            </span>
                                                        </label>
                                                    </div>
                                                    <div class="col-md-6 col-12">
                                                        <label class="form-check form--check mr-2 mr-md-4">
                                                            <input class="form-check-input transaction_type" type="radio" value="debit" name="transaction_type" id="debit">
                                                            <span class="form-check-label">
                                                                {{ __('messages.Debit') }}
                                                            </span>
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-12 col-12 mt-3">
                                                        <div class="form-group">
                                                            <label class="form-label" for="amount">{{ __('messages.amount') }}</label>

                                                            <input type="number" class="form-control h--45px" placeholder="{{ __('messages.Enter Amount') }}" name="amount" id="amount" step=".01" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label class="form-label" for="referance">{{ __('messages.reference') }} <small>({{ __('messages.optional') }})</small></label>

                                                            <input type="text" class="form-control h--45px" placeholder="{{ __('messages.Reference') }}" name="referance" id="referance">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="btn--container justify-content-end">
                                                    <button type="reset" id="reset" class="btn btn--reset">{{ __('messages.reset') }}</button>
                                                    <button type="submit" id="submit" class="btn btn--primary">{{ __('messages.submit') }}</button>
                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                    <!-- End Body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Row -->
    </div>
@endsection

@push('javascript')

<script>
// Initialize Bootstrap tabs functionality
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all tabs
    var triggerTabList = [].slice.call(document.querySelectorAll('#referralTabs button'))
    triggerTabList.forEach(function (triggerEl) {
        var tabTrigger = new bootstrap.Tab(triggerEl)
        
        triggerEl.addEventListener('click', function (event) {
            event.preventDefault()
            tabTrigger.show()
        })
    })
});

const TXN_METHOD = {
    all : true,
    wallet : false,
    cash : false,
    online : false,
    referal : false,
}

const TXN_TYPE = {
    all : true ,
    received : false,
    paid : false,
    refund : false,
}

document.querySelectorAll('#txn_method input[name="txn_method"]').forEach((method) => {
    method.addEventListener('change', (event) => {
        if(TXN_METHOD.hasOwnProperty(event.target.value) && event.target.checked){
            for (let key in TXN_METHOD) {
                TXN_METHOD[key] = false;
            }
            TXN_METHOD[event.target.value] = true;
        }
        getHistories();
    });
});

document.querySelectorAll('#txn_type input[name="txn_type"]').forEach((type) => {
    type.addEventListener('change', (event) => {
        if(TXN_TYPE.hasOwnProperty(event.target.value) && event.target.checked){
            for (let key in TXN_TYPE) {
                TXN_TYPE[key] = false;
            }
            TXN_TYPE[event.target.value] = true;
        }
        // console.log(TXN_TYPE);
        getHistories();

    });
});

async function getHistories() {
    const url = "{{route('admin.customer.payments.history')}}?customer_id={{$customer->id}}"
    try {
        const resp = await fetch(url, {
            method: "post",
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify({
                txn_type: TXN_TYPE,
                txn_method: TXN_METHOD,
            })
        });

        if (resp.status == 201) {
            const warning = await resp.json();
            Swal.fire(warning.message);
            return true;
        } else if (!resp.ok) {
            const error = await resp.json();

            throw new Error(error.message);
        }
        const result = await resp.json();
        // $('#custom_item').modal('hide');
        appendTransactionData(result);

    } catch (error) {
        console.error('Error:', error);
    }
}
getHistories();

function appendTransactionData(transactions) {
    // Get the container where transaction history will be appended
    const txnHistoryContainer = document.getElementById('txn_history');
    txnHistoryContainer.innerHTML ='';
    const transactionArray = Object.values(transactions);

    // Loop through each transaction in the array
    transactionArray.forEach((txn) => {
        // Create the outer transaction container
        const txnContainer = document.createElement('div');
        // txnContainer.classList.add('col-lg-12', 'col-12', 'mt-2');

        // Create the inner card structure
        txnContainer.innerHTML = `
                <div class="bg-light rounded-end-2 p-3 d-flex justify-content-between" style="border-left:3px solid #ff810a;">
                    <h6 class="mb-0">${txn.remarks || 'Transaction Details'}</h6>
                    <div>₹ ${txn.amount.toFixed(2)}</div>
                </div>
                <small class="text-muted">Date: ${new Date(txn.date).toLocaleString()}</small>
        `;

        // Append the transaction container to the main transaction history row
        txnHistoryContainer.appendChild(txnContainer);
    });
}

// Auto-refresh customer stats every 30 seconds
setInterval(function() {
    // Only refresh if the page is visible
    if (!document.hidden) {
        // Refresh wallet balance and loyalty points
        refreshCustomerStats();
    }
}, 30000);

function refreshCustomerStats() {
    // You can implement AJAX calls here to refresh stats without page reload
    console.log('Refreshing customer stats...');
}

// Add loading states for accordion sections
document.addEventListener('DOMContentLoaded', function() {
    const accordionButtons = document.querySelectorAll('.accordion-button');
    
    accordionButtons.forEach(button => {
        button.addEventListener('click', function() {
            const target = this.getAttribute('data-bs-target');
            const accordionBody = document.querySelector(target + ' .accordion-body');
            
            if (accordionBody && !accordionBody.dataset.loaded) {
                // Mark as loaded to prevent multiple loadings
                accordionBody.dataset.loaded = 'true';
            }
        });
    });
});

// Order search functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('search-form');
    const searchInput = document.getElementById('datatableSearch_');
    const setRows = document.getElementById('set-rows');
    const itemCount = document.getElementById('itemCount');
    const loadingIndicator = document.getElementById('loading-indicator');
    const dataTable = document.getElementById('columnSearchDatatable');
    
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            // Show loading state
            if (loadingIndicator && dataTable) {
                loadingIndicator.style.display = 'block';
                dataTable.style.opacity = '0.5';
            }
            
            fetch('{{ route("admin.customer.order-search") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.view) {
                    setRows.innerHTML = data.view;
                    itemCount.textContent = data.total || 0;
                    
                    // Hide pagination when search is active
                    const pagination = document.querySelector('.hide-page');
                    if (pagination) {
                        pagination.style.display = searchInput.value.trim() ? 'none' : 'block';
                    }
                }
                
                // Hide loading state
                if (loadingIndicator && dataTable) {
                    loadingIndicator.style.display = 'none';
                    dataTable.style.opacity = '1';
                }
            })
            .catch(error => {
                console.error('Search error:', error);
                setRows.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Search failed. Please try again.</td></tr>';
                
                // Hide loading state
                if (loadingIndicator && dataTable) {
                    loadingIndicator.style.display = 'none';
                    dataTable.style.opacity = '1';
                }
            });
        });
        
        // Reset search functionality
        searchInput.addEventListener('input', function() {
            if (this.value.trim() === '') {
                // Reload page to reset search
                window.location.reload();
            }
        });
        
        // Clear search on escape key
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                this.value = '';
                window.location.reload();
            }
        });
    }
});
</script>
@endpush
