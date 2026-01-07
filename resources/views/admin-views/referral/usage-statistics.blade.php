@extends('layouts.dashboard-main')

@section('title', 'Referral Usage Statistics')

@push('css_or_js')
<style>
    .stats-overview {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 25px;
    }
    
    .stat-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }
    
    .stat-number {
        font-size: 2rem;
        font-weight: bold;
        color: #667eea;
        margin-bottom: 10px;
    }
    
    .stat-label {
        color: #666;
        font-size: 14px;
    }
    
    .referral-code-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 15px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border-left: 4px solid #28a745;
    }
    
    .usage-list {
        max-height: 200px;
        overflow-y: auto;
    }
    
    .usage-item {
        padding: 8px 0;
        border-bottom: 1px solid #eee;
    }
    
    .usage-item:last-child {
        border-bottom: none;
    }
    
    .code-header {
        display: flex;
        justify-content-between;
        align-items: center;
        margin-bottom: 15px;
    }
    
    .code-stats {
        display: flex;
        gap: 15px;
        font-size: 14px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid content-inner mt-n5 py-0">
    <div class="row">
        <div class="col-12">
            <!-- Stats Overview -->
            <div class="stats-overview">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2 class="mb-2">
                            <i class="fas fa-chart-line"></i> Referral Usage Statistics
                        </h2>
                        <p class="mb-0">Monitor referral code usage and user engagement</p>
                    </div>
                    <div class="col-md-4 text-end">
                        <button class="btn btn-light" onclick="refreshData()">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-2">
                    <div class="stat-card">
                        <div class="stat-number" id="total-codes">0</div>
                        <div class="stat-label">Total Codes</div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="stat-card">
                        <div class="stat-number" id="total-uses">0</div>
                        <div class="stat-label">Total Uses</div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="stat-card">
                        <div class="stat-number" id="active-codes">0</div>
                        <div class="stat-label">Active Codes</div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="stat-card">
                        <div class="stat-number" id="used-codes">0</div>
                        <div class="stat-label">Used Codes</div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="stat-card">
                        <div class="stat-number" id="avg-uses">0</div>
                        <div class="stat-label">Avg Uses per Code</div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="stat-card">
                        <div class="stat-number" id="conversion-rate">0%</div>
                        <div class="stat-label">Usage Rate</div>
                    </div>
                </div>
            </div>

            <!-- Referral Codes List -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list"></i> Referral Codes with Usage Details
                    </h5>
                </div>
                <div class="card-body">
                    <div id="referral-codes-container">
                        <div class="text-center py-4">
                            <i class="fas fa-spinner fa-spin fa-2x text-muted"></i>
                            <p class="text-muted mt-2">Loading referral data...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('javascript')
<script>
document.addEventListener('DOMContentLoaded', function() {
    loadReferralData();
});

function refreshData() {
    loadReferralData();
}

function loadReferralData() {
    // Load statistics
    fetch('{{ route("admin.referral.statistics") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateStatistics(data.statistics);
            }
        })
        .catch(error => console.error('Error loading statistics:', error));
    
    // Load referral codes with usage
    fetch('{{ route("admin.referral.usage-details") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayReferralCodes(data.referral_codes);
            }
        })
        .catch(error => {
            console.error('Error loading referral codes:', error);
            document.getElementById('referral-codes-container').innerHTML = `
                <div class="text-center py-4">
                    <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
                    <p class="text-muted mt-2">Error loading referral data</p>
                </div>
            `;
        });
}

function updateStatistics(stats) {
    document.getElementById('total-codes').textContent = stats.total_referral_codes || 0;
    document.getElementById('total-uses').textContent = stats.total_referral_uses || 0;
    document.getElementById('active-codes').textContent = stats.active_referral_codes || 0;
    document.getElementById('used-codes').textContent = stats.used_referral_codes || 0;
    
    const avgUses = stats.total_referral_codes > 0 ? 
        (stats.total_referral_uses / stats.total_referral_codes).toFixed(1) : 0;
    document.getElementById('avg-uses').textContent = avgUses;
    
    const conversionRate = stats.total_referral_codes > 0 ? 
        ((stats.used_referral_codes / stats.total_referral_codes) * 100).toFixed(1) : 0;
    document.getElementById('conversion-rate').textContent = conversionRate + '%';
}

function displayReferralCodes(codes) {
    const container = document.getElementById('referral-codes-container');
    
    if (!codes || codes.length === 0) {
        container.innerHTML = `
            <div class="text-center py-4">
                <i class="fas fa-inbox fa-2x text-muted"></i>
                <p class="text-muted mt-2">No referral codes found</p>
            </div>
        `;
        return;
    }
    
    container.innerHTML = codes.map(code => {
        const createdDate = new Date(code.created_at).toLocaleDateString();
        const lastUsedDate = code.last_used_at ? 
            new Date(code.last_used_at).toLocaleDateString() : 'Never';
        
        return `
            <div class="referral-code-card">
                <div class="code-header">
                    <div>
                        <h6 class="mb-1">
                            <i class="fas fa-qrcode text-primary"></i> 
                            <strong>${code.referral_code}</strong>
                        </h6>
                        <small class="text-muted">
                            by ${code.sponsor.f_name} ${code.sponsor.l_name} • Created: ${createdDate}
                        </small>
                    </div>
                    <div class="code-stats">
                        <span class="badge bg-primary">${code.total_uses} Uses</span>
                        <span class="badge bg-${code.is_active ? 'success' : 'secondary'}">
                            ${code.is_active ? 'Active' : 'Inactive'}
                        </span>
                    </div>
                </div>
                
                ${code.uses && code.uses.length > 0 ? `
                    <div class="usage-list">
                        <h6 class="text-muted mb-2">Recent Usage:</h6>
                        ${code.uses.slice(0, 5).map(use => {
                            const useDate = new Date(use.used_at).toLocaleDateString();
                            return `
                                <div class="usage-item">
                                    <div class="d-flex justify-content-between">
                                        <span>
                                            <i class="fas fa-user text-success"></i>
                                            ${use.beneficiary.f_name} ${use.beneficiary.l_name}
                                        </span>
                                        <small class="text-muted">${useDate}</small>
                                    </div>
                                </div>
                            `;
                        }).join('')}
                        ${code.uses.length > 5 ? `
                            <div class="text-center mt-2">
                                <small class="text-muted">
                                    ... and ${code.uses.length - 5} more uses
                                </small>
                            </div>
                        ` : ''}
                    </div>
                ` : `
                    <div class="text-center py-2">
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i> 
                            No uses yet
                        </small>
                    </div>
                `}
            </div>
        `;
    }).join('');
}
</script>
@endpush
