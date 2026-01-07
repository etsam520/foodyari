@php
$deliveryAddress = json_decode($order->delivery_address);
$customerOrderData = json_decode($order->orderCalculationStmt?->customerData);
@endphp
<style>
    div {
        font-family: "DejaVu Sans", sans-serif; /* This font supports ₹ */
    }
</style>

<div style="max-width: 600px; margin: auto; padding: 20px; font-family: Arial, sans-serif;">
    <div style="background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <div style="text-align: center; margin-bottom: 20px;">
            <h4 style="margin: 0; font-size: 24px;">Order Invoice</h4>
        </div>

        <div style="margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between;">
                <p style="margin: 0; font-size: 14px;"><strong>Date :</strong> {{ App\CentralLogics\Helpers::format_date($order->created_at) }}</p>
                <p style="margin: 0; font-size: 14px;"><strong>Time :</strong> {{ \Carbon\Carbon::parse($order->created_at)->format('h:i A') }}</p>
            </div>
        </div>

        {{-- <div style="text-align: center; margin-bottom: 20px;">
            <img src="img/qr-code.png" alt="QR Code" style="height: 100px;">
        </div> --}}

        <h5 style="text-align: center; font-size: 18px; margin: 10px 0;">{{ Str::upper($order->restaurant->name) }}</h5>
        <p style="text-align: center; font-size: 14px;">
            @php($address = json_decode($order->restaurant->address))
            <i class="feather-map-pin"></i> {{ Str::ucfirst($address->street) }} {{ Str::ucfirst($address->city) }}, {{ Str::ucfirst($address->pincode) }}
        </p>
        @if ($order->restaurant->phone != null)
        <p style="text-align: center; font-size: 14px;">
            <i class="feather-phone"></i> +91 {{ $order->restaurant->phone ?? 'N/A' }}
        </p>
        @endif

        <hr style="border-top: dashed;">

        <p style="font-size: 14px;"><strong>Order ID :</strong> #{{ $order->id }}</p>

        <div style="font-size: 14px;">
            <strong>Customer Name :</strong> {{ $deliveryAddress->contact_person_name ?? Str::ucfirst($order->customer->f_name) . ' ' . Str::ucfirst($order->customer->l_name) }}
        </div>

        <div style="font-size: 14px;">
            <strong>Phone :</strong> {{ $deliveryAddress->contact_person_number ?? $order->customer->phone }}
        </div>

        <div style="font-size: 14px;">
            <strong>Address :</strong> {{ $deliveryAddress->stringAddress ?? 'N/A' }}
        </div>

        <hr style="border-top: dashed;">

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
            <thead>
                <tr>
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Sl No.</th>
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Description</th>
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: center;">Qty</th>
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @if($customerOrderData?->foodItemList != null)
                    @foreach ($customerOrderData->foodItemList as $key => $listItem)
                        <tr>
                            <td style="border: 1px solid #ddd; padding: 8px;">{{ $key + 1 }}</td>
                            <td style="border: 1px solid #ddd; padding: 8px;">{{ Str::ucfirst($listItem->foodName) }}</td>
                            <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">{{ $listItem->quantity }}</td>
                            <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">{{ Helpers::format_currency($listItem->foodPrice) }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>

        <hr style="border-top: dashed;">

        <p style="font-size: 14px;">Sub Total <span style="float: right;">{{ Helpers::format_currency($customerOrderData?->sumOfFoodPriceBeforDiscount) }}</span></p>
        @if($customerOrderData?->sumOfDiscount > 0)
            <p style="font-size: 14px;">Discount <span style="float: right;">{{ Helpers::format_currency($customerOrderData?->sumOfDiscount) }}</span></p>
        @endif

        @if($customerOrderData?->couponDiscountAmount > 0)
            <p style="font-size: 14px;">Coupon Discount <span style="float: right;">{{ Helpers::format_currency($customerOrderData?->couponDiscountAmount) }}</span></p>
        @endif

        @if ($customerOrderData?->platformCharge > 0)
            <p style="font-size: 14px;">Platform Fee <span style="float: right;">{{ Helpers::format_currency($customerOrderData?->platformCharge) }}</span></p>
        @endif

        @if ($customerOrderData?->sumofPackingCharge > 0)
            <p style="font-size: 14px;">Packing Charge <span style="float: right;">{{ Helpers::format_currency($customerOrderData?->sumofPackingCharge) }}</span></p>
        @endif

        <p style="font-size: 14px;">Delivery Charge <span style="float: right;">{{ Helpers::format_currency($customerOrderData?->deliveryCharge) }}</span></p>

        <hr>

        <h6 style="font-size: 16px; margin-top: 20px;">Gross Total <span style="float: right;">{{ Helpers::format_currency($customerOrderData?->grossTotal) }}</span></h6>
        <p style="font-size: 14px;">GST {{ $customerOrderData?->gstPercent }}% <span style="float: right;">{{ Helpers::format_currency($customerOrderData?->gstAmount) }}</span></p>

        @if ($customerOrderData?->dm_tips > 0)
            <p style="font-size: 14px;">Deliveryman Tip <span style="float: right;">{{ Helpers::format_currency($customerOrderData?->dm_tips) }}</span></p>
        @endif

        <hr>

        <h4 style="font-size: 18px; margin-top: 20px;">Total <span style="float: right;">{{ Helpers::format_currency(ceil($customerOrderData?->billingTotal)) }}</span></h4>

        <hr style="border-top: dashed;">

        <div style="font-size: 14px;">
            <strong>Paid By :</strong> {{ Str::ucfirst($order->payment_method) }} <span style="background-color: #28a745; color: white; padding: 2px 8px; border-radius: 4px;">{{ $order->payment_status }}</span>
        </div>

        <hr style="border-top: dashed;">

        {{-- <div style="text-align: center;">
            <img src="img/qr-code.png" alt="QR Code" style="height: 100px;">
        </div> --}}

        <hr>

        <h2 style="text-align: center; font-size: 22px; font-weight: bold;">* THANK YOU **</h2>

        <hr style="border-top: dashed;">

        <p style="text-align: center; font-size: 14px;">© 2024 FoodYari. All rights reserved.</p>
    </div>
</div>
