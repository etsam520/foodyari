<?php

namespace App\CentralLogics;

use App\Models\Admin;
use App\Models\AdminToRestaurantSubscriptonPackage;
use App\Models\BusinessSetting;
use App\Models\Category;
use App\Models\Customer;
use App\Models\DeliveryMan;
use App\Models\Food;
use App\Models\GuestSession;
use App\Models\Order;
use App\Models\OrderTransaction;
use App\Models\Restaurant;
use App\Models\RestaurantSubscription;
use App\Models\User;
use App\Models\UserPassKey;
use App\Models\Vendor;
use App\Models\Wallet;
use App\Models\Zone;
use App\Models\ZoneBusinessSetting;
use App\Notifications\FoodOrderNotification;
use App\Services\JsonDataService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use DateTime;
use Error;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Calculation\Token\Stack;
use Razorpay\Api\Product;

use function PHPUnit\Framework\fileExists;
use function Symfony\Component\String\s;

class Helpers
{
    public static function getBusinessPhone() {
        $phone = BusinessSetting::where('key', 'business_phone')->first();
        return $phone ? $phone->value : '9155289998';
    }

    public static function merchant_txn_id($gateway = 'G', $type = "O")
    {
        return "FY-$gateway-" . rand(000000, 999999) . "-$type";
    }
    // public static function get_business_settings($key, $default = null)
    // {App\CentralLogics\Helpers::getService();
    //     // Your logic to retrieve business settings
    //     // Example logic here:
    //     return 'Business Settings for ' . $key;
    // }

    public static function uploadFile(UploadedFile $file, string $directory)
    {
        if ($file->isValid()) {
            $fileName = Carbon::now()->format('dmY_His') . '-' . uniqid() . '-' . $file->getClientOriginalName();
            $file->move(public_path($directory), $fileName);
            return $fileName;
        }
        return null;
    }
    public static function getUploadFile($path, $type)
    {
        $types = [
            'banner' => 'banner',
            'marquee' => 'marquee',
            'restaurant' => 'restaurant',
            'product' => 'product',
            "restaurant-cover" => "restaurant/cover/",
            'deliveryman' => 'delivery-man'
        ];
        // return 'anc';
        $default = '';
        if ($types[$type] == 'banner') {
            $default = asset('assets/images/icons/banner.jpg');
        } else if ($types[$type] == 'restaurant') {
            $default = asset('assets/images/icons/food-default-image.png');
        } else if ($types[$type] == 'product') {
            $default = asset('assets/images/icons/food-default-image.png');
        } else if ($types[$type] == "restaurant-cover") {
            $default = asset('assets/images/icons/food-default-image.png');
        } else if ($types[$type] == "marquee") {
            $default = asset('assets/images/icons/banner.jpg');
        } else if ($types[$type] == "delivery-man") {
            $default = asset('assets/user/img/user2.png');
        }

        if (!array_key_exists($type, $types)) {
            return $default;
        }
        $images = asset($types[$type] . "/$path");

        if ($path == null || !file_exists(public_path($types[$type] . "/$path"))) {
            return $default;
        }
        return $images;
    }

    public static function updateFile(UploadedFile $file, string $directory, $old = null)
    {
        if ($file == null) {
            return $old;
        }
        if ($old != null && !empty($old) && file_exists(public_path("$directory/$old"))) {
            unlink(public_path("$directory/$old"));
        }
        return Helpers::uploadFile($file, $directory);
    }

    public static function deleteFile(string $directory, $old = null)
    {

        if ($old != null && !empty($old) && file_exists(public_path("$directory/$old"))) {
            return unlink(public_path("$directory/$old"));
        }
        return true;
    }

    public static function calculateAverageDeliveryTime($minimum_delivery_time, $maximum_delivery_time)
    {
        // Parse the minimum and maximum delivery times
        $minDeliveryTime = Carbon::parse($minimum_delivery_time);
        $maxDeliveryTime = Carbon::parse($maximum_delivery_time);

        // Convert the delivery times into total seconds
        $minSeconds = $minDeliveryTime->hour * 3600 + $minDeliveryTime->minute * 60 + $minDeliveryTime->second;
        $maxSeconds = $maxDeliveryTime->hour * 3600 + $maxDeliveryTime->minute * 60 + $maxDeliveryTime->second;

        // Calculate the average delivery time in seconds
        $averageDeliveryTime = ($minSeconds + $maxSeconds) / 2;

        return $averageDeliveryTime;  // Return the average time in seconds
    }

    public static function calculateTimeDifference($created_at, $averageDeliveryTime)
    {
        // Parse the created_at timestamp
        $createdAt = Carbon::parse($created_at);

        // Calculate the estimated delivery time by adding the average delivery time in seconds to the created_at timestamp
        $estimatedDeliveryTime = $createdAt->addSeconds($averageDeliveryTime);

        // Calculate the difference between the current time and the estimated delivery time in seconds
        $timeDifferenceInSeconds = Carbon::now()->diffInSeconds($estimatedDeliveryTime, false);

        return $timeDifferenceInSeconds;
    }





    public static function generateExcel($excelHeader, $data, $filename)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Write headers to the first row
        $sheet->fromArray($excelHeader, null, 'A1');

        // Write data rows
        $sheet->fromArray($data, null, 'A2');

        $writer = new Xlsx($spreadsheet);

