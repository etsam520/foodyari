@extends('layouts.dashboard-main')

@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Zone-wise Delivery Charge Management</h4>
                        <p class="mb-0">Configure delivery charge tiers and factors for each zone</p>
                    </div>
                    <div class="header-action">
                        <a href="{{ route('admin.zone-delivery-charge.environmental-factors') }}" class="btn btn-info me-2">
                            <i class="fas fa-cloud-rain"></i> Environmental Factors
                        </a>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cloneSettingsModal">
                            <i class="fas fa-copy"></i> Clone Settings
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive mt-4">
                        <table id="datatable" class="table table-striped mb-0" role="grid" data-toggle="data-table">
                            <thead>
                                <tr>
                                    <th>Zone Name</th>
                                    <th>Restaurants</th>
                                    <th>Delivery Men</th>
                                    <th>Settings Status</th>
                                    <th>Last Updated</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($zones as $zone)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <h6 class="mb-0">{{ $zone->name }}</h6>
                                                <small class="text-muted">ID: {{ $zone->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $zone->restaurants_count }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">{{ $zone->deliverymen_count }}</span>
                                    </td>
                                    <td>
                                        @if($zone->activeDeliveryChargeSetting)
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle"></i> Configured
                                            </span>
                                        @else
                                            <span class="badge bg-warning">
                                                <i class="fas fa-exclamation-triangle"></i> Default
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($zone->activeDeliveryChargeSetting)
                                            {{ $zone->activeDeliveryChargeSetting->updated_at->format('M d, Y H:i') }}
                                        @else
                                            <span class="text-muted">Never</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.zone-delivery-charge.edit', $zone->id) }}" 
                                               class="btn btn-sm btn-primary" title="Configure Settings">
                                                <i class="fas fa-cog"></i> Configure
                                            </a>
                                            <a href="{{ route('admin.zone-delivery-charge.environmental-factors.edit', $zone->id) }}" 
                                               class="btn btn-sm btn-warning" title="Environmental Factors">
                                                <i class="fas fa-cloud-rain"></i> Factors
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-info test-calculation-btn" 
                                                    data-zone-id="{{ $zone->id }}" 
                                                    data-zone-name="{{ $zone->name }}"
                                                    title="Test Calculation">
                                                <i class="fas fa-calculator"></i> Test
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Clone Settings Modal -->
<div class="modal fade" id="cloneSettingsModal" tabindex="-1" aria-labelledby="cloneSettingsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.zone-delivery-charge.clone') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="cloneSettingsModalLabel">Clone Delivery Charge Settings</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="source_zone_id" class="form-label">Source Zone</label>
                        <select name="source_zone_id" id="source_zone_id" class="form-select" required>
                            <option value="">Select source zone</option>
                            @foreach($zones->where('activeDeliveryChargeSetting') as $zone)
                                <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="target_zone_ids" class="form-label">Target Zones</label>
                        <select name="target_zone_ids[]" id="target_zone_ids" class="form-select" multiple required>
                            @foreach($zones as $zone)
                                <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Hold Ctrl/Cmd to select multiple zones</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Clone Settings</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Test Calculation Modal -->
<div class="modal fade" id="testCalculationModal" tabindex="-1" aria-labelledby="testCalculationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="testCalculationModalLabel">Test Delivery Charge Calculation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="testCalculationForm">
                    <input type="hidden" id="test_zone_id" name="zone_id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="distance" class="form-label">Distance (KM)</label>
                                <input type="number" class="form-control" id="distance" name="distance" step="0.1" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="order_amount" class="form-label">Order Amount (₹)</label>
                                <input type="number" class="form-control" id="order_amount" name="order_amount" step="0.01" min="0" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="rain_factor" class="form-label">Rain Factor (0-1)</label>
                                <input type="number" class="form-control" id="rain_factor" name="rain_factor" step="0.1" min="0" max="1" value="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="traffic_factor" class="form-label">Traffic Factor (0-1)</label>
                                <input type="number" class="form-control" id="traffic_factor" name="traffic_factor" step="0.1" min="0" max="1" value="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="night_factor" class="form-label">Night Factor (0-1)</label>
                                <input type="number" class="form-control" id="night_factor" name="night_factor" step="0.1" min="0" max="1" value="0">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Calculate</button>
                </form>
                
                <div id="calculationResult" class="mt-4" style="display: none;">
                    <h6>Calculation Result:</h6>
                    <div class="alert alert-info">
                        <div id="resultContent"></div>
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
    // Test calculation button click
    $('.test-calculation-btn').click(function() {
        const zoneId = $(this).data('zone-id');
        const zoneName = $(this).data('zone-name');
        
        $('#testCalculationModalLabel').text('Test Delivery Charge - ' + zoneName);
        $('#test_zone_id').val(zoneId);
        $('#testCalculationModal').modal('show');
    });
    
    // Test calculation form submit
    $('#testCalculationForm').submit(function(e) {
        e.preventDefault();
        
        const zoneId = $('#test_zone_id').val();
        const formData = $(this).serialize();
        
        $.ajax({
            url: '{{ url("admin/zone-delivery-charge") }}/' + zoneId + '/test',
            type: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    const result = response.result;
                    let html = '<div class="row">';
                    html += '<div class="col-md-6"><strong>Final Charge:</strong> ₹' + result.charge + '</div>';
                    html += '<div class="col-md-6"><strong>Tier:</strong> ' + result.details.tier + '</div>';
                    html += '</div>';
                    
                    if (result.details.free_delivery) {
                        html += '<div class="mt-2"><span class="badge bg-success">Free Delivery Applied</span></div>';
                    }
                    
                    if (result.details.calculation_breakdown) {
                        html += '<hr><h6>Breakdown:</h6>';
                        html += '<ul>';
                        html += '<li>Base Charge: ₹' + result.details.calculation_breakdown.base_charge + '</li>';
                        html += '<li>After Environmental Factors: ₹' + result.details.calculation_breakdown.after_environmental + '</li>';
                        html += '<li>After Surge: ₹' + result.details.calculation_breakdown.after_surge + '</li>';
                        html += '<li>Final Charge: ₹' + result.details.calculation_breakdown.final_charge + '</li>';
                        html += '</ul>';
                    }
                    
                    $('#resultContent').html(html);
                    $('#calculationResult').show();
                } else {
                    alert('Calculation failed: ' + response.error);
                }
            },
            error: function(xhr) {
                alert('Error: ' + xhr.responseJSON.error);
            }
        });
    });
});
</script>
@endpush