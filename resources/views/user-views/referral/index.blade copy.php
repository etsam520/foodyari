@extends('user-views.restaurant.layouts.main')

@section('title', 'Referral Program')

@push('css')
<style>
    .referral-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }
    
    .referral-card {
        /* background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); */
        background:#ff810a70;
        color: white;
        border-radius: 15px;
        padding: 30px;
        margin-bottom: 30px;
        text-align: center;
    }
    
    .referral-code {
        background: rgba(255, 255, 255, 0.2);
        padding: 15px 25px;
        border-radius: 10px;
        font-size: 24px;
        font-weight: bold;
        letter-spacing: 2px;
        margin: 20px 0;
        display: inline-block;
        border: 2px dashed rgba(255, 255, 255, 0.5);
    }
    
    .share-buttons {
        margin-top: 20px;
    }
    
    .share-btn {
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
        padding: 10px 20px;
        border-radius: 25px;
        margin: 5px;
        transition: all 0.3s;
    }
    
    .share-btn:hover {
        background: white;
        color: #667eea;
        transform: translateY(-2px);
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .stat-card {
        background: white;
        padding: 25px;
        border-radius: 15px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
    }
    
    .stat-number {
        font-size: 2.5rem;
        font-weight: bold;
        color: #667eea;
        margin-bottom: 10px;
    }
    
    .stat-label {
        color: #666;
        font-size: 14px;
    }
    
    .rewards-section {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
    }
    
    .reward-item {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 15px;
        border-left: 5px solid #667eea;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .reward-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    }
        position: relative;
    }
    
    .reward-item.unlocked {
        border-left-color: #28a745;
        background: #f8fff9;
    }
    
    .reward-item.claimed {
        border-left-color: #6c757d;
        background: #f5f5f5;
        opacity: 0.7;
    }
    
    .reward-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: bold;
    }
    
    .reward-badge.pending {
        background: #ffc107;
        color: #212529;
    }
    
    .reward-badge.unlocked {
        background: #28a745;
        color: white;
    }
    
    .reward-badge.claimed {
        background: #6c757d;
        color: white;
    }
    
    .progress-bar-wrapper {
        margin: 15px 0;
    }
    
    .progress {
        height: 10px;
        border-radius: 5px;
        background: #e9ecef;
        overflow: hidden;
    }
    
    .progress-bar {
        height: 100%;
        background: linear-gradient(90deg, #667eea, #764ba2);
        border-radius: 5px;
        transition: width 0.3s;
    }
    
    .referral-history {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    
    .history-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 0;
        border-bottom: 1px solid #eee;
    }
    
    .history-item:last-child {
        border-bottom: none;
    }
    
    .copy-btn {
        background: #667eea;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 5px 10px;
        cursor: pointer;
        font-size: 12px;
        transition: all 0.3s;
    }
    
    .copy-btn:hover {
        background: #5a6fd8;
        transform: scale(1.05);
    }
    
    .claim-btn {
        background: #28a745;
        color: white;
        border: none;
        border-radius: 20px;
        padding: 8px 15px;
        cursor: pointer;
        font-size: 14px;
        font-weight: bold;
        transition: all 0.3s;
    }
    
    .claim-btn:hover {
        background: #218838;
        transform: scale(1.05);
    }
    
    .claim-btn:disabled {
        background: #6c757d;
        cursor: not-allowed;
        transform: none;
    }
</style>
@endpush

@section('containt')
<div class="referral-container">
    <!-- Main Referral Card -->
    <div class="referral-card">
        <h1>🎉 Refer Friends & Earn Rewards!</h1>
        <p class="mb-4">Share your referral code and both you and your friends get amazing rewards when they place orders!</p>
        
        @if($stats['referral_code'])
            <div>
                <label class="d-block mb-2">Your Referral Code</label>
                <div class="referral-code" id="referralCode">{{ $stats['referral_code'] }}</div>
                <button class="copy-btn" onclick="copyReferralCode()">
                    <i class="fas fa-copy"></i> Copy Code
                </button>
            </div>
            
            <div class="share-buttons">
                <button class="share-btn" onclick="shareWhatsApp()">
                    <i class="fab fa-whatsapp"></i> Share on WhatsApp
                </button>
                <button class="share-btn" onclick="shareTwitter()">
                    <i class="fab fa-twitter"></i> Share on Twitter
                </button>
                <button class="share-btn" onclick="shareFacebook()">
                    <i class="fab fa-facebook"></i> Share on Facebook
                </button>
                <button class="share-btn" onclick="copyShareLink()">
                    <i class="fas fa-link"></i> Copy Link
                </button>
            </div>
        @else
            <button class="btn btn-light btn-lg" onclick="generateReferralCode()">
                <i class="fas fa-plus"></i> Generate My Referral Code
            </button>
        @endif
    </div>

    <!-- Statistics Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">{{ $stats['total_referrals'] }}</div>
            <div class="stat-label">Total Referrals</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $stats['successful_orders'] }}</div>
            <div class="stat-label">Your Orders</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $stats['user_rewards']['unlocked'] + $stats['sponsor_rewards']['unlocked'] }}</div>
            <div class="stat-label">Total Unlocked</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $stats['user_rewards']['claimed'] + $stats['sponsor_rewards']['claimed'] }}</div>
            <div class="stat-label">Total Claimed</div>
        </div>
    </div>

    <!-- User Rewards Stats -->
    <div class="stats-grid">
        <div class="stat-card" style="border-left: 4px solid #28a745;">
            <div class="stat-number">{{ $stats['user_rewards']['total'] }}</div>
            <div class="stat-label">Beneficiary Rewards</div>
        </div>
        <div class="stat-card" style="border-left: 4px solid #17a2b8;">
            <div class="stat-number">{{ $stats['user_rewards']['unlocked'] }}</div>
            <div class="stat-label">Unlocked (User)</div>
        </div>
        <div class="stat-card" style="border-left: 4px solid #ffc107;">
            <div class="stat-number">{{ $stats['sponsor_rewards']['total'] }}</div>
            <div class="stat-label">Sponsor Rewards</div>
        </div>
        <div class="stat-card" style="border-left: 4px solid #dc3545;">
            <div class="stat-number">{{ $stats['sponsor_rewards']['unlocked'] }}</div>
            <div class="stat-label">Unlocked (Sponsor)</div>
        </div>
    </div>

    <!-- User Rewards (As Beneficiary) -->
    <div class="rewards-section">
        <h3 class="mb-4">
            <i class="fas fa-gift"></i> Your Rewards as Beneficiary
            <small class="text-muted">(Rewards you earn when using referral codes)</small>
        </h3>
        
        <div id="user-rewards-container">
            <!-- User rewards will be loaded here -->
        </div>
    </div>

    <!-- Sponsor Rewards (As Sponsor) -->
    <div class="rewards-section">
        <h3 class="mb-4">
            <i class="fas fa-trophy"></i> Your Rewards as Sponsor
            <small class="text-muted">(Rewards you earn when others use your referral code)</small>
        </h3>
        
        <div id="sponsor-rewards-container">
            <!-- Sponsor rewards will be loaded here -->
        </div>
    </div>

    <!-- Referral History -->
    <div class="referral-history">
        <h3 class="mb-4">
            <i class="fas fa-history"></i> Referral History
        </h3>
        
        <div id="history-container">
            <!-- History will be loaded here -->
        </div>
    </div>
