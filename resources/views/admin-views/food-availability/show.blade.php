@extends('layouts.dashboard-main')

@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="card-title">Manage Availability Times</h4>
                        <div class="d-flex align-items-center mt-2">
                            <img src="{{ asset('product/'.$food->image) }}" 
                                 onerror="this.src='{{ asset('assets/images/icons/food-default-image.png') }}'" 
                                 alt="" style="width:40px;height:40px;border-radius:8px;" class="me-3">
                            <div>
                                <h6 class="mb-0">{{ $food->name }}</h6>
                                <small class="text-muted">{{ $food->restaurant->name }} • {{ $food->price == 0 ? 'Customized' : Helpers::format_currency($food->price) }}</small>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('admin.food-availability.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Default Schedule Info -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card border-secondary">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-info-circle"></i> Default Schedule</h6>
                                </div>
                                <div class="card-body">
                                    @if($food->available_time_starts && $food->available_time_ends)
                                        <p class="mb-0">
                                            <strong>Daily:</strong> {{ $food->available_time_starts }} - {{ $food->available_time_ends }}
                                        </p>
                                        <small class="text-muted">This schedule applies when no specific times are set.</small>
                                    @else
                                        <p class="mb-0"><strong>24/7 Available</strong></p>
                                        <small class="text-muted">No default time restrictions set.</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-primary">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-clock"></i> Current Status</h6>
                                </div>
                                <div class="card-body">
                                    @php
                                        $isAvailable = $food->isAvailableNow();
                                        $currentDay = strtolower(now()->format('l'));
                                        $currentTime = now()->format('H:i');
                                    @endphp
                                    <p class="mb-0">
                                        <strong>Right Now ({{ ucfirst($currentDay) }}, {{ $currentTime }}):</strong>
                                        @if($isAvailable)
                                            <span class="badge bg-success ms-2">Available</span>
                                        @else
                                            <span class="badge bg-danger ms-2">Not Available</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Add New Availability Time -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="fas fa-plus"></i> Add New Availability Time</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.food-availability.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="food_id" value="{{ $food->id }}">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label">Day</label>
                                        <select name="day" class="form-select" required>
                                            <option value="">Select Day</option>
                                            @foreach($daysOfWeek as $value => $label)
                                                <option value="{{ $value }}" {{ old('day') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Start Time</label>
                                        <input type="time" name="start_time" class="form-control" value="{{ old('start_time') }}" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">End Time</label>
                                        <input type="time" name="end_time" class="form-control" value="{{ old('end_time') }}" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">&nbsp;</label>
                                        <button type="submit" class="btn btn-primary d-block w-100">
                                            <i class="fas fa-plus"></i> Add Time
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Existing Availability Times -->
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0"><i class="fas fa-list"></i> Specific Availability Times</h6>
                            @if($food->availabilityTimes->count() > 0)
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-danger dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        Bulk Delete
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <form action="{{ route('admin.food-availability.bulk-delete') }}" method="POST" 
                                                  onsubmit="return confirm('Are you sure you want to delete all availability times?')">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="food_id" value="{{ $food->id }}">
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="fas fa-trash"></i> Delete All Times
                                                </button>
                                            </form>
                                        </li>
                                        @foreach($daysOfWeek as $value => $label)
                                            @if($food->availabilityTimes->where('day', $value)->count() > 0)
                                                <li>
                                                    <form action="{{ route('admin.food-availability.bulk-delete') }}" method="POST" 
                                                          onsubmit="return confirm('Are you sure you want to delete all {{ $label }} times?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="hidden" name="food_id" value="{{ $food->id }}">
                                                        <input type="hidden" name="day" value="{{ $value }}">
                                                        <button type="submit" class="dropdown-item text-warning">
                                                            <i class="fas fa-trash"></i> Delete All {{ $label }}
                                                        </button>
                                                    </form>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                        <div class="card-body">
                            @if($food->availabilityTimes->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Day</th>
                                                <th>Time Slot</th>
                                                <th>Duration</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($food->availabilityTimes as $time)
                                                <tr>
                                                    <td>
                                                        <span class="badge bg-info">{{ ucfirst($time->day) }}</span>
                                                    </td>
                                                    <td>{{ $time->start_time }} - {{ $time->end_time }}</td>
                                                    <td>
                                                        @php
                                                            $start = \Carbon\Carbon::createFromTimeString($time->start_time);
                                                            $end = \Carbon\Carbon::createFromTimeString($time->end_time);
                                                            $duration = $start->diff($end);
                                                        @endphp
                                                        {{ $duration->h }}h {{ $duration->i }}m
                                                    </td>
                                                    <td>
                                                        @php
                                                            $currentDay = strtolower(now()->format('l'));
                                                            $isToday = $time->day === $currentDay;
                                                            $isCurrentlyActive = $isToday && $time->isCurrentlyAvailable();
                                                        @endphp
                                                        @if($isCurrentlyActive)
                                                            <span class="badge bg-success">Active Now</span>
                                                        @elseif($isToday)
                                                            <span class="badge bg-warning">Today</span>
                                                        @else
                                                            <span class="badge bg-light text-dark">Scheduled</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="d-flex gap-2">
                                                            <button type="button" class="btn btn-sm btn-outline-primary" 
                                                                    data-bs-toggle="modal" data-bs-target="#editModal{{ $time->id }}">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <form action="{{ route('admin.food-availability.destroy', $time->id) }}" 
                                                                  method="POST" style="display: inline;"
                                                                  onsubmit="return confirm('Are you sure you want to delete this availability time?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <!-- Edit Modal -->
                                                <div class="modal fade" id="editModal{{ $time->id }}" tabindex="-1">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Edit Availability Time</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <form action="{{ route('admin.food-availability.update', $time->id) }}" method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Day</label>
                                                                        <select name="day" class="form-select" required>
                                                                            @foreach($daysOfWeek as $value => $label)
                                                                                <option value="{{ $value }}" {{ $time->day == $value ? 'selected' : '' }}>{{ $label }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-6">
                                                                            <label class="form-label">Start Time</label>
                                                                            <input type="time" name="start_time" class="form-control" value="{{ $time->start_time }}" required>
                                                                        </div>
                                                                        <div class="col-6">
                                                                            <label class="form-label">End Time</label>
                                                                            <input type="time" name="end_time" class="form-control" value="{{ $time->end_time }}" required>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                    <button type="submit" class="btn btn-primary">Update Time</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-clock fa-3x text-muted mb-3"></i>
                                    <h5>No specific availability times set</h5>
                                    <p class="text-muted">This food item will use the default schedule or be available 24/7.</p>
                                </div>
                            @endif
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
    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
        var alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            var bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});
</script>
@endpush