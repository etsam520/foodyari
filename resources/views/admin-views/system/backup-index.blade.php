@extends('layouts.dashboard-main')

@section('content')
<div class="container-fluid content-inner mt-n5 py-0">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="header-title">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-database me-2"></i>
                            {{ __('Database Backup Management') }}
                        </h4>
                    </div>
                    <div>
                        <a href="{{ route('admin.system.backup-download') }}" class="btn btn-primary">
                            <i class="fas fa-download me-2"></i>
                            {{ __('Create New Backup') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Display Success/Error Messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('info'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            {{ session('info') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Information Section -->
                    <div class="alert alert-info mb-4">
                        <h5 class="alert-heading">
                            <i class="fas fa-info-circle me-2"></i>
                            {{ __('Database Backup Information') }}
                        </h5>
                        <hr>
                        <ul class="mb-0">
                            <li>{{ __('Database backups are created in SQL format (.sql extension)') }}</li>
                            <li>{{ __('Backup files are stored in the storage/app/backups directory') }}</li>
                            <li>{{ __('Each backup includes the complete database structure and data') }}</li>
                            <li>{{ __('It is recommended to download and store backups in a secure location') }}</li>
                            <li>{{ __('Old backup files are not automatically deleted - manage them manually') }}</li>
                        </ul>
                    </div>

                    <!-- Database Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-2 text-muted">{{ __('Database Information') }}</h6>
                                    <p class="mb-1"><strong>{{ __('Database Name:') }}</strong> {{ config('database.connections.mysql.database') }}</p>
                                    <p class="mb-1"><strong>{{ __('Host:') }}</strong> {{ config('database.connections.mysql.host') }}</p>
                                    <p class="mb-0"><strong>{{ __('Port:') }}</strong> {{ config('database.connections.mysql.port') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-2 text-muted">{{ __('Backup Statistics') }}</h6>
                                    <p class="mb-1"><strong>{{ __('Total Backups:') }}</strong> {{ count($backups) }}</p>
                                    <p class="mb-0"><strong>{{ __('Storage Location:') }}</strong> storage/app/backups/</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Backup Files Table -->
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>{{ __('#') }}</th>
                                    <th>{{ __('Backup File Name') }}</th>
                                    <th>{{ __('File Size') }}</th>
                                    <th>{{ __('Created Date') }}</th>
                                    <th class="text-center">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($backups as $index => $backup)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <i class="fas fa-file-export text-primary me-2"></i>
                                            {{ $backup['name'] }}
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $backup['size'] }}</span>
                                        </td>
                                        <td>{{ $backup['date'] }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.system.backup-download-file', ['filename' => $backup['name']]) }}" 
                                               class="btn btn-sm btn-success me-2" 
                                               title="{{ __('Download') }}">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-danger" 
                                                    onclick="confirmDelete('{{ $backup['name'] }}')"
                                                    title="{{ __('Delete') }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                            <p class="text-muted mb-0">{{ __('No backup files found. Click "Create New Backup" to generate your first backup.') }}</p>
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

<!-- Delete Confirmation Form -->
<form id="delete-form" method="POST" action="{{ route('admin.system.backup-delete') }}" style="display: none;">
    @csrf
    @method('DELETE')
    <input type="hidden" name="filename" id="delete-filename">
</form>

@endsection

@push('scripts')
<script>
    function confirmDelete(filename) {
        if (confirm('{{ __("Are you sure you want to delete this backup file?") }}\n\n' + filename + '\n\n{{ __("This action cannot be undone!") }}')) {
            document.getElementById('delete-filename').value = filename;
            document.getElementById('delete-form').submit();
        }
    }
</script>
@endpush
