@extends('layouts.dashboard-main')

@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Configure Delivery Charge - {{ $zone->name }}</h4>
                        <p class="mb-0">Setup delivery charge tiers and environmental factors for this zone</p>
                    </div>
                    <div class="header-action">
                        <a href="{{ route('admin.zone-delivery-charge.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Display Success/Error Messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle"></i> <strong>Validation Errors:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.zone-delivery-charge.store', $zone->id) }}" method="POST" id="deliveryChargeForm">
                        @csrf
                        
                        <!-- Tier Configuration -->
                        <div class="row">
                            <div class="col-12">
                                <h5 class="mb-3">Delivery Tiers Configuration</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Tier</th>
                                                <th>Max Distance (KM)</th>
                                                <th>Base Charge (₹)</th>
                                                <th>Min Order for Free Delivery (₹)</th>
                                                <th>Per KM Charge (₹)</th>
                                                <th>Start KM</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $tiers = $setting->tiers ?? \App\Models\ZoneDeliveryChargeSetting::getDefaultTiers();
                                            @endphp
                                            @foreach($tiers as $tierName => $tierData)
                                            <tr>
                                                <td>
                                                    <strong>Tier {{ $tierName }}</strong>
                                                    @if($tierName == 'A')
                                                        <br><small class="text-muted">Short Distance</small>
                                                    @elseif($tierName == 'B')
                                                        <br><small class="text-muted">Medium Distance</small>
                                                    @else
                                                        <br><small class="text-muted">Long Distance</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($tierName == 'C')
                                                        <input type="text" class="form-control @error('tiers.'.$tierName.'.max_distance') is-invalid @enderror" value="Unlimited" readonly>
                                                        <input type="hidden" name="tiers[{{ $tierName }}][max_distance]" value="unlimited">
                                                        @error('tiers.'.$tierName.'.max_distance')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    @else
                                                        <input type="number" 
                                                               class="form-control @error('tiers.'.$tierName.'.max_distance') is-invalid @enderror" 
                                                               name="tiers[{{ $tierName }}][max_distance]" 
                                                               value="{{ old('tiers.'.$tierName.'.max_distance', $tierData['max_distance']) }}" 
                                                               min="0" 
                                                               step="0.1" 
                                                               required>
                                                        @error('tiers.'.$tierName.'.max_distance')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    @endif
                                                </td>
                                                <td>
                                                    <input type="number" 
                                                           class="form-control @error('tiers.'.$tierName.'.base') is-invalid @enderror" 
                                                           name="tiers[{{ $tierName }}][base]" 
                                                           value="{{ old('tiers.'.$tierName.'.base', $tierData['base']) }}" 
                                                           min="0" 
                                                           step="0.01" 
                                                           required>
                                                    @error('tiers.'.$tierName.'.base')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input type="number" 
                                                           class="form-control @error('tiers.'.$tierName.'.min_order') is-invalid @enderror" 
                                                           name="tiers[{{ $tierName }}][min_order]" 
                                                           value="{{ old('tiers.'.$tierName.'.min_order', $tierData['min_order']) }}" 
                                                           min="0" 
                                                           step="0.01"
                                                           placeholder="Leave empty for no free delivery">
                                                    @error('tiers.'.$tierName.'.min_order')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input type="number" 
                                                           class="form-control @error('tiers.'.$tierName.'.per_km') is-invalid @enderror" 
                                                           name="tiers[{{ $tierName }}][per_km]" 
                                                           value="{{ old('tiers.'.$tierName.'.per_km', $tierData['per_km']) }}" 
                                                           min="0" 
                                                           step="0.01" 
                                                           required>
                                                    @error('tiers.'.$tierName.'.per_km')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input type="number" 
                                                           class="form-control @error('tiers.'.$tierName.'.start_km') is-invalid @enderror" 
                                                           name="tiers[{{ $tierName }}][start_km]" 
                                                           value="{{ old('tiers.'.$tierName.'.start_km', $tierData['start_km']) }}" 
                                                           min="0" 
                                                           step="0.1" 
                                                           required>
                                                    @error('tiers.'.$tierName.'.start_km')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Environmental Factors -->
                        <div class="row">
                            <div class="col-12">
                                <h5 class="mb-3">Environmental Weight Factors</h5>
                                <p class="text-muted mb-3">These factors adjust delivery charges based on environmental conditions (0-1 scale)</p>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="rain_weight" class="form-label">Rain Weight Factor</label>
                                    <input type="number" 
                                           class="form-control @error('rain_weight') is-invalid @enderror" 
                                           id="rain_weight" 
                                           name="rain_weight" 
                                           value="{{ old('rain_weight', $setting->rain_weight ?? 0.20) }}" 
                                           min="0" 
                                           max="1" 
                                           step="0.01" 
                                           required>
                                    @error('rain_weight')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Typically 0.20 (20% increase in heavy rain)</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="traffic_weight" class="form-label">Traffic Weight Factor</label>
                                    <input type="number" 
                                           class="form-control @error('traffic_weight') is-invalid @enderror" 
                                           id="traffic_weight" 
                                           name="traffic_weight" 
                                           value="{{ old('traffic_weight', $setting->traffic_weight ?? 0.15) }}" 
                                           min="0" 
                                           max="1" 
                                           step="0.01" 
                                           required>
                                    @error('traffic_weight')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Typically 0.15 (15% increase in high traffic)</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="night_weight" class="form-label">Night Weight Factor</label>
                                    <input type="number" 
                                           class="form-control @error('night_weight') is-invalid @enderror" 
                                           id="night_weight" 
                                           name="night_weight" 
                                           value="{{ old('night_weight', $setting->night_weight ?? 0.10) }}" 
                                           min="0" 
                                           max="1" 
                                           step="0.01" 
                                           required>
                                    @error('night_weight')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Typically 0.10 (10% increase at night)</small>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Multipliers and Fees -->
                        <div class="row">
                            <div class="col-12">
                                <h5 class="mb-3">Multipliers and Minimum Fee</h5>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="surge_multiplier" class="form-label">Surge Multiplier</label>
                                    <input type="number" 
                                           class="form-control @error('surge_multiplier') is-invalid @enderror" 
                                           id="surge_multiplier" 
                                           name="surge_multiplier" 
                                           value="{{ old('surge_multiplier', $setting->surge_multiplier ?? 1.0) }}" 
                                           min="0" 
                                           step="0.01" 
                                           required>
                                    @error('surge_multiplier')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">1.0 = normal, >1.0 = surge pricing</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="location_multiplier" class="form-label">Location Multiplier</label>
                                    <input type="number" 
                                           class="form-control @error('location_multiplier') is-invalid @enderror" 
                                           id="location_multiplier" 
                                           name="location_multiplier" 
                                           value="{{ old('location_multiplier', $setting->location_multiplier ?? 1.0) }}" 
                                           min="0" 
                                           step="0.01" 
                                           required>
                                    @error('location_multiplier')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">1.0 = normal, >1.0 = difficult area</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="min_fee" class="form-label">Minimum Fee (₹)</label>
                                    <input type="number" 
                                           class="form-control @error('min_fee') is-invalid @enderror" 
                                           id="min_fee" 
                                           name="min_fee" 
                                           value="{{ old('min_fee', $setting->min_fee ?? 5.0) }}" 
                                           min="0" 
                                           step="0.01" 
                                           required>
                                    @error('min_fee')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Minimum delivery charge if not free</small>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Test Section -->
                        <div class="row">
                            <div class="col-12">
                                <h5 class="mb-3">Test Configuration</h5>
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <input type="number" class="form-control" id="test_distance" placeholder="Distance (KM)" step="0.1" min="0">
                                            </div>
                                            <div class="col-md-3">
                                                <input type="number" class="form-control" id="test_order_amount" placeholder="Order Amount (₹)" step="0.01" min="0">
                                            </div>
                                            <div class="col-md-3">
                                                <button type="button" class="btn btn-info" id="testConfigBtn">Test Current Config</button>
                                            </div>
                                            <div class="col-md-3">
                                                <div id="testResult" class="alert alert-info" style="display: none; padding: 10px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Save Configuration
                                </button>
                                <a href="{{ route('admin.zone-delivery-charge.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('javascript')