        $response = new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        });

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        // Use double quotes and interpolate the variable correctly
        $response->headers->set('Content-Disposition', "attachment; filename=\"$filename\".xlsx");
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }
    public static function slugToString($slug, $separator = '-')
    {
        // Replace the separator with a space
        $string = str_replace($separator, ' ', $slug);

        // Capitalize each word
        return Str::title($string);
    }




    public static function product_discount_calculate($product, $price, $restaurant)
    {
        $restaurant_discount = self::get_restaurant_discount($restaurant);
        if (isset($restaurant_discount)) {
            $price_discount = ($price / 100) * $restaurant_discount['discount'];
        } else if ($product['discount_type'] == 'percent') {
            $price_discount = ($price / 100) * $product['discount'];
        } else {
            $price_discount = $product['discount'];
        }
        return $price_discount;
    }

    public static function order_status_update_message($status, $zoneId)
    {
        if ($status == 'pending') {
            $data = ZoneBusinessSetting::getSettingValue('admin_order_pending_message', $zoneId);
        } elseif ($status == 'confirmed') {
            $data = ZoneBusinessSetting::getSettingValue('admin_order_confirmed_message', $zoneId);
        } elseif ($status == 'processing') {
            $data = ZoneBusinessSetting::getSettingValue('admin_order_processing_message', $zoneId);
        } elseif ($status == 'picked_up') {
            $data = ZoneBusinessSetting::getSettingValue('admin_order_picked_up_message', $zoneId);
        } elseif ($status == 'handover') {
            $data = ZoneBusinessSetting::getSettingValue('admin_order_handovered_message', $zoneId);
        } elseif ($status == 'delivered') {
            $data = ZoneBusinessSetting::getSettingValue('admin_order_delivered_message', $zoneId);
        } elseif ($status == 'delivery_boy_delivered') {
            $data = ZoneBusinessSetting::getSettingValue('dm_order_delivered_message', $zoneId);
        } elseif ($status == 'accepted') {
            $data = ZoneBusinessSetting::getSettingValue('admin_order_accepted_message', $zoneId);
        } elseif ($status == 'canceled') {
            $data = ZoneBusinessSetting::getSettingValue('admin_order_cancel_message', $zoneId);
        } elseif ($status == 'refunded') {
            $data = ZoneBusinessSetting::getSettingValue('admin_order_refund_response_message', $zoneId);
        } elseif ($status == 'refund_request_canceled') {
            $data = "Refund Request Canceled";
        } else {
            $data = '{"status":"0","message":""}';
        }

        $res = json_decode($data, true);

        if ($res['status'] == 0) {
            return 0;
        }
        return $res['message'];
    }


    public static function get_restaurant_discount($restaurant)
    {
        //dd($restaurant);
        if ($restaurant->discount) {
            if (date('Y-m-d', strtotime($restaurant->discount->start_date)) <= now()->format('Y-m-d') && date('Y-m-d', strtotime($restaurant->discount->end_date)) >= now()->format('Y-m-d') && date('H:i', strtotime($restaurant->discount->start_time)) <= now()->format('H:i') && date('H:i', strtotime($restaurant->discount->end_time)) >= now()->format('H:i')) {
                return [
                    'discount' => $restaurant->discount->discount,
                    'min_purchase' => $restaurant->discount->min_purchase,
                    'max_discount' => $restaurant->discount->max_discount
                ];
            }
        }
        return null;
    }

    public static function getCategoriesByzonesHavingAtLeastOneProduct($zoneId){
       $categores = Category::select('id','name')
       ->whereExists(function ($query) use ($zoneId) {
           $query->select(DB::raw(1))
               ->from('food')
               ->join('restaurants', 'restaurants.id', '=', 'food.restaurant_id')
               ->whereColumn('food.category_id', 'categories.id')
               ->where('restaurants.zone_id', $zoneId); 
       });

       dd($categores);

       return $categores->get();


        // SELECT DISTINCT c.id, c.name
        // FROM categories AS c
        // WHERE EXISTS (
        //     SELECT 1
        //     FROM food AS f
        //     JOIN restaurants AS r ON r.id = f.restaurant_id
        //     WHERE f.category_id = c.id
        //     AND r.zone_id = 2
        // );
        
    }




    public static function get_restaurant_id()
    {
        return Session::get('restaurant')->id;
    }

    public static function get_vendor_id()
    {
        if (auth('vendor')->check()) {
            return auth('vendor')->id();
        } else if (auth('vendor_employee')->check()) {
            return auth('vendor_employee')->user()->vendor_id;
        }
        return 0;
    }

    public static function get_vendor_data()
    {
        if (auth('vendor')->check()) {
            return auth('vendor')->user();
        } else if (auth('vendor_employee')->check()) {
            return auth('vendor_employee')->user()->vendor;
        }
        return 0;
    }

    public static function get_loggedin_user()
    {
        if (auth('vendor')->check()) {
            return auth('vendor')->user();
        } else if (auth('vendor_employee')->check()) {
            return auth('vendor_employee')->user();
        }
        return 0;
    }

    public static function get_restaurant_data()
    {
        // if (auth('vendor_employee')->check()) {
        //     return auth('vendor_employee')->user()->restaurant;
        // }
        // return auth('vendor')->user()->restaurants[0];
        return Session::get('restaurant');
    }


    public static function currency_symbol()
    {
        $symbol = "₹";
        return $symbol;
    }
    public static function format_currency($value = null, $precesion = true)
    {
        if ($precesion) {
            return self::currency_symbol() . " " . number_format($value, 2);
        } else {
            return self::currency_symbol() . " " . floor($value);
        }
    }
    public static  function timeAgo($timestamp)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $timestamp)->diffForHumans();
    }
    public static function format_time($value)
    {
        return Carbon::parse($value)->format('h:i A');
    }

    public static function format_date($date)
    {
        return Carbon::createFromTimestamp(strtotime($date))->format('d M y');
    }
    public static function parseDateToFormat($dateString, $inputFormat = 'd-m-Y', $outputFormat = 'Y-m-d') {
        $date = DateTime::createFromFormat($inputFormat, $dateString);
        return $date ? $date->format($outputFormat) : null;
    }

    public static function format_day($date)
    {
        return Carbon::createFromTimestamp(strtotime($date))->format('l');
    }
    public static function format_floatToInt($floatNumber)
    {
        return number_format($floatNumber, 0, '.', '');
    }

    public static function flat_discount($price, $discount)
    {
        return (int) $price - (int) $discount;
    }
    public static function percent_discount($price, $discount)
    {
        return $price - (($price * $discount) / 100);
    }
    public static function food_discount($price, $discount, $d_type = 'amount')
    {
        $price = (float) $price;
        $discount = (float) $discount;

        if ($d_type === 'percent') {
            $discountedPrice = $price - ($price * $discount / 100);
        } else {
            $discountedPrice = $price - $discount;
        }

        return round($discountedPrice, 2);
    }

    public static function tax_calculate($food, $price)
    {
        if ($food['tax_type'] == 'percent') {
            $price_tax = ($price / 100) * $food['tax'];
        } else {
            $price_tax = $food['tax'];
        }
        return $price_tax;
    }

    public static function product_tax($price, $tax, $is_include = false)
    {
        $price_tax = ($price * $tax) / (100 + ($is_include ? $tax : 0));
        return $price_tax;
    }


    public static function  getNextDay($date)
    {
        $dateTime = new DateTime($date);

        // $lastDayOfMonth = (int) $dateTime->format('t');// Get the last day of the month for the given date

        // $day = (int) $dateTime->format('d');// Get the day of the current date

        // If the current day is the last day of the month
        // if ($day == $lastDayOfMonth) {
        //     $dateTime->modify('next month')->modify('first day of');// Add 1 day and set it to the first day of the next month
        // } else {
        //     $dateTime->modify('+1 day');// Add 1 day
        // }

        // Return the formatted date of the next day
        $dateTime->modify('+1 day'); // Add 1 day
        return $dateTime->format('Y-m-d');
    }

    public static function error_processor($validator)
    {
        $err_keeper = [];
        foreach ($validator->errors()->getMessages() as $index => $error) {
            array_push($err_keeper, ['code' => $index, 'message' => $error[0]]);
        }
        return $err_keeper;
    }
    public static function error_list($validator)
    {
        $err_keeper = [];
        foreach ($validator->errors()->getMessages() as $index => $error) {
            array_push($err_keeper,  $error);
        }
        return $err_keeper;
    }

    public static function deliverymen_list_formatting($data)
    {

        $storage = [];
        foreach ($data as $item) {
            $dmData = new JsonDataService($item->id);
            $dmData = $dmData->readData();
            // dd($dmData);
            if ($dmData->active) {
                $storage[] = [
                    'id' => $item['id'],
                    'name' => $item['f_name'] . ' ' . $item['l_name'],
                    'image' => $item['image'],
                    'lat' => $dmData->last_location ? $dmData->last_location['lat'] : false,
                    'lng' => $dmData->last_location ? $dmData->last_location['lng'] : false,
                    'last_time' => $dmData->updated_at ?? null,
                    // 'location' => $item->last_location ? $item->last_location->location : '',
                ];
            }
        }
        $data = $storage;

        return $data;
    }

    public static function adminDeliveryMan($zoneId)
    {
        return DeliveryMan::where('zone_id', $zoneId)
            ->where('type', 'admin')->get();
    }
    public static function messDeliveryMan($zoneId)
    {
        return DeliveryMan::where('zone_id', $zoneId)
            ->where('type', 'mess')->get();
    }

    public static function error_formater($key, $mesage, $errors = [])
    {
        $errors[] = ['code' => $key, 'message' => $mesage];

        return $errors;
    }

    public static function hex_to_rbg($color)
    {
        list($r, $g, $b) = sscanf($color, "#%02x%02x%02x");
        $output = "$r, $g, $b";
        return $output;
    }

    public static function get_business_settings($name, $json_decode = true)
    {
        $config = null;

        $paymentmethod = BusinessSetting::where('key', $name)->first();

        if ($paymentmethod) {
            $config = $json_decode ? json_decode($paymentmethod->value, true) : $paymentmethod->value;
        }

        return $config;
    }

    public static function react_activation_check($react_domain, $react_license_code)
    {
        $scheme = str_contains($react_domain, 'localhost') ? 'http://' : 'https://';
        $url = empty(parse_url($react_domain)['scheme']) ? $scheme . ltrim($react_domain, '/') : $react_domain;
        $response = Http::post('https://store.6amtech.com/api/v1/customer/license-check', [
            'domain_name' => str_ireplace('www.', '', parse_url($url, PHP_URL_HOST)),
            'license_code' => $react_license_code
        ]);
        return ($response->successful() && isset($response->json('content')['is_active']) && $response->json('content')['is_active']);
    }


    public static function activation_submit($purchase_key)
    {
        $post = [
            'purchase_key' => $purchase_key
        ];
        $live = 'https://check.6amtech.com';
        $ch = curl_init($live . '/api/v1/software-check');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $response = curl_exec($ch);
        curl_close($ch);
        $response_body = json_decode($response, true);

        try {
            if ($response_body['is_valid'] && $response_body['result']['item']['id'] == env('REACT_APP_KEY')) {
                $previous_active = json_decode(BusinessSetting::where('key', 'app_activation')->first()->value ?? '[]');
                $found = 0;
                foreach ($previous_active as $key => $item) {
                    if ($item->software_id == env('REACT_APP_KEY')) {
                        $found = 1;
                    }
                }
                if (!$found) {
                    $previous_active[] = [
                        'software_id' => env('REACT_APP_KEY'),
                        'is_active' => 1
                    ];
                    DB::table('business_settings')->updateOrInsert(['key' => 'app_activation'], [
                        'value' => json_encode($previous_active)
                    ]);
                }
                return true;
            }
        } catch (\Exception $exception) {
            info($exception->getMessage());
        }
        return false;
    }

    public static function react_domain_status_check()
    {
        $data = self::get_business_settings('react_setup');
        if ($data && isset($data['react_domain']) && isset($data['react_license_code'])) {
            if (isset($data['react_platform']) && $data['react_platform'] == 'codecanyon') {
                $data['status'] = (int)self::activation_submit($data['react_license_code']);
            } elseif (!self::react_activation_check($data['react_domain'], $data['react_license_code'])) {
                $data['status'] = 0;
            } elseif ($data['status'] != 1) {
                $data['status'] = 1;
            }
            DB::table('business_settings')->updateOrInsert(['key' => 'react_setup'], [
                'value' => json_encode($data)
            ]);
        }
    }

    public static function insert_business_settings_key($key, $value = null)
    {
        $data =  BusinessSetting::where('key', $key)->first();
        if (!$data) {
            DB::table('business_settings')->updateOrInsert(['key' => $key], [
                'value' => $value,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        return true;
    }
    public static function mess_addon_price_sum($addons, $symbol = null)
    {
        // App\CentralLogics\Helpers::mess_addon_price_sum();
        $sum = 0;
        foreach ($addons as $key => $value):
            $sum += ((int) $value['quantity'] * (int) $value['price']);
        endforeach;
        if ($symbol == "NO_SYMBOL") {
            return $sum;
        } else {
            return Helpers::format_currency($sum);
        }
    }

    public static function getService($key = null)
    {
        $arr = ['B' => 'breakfast', 'L' => 'lunch', 'D' => 'dinner'];
        if (!empty($key)) {
            if ($key == "B" || $key == "L" || $key == "D") {
                return $arr[$key];
            } else {
                return null;
            }
        } else {
            return $arr;
        }
    }

    public static function getFoodType($key = null)
    {
        $arr = ['V' => 'veg', 'N' => 'non veg', 'B' => 'both'];
        if (!empty($key)) {
            if ($key == "V" || $key == "N" || $key == "B") {
                return $arr[$key];
            } else {
                return null;
            }
        } else {
            return $arr;
        }
    }
    public static function getSpeciality($key = null)
    {
        $arr = ['N' => 'normal', 'S' => 'special', 'O' => 'off'];
        if (!empty($key)) {
            if ($key == "N" || $key == "S" || $key == "O") {
                return $arr[$key];
            } else {
                return null;
            }
        } else {
            return $arr;
        }
    }
    public static function getDayname($key = null)
    {
        $arr = [
            'Mon' => 'monday',
            'Tue' => 'tuesday',
            'Wed' => 'wednesday',
            'Thu' => 'thursday',
            'Fri' => 'friday',
            'Sat' => 'saturday',
            'Sun' => 'sunday'
        ];
        if (!empty($key)) {
            if ($key == "Mon" || $key == "Tue" || $key == 'Wed' || $key == 'Thu' || $key == 'Fri' || $key == 'Sat' || $key == 'Sun') {
                return $arr[$key];
            } else {
                return null;
            }
        } else {
            return $arr;
        }
    }

    public static function timeStringToMinutes($timeString)
    {
        $timeParts = explode(':', $timeString);
        $hoursInMinutes = intval($timeParts[0]) * 60;
        $minutes = intval($timeParts[1]);
        $seconds = intval($timeParts[2]);
        return $hoursInMinutes + $minutes + round($seconds / 60);
    }



    public static function formatDistance($distance)
    {
        $distanceInMeters = $distance * 1000;

        if ($distanceInMeters < 1000) {
            return round($distanceInMeters) . ' meters';
        }

        $kilometers = $distanceInMeters / 1000;

        return number_format($kilometers, 2) . ' km';
    }

    public static function haversineDistance($point1, $point2)
    {
        $earthRadius = 6371; // Radius of the earth in kilometers

        $lat1 = $point1['lat'];
        $lon1 = $point1['lon'];
        $lat2 = $point2['lat'];
        $lon2 = $point2['lon'];

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c; // Distance in kilometers

        return $distance;
    }




    public static function remainingTime($openingTime, $closingTime)
    {
        $current = new DateTime();  // Get the current time
        $opening = new DateTime($openingTime);  // Parse opening time
        $closing = new DateTime($closingTime);  // Parse closing time
        $data = [];
        $data['closingDifferance'] = $closing->diff($current);
        $data['openingDifferance'] = $opening->diff($current);

        // Check if the restaurant is currently open
        if ($current >= $opening && $current < $closing) {
            $data['isClosed'] = false;  // Restaurant is open

            // Calculate the time difference between closing time and current time
            $difference = $closing->diff($current);


            // Format the remaining time until the restaurant closes
            if ($difference->h == 0 && $difference->i == 0) {
                $data['format'] = 'Closing soon <span class="text-warning">Left</span>';
            } elseif ($difference->h < 2 && $difference->h > 0) {
                $data['format'] = sprintf('%d Hrs : %d Min <span class="text-warning">Left</span>', $difference->h, $difference->i);
            } elseif ($difference->h < 1) {
                $data['format'] = sprintf('%d Min <span class="text-warning">Left</span>', $difference->i);
            } else {
                $data['format'] = $closing->format('h:i A');  // Show the closing time
            }
        } else {
            $data['isClosed'] = true;  // Restaurant is closed

            // Check if the current time is before opening time or after closing time
            if ($current >= $closing) {
                // Restaurant will open the next day
                $nextOpening = clone $opening;
                $nextOpening->modify('+1 day');
                $difference = $current->diff($nextOpening);
            } else {
                // Restaurant will open later today
                $difference = $current->diff($opening);
            }


            // Format the time remaining until the restaurant opens
            if ($difference->h == 0 && $difference->i == 0) {
                $data['format'] = 'Opening soon <span class="text-warning">Left</span>';
            } elseif ($difference->h < 2 && $difference->h > 0) {
                $data['format'] = sprintf('%d Hrs : %d Min <span class="text-warning">Left</span>', $difference->h, $difference->i);
            } elseif ($difference->h < 1) {
                $data['format'] = sprintf('%d Min <span class="text-warning">Left</span>', $difference->i);
            } else {
                $data['format'] = $opening->format('h:i A');  // Show the opening time
            }
        }

        return $data;
    }



    public static function isClosed($openingTime, $closingTime)
    {
        $current = Carbon::now();
        $opening = Carbon::parse($openingTime);
        $closing = Carbon::parse($closingTime);
        $data = [];

        // Check if the current time is outside the opening and closing times
        if ($current->lessThan($opening) || $current->greaterThanOrEqualTo($closing)) {
            // Restaurant is closed
            $data['isClosed'] = true;
        } else {
            // Restaurant is open
            $data['isClosed'] = false;
        }

        return $data;
    }




    public static function splitStringToArray($input_string, $regex = "/[,: ]+/")
    {
        $words = preg_split($regex, $input_string);
        $words = array_filter($words);
        $words = array_values($words);
        return $words;
    }

    public static function getDateAfterDays($days, $startDate = null)
    {

        if (is_null($startDate)) {
            $startDate = Carbon::now();
        } else {

            $startDate = Carbon::parse($startDate);
        }

        return $startDate->addDays($days)->toDateString();
    }

    public static function daysUntilExpiry($expiryDate)
    {
        $expiryDate = Carbon::parse($expiryDate);
        $today = Carbon::today();
        return $today->diffInDays($expiryDate);
    }

    public static function getRandomFood($foods)
    {
        if (empty($foods)) {
            return null;
        }
        return $foods[array_rand($foods)];
    }

    // public static function send_order_notification($order)
    // {
    //     $order= Order::where('id',$order->id)->with('zone:id,deliveryman_wise_topic','restaurant:id,restaurant_model,self_delivery_system,vendor_id','restaurant.restaurant_sub','customer:id,cm_firebase_token,email','restaurant.vendor:id,firebase_token','delivery_man:id,fcm_token')->first();

    //     try {
    //         $status = ($order->order_status == 'delivered' && $order->delivery_man) ? 'delivery_boy_delivered' : $order->order_status;
    //         if($order->order_status=='confirmed' && $order->payment_method != 'cash_on_delivery' && $order->restaurant->restaurant_model == 'subscription' && isset($order->restaurant->restaurant_sub)){
    //             if ($order->restaurant->restaurant_sub->max_order != "unlimited" && $order->restaurant->restaurant_sub->max_order > 0 ) {
    //                 $order->restaurant->restaurant_sub()->decrement('max_order' , 1);
    //             }
    //         }

    //         if($order->subscription_id == null &&  ($order->payment_method == 'cash_on_delivery' && $order->order_status == 'pending' )||($order->payment_method != 'cash_on_delivery' && $order->order_status == 'confirmed' )){
    //             $data = [
    //                 'title' => translate('messages.order_push_title'),
    //                 'description' => translate('messages.new_order_push_description'),
    //                 'order_id' => $order->id,
    //                 'image' => '',
    //                 'type' => 'new_order_admin',
    //             ];
    //             self::send_push_notif_to_topic($data, 'admin_message', 'order_request', url('/').'/admin/order/list/all');
    //         }

    //         $value = self::order_status_update_message($status);
    //         if ($value && $order->customer) {
    //             $data = [
    //                 'title' => translate('messages.order_push_title'),
    //                 'description' => $value,
    //                 'order_id' => $order->id,
    //                 'image' => '',
    //                 'type' => 'order_status',
    //             ];
    //             self::send_push_notif_to_device($order->customer->cm_firebase_token, $data);
    //             DB::table('user_notifications')->insert([
    //                 'data' => json_encode($data),
    //                 'user_id' => $order->user_id,
    //                 'created_at' => now(),
    //                 'updated_at' => now()
    //             ]);
    //         }

    //         if($order->customer && $order->order_status == 'refund_request_canceled'){
    //             $data = [
    //                 'title' => translate('messages.order_push_title'),
    //                 'description' => translate('messages.Your_refund_request_has_been_canceled'),
    //                 'order_id' => $order->id,
    //                 'image' => '',
    //                 'type' => 'order_status',
    //             ];
    //             self::send_push_notif_to_device($order->customer->cm_firebase_token, $data);
    //             DB::table('user_notifications')->insert([
    //                 'data' => json_encode($data),
    //                 'user_id' => $order->user_id,
    //                 'created_at' => now(),
    //                 'updated_at' => now()
    //             ]);
    //         }

    //         if ($status == 'picked_up') {
    //             $data = [
    //                 'title' => translate('messages.order_push_title'),
    //                 'description' => $value,
    //                 'order_id' => $order->id,
    //                 'image' => '',
    //                 'type' => 'order_status',
    //             ];
    //             self::send_push_notif_to_device($order->restaurant->vendor->firebase_token, $data);
    //             DB::table('user_notifications')->insert([
    //                 'data' => json_encode($data),
    //                 'vendor_id' => $order->restaurant->vendor_id,
    //                 'created_at' => now(),
    //                 'updated_at' => now()
    //             ]);
    //         }

    //         if ($order->order_type == 'delivery' && !$order->scheduled && $order->order_status == 'pending' && $order->payment_method == 'cash_on_delivery' && config('order_confirmation_model') == 'deliveryman' && $order->order_type != 'take_away') {
    //             // if ($order->restaurant->self_delivery_system)
    //             if (($order->restaurant->restaurant_model == 'commission' && $order->restaurant->self_delivery_system)
    //             || ($order->restaurant->restaurant_model == 'subscription' &&  isset($order->restaurant->restaurant_sub) && $order->restaurant->restaurant_sub->self_delivery)
    //             )
    //             {
    //                 $data = [
    //                     'title' => translate('messages.order_push_title'),
    //                     'description' => translate('messages.new_order_push_description'),
    //                     'order_id' => $order->id,
    //                     'image' => '',
    //                     'type' => 'new_order',
    //                 ];
    //                 self::send_push_notif_to_device($order->restaurant->vendor->firebase_token, $data);
    //                 DB::table('user_notifications')->insert([
    //                     'data' => json_encode($data),
    //                     'vendor_id' => $order->restaurant->vendor_id,
    //                     'created_at' => now(),
    //                     'updated_at' => now()
    //                 ]);
    //                 $web_push_link = url('/').'/restaurant-panel/order/list/all';
    //                 self::send_push_notif_to_topic($data, "restaurant_panel_{$order->restaurant_id}_message", 'new_order', $web_push_link);
    //             } else {
    //                 $data = [
    //                     'title' => translate('messages.order_push_title'),
    //                     'description' => translate('messages.new_order_push_description'),
    //                     'order_id' => $order->id,
    //                     'image' => '',
    //                 ];

    //                 if($order->zone){
    //                     if($order->vehicle_id){
    //                         $topic = 'delivery_man_'.$order->zone_id.'_'.$order->vehicle_id;
    //                         self::send_push_notif_to_topic($data, $topic, 'order_request');
    //                     }
    //                     self::send_push_notif_to_topic($data, $order->zone->deliveryman_wise_topic, 'order_request');
    //                 }
    //             }
    //         }

    //         if ($order->order_type == 'delivery' && !$order->scheduled && $order->order_status == 'pending' && $order->payment_method == 'cash_on_delivery' && config('order_confirmation_model') == 'restaurant') {
    //             $data = [
    //                 'title' => translate('messages.order_push_title'),
    //                 'description' => translate('messages.new_order_push_description'),
    //                 'order_id' => $order->id,
    //                 'image' => '',
    //                 'type' => 'new_order',
    //             ];
    //             self::send_push_notif_to_device($order->restaurant->vendor->firebase_token, $data);
    //             DB::table('user_notifications')->insert([
    //                 'data' => json_encode($data),
    //                 'vendor_id' => $order->restaurant->vendor_id,
    //                 'created_at' => now(),
    //                 'updated_at' => now()
    //             ]);
    //             $web_push_link = url('/').'/restaurant-panel/order/list/all';
    //             self::send_push_notif_to_topic($data, "restaurant_panel_{$order->restaurant_id}_message", 'new_order', $web_push_link);
    //         }

    //         if (!$order->scheduled && (($order->order_type == 'take_away' && $order->order_status == 'pending') || ($order->payment_method != 'cash_on_delivery' && $order->order_status == 'confirmed'))) {
    //             $data = [
    //                 'title' => translate('messages.order_push_title'),
    //                 'description' => translate('messages.new_order_push_description'),
    //                 'order_id' => $order->id,
    //                 'image' => '',
    //                 'type' => 'new_order',
    //             ];
    //             self::send_push_notif_to_device($order->restaurant->vendor->firebase_token, $data);

    //             DB::table('user_notifications')->insert([
    //                 'data' => json_encode($data),
    //                 'vendor_id' => $order->restaurant->vendor_id,
    //                 'created_at' => now(),
    //                 'updated_at' => now()
    //             ]);
    //             $web_push_link = url('/').'/restaurant-panel/order/list/all';
    //             self::send_push_notif_to_topic($data, "restaurant_panel_{$order->restaurant->id}_message", 'new_order', $web_push_link);
    //         }

    //         if ($order->order_status == 'confirmed' && $order->order_type != 'take_away' && config('order_confirmation_model') == 'deliveryman' && $order->payment_method == 'cash_on_delivery') {
    //             if ($order->restaurant->restaurant_model == 'commission' && $order->restaurant->self_delivery_system
    //             || ($order->restaurant->restaurant_model == 'subscription' &&  isset($order->restaurant->restaurant_sub) && $order->restaurant->restaurant_sub->self_delivery)
    //             ) {
    //                 $data = [
    //                     'title' => translate('messages.order_push_title'),
    //                     'description' => translate('messages.new_order_push_description'),
    //                     'order_id' => $order->id,
    //                     'image' => '',
    //                 ];

    //                 self::send_push_notif_to_topic($data, "restaurant_dm_" . $order->restaurant_id, 'new_order');
    //             } else {
    //                 $data = [
    //                     'title' => translate('messages.order_push_title'),
    //                     'description' => translate('messages.new_order_push_description'),
    //                     'order_id' => $order->id,
    //                     'image' => '',
    //                     'type' => 'new_order',
    //                 ];

    //                 self::send_push_notif_to_device($order->restaurant->vendor->firebase_token, $data);

    //                 DB::table('user_notifications')->insert([
    //                     'data' => json_encode($data),
    //                     'vendor_id' => $order->restaurant->vendor_id,
    //                     'created_at' => now(),
    //                     'updated_at' => now()
    //                 ]);
    //                 $web_push_link = url('/').'/restaurant-panel/order/list/all';
    //                 self::send_push_notif_to_topic($data, "restaurant_panel_{$order->restaurant_id}_message", 'new_order', $web_push_link);
    //             }
    //         }

    //         if ($order->order_type == 'delivery' && !$order->scheduled && $order->order_status == 'confirmed'  && ($order->payment_method != 'cash_on_delivery' || config('order_confirmation_model') == 'restaurant')) {
    //             $data = [
    //                 'title' => translate('messages.order_push_title'),
    //                 'description' => translate('messages.new_order_push_description'),
    //                 'order_id' => $order->id,
    //                 'image' => '',
    //             ];
    //             if (($order->restaurant->restaurant_model == 'commission' && $order->restaurant->self_delivery_system)
    //             || ($order->restaurant->restaurant_model == 'subscription' &&  isset($order->restaurant->restaurant_sub) && $order->restaurant->restaurant_sub->self_delivery)
    //             )
    //             {
    //                 self::send_push_notif_to_topic($data, "restaurant_dm_" . $order->restaurant_id, 'order_request');
    //             } else {
    //                 if($order->zone){
    //                     if($order->vehicle_id){
    //                         $topic = 'delivery_man_'.$order->zone_id.'_'.$order->vehicle_id;
    //                         self::send_push_notif_to_topic($data, $topic, 'order_request');
    //                     }
    //                     self::send_push_notif_to_topic($data, $order->zone->deliveryman_wise_topic, 'order_request');
    //                 }
    //             }
    //         }

    //         if (in_array($order->order_status, ['processing', 'handover']) && $order->delivery_man) {
    //             $data = [
    //                 'title' => translate('messages.order_push_title'),
    //                 'description' => $order->order_status == 'processing' ? translate('messages.Proceed_for_cooking') : translate('messages.ready_for_delivery'),
    //                 'order_id' => $order->id,
    //                 'image' => '',
    //                 'type' => 'order_status'
    //             ];
    //             self::send_push_notif_to_device($order->delivery_man->fcm_token, $data);
    //             DB::table('user_notifications')->insert([
    //                 'data' => json_encode($data),
    //                 'delivery_man_id' => $order->delivery_man->id,
    //                 'created_at' => now(),
    //                 'updated_at' => now()
    //             ]);
    //         }

    //         try {
    //             if ($order->order_status == 'confirmed' && $order->payment_method != 'cash_on_delivery' && config('mail.status')) {
    //                 Mail::to($order->customer->email)->send(new OrderPlaced($order->id));
    //             }
    //             if($order->order_status == 'refund_request_canceled' && config('mail.status')){
    //                 Mail::to($order->customer->email)->send(new RefundRejected($order->id));
    //             }
    //         } catch (\Exception $ex) {
    //             info($ex);
    //         }
    //         return true;
    //     } catch (\Exception $e) {
    //         info($e);
    //     }
    //     return false;
    // }

    public static function module_permission_check($modul)
    {

        return true;
    }
    public static function module_restaurant_permission_check($module, $next)
    {

        if ($module == "active") {
            $restaurant = Restaurant::find(Session::get('restaurant')->id);
            if (empty($restaurant->subscription_type)) {
                // dd(empty($restaurant->subscription_type));
                $subscription = AdminToRestaurantSubscriptonPackage::latest()->get();
                return response()->view('vendor-views.Activation.index', compact('restaurant', 'subscription'));
            } elseif ($restaurant->subscription_type == "subscription") {
                $subscribed = RestaurantSubscription::where('restaurant_id', $restaurant->id)->latest()->limit(1)->first();
                $package = AdminToRestaurantSubscriptonPackage::with(['transactions' => function ($query) {
                    return $query->latest()->limit(1);
                }])->find($subscribed->package_id);
            }
        } 
        return $next;
    }
    public static function module_mess_permission_check($modul)
    {
        return true;
    }



    public static function send_dlt_sms($otp, $numbers)
    {
        // $apiKey = env('FAST2SMS_API_KEY');
        // Log::info($apiKey);


        $fields = array(
            "sender_id" => "RYROOM",
            "message" => "159762",
            "variables_values" => "$otp",
            "route" => "dlt",
            "numbers" => $numbers
        );

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://www.fast2sms.com/dev/bulkV2",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($fields),
            CURLOPT_HTTPHEADER => array(
                "authorization: zJDjuDR241G1D43WaIDIZ6EeiBrxszE0vHoZGsrNilGZ4JdE6ge6H8YI90C0",
                "accept: */*",
                "cache-control: no-cache",
                "content-type: application/json"
            ),
        ));


        $response = curl_exec($curl);
        $err = curl_error($curl);
        if ($err) {
            Log::error("fastTOsms" . $err);
        }
        curl_close($curl);
        Log::info(json_encode($response));

        return $response;
    }

    function send_sms($phone = '', $sms = '')
    {
        $apiKey = env('FAST2SMS_API_KEY');
        $fields = array(
            "sender_id" => "TXTIND",
            "message" => $sms,
            "route" => "v3",
            "numbers" => $phone,
        );
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://www.fast2sms.com/dev/bulkV2",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($fields),
            CURLOPT_HTTPHEADER => array(
                "authorization: " . $apiKey,
                "accept: */*",
                "cache-control: no-cache",
                "content-type: application/json"
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return "failed to send otp";
        } else {
            return 'success';
        }
    }

    public static function send_notification($fcm_token, $data, $web_push_link = null)
    {
        $key = BusinessSetting::where(['key' => 'push_notification_key'])->first()->value;
        $url = "https://fcm.googleapis.com/fcm/send";
        $header = array(
            "authorization: key=" . $key . "",
            "content-type: application/json"
        );

        if (isset($data['conversation_id'])) {
            $conversation_id = $data['conversation_id'];
        } else {
            $conversation_id = '';
        }
        if (isset($data['sender_type'])) {
            $sender_type = $data['sender_type'];
        } else {
            $sender_type = '';
        }
        if (isset($data['order_type'])) {
            $order_type = $data['order_type'];
        } else {
            $order_type = '';
        }

        $click_action = "";
        if ($web_push_link) {
            $click_action = ',
            "click_action": "' . $web_push_link . '"';
        }

        $postdata = '{
            "to" : "' . $fcm_token . '",
            "mutable_content": true,
            "data" : {
                "title":"' . $data['title'] . '",
                "body" : "' . $data['description'] . '",
                "image" : "' . $data['image'] . '",
                "order_id":"' . $data['order_id'] . '",
                "conversation_id":"' . $conversation_id . '",
                "sender_type":"' . $sender_type . '",
                "order_type":"' . $order_type . '",
                "is_read": 0
            },
            "notification" : {
                "title" :"' . $data['title'] . '",
                "body" : "' . $data['description'] . '",
                "image" : "' . $data['image'] . '",
                "order_id":"' . $data['order_id'] . '",
                "title_loc_key":"' . $data['order_id'] . '",

                "is_read": 0,
                "icon" : "new",
                "sound": "notification.wav",
                "android_channel_id": ""
                ' . $click_action . '
            }
        }';

        $ch = curl_init();
        $timeout = 120;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        // Get URL content
        $result = curl_exec($ch);
        // close handle to release resources
        curl_close($ch);

        return $result;
    }

    public static function send_push_notif_to_topic($data, $topic, $type, $web_push_link = null)
    {
        // info([$data, $topic, $type, $web_push_link]);
        $key = BusinessSetting::where(['key' => 'push_notification_key'])->first()->value;


        $url = "https://fcm.googleapis.com/fcm/send";
        $header = array(
            "authorization: key=" . $key . "",
            "content-type: application/json"
        );

        if (isset($data['order_type'])) {
            $order_type = $data['order_type'];
        } else {
            $order_type = '';
        }
        $click_action = "";
        if ($web_push_link) {
            $click_action = ',
            "click_action": "' . $web_push_link . '"';
        }

        if (isset($data['order_id'])) {
            $postdata = '{
                "to" : "/topics/' . $topic . '",
                "mutable_content": true,
                "data" : {
                    "title":"' . $data['title'] . '",
                    "body" : "' . $data['description'] . '",
                    "image" : "' . $data['image'] . '",
                    "order_id":"' . $data['order_id'] . '",
                    "order_type":"' . $order_type . '",
                    "is_read": 0,
                    "type":"' . $type . '"
                },
                "notification" : {
                    "title":"' . $data['title'] . '",
                    "body" : "' . $data['description'] . '",
                    "image" : "' . $data['image'] . '",
                    "order_id":"' . $data['order_id'] . '",
                    "title_loc_key":"' . $data['order_id'] . '",
                    "body_loc_key":"' . $type . '",
                    "type":"' . $type . '",
                    "is_read": 0,
                    "icon" : "new",
                    "sound": "notification.wav",
                    "android_channel_id": "stackfood"
                    ' . $click_action . '
                  }
            }';
        } else {
            $postdata = '{
                "to" : "/topics/' . $topic . '",
                "mutable_content": true,
                "data" : {
                    "title":"' . $data['title'] . '",
                    "body" : "' . $data['description'] . '",
                    "image" : "' . $data['image'] . '",
                    "is_read": 0,
                    "type":"' . $type . '",
                },
                "notification" : {
                    "title":"' . $data['title'] . '",
                    "body" : "' . $data['description'] . '",
                    "image" : "' . $data['image'] . '",
                    "body_loc_key":"' . $type . '",
                    "type":"' . $type . '",
                    "is_read": 0,
                    "icon" : "new",
                    "sound": "notification.wav",
                    "android_channel_id": "stackfood"
                    ' . $click_action . '
                  }
            }';
        }

        $ch = curl_init();
        $timeout = 120;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        // Get URL content
        $result = curl_exec($ch);
        // close handle to release resources
        curl_close($ch);

        return $result;
    }

    public static function posNotify($state, $restaurantId, $customerId = null)
    {
        $restaurant = Restaurant::find($restaurantId);
        if (!$restaurant) {
            // Handle case where restaurant is not found
            return;
        }

        if ($state == "order-made") {
            $message = ZoneBusinessSetting::getSettingValue('admin_new_order', $restaurant->zone_id);

            if ($restaurant->self_delivery_system == 1) {
                $dms = DeliveryMan::where('restaurant_id', $restaurant->id)->get();
                foreach ($dms as $dm) {
                    $dm->notify(new FoodOrderNotification($message));
                    if ($dm->fcm_token) {
                        self::send_notification($dm->fcm_token, [
                            'title' => $message,
                            "description" => '',
                            "image" => '',
                            "order_id" => '',
                        ]);
                    }
                }
            }

            if ($customerId) {
                $customer = Customer::find($customerId);
                if ($customer) {
                    $customer->notify(new FoodOrderNotification($message));
                    if ($customer->fcm_token) {
                        self::send_notification($customer->fcm_token, [
                            'title' => $message,
                            "description" => '',
                            "image" => '',
                            "order_id" => '',
                        ]);
                    }
                }
            }
        }
    }



    public static function order_data_formatting($data, $multi_data = false)
    {
        $storage = [];
        if ($multi_data) {
            foreach ($data as $item) {
                if (isset($item['restaurant'])) {
                    $item['restaurant_name'] = $item['restaurant']['name'];
                    $item['restaurant_address'] = $item['restaurant']['address'];
                    $item['restaurant_phone'] = $item['restaurant']['phone'];
                    $item['restaurant_lat'] = $item['restaurant']['latitude'];
                    $item['restaurant_lng'] = $item['restaurant']['longitude'];
                    $item['restaurant_logo'] = $item['restaurant']['logo'];
                    $item['restaurant_delivery_time'] = $item['restaurant']['delivery_time'];
                    $item['vendor_id'] = $item['restaurant']['vendor_id'];
                    // $item['chat_permission'] = $item['restaurant']['restaurant_sub']['chat'] ?? 0;
                    // $item['restaurant_model'] = $item['restaurant']['restaurant_model'];
                    unset($item['restaurant']);
                } else {
                    $item['restaurant_name'] = null;
                    $item['restaurant_address'] = null;
                    $item['restaurant_phone'] = null;
                    $item['restaurant_lat'] = null;
                    $item['restaurant_lng'] = null;
                    $item['restaurant_logo'] = null;
                    $item['restaurant_delivery_time'] = null;
                    $item['restaurant_model'] = null;
                    $item['chat_permission'] = null;
                }
                // $item['food_campaign'] = 0;
                // foreach ($item->details as $d) {
                //     if ($d->item_campaign_id != null) {
                //         $item['food_campaign'] = 1;
                //     }
                // }

                // $item['delivery_address'] = $item->delivery_address ? json_decode($item->delivery_address, true) : null;
                // $item['details_count'] = (int)$item->details->count();
                unset($item['details']);
                array_push($storage, $item);
            }
            $data = $storage;
        } else {
            if (isset($data['restaurant'])) {
                $data['restaurant_name'] = $data['restaurant']['name'];
                $data['restaurant_address'] = $data['restaurant']['address'];
                $data['restaurant_phone'] = $data['restaurant']['phone'];
                $data['restaurant_lat'] = $data['restaurant']['latitude'];
                $data['restaurant_lng'] = $data['restaurant']['longitude'];
                $data['restaurant_logo'] = $data['restaurant']['logo'];
                $data['restaurant_delivery_time'] = $data['restaurant']['delivery_time'];
                $data['vendor_id'] = $data['restaurant']['vendor_id'];
                $data['chat_permission'] = $data['restaurant']['restaurant_sub']['chat'] ?? 0;
                $data['restaurant_model'] = $data['restaurant']['restaurant_model'];
                unset($data['restaurant']);
            } else {
                $data['restaurant_name'] = null;
                $data['restaurant_address'] = null;
                $data['restaurant_phone'] = null;
                $data['restaurant_lat'] = null;
                $data['restaurant_lng'] = null;
                $data['restaurant_logo'] = null;
                $data['restaurant_delivery_time'] = null;
                $data['chat_permission'] = null;
                $data['restaurant_model'] = null;
            }

            // $data['food_campaign'] = 0;
            // foreach ($data->details as $d) {
            //     if ($d->item_campaign_id != null) {
            //         $data['food_campaign'] = 1;
            //     }
            // }
            // $data['delivery_address'] = $data->delivery_address ? json_decode($data->delivery_address, true) : null;
            // $data['details_count'] = (int)$data->details->count();
            unset($data['details']);
        }
        return $data;
    }




    public static function product_data_formatting(Food $product)
    {
        $data = [
            "id" => $product->id,
            "name" => $product->name ?? '',
            "description" => $product->description ?? '',
            "category_id" => $product->description ?? null,
            "restaurant_menu_id" => $product->restaurant_menu_id ?? null,
            "restaurant_submenu_id" => $product->restaurant_submenu_id ?? null,
            "isCustomize" => $product->isCustomize ?? 0,
            "isRecommended" => $product->isRecommended ?? 0,
            "variations" => $product->variations ?? null,
            "add_ons" => $product->variations ?? null,


        ];

        return $data;
    }

    public static function get_varient($product, array $variations)
    {
        $product_variations =  json_decode($product->variations);
        $result = [];

        foreach ($variations as $k => $variation) {
            foreach ($product_variations as  $product_variation) {
                if (isset($variation['values'])  && isset($product_variation->values) && $product_variation->name == $variation['option']) {
                    // dd($product_variation);
                    foreach ($product_variation->values as  $option) {
                        foreach ($variation['values'] as &$value) {
                            if ($option->label == $value['label']) {
                                $value['admin_margin'] = $option->optionMargin ?? 0; // admin margin set to be on varatin price
                                $value['packing_charge'] = $product->packing_charge; // packing charge
                                $value["price"] = $option->optionPrice; // here price is restaurant vairation price
                                break;
                            }
                            if ($product_variation->max < $value['qty'] && ($product_variation->max != 0)) {
                                throw new \Exception(ucfirst($option->label) . " quantity can'\t be more than $product_variation->max");
                            }
                        }
                    }
                    $result[$k] = $variation;
                }
            }
        }
        return $result;
    }

    public static function getAdmin() :Admin
    {
        return Admin::where('role_id', 1)->first();
    }

    public static function isAdmin() : bool
    {
        return Auth::guard('admin')->user()->hasRole('Super Admin');
    }
    public static function getStaff() : Admin
    {
        return Admin::
        leftJoin('zones', 'zones.id', '=', 'admins.zone_id')
        ->select('admins.f_name',
            'admins.l_name',
            'admins.id',
            'admins.email', 
            'admins.phone',
            'admins.zone_id',
            DB::raw("CONCAT(admins.f_name, ' ', admins.l_name) as full_name"),
            'zones.name as zone_name')
        ->find(Auth::guard('admin')->id());

    }

   


    public static function order_transaction_process_by_deliveryMan($orderId, $received_by = null)
    {
        // Fetch the order with related restaurant and details
        $order = Order::with(['restaurant', 'details'])->find($orderId);
        $ADMIN = self::getAdmin();

        // Initialize variables
        $adminExp = 0;
        $restaurantExp = 0;
        $discountByRestaurant = 0;
        $dmExpense = 0;
        $adminCommissionByRestaurant = 0;
        $commissionPercent = 0;
        $total_food_price = 0;

        // Calculate total food price and restaurant expense
        foreach ($order->details as $detailedItem) {
            $foodPrice = $detailedItem->price * $detailedItem->quantity;
            $discountByRestaurant = $detailedItem->discount_on_food * $detailedItem->quantity;
            $foodPrice += $detailedItem->addon_price + $detailedItem->variation_price;
            $total_food_price += $foodPrice;
        }

        $discountByRestaurant += $order->custom_discount;
        $total_food_price -= $discountByRestaurant;

        // Calculate admin commission based on subscription type


        if ($order->restaurant->subscription_type == 'commission') {
            if ($order->restaurant->commission > 0) {
                $adminCommissionByRestaurant = $order->restaurant->commission;
            } else {
                $commissionPercent = ZoneBusinessSetting::getSettingValue('admin_commission', $order->restaurant->zone_id);
                if ($commissionPercent > 0) {
                    $adminCommissionByRestaurant = ($total_food_price * $commissionPercent) / 100;
                }
            }
        }

        $restaurantExp = $total_food_price - $adminCommissionByRestaurant;

        // Calculate delivery man expense
        $deliveryCharge = $order->delivery_charge;
        $deliveryCommissionInPercentage = ZoneBusinessSetting::getSettingValue('delivery_charge_comission', $order->restaurant->zone_id);
        $deliveryCommissionAmount = 0;

        if ($deliveryCommissionInPercentage > 0) {
            $deliveryCommissionAmount = ($deliveryCharge * $deliveryCommissionInPercentage) / 100;
            $deliveryCharge -= $deliveryCommissionAmount;
        }

        $dmExpense = $deliveryCharge + $order->dm_tips;

        // Calculate admin expense
        $adminExp = $deliveryCommissionAmount + $adminCommissionByRestaurant;
        $total = $order->order_amount - $restaurantExp - $dmExpense;

        // Fetch wallets
        $dmWallet = Wallet::where('deliveryman_id', $order->delivery_man_id)->first();
        $adminWallet = Wallet::where('admin_id', $ADMIN->id)->first();
        $vendorWallet = Wallet::where('vendor_id', $order->restaurant->vendor_id)->first();


        try {
            DB::beginTransaction();
            // Order amount transferred to Admin Wallet for cash payment method
            if ($order->payment_method == 'cash') {
                if ($dmWallet->balance < $order->order_amount) {
                    throw new \Exception('Insufficient Wallet Balance');
                }
                $dmWallet->balance -= $order->order_amount;
                $dmWallet->WalletTransactions()->create([
                    'amount' => $order->order_amount,
                    'type' => 'Cr',
                    'admin_id' => $ADMIN->id,
                    'remarks' => 'Cash Order Amount Transferred To Admin Wallet',
                ]);

                $adminWallet->balance += $order->order_amount;
                $adminWallet->WalletTransactions()->create([
                    'amount' => $order->order_amount,
                    'type' => 'Dr',
                    'deliveryman_id' => $order->delivery_man_id,
                    'remarks' => 'Cash Order Amount Accepted From Deliveryman Wallet',
                ]);

                $dmWallet->save();
                $adminWallet->save();
            }

            // Delivery charge and tips transferred from admin to deliveryman wallet
            $dmWallet->balance += $deliveryCharge;
            $adminWallet->balance -= $deliveryCharge;
            $dmWallet->WalletTransactions()->create([
                'amount' => $deliveryCharge,
                'admin_id' => $ADMIN->id,
                'type' => 'Dr',
                'remarks' => 'Delivery Charge Accepted From Admin Wallet',
            ]);
            $adminWallet->WalletTransactions()->create([
                'amount' => $deliveryCharge,
                'type' => 'Cr',
                'deliveryman_id' => $order->delivery_man_id,
                'remarks' => 'Delivery Charge Transferred To Deliveryman Wallet',
            ]);
            $adminWallet->save();
            $dmWallet->save();

            if ($order->dm_tips > 0) {
                $dmWallet->balance += $order->dm_tips;
                $adminWallet->balance -= $order->dm_tips;
                $dmWallet->WalletTransactions()->create([
                    'amount' => $order->dm_tips,
                    'admin_id' => $ADMIN->id,
                    'type' => 'Dr',
                    'remarks' => 'Delivery Tips for Order No ' . $order->id,
                ]);
                $adminWallet->WalletTransactions()->create([
                    'amount' => $order->dm_tips,
                    'type' => 'Cr',
                    'deliveryman_id' => $order->delivery_man_id,
                    'remarks' => 'Delivery Tips Transferred To Deliveryman Wallet',
                ]);
                $adminWallet->save();
                $dmWallet->save();
            }

            // Food amount and GST transferred to restaurant wallet
            $totalRestaurantPayable = $restaurantExp + $order->total_tax_amount;
            $dmWallet->balance += $totalRestaurantPayable;
            $vendorWallet->balance -= $totalRestaurantPayable;
            $vendorWallet->WalletTransactions()->create([
                'amount' => $totalRestaurantPayable,
                'admin_id' => $ADMIN->id,
                'type' => 'Dr',
                'remarks' => 'Restaurant Order Amount Accepted From Admin Wallet',
            ]);
            $adminWallet->WalletTransactions()->create([
                'amount' => $totalRestaurantPayable,
                'type' => 'Cr',
                'deliveryman_id' => $order->delivery_man_id,
                'remarks' => 'Restaurant Order Amount Transferred To Restaurant Wallet',
            ]);
            $adminWallet->save();
            $vendorWallet->save();

            // Define the data array for OrderTransaction insertion
            $data = [
                'order_id' => $order->id,
                'restaurant_id' => $order->restaurant_id,
                'delivery_man_id' => $order->delivery_man_id,
                'dm_tips' => $order->dm_tips,
                'received_by' => $received_by ?? $order->customer->f_name,
                'zone_id' => $order->restaurant->zone_id,
                'status' => 'delivered',
                'original_delivery_charge' => $deliveryCharge,
                'tax' => $order->total_tax_amount,
                'admin_expense' => $adminExp,
                'restaurant_expense' => $restaurantExp,
                'discount_amount_by_restaurant' => $discountByRestaurant,
                'commission_percentage' => $commissionPercent,
                'is_subscription' => $order->is_subscription,
                'is_subscribed' => $order->is_subscribed
            ];

            // Insert the order transaction
            OrderTransaction::insert($data);
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            // Handle any errors that occur during the transaction process
            throw $th;
        }
    }

    public static function sendOrderNotification($sendTo, $notification)
    {
        \Illuminate\Support\Facades\Notification::send($sendTo, new \App\Notifications\FirebaseNotification($notification));
        //    $adminNotificationPermission = DB::table('business_settings')->where('key', 'admin_order_notification')->first();

        return true;
    }

    public static function numberToWords($number)
    {
        $hyphen = '-';
        $conjunction = ' and ';
        $separator = ', ';
        $negative = 'Negative ';
        $decimal = ' point ';
        $dictionary = [
            0 => 'Zero',
            1 => 'One',
            2 => 'Two',
            3 => 'Three',
            4 => 'Four',
            5 => 'Five',
            6 => 'Six',
            7 => 'Seven',
            8 => 'Eight',
            9 => 'Nine',
            10 => 'Ten',
            11 => 'Eleven',
            12 => 'Twelve',
            13 => 'Thirteen',
            14 => 'Fourteen',
            15 => 'Fifteen',
            16 => 'Sixteen',
            17 => 'Seventeen',
            18 => 'Eighteen',
            19 => 'Nineteen',
            20 => 'Twenty',
            30 => 'Thirty',
            40 => 'Forty',
            50 => 'Fifty',
            60 => 'Sixty',
            70 => 'Seventy',
            80 => 'Eighty',
            90 => 'Ninety',
            100 => 'Hundred',
            1000 => 'Thousand',
            100000 => 'Lakh',
            10000000 => 'Crore'
        ];

        if (!is_numeric($number)) {
            return false;
        }

        if ($number < 0) {
            return $negative . self::numberToWords(abs($number));
        }

        $string = '';

        if ($number >= 10000000) {
            $crores = floor($number / 10000000);
            $string .= self::numberToWords($crores) . ' Crore';
            $number %= 10000000;
            if ($number) {
                $string .= $separator;
            }
        }

        if ($number >= 100000) {
            $lakhs = floor($number / 100000);
            $string .= self::numberToWords($lakhs) . ' Lakh';
            $number %= 100000;
            if ($number) {
                $string .= $separator;
            }
        }

        if ($number >= 1000) {
            $thousands = floor($number / 1000);
            $string .= self::numberToWords($thousands) . ' Thousand';
            $number %= 1000;
            if ($number) {
                $string .= $separator;
            }
        }

        if ($number >= 100) {
            $hundreds = floor($number / 100);
            $string .= self::numberToWords($hundreds) . ' Hundred';
            $number %= 100;
            if ($number) {
                $string .= $conjunction;
            }
        }

        if ($number < 20 && $number > 0) {
            $string .= $dictionary[$number];
        } elseif ($number >= 20) {
            $tens = floor($number / 10) * 10;
            $units = $number % 10;
            $string .= $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
        }

        return $string ? $string . ' Rupees Only' : $dictionary[0];
    }

    public static function getCusomerOrderItems($order_id)
    {
        $stmt =  \App\Models\OrderCalculationStatement::where('order_id', $order_id)->first(); //OrderCalculationStatement
        if ($stmt == null || $stmt->customerData == null) {
            return [];
        }
        $stmt = json_decode($stmt->customerData, true);
        return $stmt['foodItemList'];
    }

    public static function formatDmById($dmId, $withLocation = false,)
    {
        $deliverman = \App\Models\DeliveryMan::find($dmId);
        if (!$deliverman) {
            return null;
        }
        $data = [
            'id' => $deliverman->id,
            'name' => $deliverman->f_name . ' ' . $deliverman->l_name,
            'email' => $deliverman->email,
            'phone' => $deliverman->phone,
            'image' => $deliverman->image != null ? asset('delivery-man/' . $deliverman->image) : asset('assets/user/img/user2.png'),
        ];
        if ($withLocation) {
            $data['latitude'] = $deliverman->latitude;
            $data['longitude'] = $deliverman->longitude;
        }
        return $data;
    }

    // public function formatDm($dmData)



    public static function getDirections($request)
    {
        $origin = $request->input('origin');           // e.g., "25.6234486,85.1323779"
        $destination = $request->input('destination');
        $mode = $request->input('mode', env('DRIVING_MODE'));  // e.g., "25.6343,85.1103"
        $apiKey = env('GOOGLE_MAPS_API_KEY'); // Secure your key in .env file
        $query = [
            'origin' => $origin,
            'destination' => $destination,
            'mode' => $mode == null ? env('DRIVING_MODE') : $mode, //driving, walking, bicycling, transit
            'key' => $apiKey,
        ];


        // Add traffic data only if driving
        if ($mode === 'driving') {
            $query['departure_time'] = 'now';
        }

        $response = Http::get("https://maps.googleapis.com/maps/api/directions/json", $query);
        $data = $response->json();

        if (isset($data['routes'][0]['legs'][0])) {
            $leg = $data['routes'][0]['legs'][0];

            return response()->json([
                'start_address' => $leg['start_address'],
                'end_address' => $leg['end_address'],
                'distance_text' => $leg['distance']['text'],
                'distance_value' => $leg['distance']['value'],
                'duration_text' => $leg['duration']['text'],
                'duration_value' => $leg['duration']['value'],
                'duration_in_traffic_text' => $leg['duration_in_traffic']['text'] ?? null,
                'duration_in_traffic_value' => $leg['duration_in_traffic']['value'] ?? null,
            ]);
        } else {
            return response()->json([
                'error' => $data['error_message'] ?? 'No route found',
                'status' => $data['status'] ?? 'unknown'
            ], 400);
        }
    }

    public static function googleDirections($origin, $destination, $mode = null)
    {

        $apiKey = env('GOOGLE_MAPS_API_KEY'); // Secure your key in .env file
        $apiKey = 'AIzaSyBPVVxuVkmdE1GzSgIqm_9dx64tea7Wltk';
        $query = [
            'origin' => $origin,
            'destination' => $destination,
            'mode' => $mode == null ? env('DRIVING_MODE') : $mode, //driving, walking, bicycling, transit
            'key' => $apiKey,
        ];

        // Add traffic data only if driving
        if ($mode === 'driving') {
            $query['departure_time'] = 'now';
        }
        // dd($query);
        $response = Http::get("https://maps.googleapis.com/maps/api/directions/json", $query);
        $data = $response->json();

        if (isset($data['routes'][0]['legs'][0])) {
            $leg = $data['routes'][0]['legs'][0];

            return [
                'start_address' => $leg['start_address'],
                'end_address' => $leg['end_address'],
                'distance_text' => $leg['distance']['text'],
                'distance_value' => $leg['distance']['value'],
                'duration_text' => $leg['duration']['text'],
                'duration_value' => $leg['duration']['value'],
                'duration_in_traffic_text' => $leg['duration_in_traffic']['text'] ?? null,
                'duration_in_traffic_value' => $leg['duration_in_traffic']['value'] ?? null,
            ];
        } else {
            Log::error($data['error_message'] ?? 'No route found');
            return null;
        }
    }



    public static function getAddressLocation($location)
    {
        $apiKey = env('GOOGLE_MAPS_API_KEY',"AIzaSyBPVVxuVkmdE1GzSgIqm_9dx64tea7Wltk");
        $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$location&key=$apiKey";

        $response = Http::get($url);
        if ($response->successful()) {
            $json = $response->json();

            if (isset($json['results'][0]['formatted_address'])) {
                return $json['results'][0]['formatted_address'];
            } else {
                return 'Address not found';
            }
        }
        return 'Geocoding failed';
    }

    public static function half_whole_day_display($days)
    {

        $whole = floor($days);
        $hasHalf = fmod($days, 1) === 0.5;

        return $whole . ($hasHalf ? '½' : '');
    }

    public static function getRating($id, $type = 'deliveryman')
    {
        $reviews = DB::table('reviews')
            ->where(function ($query) use ($id, $type) {
                if ($type === 'deliveryman') {
                    $query->where('review_to', 'deliveryman')
                        ->where('deliveryman_id', $id);
                } elseif ($type === 'restaurant') {
                    $query->where('review_to', 'restaurant')
                        ->where('restaurant_id', $id);
                }
            })
            ->select(DB::raw('SUM(rating)/COUNT(rating) as rating'))
            ->value('rating'); // directly fetch the single value

        return number_format($reviews ?? 0, 2);
    }

    public static function getOrderSessions(int $customer_id, $key = null)
    {
        $orderSessions = DB::table('order_sessions')->where('customer_id', $customer_id)->first();
        if ($orderSessions == null) {
            return null;
        }
        // dd($orderSessions);
        switch ($key) {
            case 'dm_tips':
                return $orderSessions->dm_tips;
            case 'loved_one_data':
                return json_decode($orderSessions->loved_one_data ?? "[]", true);
            case 'cooking_instruction':
                return $orderSessions->cooking_instruction;
            case 'delivery_instruction':
                return json_decode($orderSessions->delivery_instruction ?? "[]", true);
            case 'applied_coupons':
                return json_decode($orderSessions->applied_coupons ?? "[]", true);
            case 'pay_from_wallet':
                return $orderSessions->pay_from_wallet;
            case 'cash_to_collect':
                return $orderSessions->cash_to_collect;
            case 'payment_method':
                return $orderSessions->payment_method;
            case 'referral_user_reward_id':
                return $orderSessions->referral_user_reward_id ?? null;
            case 'order_scheduled_time':
                return $orderSessions->order_scheduled_time;
            default:
                return [
                    'dm_tips' => $orderSessions->dm_tips,
                    'loved_one_data' => json_decode($orderSessions->loved_one_data ?? "[]", true),
                    'cooking_instruction' => $orderSessions->cooking_instruction,
                    'delivery_instruction' => json_decode($orderSessions->delivery_instruction ?? "[]", true),
                    'applied_coupons' => json_decode($orderSessions->applied_coupons ?? "[]", true),
                    'pay_from_wallet' => $orderSessions->pay_from_wallet,
                    'cash_to_collect' => $orderSessions->cash_to_collect,
                    'payment_method' => $orderSessions->payment_method,
                    'referral_user_reward_id' => $orderSessions->referral_user_reward_id ?? null,
                    'order_scheduled_time' => $orderSessions->order_scheduled_time
                ];
        }
    }

    public static function getGuestSession($key = null, $guest_token = null)
    {
        if ($guest_token == null) {
            if (isset($_COOKIE['guest_token'])) {
                $guest_token = $_COOKIE['guest_token'];
            } else {
                return null;
            }
        }
        $guestSession = GuestSession::where('guest_id', $guest_token)->first();
        if ($guestSession == null) {
            return null;
        }
        switch ($key) {
            case 'guest_location':
                return json_decode($guestSession->guest_location ?? "[]", true) ?? [];
            case 'ip_address':
                return $guestSession->ip_address;
            case 'device_info':
                return $guestSession->device_info;
            case 'user_agent':
                return $guestSession->user_agent;
            default:
                return [
                    'guest_location' => json_decode($guestSession->guest_location ?? "[]", true) ?? [],
                    'ip_address' => $guestSession->ip_address,
                    'device_info' => $guestSession->device_info,
                    'user_agent' => $guestSession->user_agent
                ];
        }
    }

    public static function guestLocationExists()
    {
       $session = self::getGuestSession('guest_location');

       
       return $session['lat'] != null && $session['lng'] != null; 
    }

    public static function guestCheck($guest_token = null)
    {
        if ($guest_token == null) {
            if (isset($_COOKIE['guest_token'])) {
                $guest_token = $_COOKIE['guest_token'];
            } else {
                return false;
            }
        }
        return  GuestSession::where('guest_id', $guest_token)->exists();
    }

    public static function isOrderSessionLock($customer_id)
    {
        $orderSession = DB::table('order_sessions')->where('customer_id', $customer_id)->select('is_locked')->first();
        if ($orderSession) {
            return $orderSession->is_locked;
        }
        return false;
    }
    public static function lockOrderSession($customer_id, $lock = true)
    {
        $orderSession = DB::table('order_sessions')->where('customer_id', $customer_id)->first();
        if ($orderSession) {
            DB::table('order_sessions')
                ->where('customer_id', $customer_id)
                ->update(['is_locked' => $lock]);
            return true;
        }
        return false;
    }
    public static function unlockOrderSession($customer_id)
    {
        $orderSession = DB::table('order_sessions')->where('customer_id', $customer_id)->select('is_locked','id')->first();
        if ($orderSession) {
            DB::table('order_sessions')
                ->where('customer_id', $customer_id)
                ->update(['is_locked' => false]);
            return true;
        }
        return false;
    }
    public static function getOrderSessionId($customer_id)
    {
        $orderSession = DB::table('order_sessions')->where('customer_id', $customer_id)->select('id')->first();
        if ($orderSession) {
            return $orderSession->id;
        }
        return null;
    }

    public static function lockCart($customer_id, $lock = true)
    {
        $orderSession = DB::table('carts')->where('customer_id', $customer_id)->select('id')->first();
        if ($orderSession) {
            DB::table('carts')
                ->where('customer_id', $customer_id)
                ->update(['is_locked' => $lock]);
            return true;
        }
        return false;
    }
    public static function unlockCart($customer_id)
    {
        $orderSession = DB::table('carts')->where('customer_id', $customer_id)->select('is_locked','id')->first();
        if ($orderSession) {
            DB::table('carts')
                ->where('customer_id', $customer_id)
                ->update(['is_locked' => false]);
            return true;
        }
        return false;
    }
    public static function isCartLock($customer_id)
    {
        $orderSession = DB::table('carts')->where('customer_id', $customer_id)->select('is_locked')->first();
        if ($orderSession) {
            return $orderSession->is_locked;
        }
        return false;
    }
    public static function getCartId($customer_id)
    {
        $orderSession = DB::table('carts')->where('customer_id', $customer_id)->select('id')->first();
        if ($orderSession) {
            return $orderSession->id;
        }
        return null;
    }

    
    public static function get_device_os_type(?string $userAgent = null): string
    {
        if (is_null($userAgent)) {
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        }

        if (empty($userAgent)) {
            return 'Unknown';
        }

        $userAgentLower = strtolower($userAgent);

        if (stripos($userAgentLower, 'android') !== false) {
            return 'Android';
        }

        if (
            stripos($userAgentLower, 'iphone') !== false ||
            stripos($userAgentLower, 'ipad') !== false ||
            stripos($userAgentLower, 'ipod') !== false
        ) {
            return 'iOS';
        }

        if (
            stripos($userAgentLower, 'windows phone') !== false ||
            stripos($userAgentLower, 'iemobile') !== false
        ) {
            return 'Windows Phone';
        }

        if (
            stripos($userAgentLower, 'macintosh') !== false ||
            stripos($userAgentLower, 'mac os x') !== false
        ) {
            return 'macOS';
        }

        if (stripos($userAgentLower, 'windows nt') !== false) {
            return 'Windows';
        }

        if (stripos($userAgentLower, 'linux') !== false) {
            return 'Linux';
        }

        if (
            stripos($userAgentLower, 'mobile') !== false ||
            stripos($userAgentLower, 'opera mini') !== false
        ) {
            return 'Other Mobile';
        }

        return 'Desktop'; // Fallback
    }

    public static function visibleToThisDevice()
    {
        $device = self::get_device_os_type();
        // dd($device);
        // return $device;
        switch ($device) {
            case 'iOS':
                return false;
            case 'macOS':
                return true;
            case 'Android':
                return false;
            case 'Desktop':
                return true;
            case 'Windows Phone':
                return false;
            case 'Other Mobile':
                return false;
            default:
                return true;
        }
    }


    public static function qrGenerate($restaurantName, $link)
    {
        $mainPath = public_path('qrtemplate/imagePic');

        // Build the command without redirecting stderr
        $command = 'qrencode -o - -s 30 -l H ' . escapeshellarg($link) . ' | ffmpeg -y ' .
            '-i ' . escapeshellarg($mainPath . '/logo.png') . ' ' .
            '-f image2pipe -vcodec png -i - ' .
            '-filter_complex "[0]scale=250:250[logo]; ' .
            '[1]scale=800:800[qr]; ' .
            '[qr][logo]overlay=(W-w)/2:(H-h)/2[final]" ' .
            '-map "[final]" -frames:v 1 -f image2pipe -vcodec png -';

        $descriptors = [
            0 => ['pipe', 'r'],  // stdin
            1 => ['pipe', 'w'],  // stdout (PNG binary)
            2 => ['pipe', 'w'],  // stderr (FFmpeg logs)
        ];

        $process = proc_open($command, $descriptors, $pipes);

        if (!is_resource($process)) {
            Log::error("Failed to start QR generation for {$restaurantName}");
            return null;
        }

        // Read the PNG binary from stdout
        $pngData = stream_get_contents($pipes[1]);
        fclose($pipes[1]);

        // Read FFmpeg logs from stderr
        $ffmpegLogs = stream_get_contents($pipes[2]);
        fclose($pipes[2]);

        $returnCode = proc_close($process);

        if ($returnCode !== 0) {
            Log::error("QR generation failed for {$restaurantName}", ['ffmpeg' => $ffmpegLogs]);
            return null;
        }

        Log::info("QR generated successfully for {$restaurantName}", ['ffmpeg' => $ffmpegLogs]);

        // Convert PNG binary to Base64
        $base64 = base64_encode($pngData);

        return 'data:image/png;base64,' . $base64;
    }

    /**
     * Point in polygon algorithm (ray casting)for checking if a point is inside a polygon.
     */
    public static function pointInPolygon($lat, $lng, $polygon)
    {
        if (count($polygon) < 3) {
            return true; // Not a valid polygon
        }

        $inside = false;
        $j = count($polygon) - 1;

        for ($i = 0; $i < count($polygon); $i++) {
            $xi = floatval($polygon[$i]['latitude'] ?? $polygon[$i]['lat'] ?? 0);
            $yi = floatval($polygon[$i]['longitude'] ?? $polygon[$i]['lng'] ?? 0);
            $xj = floatval($polygon[$j]['latitude'] ?? $polygon[$j]['lat'] ?? 0);
            $yj = floatval($polygon[$j]['longitude'] ?? $polygon[$j]['lng'] ?? 0);

            if ((($yi > $lng) !== ($yj > $lng)) && 
                ($lat < ($xj - $xi) * ($lng - $yi) / ($yj - $yi) + $xi)) {
                $inside = !$inside;
            }
            $j = $i;
        }

        return $inside;
    }

    public static function findZoneByLocation(float $lat, float $lng) : Zone|null
    {
        $zones = Zone::where('status', 1)->get(['id', 'coordinates']);
        foreach ($zones as $zone) {
            $polygon = json_decode($zone->coordinates, true)['polygon'];
            if (self::pointInPolygon($lat, $lng, $polygon)) {
                return Zone::find($zone->id);
            }
        }
        return null;
    }


    


    public static function syncPassKey($request, bool $canCreatePassKey = false) : String
    {
        $userID = null;
        


        $userAgent = $request->header('User-Agent');
        $deviceOS = self::get_device_os_type($userAgent);
        $expireTime = now()->addDays(15);
        // Device info headers (sent from mobile app)
        $deviceId     = $request->header('X-Device-Id');
        $deviceModel  = $request->header('X-Device-Model');
        $deviceBrand  = $request->header('X-Device-Brand');
        $osVersion    = $request->header('X-OS-Version');
        $appVersion   = $request->header('X-App-Version');
        
        
        $passKey = hash('sha256',
            date('Y-m-d') . '|' .
            $userAgent . '|' .
            $deviceOS . '|' .
            config('app.key')
        );

        $getmyPassKey = function() use ($request, $passKey) {
           return isset($_COOKIE["pass_key"])? $_COOKIE["pass_key"] : $request->header('Pass-Key', $passKey);
        };
        
        // dd($request->header('Pass-Key'));
        $passKey = $getmyPassKey();
        if (Auth::guard('customer')->check()) {
            $userID = Auth::guard('customer')->user()->id;
            // dd($userID);
            $keyData = UserPassKey::where('key', $passKey)
                ->orWhere('user_id', $userID)
                ->first();
            UserPassKey::where('user_id', $userID)
                ->where('key', '!=', $passKey)
                ->delete();
            $_expireTime = Carbon::parse($keyData?->expire_at);
            if ($keyData && $_expireTime && $_expireTime->isFuture()) {
                $passKey = $keyData->key;
                $expireTime = $_expireTime->format('Y-m-d H:i:s');
                
            }
                        // dd($keyData->key, $passKey );


            $keyData?->update(
                [
                    'user_id'      => $userID,
                    'key'          => $passKey,
                    'platform'     => $deviceOS,
                    'agent'        => $userAgent,
                    'device_id'    => $deviceId,
                    'device_brand' => $deviceBrand,
                    'device_model' => $deviceModel,
                    'os_version'   => $osVersion,
                    'app_version'  => $appVersion,
                    'expire_at'    => $expireTime,
                ]
            );

        }else{
            if (!$canCreatePassKey) {
                return "";
            }
            
            UserPassKey::updateOrCreate(
                [
                    'key' => $passKey,
                    
                ],
                [
                    'agent' => $userAgent,
                    'user_id'      => $userID,
                    'platform'     => $deviceOS,
                    'device_id'    => $deviceId,
                    'device_brand' => $deviceBrand,
                    'device_model' => $deviceModel,
                    'os_version'   => $osVersion,
                    'app_version'  => $appVersion,
                    'expire_at'    => $expireTime,
                ]
            );
        }
        setcookie('pass_key', $passKey, strtotime($expireTime), '/', '', false, true);
        $request->headers->set('Pass-Key', $passKey);
        return $passKey;

        
    }

    public static function ApiResponse(bool $status, $message = null, $data = null, $code = 200)
    {
        if(!$status && $code == 200){
            $code = 400;
        }
        $response = [
            'status' => $status ? "success" : "error",
            'message' => $message,
            'data'    => $data,
        ];
        return response()->json($response, $code);
    }


    
}
