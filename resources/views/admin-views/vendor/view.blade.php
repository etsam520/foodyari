@extends('layouts.dashboard-main')

@push('css')
<style>
    .restaurant-header-card {
        border: none;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        border-radius: 15px;
        overflow: hidden;
    }

    .restaurant-cover {
        height: 200px;
        position: relative;
    }

    .restaurant-cover-bg {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .restaurant-info-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        display: flex;
        align-items: center;
        padding: 2rem;
    }

    .restaurant-logo-container {
        position: relative;
    }

    .restaurant-logo {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 4px solid white;
        object-fit: cover;
        box-shadow: 0 4px 20px rgba(0,0,0,0.2);
    }

    .restaurant-name {
        font-size: 2rem;
        font-weight: 700;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }

    .restaurant-description {
        font-size: 1.1rem;
        opacity: 0.9;
    }

    .rating-circle {
        text-align: center;
    }

    .rating-number {
        display: block;
        font-size: 2.5rem;
        font-weight: 700;
        color: #ffc107;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }

    .rating-stars {
        margin-top: 0.5rem;
    }

    .stats-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }

    .stats-card:hover {
        transform: translateY(-5px);
    }

    .stats-icon {
        opacity: 0.8;
    }

    .info-group label {
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 0.25rem;
    }

    .info-group p {
        font-size: 1.1rem;
        color: var(--bs-body-color, #333);
    }

    .qr-container {
        padding: 1rem;
        background: var(--bs-light, #f8f9fa);
        border-radius: 10px;
        border: 2px dashed var(--bs-border-color, #dee2e6);
    }

    .qr-image {
        max-width: 200px;
        border-radius: 10px;
    }

    .vendor-view-card {
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        border-radius: 10px;
    }

    .vendor-view-card .card-header {
        background: linear-gradient(135deg, var(--bs-light, #f8f9fa) 0%, var(--bs-secondary-bg, #e9ecef) 100%);
        border-bottom: 1px solid var(--bs-border-color, #dee2e6);
        border-radius: 10px 10px 0 0 !important;
    }

    .btn-vendor-action {
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-vendor-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .restaurant-info-overlay {
            padding: 1rem;
        }
        
        .restaurant-logo {
            width: 80px;
            height: 80px;
        }
        
        .restaurant-name {
            font-size: 1.5rem;
        }
        
        .rating-number {
            font-size: 2rem;
        }
        
        .restaurant-cover {
            height: 150px;
        }
    }

    /* Dark mode support */
    @media (prefers-color-scheme: dark) {
        .info-group p {
            color: var(--bs-body-color, #fff);
        }
    }
</style>
@endpush

@section('title', 'Restaurant Details - ' . $restaurant->name)

@section('content')
<div class="container-fluid content-inner mt-n5 py-0">
    <!-- Restaurant Header Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card restaurant-header-card">
                <div class="card-body p-0">
                    <div class="restaurant-cover position-relative">
                        <div class="restaurant-cover-bg"></div>
                        <div class="restaurant-info-overlay">
                            <div class="row align-items-center">
                                <div class="col-md-3 text-center">
                                    <div class="restaurant-logo-container">
                                        <img src="{{ asset('storage/restaurant/' . $restaurant->logo) }}" 
                                             alt="{{ $restaurant->name }}" 
                                             class="restaurant-logo img-fluid">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="restaurant-details text-white">
                                        <h2 class="restaurant-name mb-2">
                                            {{ $restaurant->name }}
                                            @if($restaurant->status == 1)
                                                <span class="badge bg-success ms-2">Active</span>
                                            @else
                                                <span class="badge bg-danger ms-2">Inactive</span>
                                            @endif
                                        </h2>
                                        <p class="restaurant-description mb-3">
                                            <i class="fas fa-map-marker-alt me-2"></i>
                                            @php($address = json_decode($restaurant->address))
                                            {{ $address->street ?? '' }}, {{ $address->city ?? '' }} - {{ $address->pincode ?? '' }}
                                        </p>
                                        <div class="restaurant-badges">
                                            @if($restaurant->badges)
                                                @php($badges = json_decode($restaurant->badges))
                                                @if($badges->b1)
                                                    <span class="badge bg-warning me-2">{{ $badges->b1 }}</span>
                                                @endif
                                                @if($badges->b2)
                                                    <span class="badge bg-info me-2">{{ $badges->b2 }}</span>
                                                @endif
                                                @if($badges->b3)
                                                    <span class="badge bg-primary">{{ $badges->b3 }}</span>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 text-center">
                                    <div class="restaurant-rating">
                                        <div class="rating-circle">
                                            <span class="rating-number">{{ number_format($avgRating, 1) }}</span>
                                            <div class="rating-stars">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= floor($avgRating))
                                                        <i class="fas fa-star text-warning"></i>
                                                    @elseif($i <= ceil($avgRating))
                                                        <i class="fas fa-star-half-alt text-warning"></i>
                                                    @else
                                                        <i class="far fa-star text-warning"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    {{-- <div class="row mb-4">
        <div class="col-md-4">
            <div class="card stats-card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1">{{ $totalOrders }}</h3>
                            <p class="mb-0">Total Orders</p>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-shopping-cart fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stats-card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1">₹{{ number_format($totalRevenue, 2) }}</h3>
                            <p class="mb-0">Total Revenue</p>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-rupee-sign fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stats-card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1">{{ $restaurant->foods->count() }}</h3>
                            <p class="mb-0">Menu Items</p>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-utensils fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Main Content -->
    <div class="row">
        <!-- Restaurant Details -->
        <div class="col-md-8">
            <div class="card vendor-view-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-store me-2"></i>Restaurant Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-group mb-3">
                                <label class="fw-bold text-muted">Restaurant Name</label>
                                <p class="mb-0">{{ $restaurant->name }}</p>
                            </div>
                            <div class="info-group mb-3">
                                <label class="fw-bold text-muted">Phone</label>
                                <p class="mb-0">
                                    <i class="fas fa-phone me-2"></i>{{ $restaurant->phone }}
                                </p>
                            </div>
                            <div class="info-group mb-3">
                                <label class="fw-bold text-muted">Email</label>
                                <p class="mb-0">
                                    <i class="fas fa-envelope me-2"></i>{{ $restaurant->email ?? 'Not provided' }}
                                </p>
                            </div>
                            <div class="info-group mb-3">
                                <label class="fw-bold text-muted">Zone</label>
                                <p class="mb-0">
                                    <i class="fas fa-map me-2"></i>{{ $restaurant->zone->name ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-group mb-3">
                                <label class="fw-bold text-muted">Delivery Radius</label>
                                <p class="mb-0">{{ $restaurant->radius }} km</p>
                            </div>
                            <div class="info-group mb-3">
                                <label class="fw-bold text-muted">Tax</label>
                                <p class="mb-0">{{ $restaurant->tax }}%</p>
                            </div>
                            <div class="info-group mb-3">
                                <label class="fw-bold text-muted">Commission</label>
                                <p class="mb-0">{{ $restaurant->comission ?? 0 }}%</p>
                            </div>
                            <div class="info-group mb-3">
                                <label class="fw-bold text-muted">Minimum Order</label>
                                <p class="mb-0">₹{{ $restaurant->minimum_order ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="info-group mb-3">
                                <label class="fw-bold text-muted">Delivery Time</label>
                                <p class="mb-0">
                                    <i class="fas fa-clock me-2"></i>
                                    {{ $restaurant->min_delivery_time }} - {{ $restaurant->max_delivery_time }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Owner Details -->
            <div class="card vendor-view-card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-tie me-2"></i>Owner Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-group mb-3">
                                <label class="fw-bold text-muted">Owner Name</label>
                                <p class="mb-0">{{ $restaurant->vendor->f_name }} {{ $restaurant->vendor->l_name }}</p>
                            </div>
                            <div class="info-group mb-3">
                                <label class="fw-bold text-muted">Phone</label>
                                <p class="mb-0">
                                    <i class="fas fa-phone me-2"></i>{{ $restaurant->vendor->phone }}
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-group mb-3">
                                <label class="fw-bold text-muted">Email</label>
                                <p class="mb-0">
                                    <i class="fas fa-envelope me-2"></i>{{ $restaurant->vendor->email ?? 'Not provided' }}
                                </p>
                            </div>
                            <div class="info-group mb-3">
                                <label class="fw-bold text-muted">Member Since</label>
                                <p class="mb-0">
                                    <i class="fas fa-calendar me-2"></i>{{ $restaurant->vendor->created_at->format('M d, Y') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- QR Code and Actions -->
        <div class="col-md-4">
            <div class="card vendor-view-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-qrcode me-2"></i>Restaurant QR Code
                    </h5>
                </div>
                <div class="card-body text-center">
                    <div class="qr-container mb-3">
                        <img src="{{ $qrbase64 }}" alt="QR Code for {{ $restaurant->name }}" class="img-fluid qr-image">
                    </div>
                    <p class="text-muted small mb-3">Scan to visit restaurant page</p>
                    {{-- <div class="d-grid gap-2">
                        <button class="btn btn-primary btn-vendor-action" onclick="downloadQR()">
                            <i class="fas fa-download me-2"></i>Download QR
                        </button>
                        <button class="btn btn-outline-primary btn-vendor-action" onclick="printQR()">
                            <i class="fas fa-print me-2"></i>Print QR
                        </button>
                    </div> --}}
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card vendor-view-card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-cogs me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.restaurant.edit', $restaurant->id) }}" class="btn btn-warning btn-vendor-action">
                            <i class="fas fa-edit me-2"></i>Edit Restaurant
                        </a>
                        <a href="{{ route('admin.restaurant.access', $restaurant->id) }}" target="_blank" class="btn btn-success btn-vendor-action">
                            <i class="fas fa-sign-in-alt me-2"></i>Access Dashboard
                        </a>
                        <a href="{{ route('admin.restaurant.list') }}" class="btn btn-secondary btn-vendor-action">
                            <i class="fas fa-arrow-left me-2"></i>Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
function downloadQR() {
    const link = document.createElement('a');
    link.href = '{{ $qrbase64 }}';
    link.download = '{{ $restaurant->name }}_QR_Code.png';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function printQR() {
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
            <head>
                <title>QR Code - {{ $restaurant->name }}</title>
                <style>
                    body { text-align: center; padding: 20px; font-family: Arial, sans-serif; }
                    .qr-print { max-width: 300px; margin: 20px auto; }
                    h2 { color: #333; margin-bottom: 20px; }
                </style>
            </head>
            <body>
                <h2>{{ $restaurant->name }}</h2>
                <div class="qr-print">
                    <img src="{{ $qrbase64 }}" alt="QR Code" style="width: 100%;">
                </div>
                <p>Scan to visit restaurant page</p>
                <script>
                    window.onload = function() {
                        window.print();
                        window.onafterprint = function() {
                            window.close();
                        }
                    }
                </script>
            </body>
        </html>
    `);
    printWindow.document.close();
}
</script>
@endsection
