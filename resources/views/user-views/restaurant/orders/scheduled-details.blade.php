@extends('user-views.restaurant.layouts.main')

@php
    $customerOrderData = json_decode($order->orderCalculationStmt?->customerData);
@endphp

@section('containt')
<div class="d-lg-none d-block">
    <div class="bg-primary p-3 d-flex align-items-center">
        <a class="text-white fw-bolder fs-4 me-3" href="{{ route('user.restaurant.scheduled-orders') }}">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h4 class="fw-bold m-0 text-white">Scheduled Order #{{ $order->id }}</h4>
    </div>
</div>

<div class="container position-relative">
    <div class="row mt-3 justify-content-center mx-1">
        <div class="col-lg-8 col-12 p-0">

            <!-- Order Status Card -->
            <div class="bg-white rounded shadow-sm p-3 mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-1">Order #{{ $order->id }}</h5>
                        <span class="badge bg-warning text-dark">
                            <i class="feather-clock me-1"></i>Scheduled
                        </span>
                    </div>
                    <div class="text-end">
                        <p class="fw-bold mb-0 fs-5 text-primary">
                            {{ Helpers::format_currency($order->order_amount) }}
                        </p>
                        <p class="small text-muted mb-0">Total Amount</p>
                    </div>
                </div>

                <div class="mt-3 pt-3 border-top d-flex justify-content-between align-items-center">
                    @if(Carbon\Carbon::parse($order->schedule_at)->diffInHours(now()) >= 1)
                        <button class="btn btn-outline-danger" onclick="cancelScheduledOrder({{ $order->id }})">
                            <i class="feather-x me-1"></i>Cancel This Order
                        </button>
                    @else
                        <span></span>
                    @endif

                    <div class="d-flex gap-2">
                        @if ($order->share_token != null)
                            <button class="btn btn-outline-primary btn-sm" onclick="shareLink('{!! route('user.restaurant.share-order') . "?share_token=$order->share_token" !!}')">
                                <i class="feather-share-2 me-1"></i>Share
                            </button>
                        @endif
                        <a href="{{ route('user.restaurant.order-invoice', ['order_id' => $order->id]) }}" class="btn btn-primary btn-sm">
                            <i class="feather-printer me-1"></i>Invoice
                        </a>
                    </div>
                </div>
            </div>

            <!-- Scheduled Time Card -->
            <div class="bg-white rounded shadow-sm p-3 mb-3">
                <h6 class="fw-bold mb-3">
                    <i class="feather-calendar text-primary me-2"></i>
                    Scheduled Delivery Time
                </h6>
                <div class="row">
                    <div class="col-6">
                        <div class="text-center p-3 bg-light rounded">
                            <i class="feather-calendar fs-3 text-success mb-2"></i>
                            <p class="fw-bold mb-0">{{ Carbon\Carbon::parse($order->schedule_at)->format('d M Y') }}</p>
                            <p class="small text-muted mb-0">Date</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 bg-light rounded">
                            <i class="feather-clock fs-3 text-success mb-2"></i>
                            <p class="fw-bold mb-0">{{ Carbon\Carbon::parse($order->schedule_at)->format('h:i A') }}</p>
                            <p class="small text-muted mb-0">Time</p>
                        </div>
                    </div>
                </div>
                <div class="mt-3 text-center">
                    @php($timeRemaining = Carbon\Carbon::parse($order->schedule_at)->diffInHours(now()))
                    @if($timeRemaining > 24)
                        <p class="text-info mb-0">
                            <i class="feather-info me-1"></i>
                            {{ Carbon\Carbon::parse($order->schedule_at)->diffForHumans() }}
                        </p>
                    @elseif($timeRemaining > 1)
                        <p class="text-warning mb-0">
                            <i class="feather-clock me-1"></i>
                            Starting {{ Carbon\Carbon::parse($order->schedule_at)->diffForHumans() }}
                        </p>
                    @else
                        <p class="text-danger mb-0">
                            <i class="feather-alert-triangle me-1"></i>
                            Starting very soon!
                        </p>
                    @endif
                </div>
            </div>

            <!-- Restaurant Information -->
            @php($restaurant = $order->restaurant)
            <div class="bg-white rounded shadow-sm p-3 mb-3">
                <h6 class="fw-bold mb-3">
                    <i class="feather-home text-primary me-2"></i>
                    Restaurant Details
                </h6>
                <div class="d-flex align-items-center">
                    <img src="{{ Helpers::getUploadFile($restaurant->logo, 'restaurant') }}"
                         alt="{{ $restaurant->name }}"
                         class="img-fluid rounded me-3"
                         style="width: 60px; height: 60px; object-fit: cover;">
                    <div>
                        <h6 class="fw-bold mb-1">{{ $restaurant->name }}</h6>
                        <p class="text-muted mb-0">
                            <i class="feather-map-pin me-1"></i>
                            <?php
                                $fullAddress = null;

                                $addr = json_decode($restaurant->address ?? '{}', true);

                                $street = $addr['street'] ?? null;
                                $city = $addr['city'] ?? null;
                                $pincode = $addr['pincode'] ?? null;

                                // Build final string
                                $fullAddress = trim(
                                    ($street ? $street . ' ' : '') .
                                    ($city ? $city . ' ' : '') .
                                    ($pincode ? '- ' . $pincode : '')
                                );
                            ?>
                            {{ $fullAddress ?: 'Address not available' }}
                        </p>
                        @if($restaurant->phone)
                            <p class="text-muted mb-0">
                                <i class="feather-phone me-1"></i>
                                {{ $restaurant->phone }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-white rounded shadow-sm p-3 mb-3">
                <h6 class="fw-bold mb-3">
                    <i class="feather-shopping-bag text-primary me-2"></i>
                    Order Items
                </h6>
                <div class="border-top pt-2">
                    <div class="row fw-bold border-bottom pb-2">
                        <div class="col-2">
                            <p class="mb-0">SI.</p>
                        </div>
                        <div class="col-4">
                            <p class="mb-0">Name</p>
                        </div>
                        <div class="col-2">
                            <p class="mb-0">Quantity</p>
                        </div>
                        <div class="col-4">
                            <p class="mb-0">Amount</p>
                        </div>
                    </div>
                    @if ($customerOrderData?->foodItemList != null)
                        @foreach ($customerOrderData->foodItemList as $key => $listItem)
                            <div class="row py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                                <div class="col-2">
                                    <p class="mb-0">{{ $key + 1 }}</p>
                                </div>
                                <div class="col-4">
                                    <p class="fw-bold mb-0">{{ Str::ucfirst($listItem->foodName) }}</p>
                                    @if(isset($listItem->variationName) && $listItem->variationName)
                                        <p class="small text-info mb-0">{{ $listItem->variationName }}</p>
                                    @endif
                                </div>
                                <div class="col-2">
                                    <p class="mb-0">{{ $listItem->quantity }}</p>
                                </div>
                                <div class="col-4">
                                    <p class="fw-bold mb-0">{{ Helpers::format_currency($listItem->foodPrice) }}</p>
                                </div>
                            </div>
                        @endforeach
                    @elseif($order->details->count() > 0)
                        @foreach($order->details as $detail)
                            <div class="row py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                                <div class="col-2">
                                    <p class="mb-0">{{ $loop->iteration }}</p>
                                </div>
                                <div class="col-4">
                                    <p class="fw-bold mb-0">{{ $detail->food->name ?? 'Unknown Item' }}</p>
                                    @if($detail->variation)
                                        <p class="small text-info mb-0">{{ json_decode($detail->variation, true)['type'] ?? '' }}</p>
                                    @endif
                                </div>
                                <div class="col-2">
                                    <p class="mb-0">{{ $detail->quantity }}</p>
                                </div>
                                <div class="col-4">
                                    <p class="fw-bold mb-0">{{ Helpers::format_currency($detail->price * $detail->quantity) }}</p>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Delivery Address -->
            @if($order->delivery_address)
                @php($deliveryAddress = json_decode($order->delivery_address, true))
                <div class="bg-white rounded shadow-sm p-3 mb-3">
                    <h6 class="fw-bold mb-3">
                        <i class="feather-map-pin text-primary me-2"></i>
                        Delivery Address
                    </h6>
                    <p class="mb-0">
                        <i class="feather-navigation me-2"></i>
                        {{ $deliveryAddress['stringAddress'] ?? 'Address not available' }}
                    </p>
                </div>
            @endif

            <!-- Order Instructions -->
            @if($order->cooking_instruction || $order->delivery_instruction !== '[]')
                <div class="bg-white rounded shadow-sm p-3 mb-3">
                    <h6 class="fw-bold mb-3">
                        <i class="feather-message-square text-primary me-2"></i>
                        Special Instructions
                    </h6>

                    @if($order->cooking_instruction)
                        <div class="mb-2">
                            <p class="fw-bold mb-1 text-success">
                                <i class="feather-chef-hat me-1"></i>Cooking Instructions:
                            </p>
                            <p class="mb-0 text-muted">{{ $order->cooking_instruction }}</p>
                        </div>
                    @endif

                    @if($order->delivery_instruction !== '[]')
                        @php($deliveryInstructions = json_decode($order->delivery_instruction, true))
                        @if(!empty($deliveryInstructions))
                            <div class="mb-0">
                                <p class="fw-bold mb-1 text-info">
                                    <i class="feather-truck me-1"></i>Delivery Instructions:
                                </p>
                                <p class="mb-0 text-muted">{{ implode(', ', $deliveryInstructions) }}</p>
                            </div>
                        @endif
                    @endif
                </div>
            @endif

            <!-- Payment Information -->
            <div class="bg-white rounded shadow-sm p-3 mb-3">
                <h6 class="fw-bold mb-3">
                    <i class="feather-credit-card text-primary me-2"></i>
                    Payment Details
                    @if ($order->payment_status == 'paid')
                        <span class="badge bg-success">{{ Str::ucfirst($order->payment_status) }}</span>
                    @elseif ($order->payment_status == 'unpaid')
                        <span class="badge bg-primary">{{ Str::ucfirst($order->payment_status) }}</span>
                    @endif
                </h6>

                <div class="mb-3">
                    <p class="small text-muted mb-1">Payment Method</p>
                    <p class="fw-bold mb-0 text-capitalize">
                        <i class="feather-dollar-sign me-1"></i>
                        {{ str_replace(['&', '_'], [' & ', ' '], $order->payment_method) }}
                    </p>
                </div>

                @if($customerOrderData)
                    <div class="border-top pt-3">
                        <p class="mb-1">Sub Total <span class="float-end text-dark">{{ Helpers::format_currency($customerOrderData?->sumOfFoodPriceBeforDiscount) }}</span></p>

                        @if ($customerOrderData?->sumOfDiscount)
                            <p class="mb-1 text-success">Discount <span class="float-end text-success">- {{ Helpers::format_currency($customerOrderData?->sumOfDiscount) }}</span></p>
                        @endif

                        @if ($customerOrderData?->couponDiscountAmount > 0)
                            <p class="mb-1 text-success">Coupon Discount <span class="float-end text-success">- {{ Helpers::format_currency($customerOrderData?->couponDiscountAmount) }}</span></p>
                        @endif

                        <div class="mb-1">Platform Fee
                            <button type="button" class="btn text-info ms-1 p-0" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="Platform Fee Explanation">
                                <i class="feather-info"></i>
                            </button>
                            <div class="float-end">
                                @if($customerOrderData?->zone->platform_charge != $customerOrderData?->platformCharge)
                                    <span class="text-danger"><strike class="me-2">{{ Helpers::format_currency($customerOrderData?->zone->platform_charge) }}</strike></span>
                                @endif
                                <span class="text-dark">{{ Helpers::format_currency($customerOrderData?->platformCharge) }}</span>
                            </div>
                        </div>

                        @if ($customerOrderData?->sumofPackingCharge > 0)
                            <p class="mb-1">Packing Charge <span class="float-end text-dark">{{ Helpers::format_currency($customerOrderData?->sumofPackingCharge) }}</span></p>
                        @endif

                        <p class="mb-1">Delivery Charge
                            <button type="button" class="btn text-info ms-1 p-0" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="Delivery Charge Explanation">
                                <i class="feather-info"></i>
                            </button>
                            <span class="float-end text-dark">
                                @if ($customerOrderData?->freeDelivery > 0)
                                    <span class="text-danger"><strike class="me-2">{{ Helpers::format_currency($customerOrderData?->deliveryChargeFaceVal) }}</strike></span>
                                @endif
                                {{ Helpers::format_currency($customerOrderData?->deliveryCharge) }}
                            </span>
                        </p>

                        <hr>
                        <p class="mb-1 text-success">Gross Total <span class="float-end text-success">{{ Helpers::format_currency($customerOrderData?->grossTotal) }}</span></p>

                        <p class="mb-1">GST {{ $customerOrderData?->gstPercent }}%
                            <button type="button" class="btn text-info ms-1 p-0" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="GST and Restaurant Charge's Explanation">
                                <i class="feather-info"></i>
                            </button>
                            <span class="float-end text-dark">{{ Helpers::format_currency($customerOrderData?->gstAmount) }}</span>
                        </p>

                        @if ($customerOrderData?->dm_tips > 0)
                            <p class="mb-1 text-warning">Delivery Boy Tips <span class="float-end text-warning">{{ Helpers::format_currency($customerOrderData?->dm_tips) }}</span></p>
                        @endif

                        <hr class="hr-horizontal">
                        <h6 class="fw-bold mb-0">TOTAL <span class="float-end">{{ Helpers::format_currency(number_format($customerOrderData?->billingTotal, 2, '.', '')) }}</span></h6>
                    </div>
                @else
                    @if($order->cash_to_collect > 0)
                        <div class="mt-2 pt-2 border-top">
                            <p class="small text-muted mb-1">Cash to Collect</p>
                            <p class="fw-bold mb-0 text-danger">{{ Helpers::format_currency($order->cash_to_collect) }}</p>
                        </div>
                    @endif
                @endif
            </div>

            <!-- Order Timeline -->
            <div class="bg-white rounded shadow-sm p-3 mb-3">
                <h6 class="fw-bold mb-3">
                    <i class="feather-activity text-primary me-2"></i>
                    Order Timeline
                </h6>
                <div class="timeline">
                    <div class="timeline-item completed">
                        <div class="timeline-marker bg-success">
                            <i class="feather-check text-white"></i>
                        </div>
                        <div class="timeline-content">
                            <h6 class="fw-bold mb-1 text-success">Order Scheduled</h6>
                            <p class="small text-muted mb-0">
                                <i class="feather-calendar me-1"></i>
                                {{ Carbon\Carbon::parse($order->created_at)->format('d M Y, h:i A') }}
                            </p>
                            <p class="small text-success mb-0">✓ Your order has been successfully scheduled</p>
                        </div>
                    </div>

                    @php($processingTime = Carbon\Carbon::parse($order->schedule_at)->subMinutes(30))
                    @php($isProcessingSoon = $processingTime->isPast() || $processingTime->diffInHours(now()) < 1)

                    <div class="timeline-item {{ $isProcessingSoon ? 'active' : 'pending' }}">
                        <div class="timeline-marker {{ $isProcessingSoon ? 'bg-info' : 'bg-warning' }}">
                            <i class="feather-clock text-white"></i>
                        </div>
                        <div class="timeline-content">
                            <h6 class="fw-bold mb-1 {{ $isProcessingSoon ? 'text-info' : 'text-warning' }}">
                                {{ $isProcessingSoon ? 'Processing Started' : 'Will Start Processing' }}
                            </h6>
                            <p class="small text-muted mb-0">
                                <i class="feather-clock me-1"></i>
                                {{ $processingTime->format('d M Y, h:i A') }}
                            </p>
                            @if($isProcessingSoon)
                                <p class="small text-info mb-0">⏳ Restaurant is preparing your order</p>
                            @else
                                <p class="small text-muted mb-0">🔔 Processing will begin 30 minutes before delivery</p>
                            @endif
                        </div>
                    </div>

                    @php($deliveryTime = Carbon\Carbon::parse($order->schedule_at))
                    @php($isDeliveryTime = $deliveryTime->isPast())

                    <div class="timeline-item {{ $isDeliveryTime ? 'completed' : 'pending' }}">
                        <div class="timeline-marker {{ $isDeliveryTime ? 'bg-success' : 'bg-light' }} border-2 {{ $isDeliveryTime ? 'border-success' : 'border-secondary' }}">
                            <i class="feather-truck {{ $isDeliveryTime ? 'text-white' : 'text-muted' }}"></i>
                        </div>
                        <div class="timeline-content">
                            <h6 class="fw-bold mb-1 {{ $isDeliveryTime ? 'text-success' : 'text-muted' }}">
                                {{ $isDeliveryTime ? 'Delivered' : 'Scheduled Delivery' }}
                            </h6>
                            <p class="small text-muted mb-0">
                                <i class="feather-map-pin me-1"></i>
                                {{ $deliveryTime->format('d M Y, h:i A') }}
                            </p>
                            @if($isDeliveryTime)
                                <p class="small text-success mb-0">✅ Order delivered successfully</p>
                            @else
                                @php($timeRemaining = $deliveryTime->diffForHumans())
                                <p class="small text-primary mb-0">🚚 Delivery {{ $timeRemaining }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Cancel Order Modal -->
<div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelOrderModalLabel">Cancel Scheduled Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to cancel this scheduled order?</p>
                <div class="alert alert-warning">
                    <i class="feather-alert-triangle me-2"></i>
                    <strong>Order #{{ $order->id }}</strong><br>
                    Amount: {{ Helpers::format_currency($order->order_amount) }}<br>
                    Scheduled for: {{ Carbon\Carbon::parse($order->schedule_at)->format('d M Y, h:i A') }}
                </div>
                <p class="text-muted small">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Keep Order</button>
                <button type="button" class="btn btn-danger" id="confirmCancelOrder">Yes, Cancel Order</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('style')
<style>
.timeline {
    position: relative;
    padding-left: 40px;
    margin-top: 20px;
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
    padding-bottom: 20px;
}

.timeline-item:not(:last-child):before {
    content: '';
    position: absolute;
    left: -28px;
    top: 30px;
    height: calc(100% + 10px);
    width: 3px;
    background: linear-gradient(to bottom, #e9ecef 0%, #e9ecef 100%);
    border-radius: 2px;
}

.timeline-item.completed:not(:last-child):before {
    background: linear-gradient(to bottom, #198754 0%, #e9ecef 50%);
}

.timeline-item.active:not(:last-child):before {
    background: linear-gradient(to bottom, #0dcaf0 0%, #e9ecef 50%);
}

.timeline-marker {
    position: absolute;
    left: -38px;
    top: 0;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    border: 3px solid #fff;
    z-index: 2;
}

.timeline-content {
    padding-left: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
    border-left: 4px solid #e9ecef;
    transition: all 0.3s ease;
}

.timeline-item.completed .timeline-content {
    border-left-color: #198754;
    background: #f8fff8;
}

.timeline-item.active .timeline-content {
    border-left-color: #0dcaf0;
    background: #f0fcff;
}

.timeline-item.pending .timeline-content {
    border-left-color: #ffc107;
    background: #fffef8;
}

.timeline-item.completed .timeline-marker {
    background-color: #198754;
    border-color: #fff;
}

.timeline-item.active .timeline-marker {
    background-color: #0dcaf0;
    border-color: #fff;
}

.timeline-item.pending .timeline-marker {
    background-color: #ffc107;
    border-color: #fff;
}

.timeline-content h6 {
    margin-bottom: 8px;
    font-size: 16px;
}

.timeline-content p {
    margin-bottom: 4px;
    font-size: 13px;
}

.timeline-content p:last-child {
    margin-bottom: 0;
    font-weight: 500;
}

/* Hover effects */
.timeline-item:hover .timeline-content {
    transform: translateX(5px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

/* Responsive design */
@media (max-width: 768px) {
    .timeline {
        padding-left: 35px;
    }

    .timeline-marker {
        left: -33px;
        width: 24px;
        height: 24px;
        font-size: 12px;
    }

    .timeline-item:not(:last-child):before {
        left: -25px;
        width: 2px;
    }

    .timeline-content {
        padding: 12px;
    }

    .timeline-content h6 {
        font-size: 14px;
    }

    .timeline-content p {
        font-size: 12px;
    }
}
</style>
@endpush

@push('javascript')
<script>
let orderToCancel = {{ $order->id }};

function cancelScheduledOrder(orderId) {
    $('#cancelOrderModal').modal('show');
}

function shareLink(link) {
    // Copy the link to the clipboard
    navigator.clipboard.writeText(link).then(() => {
        console.log('Link copied to clipboard: ', link);

        // Now open the share dialog (if supported)
        if (navigator.share) {
            navigator.share({
                title: 'Check out my scheduled order!',
                text: 'Take a look at this scheduled order!',
                url: link
            }).then(() => {
                console.log('Thanks for sharing!');
            }).catch((err) => {
                console.log('Error while sharing: ', err);
            });
        } else {
            toastr.success('Link copied to clipboard!');
        }
    }).catch((err) => {
        console.error('Error in copying link to clipboard: ', err);
        toastr.error('Failed to copy link to clipboard');
    });
}

document.getElementById('confirmCancelOrder').addEventListener('click', function() {
    // Show loading state
    this.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Cancelling...';
    this.disabled = true;

    fetch(`{{ route('user.restaurant.cancel-scheduled-order', '') }}/${orderToCancel}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            toastr.success(data.message);
            // Redirect to scheduled orders list after a short delay
            setTimeout(() => {
                window.location.href = '{{ route('user.restaurant.scheduled-orders') }}';
            }, 1500);
        } else {
            toastr.error(data.message || 'Failed to cancel order');
            this.innerHTML = 'Yes, Cancel Order';
            this.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        toastr.error('An error occurred. Please try again.');
        this.innerHTML = 'Yes, Cancel Order';
        this.disabled = false;
    })
    .finally(() => {
        $('#cancelOrderModal').modal('hide');
    });
});
</script>
@endpush
