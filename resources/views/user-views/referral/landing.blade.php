@extends('user-views.restaurant.layouts.main')

@section('title', 'Join with Referral')

@push('css')
<style>
    .referral-landing {
        min-height: 100vh;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    
    .referral-card {
        background: white;
        border-radius: 20px;
        padding: 40px;
        max-width: 500px;
        width: 100%;
        text-align: center;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }
    
    .referral-logo {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, #ff6b6b, #feca57);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 40px;
    }
    
    .benefits-list {
        list-style: none;
        padding: 0;
        margin: 20px 0;
    }
    
    .benefits-list li {
        padding: 10px 0;
        border-bottom: 1px solid #eee;
        display: flex;
        align-items: center;
    }
    
    .benefits-list li:last-child {
        border-bottom: none;
    }
    
    .benefits-list i {
        color: #28a745;
        margin-right: 10px;
        font-size: 18px;
    }
    
    .sponsor-info {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin: 20px 0;
    }
    
    .cta-buttons {
        margin-top: 30px;
    }
    
    .cta-buttons .btn {
        margin: 5px;
        padding: 12px 30px;
        border-radius: 25px;
        font-weight: 600;
    }
</style>
@endpush

@section('containt')
<div class="referral-landing">
    <div class="referral-card">
        <div class="referral-logo">
            🎁
        </div>
        
        <h2>You're Invited!</h2>
        <p class="text-muted">Join {{ config('app.name') }} with referral code <strong>{{ $referralCode }}</strong></p>
        
        @if($sponsor)
        <div class="sponsor-info">
            <h5>👤 Referred by</h5>
            <h4>{{ $sponsor->f_name }} {{ $sponsor->l_name }}</h4>
            <small class="text-muted">{{ $sponsor->email }}</small>
        </div>
        @endif
        
        <h4>🎉 Your Benefits</h4>
        <ul class="benefits-list">
            @forelse($benefits as $benefit)
            <li>
                <i class="fas fa-gift"></i>
                <span>
                    @if($benefit->user_reward_type === 'cashback')
                        Get ₹{{ $benefit->user_reward_value }} cashback after {{ $benefit->order_limit }} order{{ $benefit->order_limit > 1 ? 's' : '' }}
                    @else
                        Get {{ $benefit->user_discount_type === 'percentage' ? $benefit->user_reward_value.'%' : '₹'.$benefit->user_reward_value }} discount 
                        @if($benefit->max_amount) (max ₹{{ $benefit->max_amount }}) @endif
                        after {{ $benefit->order_limit }} order{{ $benefit->order_limit > 1 ? 's' : '' }}
                    @endif
                </span>
            </li>
            @empty
            <li>
                <i class="fas fa-star"></i>
                <span>Join the community and discover amazing food!</span>
            </li>
            @endforelse
        </ul>
        
        <div class="cta-buttons">
            <a href="{{ route('user.auth.login') }}?ref={{ $referralCode }}" class="btn btn-primary btn-lg">
                <i class="fas fa-user-plus"></i> Join Now
            </a>
            <a href="{{ route('userHome') }}" class="btn btn-outline-secondary">
                Browse Without Referral
            </a>
        </div>
        
        <small class="text-muted d-block mt-3">
            By joining, you agree to our Terms & Conditions
        </small>
    </div>
</div>
@endsection