</div>

<!-- Hidden data for sharing -->
<div id="shareData" style="display: none;" data-code="{{ $stats['referral_code'] ?? '' }}"></div>
@endsection

@push('javascript')
    
<script>
let shareData = {};

document.addEventListener('DOMContentLoaded', function() {
    loadRewards();
    loadHistory();
    loadShareData();
});

function loadShareData() {
    fetch('{{ route("user.referral.share-info") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                shareData = data.share_data;
            }
        })
        .catch(error => console.error('Error loading share data:', error));
}

function loadRewards() {
    fetch('{{ route("user.referral.rewards") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayUserRewards(data.user_rewards);
                displaySponsorRewards(data.sponsor_rewards);
            }
        })
        .catch(error => console.error('Error loading rewards:', error));
}

function loadHistory() {
    fetch('{{ route("user.referral.history") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayHistory(data);
            }
        })
        .catch(error => console.error('Error loading history:', error));
}

function displayUserRewards(rewards) {
    const container = document.getElementById('user-rewards-container');
    
    if (rewards.length === 0) {
        container.innerHTML = `
            <div class="text-center py-4">
                <i class="fas fa-gift fa-3x text-muted mb-3"></i>
                <p class="text-muted">No rewards available yet. Start referring friends to unlock rewards!</p>
            </div>
        `;
        return;
    }
    
    container.innerHTML = rewards.map(reward => {
        const progress = Math.min((reward.current_orders / reward.order_limit) * 100, 100);
        const statusClass = reward.is_claimed ? 'claimed' : (reward.is_unlocked ? 'unlocked' : 'pending');
        const statusText = reward.is_claimed ? 'Claimed' : (reward.is_unlocked ? 'Available' : 'Pending');
        
        let rewardText = '';
        if (reward.reward_type === 'cashback') {
            rewardText = `₹${reward.reward_value} Cashback`;
        } else {
            rewardText = reward.discount_type === 'percentage' 
                ? `${reward.reward_value}% Discount${reward.max_amount ? ` (Max ₹${reward.max_amount})` : ''}`
                : `₹${reward.reward_value} Discount`;
        }
        
        return `
            <div class="reward-item ${statusClass}">
                <div class="reward-badge ${statusClass}">${statusText}</div>
                <h5>${rewardText}</h5>
                <p class="text-muted mb-2">
                    <i class="fas fa-user text-success"></i> Beneficiary Reward • Complete ${reward.order_limit} orders
                    ${reward.sponsor ? `<br><small>Sponsored by: ${reward.sponsor.full_name || reward.sponsor.name}</small>` : ''}
                </p>
                <div class="progress-bar-wrapper">
                    <div class="d-flex justify-content-between mb-1">
                        <small>Progress: ${reward.current_orders}/${reward.order_limit} orders</small>
                        <small>${Math.round(progress)}%</small>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-success" style="width: ${progress}%"></div>
                    </div>
                </div>
                ${reward.is_unlocked && !reward.is_claimed ? 
                    `<button class="claim-btn mt-2" onclick="claimUserReward(${reward.id})">
                        <i class="fas fa-gift"></i> Claim User Reward
                    </button>` : ''
                }
            </div>
        `;
    }).join('');
}