<script>
$(document).ready(function() {
    // Test configuration button
    $('#testConfigBtn').click(function() {
        const distance = $('#test_distance').val();
        const orderAmount = $('#test_order_amount').val();
        
        if (!distance || !orderAmount) {
            alert('Please enter both distance and order amount');
            return;
        }

        // Get current form values
        let formData = $('#deliveryChargeForm').serialize();
        formData += '&distance=' + distance + '&order_amount=' + orderAmount;
        
        $.ajax({
            url: '{{ route("admin.zone-delivery-charge.test", $zone->id) }}',
            type: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    const result = response.result;
                    let html = '<strong>Charge: ₹' + result.charge + '</strong><br>';
                    html += 'Tier: ' + result.details.tier;
                    if (result.details.free_delivery) {
                        html += '<br><span class="badge bg-success">Free Delivery</span>';
                    }
                    $('#testResult').html(html).show();
                } else {
                    $('#testResult').html('Error: ' + response.error).removeClass('alert-info').addClass('alert-danger').show();
                }
            },
            error: function(xhr) {
                $('#testResult').html('Error: ' + xhr.responseJSON.error).removeClass('alert-info').addClass('alert-danger').show();
            }
        });
    });

    // Form validation
    $('#deliveryChargeForm').submit(function(e) {
        // Basic validation
        
        // Check if tier A max distance < tier B max distance
        const tierAMax = parseFloat($('input[name="tiers[A][max_distance]"]').val());
        const tierBMax = parseFloat($('input[name="tiers[B][max_distance]"]').val());
        
        if (tierAMax >= tierBMax) {
            alert('Tier A max distance should be less than Tier B max distance');
            valid = false;
        }
        
        if (!valid) {
            e.preventDefault();
        }
    });
});
</script>
@endpush