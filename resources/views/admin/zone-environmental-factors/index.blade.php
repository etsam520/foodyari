@extends('layouts.dashboard-main')

@section('title', 'Zone Environmental Factors')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Zone Environmental Factors</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Zone Environmental Factors</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Manage Environmental Factors by Zone</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-info btn-sm" onclick="refreshFactors()">
                                <i class="fa fa-refresh"></i> Refresh Current Factors
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <h5><i class="icon fa fa-info"></i> Environmental Factors Guide</h5>
                            <ul class="mb-0">
                                <li><strong>Rain Factor (0-1):</strong> 0 = No rain, 1 = Heavy rain</li>
                                <li><strong>Traffic Factor (0-1):</strong> 0 = Clear traffic, 1 = Heavy traffic</li>
                                <li><strong>Night Factor (0-1):</strong> 0 = Day time, 1 = Deep night</li>
                                <li><strong>Auto-Detection:</strong> System can automatically detect factors based on time/conditions</li>
                                <li><strong>Manual Override:</strong> Set specific values to override auto-detection</li>
                            </ul>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Zone</th>
                                        <th>Rain Factor</th>
                                        <th>Traffic Factor</th>
                                        <th>Night Factor</th>
                                        <th>Night Hours</th>
                                        <th>Current Values</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($zonesData as $zone)
                                    
                                        <tr>
                                            <td>
                                                <strong>{{ $zone['name'] }}</strong>
                                                <br>
                                                <small class="text-muted">ID: {{ $zone['id'] }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $zone['delivery_setting'] == 'Configured' ? 'success' : 'warning' }}">
                                                    {{ $zone['delivery_setting'] }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $zone['rain_factor'] }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-warning">{{ $zone['traffic_factor'] }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-dark">{{ $zone['night_factor'] }}</span>
                                            </td>
                                            <td>
                                                <small>{{ $zone['night_hours'] }}</small>
                                            </td>
                                            <td>
                                                <code>{{ $zone['current_factors'] }}</code>
                                            </td>
                                            <td>
                                                <a href="{{ $zone['edit_url'] }}" class="btn btn-primary btn-sm">
                                                    <i class="fa fa-edit"></i> Configure
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">
                                                <div class="alert alert-info">
                                                    <i class="fa fa-info-circle"></i> No zones found
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Simple table functionality without DataTables
    console.log('Environmental factors page loaded');
});

function refreshFactors() {
    // Refresh the page to get updated factors
    window.location.reload();
    toastr.info('Environmental factors refreshed');
}
</script>
@endpush