


<p class="mb-1">Sub Total <span class="text-info ms-1"></span><span
    class="float-end text-dark">{{Helpers::format_currency($billData->sumOfFoodPriceBeforDiscount)}}</span>
</p>
@if ($billData->sumOfDiscount > 0)
<p class="mb-1 text-success">Discount <span class="float-end text-success">{{Helpers::format_currency($billData->sumOfDiscount)}}</span>
@endif
@if ($billData->couponDiscountAmount > 0)
</p>
<p class="mb-1 text-success">Coupon Discount <span class="float-end text-success">{{Helpers::format_currency($billData->couponDiscountAmount)}}</span>
@endif
@if (isset($billData->referralDiscountAmount) && $billData->referralDiscountAmount > 0)
<p class="mb-1 text-success">
    <i class="fas fa-gift text-warning"></i> Referral Discount 
    <span class="float-end text-success">{{Helpers::format_currency($billData->referralDiscountAmount)}}</span>
</p>
@endif
@if($billData->zone->platform_charge > 0)
<div class="mb-1">Platform Fee
<button type="button" class="btn text-info ms-1 p-0 info-tooltip" 
    data-bs-toggle="tooltip"
    data-bs-placement="top" 
    data-bs-custom-class="custom-tooltip"
    data-bs-title="Platform Fee
A small fee that helps us maintain the Foodyari app and support smooth, reliable deliveries."
    data-tooltip="Platform Fee
A small fee that helps us maintain the Foodyari app and support smooth, reliable deliveries."
    title="Platform Fee
A small fee that helps us maintain the Foodyari app and support smooth, reliable deliveries.">
    <i class="feather-info"></i>
</button>
    <div class="float-end">
        <span class="text-danger "><strike class="me-2">{{Helpers::format_currency($billData->zone->platform_charge )}}</strike></span>
        <span class="text-dark ">{{Helpers::format_currency($billData->platformCharge)}}</span>
    </div>
</div>
@endif
@if ($billData->sumofPackingCharge > 0)
<p class="mb-1">Packing Charge <span class="text-info ms-1"></span><span
    class="float-end text-dark">{{Helpers::format_currency($billData->sumofPackingCharge)}}</span>
</p>
@endif

<p class="mb-1">Delivery Charge<button type="button" class="btn text-info ms-1 p-0 info-tooltip"
    data-bs-toggle="tooltip" 
    data-bs-placement="top" 
    data-bs-custom-class="custom-tooltip"
    data-bs-title="Delivery charge is calculated based on distance from restaurant to your location. Free delivery may apply for orders above minimum amount."
    data-tooltip="Delivery charge is calculated based on distance from restaurant to your location. Free delivery may apply for orders above minimum amount."
    title="Delivery charge is calculated based on distance from restaurant to your location. Free delivery may apply for orders above minimum amount.">
    <i class="feather-info"></i>
</button><span
    class="float-end text-dark">
    @if($billData->freeDelivery > 0)
    <span class="text-danger "><strike class="me-2">{{Helpers::format_currency($billData->deliveryChargeFaceVal)}}</strike></span>
    @endif
    {{ Helpers::format_currency($billData->deliveryCharge)}}
    </span>
</p>
<p class="mb-1">Distance ({{Helpers::formatDistance($billData->distance)}})</p>
   <hr>
 <p class="mb-1  text-success">Gross Total<span class="float-end text-success">{{Helpers::format_currency($billData->grossTotal)}}</span>
<p class="mb-1">GST {{$billData->gstPercent}}%<button type="button" class="btn text-info ms-1 p-0 info-tooltip"
    data-bs-toggle="tooltip" 
    data-bs-placement="top" 
    data-bs-custom-class="custom-tooltip"
    data-bs-title="Goods and Services Tax (GST) is a government tax applied to restaurant charges and services as per Indian tax regulations."
    data-tooltip="Goods and Services Tax (GST) is a government tax applied to restaurant charges and services as per Indian tax regulations."
    title="Goods and Services Tax (GST) is a government tax applied to restaurant charges and services as per Indian tax regulations.">
    <i class="feather-info"></i>
</button><span class="float-end text-dark">{{Helpers::format_currency($billData->gstAmount)}}</span></p>
 @if ($billData->dm_tips > 0)
 <p class="mb-1  text-warning">Delivery Boy Tips<span class="float-end text-warning">{{Helpers::format_currency($billData->dm_tips)}}</span>
</p>
 @endif

{{--<p class="mb-1  text-success">Donation<span class="float-end text-success">₹0</span>
</p>--}}
{{-- <hr> --}}
<h6 class="fw-bold mb-0">TOTAL <span class="float-end">{{ Helpers::format_currency(number_format($billData->billingTotal, 2, '.', ''))}}</span></h6>
