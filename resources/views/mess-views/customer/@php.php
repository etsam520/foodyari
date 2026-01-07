  @php
    $pricing = json_decode($customer->pricing, true);
    if (is_array($pricing)) {
        $breakfastPrice = $pricing['breakfast']['price'];
        $breakfastQuantity = $pricing['breakfast']['quantity'];
        $lunchPrice = $pricing['lunch']['price'];
        $lunchQuantity = $pricing['lunch']['quantity'];
        $dinnerPrice = $pricing['dinner']['price'];
        $dinnerQuantity = $pricing['dinner']['quantity'];
        $totalCost = $pricing['totalCost'];
    } 
@endphp