function displaySponsorRewards(rewards) {
    const container = document.getElementById('sponsor-rewards-container');
    
    if (rewards.length === 0) {
        container.innerHTML = `
            <div class="text-center py-4">
                <i class="fas fa-trophy fa-3x text-muted mb-3"></i>
                <p class="text-muted">No sponsor rewards yet. Share your referral code to start earning!</p>
            </div>
        `;
        return;
    }
    
    container.innerHTML = rewards.map(reward => {
        const progress = Math.min((reward.current_orders / reward.order_limit) * 100, 100);
        const statusClass = reward.is_claimed ? 'claimed' : (reward.is_unlocked ? 'unlocked' : 'pending');
        const statusText = reward.is_claimed ? 'Claimed' : (reward.is_unlocked ? 'Available' : 'Pending');
        
        let rewardText = '';
        if (reward.reward_type === 'cashback') {
            rewardText = `₹${reward.reward_value} Cashback`;
        } else {
            rewardText = reward.discount_type === 'percentage' 
                ? `${reward.reward_value}% Discount${reward.max_amount ? ` (Max ₹${reward.max_amount})` : ''}`
                : `₹${reward.reward_value} Discount`;
        }
        
        return `
            <div class="reward-item ${statusClass}">
                <div class="reward-badge ${statusClass}">${statusText}</div>
                <h5>${rewardText}</h5>
                <p class="text-muted mb-2">
                    <i class="fas fa-crown text-warning"></i> Sponsor Reward • ${reward.order_limit} orders needed
                    ${reward.beneficiary ? `<br><small>From referral: ${reward.beneficiary.full_name || reward.beneficiary.name}</small>` : ''}
                </p>
                <div class="progress-bar-wrapper">
                    <div class="d-flex justify-content-between mb-1">
                        <small>Progress: ${reward.current_orders}/${reward.order_limit} orders</small>
                        <small>${Math.round(progress)}%</small>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-warning" style="width: ${progress}%"></div>
                    </div>
                </div>
                ${reward.is_unlocked && !reward.is_claimed ? 
                    `<button class="claim-btn mt-2" onclick="claimSponsorReward(${reward.id})" style="background: #ffc107; border-color: #ffc107;">
                        <i class="fas fa-crown"></i> Apply Sponsor Reward
                    </button>` : ''
                }
            </div>
        `;
    }).join('');
}

