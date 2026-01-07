@extends('layouts.dashboard-main')

@section('content')
<div class="container-fluid content-inner mt-n5 py-0">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">
                            <i class="tio-settings-outlined"></i>
                            {{ __('Zone Business Settings') }}
                        </h4>
                        <p class="mb-0">{{ __('Configure business settings for each zone separately') }}</p>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cloneSettingsModal">
                            <i class="tio-copy"></i> {{ __('Clone Settings') }}
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{ __('Zone Name') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Custom Settings') }}</th>
                                    <th>{{ __('Last Updated') }}</th>
                                    <th class="text-center">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($zones as $zone)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm avatar-circle">
                                                <span class="avatar-initials bg-soft-primary text-primary">
                                                    {{ strtoupper(substr($zone->name, 0, 1)) }}
                                                </span>
                                            </div>
                                            <div class="ms-3">
                                                <span class="d-block h5 text-hover-primary mb-0">{{ $zone->name }}</span>
                                                <span class="d-block fs-6 text-body">ID: {{ $zone->id }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($zone->status)
                                            <span class="badge bg-success">{{ __('Active') }}</span>
                                        @else
                                            <span class="badge bg-danger">{{ __('Inactive') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($zone->business_settings_count > 0)
                                            <span class="badge bg-info">{{ $zone->business_settings_count }} {{ __('settings') }}</span>
                                        @else
                                            <span class="badge bg-light text-dark">{{ __('Using Global') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($zone->updated_at)
                                            <span class="d-block fs-6">{{ $zone->updated_at->format('M d, Y') }}</span>
                                            <span class="d-block fs-7 text-muted">{{ $zone->updated_at->format('h:i A') }}</span>
                                        @else
                                            <span class="text-muted">{{ __('Never') }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.zone.business-settings.edit', $zone->id) }}" 
                                               class="btn btn-outline-info btn-sm">
                                                <i class="tio-edit"></i> {{ __('Configure') }}
                                            </a>
                                            
                                            @if($zone->business_settings_count > 0)
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle" 
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="tio-more-horizontal"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('admin.zone.business-settings.compare-global', $zone->id) }}">
                                                            <i class="tio-visible"></i> {{ __('Compare with Global') }}
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form method="POST" action="{{ route('admin.zone.business-settings.reset-to-global', $zone->id) }}" 
                                                              onsubmit="return confirm('{{ __('Are you sure you want to reset all settings to global defaults?') }}')">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="tio-restore"></i> {{ __('Reset to Global') }}
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <div class="d-flex flex-column justify-content-center align-items-center">
                                            <img src="{{ asset('assets/img/illustrations/sorry.svg') }}" alt="No zones" class="mb-3" style="width: 7rem;">
                                            <p class="mb-0">{{ __('No zones found') }}</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($zones->hasPages())
                    <div class="card-footer">
                        {{ $zones->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Clone Settings Modal -->
<div class="modal fade" id="cloneSettingsModal" tabindex="-1" aria-labelledby="cloneSettingsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.zone.business-settings.clone-settings') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="cloneSettingsModalLabel">{{ __('Clone Zone Settings') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="source_zone_id" class="form-label">{{ __('Copy Settings From') }}</label>
                        <select class="form-select" id="source_zone_id" name="source_zone_id" required>
                            <option value="">{{ __('Select Source Zone') }}</option>
                            @foreach($zones as $zone)
                                @if($zone->business_settings_count > 0)
                                <option value="{{ $zone->id }}">{{ $zone->name }} ({{ $zone->business_settings_count }} settings)</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">{{ __('Copy Settings To') }}</label>
                        <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                            @foreach($zones as $zone)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="target_zone_ids[]" 
                                       value="{{ $zone->id }}" id="target_zone_{{ $zone->id }}">
                                <label class="form-check-label" for="target_zone_{{ $zone->id }}">
                                    {{ $zone->name }}
                                    @if($zone->business_settings_count > 0)
                                        <small class="text-muted">({{ $zone->business_settings_count }} existing settings)</small>
                                    @endif
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="alert alert-warning">
                        <strong>{{ __('Warning:') }}</strong> {{ __('This will overwrite existing custom settings for the selected zones.') }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Clone Settings') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('javascript')
<script>
    // Prevent selecting the same zone as source and target
    document.getElementById('source_zone_id').addEventListener('change', function() {
        const sourceZoneId = this.value;
        const targetCheckboxes = document.querySelectorAll('input[name="target_zone_ids[]"]');
        
        targetCheckboxes.forEach(checkbox => {
            if (checkbox.value === sourceZoneId) {
                checkbox.disabled = sourceZoneId !== '';
                checkbox.checked = false;
            } else {
                checkbox.disabled = false;
            }
        });
    });
</script>
@endpush