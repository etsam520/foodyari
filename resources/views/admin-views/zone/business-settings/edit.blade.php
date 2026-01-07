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
                            {{ __('Business Settings for') }} "{{ $zone->name }}"
                        </h4>
                        <p class="mb-0">{{ __('Configure zone-specific business settings') }}</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.zone.business-settings.index') }}" class="btn btn-secondary">
                            <i class="tio-arrow-backward"></i> {{ __('Back to Zones') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Action Buttons -->

                    <form action="{{ route('admin.zone.business-settings.update', $zone->id) }}" method="post">
                        @csrf
                        
                        <!-- Notification Messages Section -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h4 class="card-title m-0 d-flex align-items-center">
                                    <span class="card-header-icon mr-2"><i class="tio-email"></i></span>
                                    <span>{{ __('Notification Messages') }}</span>
                                </h4>
                            </div>
                            <div class="card-body">
                                <!-- Customer Notification Messages -->
                                <div class="mb-4">
                                    <h5 class="text-primary mb-3">
                                        <i class="tio-user mr-2"></i>{{ __('Customer Notifications') }}
                                    </h5>
                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label class="input-label text-capitalize d-flex align-items-center" for="customer_order_place_message">
                                                    {{ __('Order Place Message') }}
                                                </label>
                                                <textarea name="customer_order_place_message" class="form-control" 
                                                       id="customer_order_place_message" rows="2" 
                                                       placeholder="Your order has been placed successfully!">{{ $settings['customer_order_place_message'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Admin Notification Messages -->
                                <div class="mb-4">
                                    <h5 class="text-success mb-3">
                                        <i class="tio-shield-checkmark mr-2"></i>{{ __('Admin Notifications') }}
                                    </h5>
                                    <div class="row">
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label class="input-label text-capitalize d-flex align-items-center" for="admin_order_pending_message">
                                                    {{ __('Order Pending Message') }}
                                                </label>
                                                <textarea name="admin_order_pending_message" class="form-control" 
                                                       id="admin_order_pending_message" rows="2" 
                                                       placeholder="New order received and pending approval">{{ $settings['admin_order_pending_message'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label class="input-label text-capitalize d-flex align-items-center" for="admin_order_processing_message">
                                                    {{ __('Order Processing Message') }}
                                                </label>
                                                <textarea name="admin_order_processing_message" class="form-control" 
                                                       id="admin_order_processing_message" rows="2" 
                                                       placeholder="Order is being processed by restaurant">{{ $settings['admin_order_processing_message'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label class="input-label text-capitalize d-flex align-items-center" for="admin_order_cancel_message">
                                                    {{ __('Order Cancel Message') }}
                                                </label>
                                                <textarea name="admin_order_cancel_message" class="form-control" 
                                                       id="admin_order_cancel_message" rows="2" 
                                                       placeholder="Order has been cancelled">{{ $settings['admin_order_cancel_message'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label class="input-label text-capitalize d-flex align-items-center" for="admin_order_confirmed_message">
                                                    {{ __('Order Confirmed Message') }}
                                                </label>
                                                <textarea name="admin_order_confirmed_message" class="form-control" 
                                                       id="admin_order_confirmed_message" rows="2" 
                                                       placeholder="Order has been confirmed">{{ $settings['admin_order_confirmed_message'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label class="input-label text-capitalize d-flex align-items-center" for="admin_order_accepted_message">
                                                    {{ __('Order Accepted Message') }}
                                                </label>
                                                <textarea name="admin_order_accepted_message" class="form-control" 
                                                       id="admin_order_accepted_message" rows="2" 
                                                       placeholder="Order has been accepted by restaurant">{{ $settings['admin_order_accepted_message'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label class="input-label text-capitalize d-flex align-items-center" for="admin_order_handovered_message">
                                                    {{ __('Order Handovered Message') }}
                                                </label>
                                                <textarea name="admin_order_handovered_message" class="form-control" 
                                                       id="admin_order_handovered_message" rows="2" 
                                                       placeholder="Order has been handovered to delivery man">{{ $settings['admin_order_handovered_message'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label class="input-label text-capitalize d-flex align-items-center" for="admin_order_picked_up_message">
                                                    {{ __('Order Picked Up Message') }}
                                                </label>
                                                <textarea name="admin_order_picked_up_message" class="form-control" 
                                                       id="admin_order_picked_up_message" rows="2" 
                                                       placeholder="Order has been picked up by delivery man">{{ $settings['admin_order_picked_up_message'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label class="input-label text-capitalize d-flex align-items-center" for="admin_order_on_way_message">
                                                    {{ __('Order On Way Message') }}
                                                </label>
                                                <textarea name="admin_order_on_way_message" class="form-control" 
                                                       id="admin_order_on_way_message" rows="2" 
                                                       placeholder="Order is on the way to customer">{{ $settings['admin_order_on_way_message'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label class="input-label text-capitalize d-flex align-items-center" for="admin_dm_at_restaurant_message">
                                                    {{ __('DM at Restaurant Message') }}
                                                </label>
                                                <textarea name="admin_dm_at_restaurant_message" class="form-control" 
                                                       id="admin_dm_at_restaurant_message" rows="2" 
                                                       placeholder="Delivery man has arrived at restaurant">{{ $settings['admin_dm_at_restaurant_message'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label class="input-label text-capitalize d-flex align-items-center" for="admin_dm_arrived_at_door_message">
                                                    {{ __('DM Arrived at Door Message') }}
                                                </label>
                                                <textarea name="admin_dm_arrived_at_door_message" class="form-control" 
                                                       id="admin_dm_arrived_at_door_message" rows="2" 
                                                       placeholder="Delivery man has arrived at customer door">{{ $settings['admin_dm_arrived_at_door_message'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label class="input-label text-capitalize d-flex align-items-center" for="admin_order_delivered_message">
                                                    {{ __('Order Delivered Message') }}
                                                </label>
                                                <textarea name="admin_order_delivered_message" class="form-control" 
                                                       id="admin_order_delivered_message" rows="2" 
                                                       placeholder="Order has been delivered successfully">{{ $settings['admin_order_delivered_message'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label class="input-label text-capitalize d-flex align-items-center" for="admin_order_refund_request_message">
                                                    {{ __('Order Refund Request Message') }}
                                                </label>
                                                <textarea name="admin_order_refund_request_message" class="form-control" 
                                                       id="admin_order_refund_request_message" rows="2" 
                                                       placeholder="Order refund request has been submitted">{{ $settings['admin_order_refund_request_message'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label class="input-label text-capitalize d-flex align-items-center" for="admin_order_refund_response_message">
                                                    {{ __('Order Refund Response Message') }}
                                                </label>
                                                <textarea name="admin_order_refund_response_message" class="form-control" 
                                                       id="admin_order_refund_response_message" rows="2" 
                                                       placeholder="Order refund request has been processed">{{ $settings['admin_order_refund_response_message'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label class="input-label text-capitalize d-flex align-items-center" for="admin_order_refunded_message">
                                                    {{ __('Order Refunded Message') }}
                                                </label>
                                                <textarea name="admin_order_refunded_message" class="form-control" 
                                                       id="admin_order_refunded_message" rows="2" 
                                                       placeholder="Order has been refunded">{{ $settings['admin_order_refunded_message'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label class="input-label text-capitalize d-flex align-items-center" for="admin_order_scheduled_message">
                                                    {{ __('Order Scheduled Message') }}
                                                </label>
                                                <textarea name="admin_order_scheduled_message" class="form-control" 
                                                       id="admin_order_scheduled_message" rows="2" 
                                                       placeholder="Order has been scheduled">{{ $settings['admin_order_scheduled_message'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Deliveryman Notification Messages -->
                                <div class="mb-4">
                                    <h5 class="text-warning mb-3">
                                        <i class="tio-fastfood mr-2"></i>{{ __('Deliveryman Notifications') }}
                                    </h5>
                                    <div class="row">
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label class="input-label text-capitalize d-flex align-items-center" for="dm_order_placed_message">
                                                    {{ __('Order Placed Message') }}
                                                </label>
                                                <textarea name="dm_order_placed_message" class="form-control" 
                                                       id="dm_order_placed_message" rows="2" 
                                                       placeholder="New order available for delivery">{{ $settings['dm_order_placed_message'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label class="input-label text-capitalize d-flex align-items-center" for="dm_order_accepted_message">
                                                    {{ __('Order Accepted Message') }}
                                                </label>
                                                <textarea name="dm_order_accepted_message" class="form-control" 
                                                       id="dm_order_accepted_message" rows="2" 
                                                       placeholder="You have accepted the order">{{ $settings['dm_order_accepted_message'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label class="input-label text-capitalize d-flex align-items-center" for="dm_order_rejected_message">
                                                    {{ __('Order Rejected Message') }}
                                                </label>
                                                <textarea name="dm_order_rejected_message" class="form-control" 
                                                       id="dm_order_rejected_message" rows="2" 
                                                       placeholder="You have rejected the order">{{ $settings['dm_order_rejected_message'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label class="input-label text-capitalize d-flex align-items-center" for="dm_order_at_restaurant_message">
                                                    {{ __('Order DM at Restaurant Message') }}
                                                </label>
                                                <textarea name="dm_order_at_restaurant_message" class="form-control" 
                                                       id="dm_order_at_restaurant_message" rows="2" 
                                                       placeholder="You have arrived at the restaurant">{{ $settings['dm_order_at_restaurant_message'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label class="input-label text-capitalize d-flex align-items-center" for="dm_order_picked_up_message">
                                                    {{ __('Order Picked Up Message') }}
                                                </label>
                                                <textarea name="dm_order_picked_up_message" class="form-control" 
                                                       id="dm_order_picked_up_message" rows="2" 
                                                       placeholder="You have picked up the order">{{ $settings['dm_order_picked_up_message'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label class="input-label text-capitalize d-flex align-items-center" for="dm_arrived_at_customer_door_message">
                                                    {{ __('Arrived at Customer Door Message') }}
                                                </label>
                                                <textarea name="dm_arrived_at_customer_door_message" class="form-control" 
                                                       id="dm_arrived_at_customer_door_message" rows="2" 
                                                       placeholder="You have arrived at customer door">{{ $settings['dm_arrived_at_customer_door_message'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label class="input-label text-capitalize d-flex align-items-center" for="dm_order_delivered_message">
                                                    {{ __('Order Delivered Message') }}
                                                </label>
                                                <textarea name="dm_order_delivered_message" class="form-control" 
                                                       id="dm_order_delivered_message" rows="2" 
                                                       placeholder="You have delivered the order">{{ $settings['dm_order_delivered_message'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Legacy Notification Messages (for backward compatibility) -->
                                <div class="mb-4">
                                    <h5 class="text-secondary mb-3">
                                        <i class="tio-archive mr-2"></i>{{ __('Legacy Notification Messages') }}
                                        <small class="text-muted">({{ __('Kept for backward compatibility') }})</small>
                                    </h5>
                                    <div class="row">
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label class="input-label text-capitalize d-flex align-items-center" for="order_confirm_message">
                                                    {{ __('Order Confirm Message') }}
                                                </label>
                                                <input type="text" name="order_confirm_message" class="form-control" 
                                                       id="order_confirm_message" 
                                                       value="{{ $settings['order_confirm_message'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label class="input-label text-capitalize d-flex align-items-center" for="order_delivered_message">
                                                    {{ __('Order Deliver Message') }}
                                                </label>
                                                <input type="text" name="order_delivered_message" class="form-control" 
                                                       id="order_delivered_message" 
                                                       value="{{ $settings['order_delivered_message'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label class="input-label text-capitalize d-flex align-items-center" for="delivery_boy_delivered_message">
                                                    {{ __('Delivery Boy Order Deliver Message') }}
                                                </label>
                                                <input type="text" name="delivery_boy_delivered_message" class="form-control" 
                                                       id="delivery_boy_delivered_message" 
                                                       value="{{ $settings['delivery_boy_delivered_message'] ?? '' }}">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label class="input-label text-capitalize d-flex align-items-center" for="order_cancled_message">
                                                    {{ __('Order Cancel Message') }}
                                                </label>
                                                <input type="text" name="order_cancled_message" class="form-control" 
                                                       id="order_cancled_message" 
                                                       value="{{ $settings['order_cancled_message'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label class="input-label text-capitalize d-flex align-items-center" for="order_handover_message">
                                                    {{ __('Order Handover Message') }}
                                                </label>
                                                <input type="text" name="order_handover_message" class="form-control" 
                                                       id="order_handover_message" 
                                                       value="{{ $settings['order_handover_message'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label class="input-label text-capitalize d-flex align-items-center" for="order_refunded_message">
                                                    {{ __('Order Refund Message') }}
                                                </label>
                                                <input type="text" name="order_refunded_message" class="form-control" 
                                                       id="order_refunded_message" 
                                                       value="{{ $settings['order_refunded_message'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label class="input-label text-capitalize d-flex align-items-center" for="out_for_delivery_message">
                                                    {{ __('Out For Delivery Message') }}
                                                </label>
                                                <input type="text" name="out_for_delivery_message" class="form-control" 
                                                       id="out_for_delivery_message" 
                                                       value="{{ $settings['out_for_delivery_message'] ?? '' }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Business Settings Section -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h4 class="card-title m-0 d-flex align-items-center">
                                    <span class="card-header-icon mr-2"><i class="tio-settings-outlined"></i></span>
                                    <span>{{ __('Business Settings') }}</span>
                                </h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <!-- Toggle Settings -->
                                    <div class="col-xl-4 col-lg-4 col-sm-6">
                                        @php($customer_verification = $settings['customer_verification'] ?? 0)
                                        <div class="form-group">
                                            <label class="form-check form-check form-switch form-check-inline d-flex justify-content-between border rounded px-3 px-xl-4 form-control">
                                                <span class="pr-2 d-flex align-items-center">
                                                    <span class="line--limit-1">{{ __('Customer Verification') }}</span>
                                                </span>
                                                <input type="checkbox" class="form-check-input" value="1" name="customer_verification" {{ $customer_verification == 1 ? 'checked' : '' }}>
                                                <span class="toggle-switch-label text">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-sm-6">
                                        @php($restaurant_self_registration = $settings['toggle_restaurant_registration'] ?? 0)
                                        <div class="form-group">
                                            <label class="form-check form-check form-switch form-check-inline d-flex justify-content-between border rounded px-3 px-xl-4 form-control">
                                                <span class="pr-2 d-flex align-items-center">
                                                    <span class="line--limit-1">{{ __('Restaurant Self Registration') }}</span>
                                                </span>
                                                <input type="checkbox" class="form-check-input" value="1" name="toggle_restaurant_registration" {{ $restaurant_self_registration == 1 ? 'checked' : '' }}>
                                                <span class="toggle-switch-label text">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-sm-6">
                                        @php($dm_self_registration = $settings['toggle_dm_registration'] ?? 0)
                                        <div class="form-group">
                                            <label class="form-check form-check form-switch form-check-inline d-flex justify-content-between border rounded px-3 px-xl-4 form-control">
                                                <span class="pr-2 d-flex align-items-center">
                                                    <span class="line--limit-1">{{ __('DM Self Registration') }}</span>
                                                </span>
                                                <input type="checkbox" class="form-check-input" value="1" name="toggle_dm_registration" {{ $dm_self_registration == 1 ? 'checked' : '' }}>
                                                <span class="toggle-switch-label text">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-sm-6">
                                        @php($admin_order_notification = $settings['admin_order_notification'] ?? 0)
                                        <div class="form-group">
                                            <label class="form-check form-check form-switch form-check-inline d-flex justify-content-between border rounded px-3 px-xl-4 form-control">
                                                <span class="pr-2 d-flex align-items-center">
                                                    <span class="line--limit-1">{{ __('Admin Order Notification') }}</span>
                                                </span>
                                                <input type="checkbox" class="form-check-input" value="1" name="admin_order_notification" {{ $admin_order_notification == 1 ? 'checked' : '' }}>
                                                <span class="toggle-switch-label text">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-sm-6">
                                        @php($schedule_order = $settings['schedule_order'] ?? 0)
                                        <div class="form-group">
                                            <label class="form-check form-check form-switch form-check-inline d-flex justify-content-between border rounded px-3 px-xl-4 form-control">
                                                <span class="pr-2 d-flex align-items-center">
                                                    <span class="line--limit-1">{{ __('Scheduled Orders') }}</span>
                                                </span>
                                                <input type="checkbox" value="1" name="schedule_order" class="form-check-input" {{ $schedule_order == 1 ? 'checked' : '' }}>
                                                <span class="toggle-switch-label text">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-sm-6">
                                        @php($order_delivery_verification = $settings['order_delivery_verification'] ?? 0)
                                        <div class="form-group">
                                            <label class="form-check form-check form-switch form-check-inline d-flex justify-content-between border rounded px-3 px-xl-4 form-control">
                                                <span class="pr-2 d-flex align-items-center">
                                                    <span class="line--limit-1">{{ __('Order Delivery Verification') }}</span>
                                                </span>
                                                <input type="checkbox" class="form-check-input" value="1" name="odc" {{ $order_delivery_verification == 1 ? 'checked' : '' }}>
                                                <span class="toggle-switch-label text">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-sm-6">
                                        @php($dm_tips_status = $settings['dm_tips_status'] ?? 0)
                                        <div class="form-group">
                                            <label class="form-check form-check form-switch form-check-inline d-flex justify-content-between border rounded px-3 px-xl-4 form-control">
                                                <span class="pr-2 d-flex align-items-center">
                                                    <span class="line--limit-1">{{ __('DM Tips Option') }}</span>
                                                </span>
                                                <input type="checkbox" class="form-check-input" value="1" name="dm_tips_status" {{ $dm_tips_status == '1' ? 'checked' : '' }}>
                                                <span class="toggle-switch-label text">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-sm-6">
                                        @php($show_dm_earning = $settings['show_dm_earning'] ?? 0)
                                        <div class="form-group">
                                            <label class="form-check form-check form-switch form-check-inline d-flex justify-content-between border rounded px-3 px-xl-4 form-control">
                                                <span class="pr-2 d-flex align-items-center">
                                                    <span class="line--limit-1">{{ __('Show DM Earning Per Order') }}</span>
                                                </span>
                                                <input type="checkbox" class="form-check-input" value="1" name="show_dm_earning" {{ $show_dm_earning == 1 ? 'checked' : '' }}>
                                                <span class="toggle-switch-label text">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-sm-6">
                                        @php($toggle_veg_non_veg = $settings['toggle_veg_non_veg'] ?? 0)
                                        <div class="form-group">
                                            <label class="form-check form-check form-switch form-check-inline d-flex justify-content-between border rounded px-3 px-xl-4 form-control">
                                                <span class="pr-2 d-flex align-items-center">
                                                    <span class="line--limit-1">{{ __('Veg / Non Veg Option') }}</span>
                                                </span>
                                                <input type="checkbox" class="form-check-input" value="1" name="vnv" {{ $toggle_veg_non_veg == 1 ? 'checked' : '' }}>
                                                <span class="toggle-switch-label text">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>

                                    @php($business_model = $settings['business_model'] ?? [])
                                    <div class="col-xl-4 col-lg-4 col-sm-6">
                                        <div class="form-group">
                                            <label class="form-check form-check form-switch form-check-inline d-flex justify-content-between border rounded px-3 px-xl-4 form-control">
                                                <span class="pr-2 d-flex align-items-center">
                                                    <span class="line--limit-1">{{ __('Commission') }}</span>
                                                </span>
                                                <input type="checkbox" class="form-check-input" value="1" name="commission" {{ isset($business_model['commission']) && $business_model['commission'] == 1 ? 'checked' : '' }}>
                                                <span class="toggle-switch-label text">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-sm-6">
                                        <div class="form-group">
                                            <label class="form-check form-check form-switch form-check-inline d-flex justify-content-between border rounded px-3 px-xl-4 form-control">
                                                <span class="pr-2 d-flex align-items-center">
                                                    <span class="line--limit-1">{{ __('Subscription') }}</span>
                                                </span>
                                                <input type="checkbox" class="form-check-input" value="1" name="subscription" {{ isset($business_model['subscription']) && $business_model['subscription'] == 1 ? 'checked' : '' }}>
                                                <span class="toggle-switch-label text">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-sm-6">
                                        @php($order_subscription = $settings['order_subscription'] ?? 0)
                                        <div class="form-group">
                                            <label class="form-check form-check form-switch form-check-inline d-flex justify-content-between border rounded px-3 px-xl-4 form-control">
                                                <span class="pr-2 d-flex align-items-center">
                                                    <span class="line--limit-1">{{ __('Order Subscription') }}</span>
                                                </span>
                                                <input type="checkbox" class="form-check-input" value="1" name="order_subscription" {{ $order_subscription == 1 ? 'checked' : '' }}>
                                                <span class="toggle-switch-label text">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Radio Button Settings -->
                                <div class="row">
                                    <div class="col-xl-4 col-lg-4 col-sm-6">
                                        @php($order_confirmation_model = $settings['order_confirmation_model'] ?? 'deliveryman')
                                        <div class="form-group">
                                            <label class="input-label text-capitalize d-flex align-items-center">
                                                {{ __('Order Confirmation Model') }}
                                            </label>
                                            <div class="resturant-type-group border">
                                                <label class="form-check form-check-inline mr-2 mr-md-4">
                                                    <input class="form-check-input" type="radio" value="restaurant" name="order_confirmation_model" {{ $order_confirmation_model == 'restaurant' ? 'checked' : '' }}>
                                                    <span class="form-check-label">{{ __('Restaurant') }}</span>
                                                </label>
                                                <label class="form-check form-check-inline mr-2 mr-md-4">
                                                    <input class="form-check-input" type="radio" value="deliveryman" name="order_confirmation_model" {{ $order_confirmation_model == 'deliveryman' ? 'checked' : '' }}>
                                                    <span class="form-check-label">{{ __('Deliveryman') }}</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-sm-6">
                                        @php($canceled_by_deliveryman = $settings['canceled_by_deliveryman'] ?? 0)
                                        <div class="form-group">
                                            <label class="input-label text-capitalize d-flex align-items-center">
                                                {{ __('Delivery Man can Cancel Order') }}
                                            </label>
                                            <div class="resturant-type-group border">
                                                <label class="form-check form-check-inline mr-2 mr-md-4">
                                                    <input class="form-check-input" type="radio" value="1" name="canceled_by_deliveryman" {{ $canceled_by_deliveryman == 1 ? 'checked' : '' }}>
                                                    <span class="form-check-label">{{ __('Yes') }}</span>
                                                </label>
                                                <label class="form-check form-check-inline mr-2 mr-md-4">
                                                    <input class="form-check-input" type="radio" value="0" name="canceled_by_deliveryman" {{ $canceled_by_deliveryman == 0 ? 'checked' : '' }}>
                                                    <span class="form-check-label">{{ __('No') }}</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-sm-6">
                                        @php($canceled_by_restaurant = $settings['canceled_by_restaurant'] ?? 0)
                                        <div class="form-group">
                                            <label class="input-label text-capitalize d-flex align-items-center">
                                                {{ __('Restaurant Can Cancel Order') }}
                                            </label>
                                            <div class="resturant-type-group border">
                                                <label class="form-check form-check-inline mr-2 mr-md-4">
                                                    <input class="form-check-input" type="radio" value="1" name="canceled_by_restaurant" {{ $canceled_by_restaurant == 1 ? 'checked' : '' }}>
                                                    <span class="form-check-label">{{ __('Yes') }}</span>
                                                </label>
                                                <label class="form-check form-check-inline mr-2 mr-md-4">
                                                    <input class="form-check-input" type="radio" value="0" name="canceled_by_restaurant" {{ $canceled_by_restaurant == 0 ? 'checked' : '' }}>
                                                    <span class="form-check-label">{{ __('No') }}</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Numeric Settings -->
                                <div class="row">
                                    <div class="col-md-4 col-12">
                                        @php($schedule_order_slot_duration = $settings['schedule_order_slot_duration'] ?? 0)
                                        <div class="form-group">
                                            <label class="input-label text-capitalize d-flex align-items-center" for="schedule_order_slot_duration">
                                                {{ __('Schedule Order Slot Duration (minutes)') }}
                                            </label>
                                            <input type="number" name="schedule_order_slot_duration" class="form-control" 
                                                   id="schedule_order_slot_duration" value="{{ $schedule_order_slot_duration }}" 
                                                   min="0" placeholder="30">
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-12">
                                        @php($dm_maximum_orders = $settings['dm_maximum_orders'] ?? 1)
                                        <div class="form-group">
                                            <label class="input-label text-capitalize d-flex align-items-center" for="dm_maximum_orders">
                                                {{ __('DM Maximum Orders') }}
                                            </label>
                                            <input type="number" name="dm_maximum_orders" class="form-control" 
                                                   id="dm_maximum_orders" min="1" value="{{ $dm_maximum_orders }}" 
                                                   placeholder="5">
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-12">
                                        @php($free_delivery_over = $settings['free_delivery_over'] ?? 0)
                                        <div class="form-group">
                                            <label class="input-label text-capitalize d-flex align-items-center" for="free_delivery_over">
                                                {{ __('Free Delivery Over') }} ({{ \App\CentralLogics\Helpers::currency_symbol() }})
                                            </label>
                                            <input type="number" name="free_delivery_over" class="form-control" 
                                                   id="free_delivery_over" value="{{ $free_delivery_over }}" 
                                                   min="0" step="0.01" placeholder="100">
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-12">
                                        @php($dm_max_cash_in_hand = $settings['dm_max_cash_in_hand'] ?? 0)
                                        <div class="form-group">
                                            <label class="input-label d-flex text-capitalize" for="dm_max_cash_in_hand">
                                                {{ __('Delivery Man Maximum Cash in Hand') }} ({{ \App\CentralLogics\Helpers::currency_symbol() }})
                                            </label>
                                            <input type="number" name="dm_max_cash_in_hand" class="form-control" 
                                                   id="dm_max_cash_in_hand" min="0" step="0.01" value="{{ $dm_max_cash_in_hand }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-12">
                                        @php($admin_commission = $settings['admin_commission'] ?? 0)
                                        <div class="form-group">
                                            <label class="input-label text-capitalize d-flex align-items-center" for="admin_commission">
                                                {{ __('Admin Commission') }} (%)
                                            </label>
                                            <input type="number" name="admin_commission" class="form-control" 
                                                   id="admin_commission" value="{{ $admin_commission }}" 
                                                   min="0" max="100" step="0.01">
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-12">
                                        @php($delivery_charge_comission = $settings['delivery_charge_comission'] ?? 0)
                                        <div class="form-group">
                                            <label class="input-label text-capitalize d-flex align-items-center" for="admin_comission_in_delivery_charge">
                                                {{ __('Admin Commission in Delivery Charge') }} (%)
                                            </label>
                                            <input type="number" name="admin_comission_in_delivery_charge" class="form-control" 
                                                   id="admin_comission_in_delivery_charge" min="0" max="100" step="0.01" 
                                                   value="{{ $delivery_charge_comission }}">
                                        </div>
                                    </div>

                                    <!-- Loyalty System Settings -->
                                    <div class="col-md-4 col-12">
                                        @php($loyalty_percent = $settings['loyalty_percent'] ?? 0)
                                        <div class="form-group">
                                            <label class="input-label text-capitalize d-flex align-items-center" for="loyalty_percent">
                                                {{ __('Loyalty Points Percentage') }} (%)
                                            </label>
                                            <input type="number" name="loyalty_percent" class="form-control" 
                                                   id="loyalty_percent" min="0" max="100" step="0.01" value="{{ $loyalty_percent }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-12">
                                        @php($loyalty_value = $settings['loyalty_value'] ?? 1)
                                        <div class="form-group">
                                            <label class="input-label text-capitalize d-flex align-items-center" for="loyalty_value">
                                                {{ __('Loyalty Point Value') }} ({{ \App\CentralLogics\Helpers::currency_symbol() }})
                                            </label>
                                            <input type="number" name="loyalty_value" class="form-control" 
                                                   id="loyalty_value" min="0" step="0.01" value="{{ $loyalty_value }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-12">
                                        @php($minimum_redeem_value = $settings['minimum_redeem_value'] ?? 10)
                                        <div class="form-group">
                                            <label class="input-label text-capitalize d-flex align-items-center" for="minimum_redeem_value">
                                                {{ __('Minimum Redeem Value') }} ({{ \App\CentralLogics\Helpers::currency_symbol() }})
                                            </label>
                                            <input type="number" name="minimum_redeem_value" class="form-control" 
                                                   id="minimum_redeem_value" min="0" step="0.01" value="{{ $minimum_redeem_value }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Save Button -->
                        <div class="btn--container justify-content-end">
                            <button type="reset" class="btn btn-outline-secondary">{{ __('Reset') }}</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="tio-save"></i> {{ __('Save Zone Settings') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection