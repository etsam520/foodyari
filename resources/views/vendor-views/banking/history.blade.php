@extends('vendor-views.layouts.dashboard-main')

@section('content')
    <div class="container-fluid content-inner mt-n5 py-0">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h5 class="page-header-title">
                                {{ __('Banking Details History') }}
                            </h5>
                        </div>
                        <div>
                            <button class="btn btn-primary btn-sm" onclick="refreshHistory()">
                                <i class="feather-refresh"></i> {{ __('Refresh') }}
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="historyTable">
                                <thead>
                                    <tr>
                                        <th>{{ __('Date & Time') }}</th>
                                        <th>{{ __('Action') }}</th>
                                        <th>{{ __('Changed Fields') }}</th>
                                        <th>{{ __('Old Values') }}</th>
                                        <th>{{ __('New Values') }}</th>
                                        <th>{{ __('IP Address') }}</th>
                                        <th>{{ __('Remarks') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- History will be loaded via AJAX -->
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
        loadHistory();
    });

    async function loadHistory() {
        try {
            const response = await fetch('{{ route("vendor.banking.banking-history") }}');
            const histories = await response.json();
            console.log(histories);
            
            const tbody = document.querySelector('#historyTable tbody');
            tbody.innerHTML = '';

            if (histories.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center">No history found</td></tr>';
                return;
            }

            histories.forEach(history => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${new Date(history.created_at).toLocaleString()}</td>
                    <td>
                        <span class="badge bg-${getActionBadgeColor(history.action_type)}">
                            ${history.action_type.toUpperCase()}
                        </span>
                    </td>
                    <td>${history.changed_fields ? history.changed_fields.join(', ') : '-'}</td>
                    <td>
                        ${history.old_data ? formatDataForDisplay(history.old_data) : '-'}
                    </td>
                    <td>
                        ${history.new_data ? formatDataForDisplay(history.new_data) : '-'}
                    </td>
                    <td>${history.ip_address || '-'}</td>
                    <td>${history.remarks || '-'}</td>
                `;
                tbody.appendChild(row);
            });
        } catch (error) {
            console.error('Error loading history:', error);
            toastr.error('Failed to load history');
        }
    }

    function getActionBadgeColor(action) {
        switch (action) {
            case 'created': return 'success';
            case 'updated': return 'warning';
            case 'deleted': return 'danger';
            default: return 'secondary';
        }
    }

    function formatDataForDisplay(data) {
        if (!data || typeof data !== 'object') return '-';
        
        return Object.entries(data)
            .map(([key, value]) => `<strong>${key}:</strong> ${value || 'N/A'}`)
            .join('<br>');
    }

    function refreshHistory() {
        loadHistory();
        toastr.success('History refreshed');
    }
</script>
@endpush