@extends('layouts.dashboard-main')

@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="header-title">
                        <h4 class="card-title">Food Availability Management</h4>
                        <p class="text-muted mb-0">Manage specific availability times for food items</p>
                    </div>
                    <div class="d-flex gap-2">
                        <form method="GET" action="{{ route('admin.food-availability.index') }}" class="d-flex">
                            <input type="text" name="search" class="form-control" placeholder="Search foods..." 
                                   value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary ms-2">Search</button>
                        </form>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive mt-4">
                        <table class="table mb-0" role="grid">
                            <thead>
                                <tr>
                                    <th>Food Item</th>
                                    <th>Restaurant</th>
                                    <th>Default Schedule</th>
                                    <th>Specific Times</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($foods as $food)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('product/'.$food->image) }}" 
                                                 onerror="this.src='{{ asset('assets/images/icons/food-default-image.png') }}'" 
                                                 alt="" style="width:50px;height:50px;border-radius:8px;" class="me-3">
                                            <div>
                                                <h6 class="mb-0">{{ $food->name }}</h6>
                                                <small class="text-muted">{{ $food->price == 0 ? 'Customized' : '$'.$food->price }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $food->restaurant->name }}</td>
                                    <td>
                                        @if($food->available_time_starts && $food->available_time_ends)
                                            <span class="badge bg-secondary">
                                                {{ $food->available_time_starts }} - {{ $food->available_time_ends }}
                                            </span>
                                        @else
                                            <span class="badge bg-success">24/7 Available</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($food->availabilityTimes && $food->availabilityTimes->count() > 0)
                                            <span class="badge bg-primary">
                                                {{ $food->availabilityTimes->count() }} time slot(s)
                                            </span>
                                        @else
                                            <span class="badge bg-light text-dark">None set</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $isAvailable = $food->isAvailableNow();
                                        @endphp
                                        @if($isAvailable)
                                            <span class="badge bg-success">Available Now</span>
                                        @else
                                            <span class="badge bg-danger">Not Available</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <a href="{{ route('admin.food-availability.show', $food->id) }}" 
                                               class="btn btn-sm btn-outline-primary" 
                                               data-bs-toggle="tooltip" title="Manage Availability Times">
                                                <i class="fas fa-clock"></i> Manage Times
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="fas fa-utensils fa-3x text-muted mb-3"></i>
                                            <h5>No food items found</h5>
                                            <p class="text-muted">
                                                @if(request('search'))
                                                    No food items match your search criteria.
                                                @else
                                                    Add some food items first to manage their availability times.
                                                @endif
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($foods->hasPages())
                    <div class="card-footer">
                        {{ $foods->withQueryString()->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('javascript')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush