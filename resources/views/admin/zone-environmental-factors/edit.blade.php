@extends('layouts.dashboard-main')

@section('title', 'Configure Environmental Factors - ' . $zone->name)

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Configure Environmental Factors</h1>
                <p class="text-muted">Zone: {{ $zone->name }}</p>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.zone-delivery-charge.environmental-factors') }}">Environmental Factors</a></li>
                    <li class="breadcrumb-item active">{{ $zone->name }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- Configuration Form -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Environmental Factors Configuration</h3>
                    </div>
                    <form id="environmental-factors-form">
                        @csrf
                        <div class="card-body">
                            <!-- Rain Factor -->
                            <div class="form-group">
                                <label class="form-label d-flex justify-content-between">
                                    <span>Rain Factor (0-1)</span>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="auto_detect_rain" 
                                               name="auto_detect_rain" {{ $setting->auto_detect_rain ? 'checked' : '' }}
                                               value="{{ $setting->auto_detect_rain ? '1' : '0' }}">
                                        <label class="form-check-label" for="auto_detect_rain">Auto-Detect</label>
                                    </div>
                                </label>
                                <div class="rain-factor-controls">
                                    <input type="number" class="form-control" id="rain_factor" name="rain_factor" 
                                           min="0" max="1" step="0.01" value="{{ $setting->rain_factor }}"
                                           placeholder="Leave empty for auto-detection">
                                    <small class="form-text text-muted">0 = No rain, 1 = Heavy rain</small>
                                </div>
                            </div>

                            <!-- Traffic Factor -->
                            <div class="form-group">
                                <label class="form-label d-flex justify-content-between">
                                    <span>Traffic Factor (0-1)</span>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="auto_detect_traffic" 
                                               name="auto_detect_traffic" {{ $setting->auto_detect_traffic ? 'checked' : '' }}
                                               value="{{ $setting->auto_detect_traffic ? '1' : '0' }}">
                                        <label class="form-check-label" for="auto_detect_traffic">Auto-Detect</label>
                                    </div>
                                </label>
                                <div class="traffic-factor-controls">
                                    <input type="number" class="form-control" id="traffic_factor" name="traffic_factor" 
                                           min="0" max="1" step="0.01" value="{{ $setting->traffic_factor }}"
                                           placeholder="Leave empty for auto-detection">
                                    <small class="form-text text-muted">0 = Clear traffic, 1 = Heavy traffic. Auto-detection based on rush hours (7-10 AM, 5-8 PM)</small>
                                </div>
                            </div>

                            <!-- Night Factor -->
                            <div class="form-group">
                                <label class="form-label d-flex justify-content-between">
                                    <span>Night Factor (0-1)</span>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="auto_detect_night" 
                                               name="auto_detect_night" {{ $setting->auto_detect_night ? 'checked' : '' }} 
                                               value="{{ $setting->auto_detect_night ? '1' : '0' }}">
                                        <label class="form-check-label" for="auto_detect_night">Auto-Detect</label>
                                    </div>
                                </label>
                                <div class="night-factor-controls">
                                    <input type="number" class="form-control" id="night_factor" name="night_factor" 
                                           min="0" max="1" step="0.01" value="{{ $setting->night_factor }}"
                                           placeholder="Leave empty for auto-detection">
                                    <small class="form-text text-muted">0 = Day time, 1 = Deep night</small>
                                </div>
                            </div>

                            <!-- Night Hours Configuration -->
                            <div class="form-group night-hours-config">
                                <label class="form-label">Night Hours (for Auto-Detection)</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="night_start_time">Night Starts</label>
                                        <input type="time" class="form-control" id="night_start_time" name="night_start_time" 
                                               value="{{ $setting->night_start_time ? $setting->night_start_time->format('H:i') : '20:00' }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="night_end_time">Night Ends</label>
                                        <input type="time" class="form-control" id="night_end_time" name="night_end_time" 
                                               value="{{ $setting->night_end_time ? $setting->night_end_time->format('H:i') : '06:00' }}" required>
                                    </div>
                                </div>
                                <small class="form-text text-muted">Night factor will be calculated based on these hours when auto-detection is enabled</small>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Save Configuration
                            </button>
                            <button type="button" class="btn btn-info" onclick="previewFactors()">
                                <i class="fa fa-eye"></i> Preview Current Factors
                            </button>
                            <a href="{{ route('admin.zone-delivery-charge.environmental-factors') }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Live Preview -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Live Preview</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-sm btn-outline-info" onclick="refreshPreview()">
                                <i class="fa fa-refresh"></i> Refresh
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="current-factors-display">
                            <div class="text-center">
                                <i class="fa fa-spinner fa-spin"></i> Loading...
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Factors Explanation -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h3 class="card-title">Factor Impact</h3>
                    </div>
                    <div class="card-body">
                        <small class="text-muted">
                            <p><strong>How factors affect delivery charges:</strong></p>
                            <ul class="list-unstyled">
                                <li>🌧️ <strong>Rain:</strong> Higher values increase charges during bad weather</li>
                                <li>🚗 <strong>Traffic:</strong> Higher values increase charges during busy periods</li>
                                <li>🌙 <strong>Night:</strong> Higher values increase charges during night hours</li>
                            </ul>
                            <p><strong>Current Time:</strong> <span id="current-time"></span></p>
                        </small>
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
    // Load initial preview
    refreshPreview();
    
    // Update current time
    updateCurrentTime();
    setInterval(updateCurrentTime, 1000);
    
    // Form submission
    $('#environmental-factors-form').on('submit', function(e) {
        e.preventDefault();
        saveConfiguration();
    });
    
    // Auto-detect toggles
    $('.form-check-input[name*="auto_detect"]').on('change', function() {
        updateControlsState();
        refreshPreview();
    });
    
    // Input changes
    $('input[name*="_factor"], input[name*="_time"]').on('input', function() {
        refreshPreview();
    });
    
    // Initial state
    updateControlsState();
});

