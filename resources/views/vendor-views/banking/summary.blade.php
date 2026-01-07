@extends('vendor-views.layouts.dashboard-main')

@section('content')
    <div class="container-fluid content-inner mt-n5 py-0">
        <div class="row">
            <!-- Summary Cards -->
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="icon iq-icon-box-2 bg-primary-subtle rounded">
                                <i class="feather-credit-card text-primary"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-0">Banking Details</h6>
                                <small class="text-muted" id="banking-status">Not Set</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="icon iq-icon-box-2 bg-success-subtle rounded">
                                <i class="feather-check-circle text-success"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-0">Last Updated</h6>
                                <small class="text-muted" id="last-updated">Never</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="icon iq-icon-box-2 bg-warning-subtle rounded">
                                <i class="feather-edit text-warning"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-0">Total Changes</h6>
                                <small class="text-muted" id="total-changes">0</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="icon iq-icon-box-2 bg-info-subtle rounded">
                                <i class="feather-shield text-info"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-0">Security</h6>
                                <small class="text-success">Secure</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Current Banking Details -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h5 class="page-header-title">{{ __('Current Banking Details') }}</h5>
                        </div>
                        <div>
                            <a href="{{ route('vendor.banking.add-bank-details') }}" class="btn btn-primary btn-sm">
                                <i class="feather-edit"></i> {{ __('Edit Details') }}
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="current-details">
                            <div class="text-center py-5">
                                <i class="feather-credit-card text-muted" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-3">Loading banking details...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <div class="header-title">
                            <h6 class="page-header-title">{{ __('Quick Stats') }}</h6>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <span>{{ __('Account Setup') }}</span>
                            <span class="badge" id="setup-status">Incomplete</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>{{ __('UPI Setup') }}</span>
                            <span class="badge" id="upi-status">Not Set</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>{{ __('Bank Setup') }}</span>
                            <span class="badge" id="bank-status">Not Set</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <span>{{ __('Overall Status') }}</span>
                            <span class="badge" id="overall-status">Pending</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Changes -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h5 class="page-header-title">{{ __('Recent Changes') }}</h5>
                        </div>
                        <div>
                            <a href="{{ route('vendor.banking.banking-history-view') }}" class="btn btn-outline-primary btn-sm">
                                <i class="feather-eye"></i> {{ __('View All History') }}
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="recent-changes-table">
                                <thead>
                                    <tr>
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Action') }}</th>
                                        <th>{{ __('Changes') }}</th>
                                        <th>{{ __('IP Address') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="4" class="text-center">{{ __('Loading recent changes...') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('javascript')
<script>
    $(document).ready(function() {
        loadBankingSummary();
        loadRecentChanges();
    });

    async function loadBankingSummary() {
        try {
            const response = await fetch('{{ route("vendor.banking.get-bank-details") }}');
            const bankingDetails = await response.json();
            
            updateSummaryCards(bankingDetails);
            displayCurrentDetails(bankingDetails);
            updateQuickStats(bankingDetails);
            
        } catch (error) {
            console.error('Error loading banking summary:', error);
            document.getElementById('current-details').innerHTML = `
                <div class="text-center py-5">
                    <i class="feather-alert-circle text-danger" style="font-size: 3rem;"></i>
                    <p class="text-danger mt-3">Failed to load banking details</p>
                </div>
            `;
        }
    }

    async function loadRecentChanges() {
        try {
            const response = await fetch('{{ route("vendor.banking.banking-history") }}');
            const histories = await response.json();
            
            const tbody = document.querySelector('#recent-changes-table tbody');
            tbody.innerHTML = '';

            if (histories.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" class="text-center">No recent changes found</td></tr>';
                return;
            }

            // Show only last 5 changes
            const recentChanges = histories.slice(0, 5);
            
            recentChanges.forEach(history => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${new Date(history.created_at).toLocaleDateString()}</td>
                    <td>
                        <span class="badge bg-${getActionBadgeColor(history.action_type)}">
                            ${history.action_type.toUpperCase()}
                        </span>
                    </td>
                    <td>${history.changed_fields ? history.changed_fields.join(', ') : 'N/A'}</td>
                    <td>${history.ip_address || 'N/A'}</td>
                `;
                tbody.appendChild(row);
            });

            // Update total changes counter
            document.getElementById('total-changes').textContent = histories.length;
            
        } catch (error) {
            console.error('Error loading recent changes:', error);
            document.querySelector('#recent-changes-table tbody').innerHTML = 
                '<tr><td colspan="4" class="text-center text-danger">Failed to load recent changes</td></tr>';
        }
    }

    function updateSummaryCards(bankingDetails) {
        // Banking Status
        const bankingStatus = bankingDetails ? 'Configured' : 'Not Set';
        document.getElementById('banking-status').textContent = bankingStatus;

        // Last Updated
        if (bankingDetails && bankingDetails.updated_at) {
            const lastUpdated = new Date(bankingDetails.updated_at).toLocaleDateString();
            document.getElementById('last-updated').textContent = lastUpdated;
        }
    }

    function displayCurrentDetails(bankingDetails) {
        const container = document.getElementById('current-details');
        
        if (!bankingDetails) {
            container.innerHTML = `
                <div class="text-center py-5">
                    <i class="feather-credit-card text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-3">No banking details found</p>
                    <a href="{{ route('vendor.banking.add-bank-details') }}" class="btn btn-primary">
                        <i class="feather-plus"></i> Add Banking Details
                    </a>
                </div>
            `;
            return;
        }

        let detailsHtml = '<div class="row">';

        // Bank Details Section
        if (bankingDetails.account_number) {
            detailsHtml += `
                <div class="col-md-6">
                    <div class="border rounded p-3 mb-3">
                        <h6 class="text-primary mb-3"><i class="feather-credit-card me-2"></i>Bank Account</h6>
                        <div class="mb-2"><strong>Account Holder:</strong> ${bankingDetails.account_holder_name || 'N/A'}</div>
                        <div class="mb-2"><strong>Bank Name:</strong> ${bankingDetails.bank_name || 'N/A'}</div>
                        <div class="mb-2"><strong>Account Number:</strong> ${'*'.repeat(bankingDetails.account_number.length - 4)}${bankingDetails.account_number.slice(-4)}</div>
                        <div class="mb-2"><strong>IFSC Code:</strong> ${bankingDetails.ifsc_code || 'N/A'}</div>
                    </div>
                </div>
            `;
        }

        // UPI Section
        if (bankingDetails.upi_id) {
            detailsHtml += `
                <div class="col-md-6">
                    <div class="border rounded p-3 mb-3">
                        <h6 class="text-success mb-3"><i class="feather-smartphone me-2"></i>UPI Details</h6>
                        <div class="mb-2"><strong>UPI ID:</strong> ${bankingDetails.upi_id}</div>
                        <button class="btn btn-outline-success btn-sm" onclick="copyUPI('${bankingDetails.upi_id}')">
                            <i class="feather-copy"></i> Copy UPI ID
                        </button>
                    </div>
                </div>
            `;
        }

        detailsHtml += '</div>';

        container.innerHTML = detailsHtml;
    }

    function updateQuickStats(bankingDetails) {
        const hasAccount = bankingDetails && bankingDetails.account_number;
        const hasUPI = bankingDetails && bankingDetails.upi_id;
        
        // Setup Status
        document.getElementById('setup-status').textContent = (hasAccount || hasUPI) ? 'Complete' : 'Incomplete';
        document.getElementById('setup-status').className = `badge bg-${(hasAccount || hasUPI) ? 'success' : 'warning'}`;
        
        // UPI Status
        document.getElementById('upi-status').textContent = hasUPI ? 'Active' : 'Not Set';
        document.getElementById('upi-status').className = `badge bg-${hasUPI ? 'success' : 'secondary'}`;
        
        // Bank Status
        document.getElementById('bank-status').textContent = hasAccount ? 'Active' : 'Not Set';
        document.getElementById('bank-status').className = `badge bg-${hasAccount ? 'success' : 'secondary'}`;
        
        // Overall Status
        const overallComplete = hasAccount || hasUPI;
        document.getElementById('overall-status').textContent = overallComplete ? 'Complete' : 'Pending';
        document.getElementById('overall-status').className = `badge bg-${overallComplete ? 'success' : 'warning'}`;
    }

    function getActionBadgeColor(action) {
        switch (action) {
            case 'created': return 'success';
            case 'updated': return 'warning';
            case 'deleted': return 'danger';
            default: return 'secondary';
        }
    }

    function copyUPI(upiId) {
        navigator.clipboard.writeText(upiId).then(() => {
            toastr.success('UPI ID copied to clipboard!');
        }).catch(() => {
            toastr.error('Failed to copy UPI ID');
        });
    }
</script>
@endpush