function displayHistory(data) {
    const container = document.getElementById('history-container');
    
    if (!data.referral_history || data.referral_history.length === 0) {
        container.innerHTML = `
            <div class="text-center py-4">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <p class="text-muted">No referrals yet. Share your code to start earning!</p>
            </div>
        `;
        return;
    }
    
    // Group by referral code for better display
    const groupedHistory = {};
    data.referral_history.forEach(item => {
        if (!groupedHistory[item.referral_code]) {
            groupedHistory[item.referral_code] = {
                code: item.referral_code,
                total_uses: item.total_code_uses,
                created_at: item.created_at,
                uses: []
            };
        }
        groupedHistory[item.referral_code].uses.push(item);
    });
    
    container.innerHTML = Object.values(groupedHistory).map(group => {
        const codeDate = new Date(group.created_at).toLocaleDateString();
        
        return `
            <div class="referral-code-group mb-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0">
                        <i class="fas fa-qrcode text-primary"></i> 
                        ${group.code}
                    </h6>
                    <small class="text-muted">
                        ${group.total_uses} uses • Created ${codeDate}
                    </small>
                </div>
                <div class="uses-list">
                    ${group.uses.map(use => {
                        const useDate = new Date(use.used_at).toLocaleDateString();
                        const userName = use.beneficiary.f_name + ' ' + use.beneficiary.l_name;
                        
                        return `
                            <div class="history-item border-start border-success ps-3 mb-2">
                                <div>
                                    <strong>${userName}</strong>
                                    <br>
                                    <small class="text-muted">
                                        <i class="fas fa-user-check text-success"></i> 
                                        Joined on ${useDate}
                                    </small>
                                </div>
                                <div>
                                    <span class="badge bg-success">Active</span>
                                </div>
                            </div>
                        `;
                    }).join('')}
                </div>
            </div>
        `;
    }).join('');
}

function generateReferralCode() {
    fetch('{{ route("user.referral.generate") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Failed to generate referral code');
        }
    })
    .catch(error => {
        console.error('Error generating code:', error);
        alert('Failed to generate referral code');
    });
}

function claimUserReward(rewardId) { 
    fetch('{{ route("user.referral.claim-user") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ reward_id: rewardId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('User reward claimed successfully!');
            loadRewards();
        } else {
            alert(data.message || 'Failed to claim user reward');
        }
    })
    .catch(error => {
        console.error('Error claiming user reward:', error);
        alert('Failed to claim user reward');
    });
}

function claimSponsorReward(rewardId) {
    fetch('{{ route("user.referral.claim-sponsor") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ reward_id: rewardId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Sponsor reward claimed successfully!');
            loadRewards();
        } else {
            alert(data.message || 'Failed to claim sponsor reward');
        }
    })
    .catch(error => {
        console.error('Error claiming sponsor reward:', error);
        alert('Failed to claim sponsor reward');
    });
}

function copyReferralCode() {
    const code = document.getElementById('referralCode').textContent;
    navigator.clipboard.writeText(code).then(() => {
        alert('Referral code copied to clipboard!');
    });
}

function copyShareLink() {
    if (shareData.share_url) {
        navigator.clipboard.writeText(shareData.share_url).then(() => {
            alert('Share link copied to clipboard!');
        });
    }
}

function shareWhatsApp() {
    if (shareData.share_text) {
        const url = `https://wa.me/?text=${encodeURIComponent(shareData.share_text)}`;
        window.open(url, '_blank');
    }
}

function shareTwitter() {
    if (shareData.share_text) {
        const url = `https://twitter.com/intent/tweet?text=${encodeURIComponent(shareData.share_text)}`;
        window.open(url, '_blank');
    }
}

function shareFacebook() {
    if (shareData.share_url) {
        const url = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(shareData.share_url)}`;
        window.open(url, '_blank');
    }
}
</script>
@endpush