function updateControlsState() {
    // Rain factor controls
    const autoRain = $('#auto_detect_rain').is(':checked');
    $('#rain_factor').prop('disabled', autoRain);
    if (autoRain) $('#rain_factor').val('');
    
    // Traffic factor controls
    const autoTraffic = $('#auto_detect_traffic').is(':checked');
    $('#traffic_factor').prop('disabled', autoTraffic);
    if (autoTraffic) $('#traffic_factor').val('');
    
    // Night factor controls
    const autoNight = $('#auto_detect_night').is(':checked');
    $('#night_factor').prop('disabled', autoNight);
    if (autoNight) $('#night_factor').val('');
    
    // Night hours configuration
    $('.night-hours-config').toggle(autoNight);
}

function saveConfiguration() {
    const formData = new FormData(document.getElementById('environmental-factors-form'));
    
    // Show loading state
    const submitBtn = $('button[type="submit"]');
    const originalText = submitBtn.html();
    submitBtn.html('<i class="fa fa-spinner fa-spin"></i> Saving...').prop('disabled', true);
    
    $.ajax({
        url: "{{ route('admin.zone-delivery-charge.environmental-factors.update', $zone->id) }}",
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                toastr.success(response.message);
                refreshPreview();
            } else {
                toastr.error(response.message);
            }
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                Object.keys(errors).forEach(field => {
                    toastr.error(errors[field][0]);
                });
            } else {
                toastr.error('An error occurred while saving');
            }
        },
        complete: function() {
            submitBtn.html(originalText).prop('disabled', false);
        }
    });
}

function refreshPreview() {
    $.ajax({
        url: "{{ route('admin.zone-delivery-charge.current-factors', $zone->id) }}",
        method: 'GET',
        success: function(response) {
            if (response.success) {
                displayCurrentFactors(response.factors);
            }
        },
        error: function() {
            $('#current-factors-display').html('<div class="text-danger">Error loading factors</div>');
        }
    });
}

function displayCurrentFactors(factors) {
    const html = `
        <div class="factors-display">
            <div class="factor-item mb-3">
                <div class="d-flex justify-content-between">
                    <span>🌧️ Rain Factor</span>
                    <strong>${factors.rain.toFixed(2)}</strong>
                </div>
                <div class="progress progress-sm">
                    <div class="progress-bar bg-info" style="width: ${factors.rain * 100}%"></div>
                </div>
            </div>
            
            <div class="factor-item mb-3">
                <div class="d-flex justify-content-between">
                    <span>🚗 Traffic Factor</span>
                    <strong>${factors.traffic.toFixed(2)}</strong>
                </div>
                <div class="progress progress-sm">
                    <div class="progress-bar bg-warning" style="width: ${factors.traffic * 100}%"></div>
                </div>
            </div>
            
            <div class="factor-item mb-3">
                <div class="d-flex justify-content-between">
                    <span>🌙 Night Factor</span>
                    <strong>${factors.night.toFixed(2)}</strong>
                </div>
                <div class="progress progress-sm">
                    <div class="progress-bar bg-dark" style="width: ${factors.night * 100}%"></div>
                </div>
            </div>
            
            <div class="total-impact">
                <div class="d-flex justify-content-between">
                    <span><strong>Combined Impact</strong></span>
                    <strong class="text-primary">${((factors.rain + factors.traffic + factors.night) / 3).toFixed(2)}</strong>
                </div>
            </div>
        </div>
    `;
    
    $('#current-factors-display').html(html);
}

function previewFactors() {
    refreshPreview();
    toastr.info('Factors preview updated');
}

function updateCurrentTime() {
    const now = new Date();
    const timeString = now.toLocaleTimeString();
    $('#current-time').text(timeString);
}
</script>
@endpush