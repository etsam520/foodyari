<?php

namespace App\CentralLogics;

use App\Http\Middleware\Restaurant;
use App\Models\Restaurant as ModelsRestaurant;

class ReportLogic {
    public static function get_varient(array $product_variations, array $variations)
    {
        $result = [];

        foreach($variations as $k=> $variation){
            foreach($product_variations as  $product_variation){
                if( isset($variation['values'])  && isset($product_variation->values) && $product_variation->name == $variation['option']  ){
                    // dd($product_variation);
                    foreach($product_variation->values as  $option){
                        foreach($variation['values'] as &$value){
                            if($option->label == $value['label']){
                                $value['optionPrice'] = $option->optionPrice;
                                $value['total'] = ($option->optionPrice * $value['qty']);
                                break;
                            }

                        }

                    }
                }
                $result[$k] = $variation;
            }

        }
        return $result;
    }

    public static function getFoodReport_process_func($productsWithOrderDetails){
        $nonVariationDetails = [];
        $variationDetails = [];
        $addonDetails = [];
        $productItems = [];

        $restaurants = ModelsRestaurant::select('id', 'name')->get()->toArray();
        // dd($restaurants);


        // dd($productsWithOrderDetails);
        foreach ($productsWithOrderDetails as $key => $ordersDetails) {
            foreach ($ordersDetails as $key => $orderDetails) {
                $foodDetails = json_decode($orderDetails->food_details);

                $restaurantIndex = array_search($foodDetails->restaurant_id, array_column($restaurants, 'id'));
                $restaurant_name = $restaurantIndex !== false ? $restaurants[$restaurantIndex]['name'] : null;

                $foodVariations = json_decode($foodDetails->variations);
                // dd($foodDetails);

                $addons = json_decode($orderDetails->add_ons);

                if($foodVariations != null){
                    // Process ordered variations
                    $orderedVarations = json_decode($orderDetails->variation, true);
                    $orderedVarations = self::get_varient($foodVariations, $orderedVarations);

                    foreach ($orderedVarations as $ord_variation) {
                        // Normalize the name (case-insensitive and fix typos if needed)
                        $normalizedName = ucfirst(strtolower(trim($ord_variation['option'])));

                        // Find if the variation already exists
                        $names = array_column($variationDetails, 'name');
                        $optionValues = [];

                        if (($index = array_search($normalizedName, $names)) !== false) {
                            // If the variation name exists, get its values
                            $optionValues = $variationDetails[$index]['values'];
                        } else {
                            // If not, add a new variation for the ordered one
                            $variationDetails[] = ['name' => $normalizedName, 'values' => []];
                            $index = count($variationDetails) - 1;  // Get the index of the newly added variation
                        }

                        // Process each value of the ordered variation
                        foreach ($ord_variation['values'] as $value) {
                            $existingOptionIndex = false;

                            // Check if this option already exists in optionValues by matching both label and optionPrice
                            foreach ($optionValues as $optIndex => $option) {
                                if ($option['label'] === $value['label'] && $option['optionPrice'] == $value['price']) {
                                    $existingOptionIndex = $optIndex;
                                    break;
                                }
                            }

                            // If a match is found, update the quantity and total
                            if ($existingOptionIndex !== false) {
                                $optionValues[$existingOptionIndex]['quantity'] += $value['qty'];
                                $optionValues[$existingOptionIndex]['total'] += ($value['price'] * $value['qty']);
                            } else {
                                // Otherwise, add a new entry
                                $optionValues[] = [
                                    'optionPrice' => $value['price'],
                                    'label' => $value['label'],
                                    'foodname' => ucfirst($foodDetails->name) . ' (' . $value['label'] . ')',
                                    'restaurant_name' => $restaurant_name ,
                                    'food_id' => $foodDetails->id,
                                    'quantity' => $value['qty'],
                                    'total' => ($value['price'] * $value['qty']),
                                ];
                            }
                        }

                        // Update the variationDetails with the new option values
                        $variationDetails[$index]['values'] = $optionValues;
                    }
                }else{
                    $optionValues = [];
                    $names = array_column($nonVariationDetails, 'name');
                    $normalizedName = ucfirst(strtolower(trim($foodDetails->name)));
                    if (($index = array_search($normalizedName, $names)) !== false) {
                        // If the variation name exists, get its values
                        $optionValues = $nonVariationDetails[$index]['values'];
                    } else {
                        // If not, add a new variation for the ordered one
                        $nonVariationDetails[] = ['name' => $normalizedName, 'values' => []];
                        $index = count($nonVariationDetails) - 1;  // Get the index of the newly added variation
                    }


                    $existingOptionIndex = false;

                    // Check if this option already exists in optionValues by matching both label and optionPrice
                    foreach ($optionValues as $optIndex => $option) {
                        if ($option['label'] === $foodDetails->name && $option['optionPrice'] == $foodDetails->price) {
                            $existingOptionIndex = $optIndex;
                            break;
                        }
                    }

                    // If a match is found, update the quantity and total
                    if ($existingOptionIndex !== false) {
                        $optionValues[$existingOptionIndex]['quantity'] += $orderDetails->quantity;
                        $optionValues[$existingOptionIndex]['total'] += ($foodDetails->price * $orderDetails->quantity);
                    } else {
                        // Otherwise, add a new entry
                        $optionValues[] = [
                            'optionPrice' => $foodDetails->price,
                            'label' => $foodDetails->name,
                            'foodname' => ucfirst($foodDetails->name),
                            'food_id' => $foodDetails->id,
                            'restaurant_name' => $restaurant_name ,
                            'quantity' => $orderDetails->quantity,
                            'total' => ($foodDetails->price * $orderDetails->quantity),
                        ];
                    }
                    $nonVariationDetails[$index]['values'] = $optionValues;
                }

                if($addons != null){
                    foreach($addons as $addon){
                        $optionValues = [];
                        $names = array_column($addonDetails, 'name');
                        $normalizedName = ucfirst(strtolower(trim($addon->name)));
                        if (($index = array_search($normalizedName, $names)) !== false) {
                            // If the variation name exists, get its values
                            $optionValues = $addonDetails[$index]['values'];
                        } else {
                            // If not, add a new variation for the ordered one
                            $addonDetails[] = ['name' => $normalizedName, 'values' => []];
                            $index = count($addonDetails) - 1;  // Get the index of the newly added variation
                        }


                        $existingOptionIndex = false;

                        // Check if this option already exists in optionValues by matching both label and optionPrice
                        foreach ($optionValues as $optIndex => $option) {
                            if ($option['label'] === $addon->name && $option['optionPrice'] == $addon->price) {
                                $existingOptionIndex = $optIndex;
                                break;
                            }
                        }

                        // If a match is found, update the quantity and total
                        if ($existingOptionIndex !== false) {
                            $optionValues[$existingOptionIndex]['quantity'] += $addon->qty;
                            $optionValues[$existingOptionIndex]['total'] += ($addon->price * $addon->qty);
                        } else {
                            // Otherwise, add a new entry
                            $optionValues[] = [
                                'optionPrice' => $addon->price,
                                'label' => $addon->name,
                                'foodname' => ucfirst($addon->name).' (addons)',
                                'restaurant_name' => '' ,
                                'quantity' => $addon->qty,
                                'total' => ($addon->price * $addon->qty),
                            ];
                        }
                        $addonDetails[$index]['values'] = $optionValues;
                    }


                    // dd($addonDetails);
                }

            }
        }

        $mergedDetails = array_merge($nonVariationDetails, $variationDetails, $addonDetails);
        $sumtotal=$sumquantity=$sumcost = 0;
        foreach($mergedDetails as $details){
            foreach($details['values'] as $d_value){
                $sumcost += (float) $d_value['optionPrice'];
                $sumquantity += (int) $d_value['quantity'];
                $sumtotal += (float) $d_value['total'];
                $productItems[] = $d_value;
            }
        }

        return ['productItems'=> $productItems, 'sumtotal' => $sumtotal, 'sumquantity' => $sumquantity, 'sumcost' =>$sumcost  ];
    }
}
