<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use App\Models\Currency;
use App\Models\Restaurant;
// use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BusinessSettingsController extends Controller
{
    private $restaurant;

    public function business_index()
    {
        return view('admin-views.business-settings.business-index');
    }

    public function business_setup(Request $request)
    {
        if (env('APP_MODE') == 'demo') {
            return back()->with('info', __('messages.update_option_is_disable_for_demo'));
        }

        try {
            // Comprehensive validation
            $validator = Validator::make($request->all(), [
                'restaurant_name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'email' => 'required|email|max:255',
                'address' => 'required|string|max:500',
                'footer_text' => 'required|string|max:1000',
                'latitude' => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ], [
                'restaurant_name.required' => __('Business name is required'),
                'restaurant_name.max' => __('Business name cannot exceed 255 characters'),
                'phone.required' => __('Phone number is required'),
                'phone.max' => __('Phone number cannot exceed 20 characters'),
                'email.required' => __('Email address is required'),
                'email.email' => __('Please provide a valid email address'),
                'address.required' => __('Address is required'),
                'address.max' => __('Address cannot exceed 500 characters'),
                'footer_text.required' => __('Footer text is required'),
                'footer_text.max' => __('Footer text cannot exceed 1000 characters'),
                'latitude.required' => __('Latitude is required'),
                'latitude.numeric' => __('Latitude must be a valid number'),
                'latitude.between' => __('Latitude must be between -90 and 90'),
                'longitude.required' => __('Longitude is required'),
                'longitude.numeric' => __('Longitude must be a valid number'),
                'longitude.between' => __('Longitude must be between -180 and 180'),
                'logo.image' => __('Logo must be a valid image file'),
                'logo.mimes' => __('Logo must be jpeg, png, jpg, or gif format'),
                'logo.max' => __('Logo size must not exceed 2MB'),
                'icon.image' => __('Icon must be a valid image file'),
                'icon.mimes' => __('Icon must be jpeg, png, jpg, or gif format'),
                'icon.max' => __('Icon size must not exceed 2MB'),
            ]);

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', __('Please fix the validation errors and try again.'));
            }

            // Start database transaction
            DB::beginTransaction();

            // Update business name
            DB::table('business_settings')->updateOrInsert(['key' => 'business_name'], [
                'value' => $request['restaurant_name'],
                'updated_at' => now()
            ]);

            // Handle logo upload
            $curr_logo = BusinessSetting::where(['key' => 'logo'])->first();
            if ($request->hasFile('logo')) {
                try {
                    $logo_name = Helpers::updateFile($request->file('logo'), 'business/', ($curr_logo ? $curr_logo->value : null));
                    DB::table('business_settings')->updateOrInsert(['key' => 'logo'], [
                        'value' => $logo_name,
                        'updated_at' => now()
                    ]);
                } catch (\Exception $e) {
                    DB::rollBack();
                    return back()->with('error', __('Failed to upload logo. Please try again.'));
                }
            }

            // Handle icon upload
            $fav_icon = BusinessSetting::where(['key' => 'icon'])->first();
            if ($request->hasFile('icon')) {
                try {
                    $icon_name = Helpers::updateFile($request->file('icon'), 'business/', ($fav_icon ? $fav_icon->value : null));
                    DB::table('business_settings')->updateOrInsert(['key' => 'icon'], [
                        'value' => $icon_name,
                        'updated_at' => now()
                    ]);
                } catch (\Exception $e) {
                    DB::rollBack();
                    return back()->with('error', __('Failed to upload icon. Please try again.'));
                }
            }

            // Update other business settings
            $settings = [
                'phone' => $request['phone'],
                'email_address' => $request['email'],
                'address' => $request['address'],
                'footer_text' => $request['footer_text'],
                'default_location' => json_encode(['lat' => $request['latitude'], 'lng' => $request['longitude']])
            ];

            foreach ($settings as $key => $value) {
                DB::table('business_settings')->updateOrInsert(['key' => $key], [
                    'value' => $value,
                    'updated_at' => now()
                ]);
            }

            // Commit transaction
            DB::commit();

            return back()->with('success', __('Business settings updated successfully. To see the changes in app, restart the app.'));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Business setup error: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', __('An error occurred while updating business settings. Please try again.'));
        }
    }

    public function mail_index()
    {
        return view('admin-views.business-settings.mail-index');
    }

    public function email_setup(Request $request)
    {
        if (env('APP_MODE') == 'demo') {
            return back()->with('info', __('messages.update_option_is_disable_for_demo'));
        }

        try {
            // Validate email configuration
            $validator = Validator::make($request->all(), [
                'mail_mailer' => 'required|string|in:smtp,sendmail,mailgun,ses,postmark,log,array',
                'mail_host' => 'required|string|max:255',
                'mail_port' => 'required|integer|between:1,65535',
                'mail_username' => 'required|string|max:255',
                'mail_password' => 'required|string|max:255',
                'mail_encryption' => 'nullable|string|in:tls,ssl',
                'mail_from_address' => 'required|email|max:255',
                'mail_from_name' => 'required|string|max:255',
            ], [
                'mail_mailer.required' => __('Mail mailer is required'),
                'mail_mailer.in' => __('Please select a valid mail mailer'),
                'mail_host.required' => __('Mail host is required'),
                'mail_host.max' => __('Mail host cannot exceed 255 characters'),
                'mail_port.required' => __('Mail port is required'),
                'mail_port.integer' => __('Mail port must be a valid number'),
                'mail_port.between' => __('Mail port must be between 1 and 65535'),
                'mail_username.required' => __('Mail username is required'),
                'mail_username.max' => __('Mail username cannot exceed 255 characters'),
                'mail_password.required' => __('Mail password is required'),
                'mail_password.max' => __('Mail password cannot exceed 255 characters'),
                'mail_encryption.in' => __('Mail encryption must be either TLS or SSL'),
                'mail_from_address.required' => __('From email address is required'),
                'mail_from_address.email' => __('Please provide a valid from email address'),
                'mail_from_address.max' => __('From email address cannot exceed 255 characters'),
                'mail_from_name.required' => __('From name is required'),
                'mail_from_name.max' => __('From name cannot exceed 255 characters'),
            ]);

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', __('Please fix the validation errors and try again.'));
            }

            // Start database transaction
            DB::beginTransaction();

            $mailConfig = [
                "mail_mailer" => $request['mail_mailer'],
                "mail_host" => $request['mail_host'],
                "mail_port" => (int) $request['mail_port'],
                "mail_username" => $request['mail_username'],
                "mail_password" => $request['mail_password'],
                "mail_encryption" => $request['mail_encryption'],
                "mail_from_address" => $request['mail_from_address'],
                "mail_from_name" => $request['mail_from_name'],
            ];

            BusinessSetting::updateOrInsert(
                ['key' => 'mail_config'],
                [
                    'value' => json_encode($mailConfig),
                    'updated_at' => now()
                ]
            );

            // Commit transaction
            DB::commit();

            return back()->with('success', __('Email configuration updated successfully'));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Email setup error: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', __('An error occurred while updating email configuration. Please try again.'));
        }
    }

    public function payment_index()
    {
        return view('admin-views.business-settings.payment-index');
    }

    public function payment_update(Request $request, $name)
    {

        if ($name == 'cash_on_delivery') {
            $payment = BusinessSetting::where('key', 'cash_on_delivery')->first();
            if (isset($payment) == false) {
                DB::table('business_settings')->insert([
                    'key'        => 'cash_on_delivery',
                    'value'      => json_encode([
                        'status' => $request['status'],
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('business_settings')->where(['key' => 'cash_on_delivery'])->update([
                    'key'        => 'cash_on_delivery',
                    'value'      => json_encode([
                        'status' => $request['status'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'digital_payment') {
            $payment = BusinessSetting::where('key', 'digital_payment')->first();
            if (isset($payment) == false) {
                DB::table('business_settings')->insert([
                    'key'        => 'digital_payment',
                    'value'      => json_encode([
                        'status' => $request['status'],
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('business_settings')->where(['key' => 'digital_payment'])->update([
                    'key'        => 'digital_payment',
                    'value'      => json_encode([
                        'status' => $request['status'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'ssl_commerz_payment') {
            $payment = BusinessSetting::where('key', 'ssl_commerz_payment')->first();
            if (isset($payment) == false) {
                DB::table('business_settings')->insert([
                    'key'        => 'ssl_commerz_payment',
                    'value'      => json_encode([
                        'status'         => 1,
                        'store_id'       => '',
                        'store_password' => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('business_settings')->where(['key' => 'ssl_commerz_payment'])->update([
                    'key'        => 'ssl_commerz_payment',
                    'value'      => json_encode([
                        'status'         => $request['status'],
                        'store_id'       => $request['store_id'],
                        'store_password' => $request['store_password'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'razor_pay') {
            $payment = BusinessSetting::where('key', 'razor_pay')->first();
            if (isset($payment) == false) {
                DB::table('business_settings')->insert([
                    'key'        => 'razor_pay',
                    'value'      => json_encode([
                        'status'       => 1,
                        'razor_key'    => '',
                        'razor_secret' => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('business_settings')->where(['key' => 'razor_pay'])->update([
                    'key'        => 'razor_pay',
                    'value'      => json_encode([
                        'status'       => $request['status'],
                        'razor_key'    => $request['razor_key'],
                        'razor_secret' => $request['razor_secret'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'paypal') {
            $payment = BusinessSetting::where('key', 'paypal')->first();
            if (isset($payment) == false) {
                DB::table('business_settings')->insert([
                    'key'        => 'paypal',
                    'value'      => json_encode([
                        'status'           => 1,
                        'paypal_client_id' => '',
                        'paypal_secret'    => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('business_settings')->where(['key' => 'paypal'])->update([
                    'key'        => 'paypal',
                    'value'      => json_encode([
                        'status'           => $request['status'],
                        'paypal_client_id' => $request['paypal_client_id'],
                        'paypal_secret'    => $request['paypal_secret'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'stripe') {
            $payment = BusinessSetting::where('key', 'stripe')->first();
            if (isset($payment) == false) {
                DB::table('business_settings')->insert([
                    'key'        => 'stripe',
                    'value'      => json_encode([
                        'status'        => 1,
                        'api_key'       => '',
                        'published_key' => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('business_settings')->where(['key' => 'stripe'])->update([
                    'key'        => 'stripe',
                    'value'      => json_encode([
                        'status'        => $request['status'],
                        'api_key'       => $request['api_key'],
                        'published_key' => $request['published_key'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'senang_pay') {
            $payment = BusinessSetting::where('key', 'senang_pay')->first();
            if (isset($payment) == false) {
                DB::table('business_settings')->insert([

                    'key'        => 'senang_pay',
                    'value'      => json_encode([
                        'status'        => 1,
                        'secret_key'    => '',
                        'published_key' => '',
                        'merchant_id' => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('business_settings')->where(['key' => 'senang_pay'])->update([
                    'key'        => 'senang_pay',
                    'value'      => json_encode([
                        'status'        => $request['status'],
                        'secret_key'    => $request['secret_key'],
                        'published_key' => $request['publish_key'],
                        'merchant_id' => $request['merchant_id'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'paystack') {
            $payment = BusinessSetting::where('key', 'paystack')->first();
            if (isset($payment) == false) {
                DB::table('business_settings')->insert([
                    'key'        => 'paystack',
                    'value'      => json_encode([
                        'status'        => 1,
                        'publicKey'     => '',
                        'secretKey'     => '',
                        'paymentUrl'    => '',
                        'merchantEmail' => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('business_settings')->where(['key' => 'paystack'])->update([
                    'key'        => 'paystack',
                    'value'      => json_encode([
                        'status'        => $request['status'],
                        'publicKey'     => $request['publicKey'],
                        'secretKey'     => $request['secretKey'],
                        'paymentUrl'    => $request['paymentUrl'],
                        'merchantEmail' => $request['merchantEmail'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'flutterwave') {
            $payment = BusinessSetting::where('key', 'flutterwave')->first();
            if (isset($payment) == false) {
                DB::table('business_settings')->insert([
                    'key'        => 'flutterwave',
                    'value'      => json_encode([
                        'status'        => 1,
                        'public_key'     => '',
                        'secret_key'     => '',
                        'hash'    => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('business_settings')->where(['key' => 'flutterwave'])->update([
                    'key'        => 'flutterwave',
                    'value'      => json_encode([
                        'status'        => $request['status'],
                        'public_key'     => $request['public_key'],
                        'secret_key'     => $request['secret_key'],
                        'hash'    => $request['hash'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'mercadopago') {
            $payment = BusinessSetting::updateOrInsert(
                ['key' => 'mercadopago'],
                [
                    'value'      => json_encode([
                        'status'        => $request['status'],
                        'public_key'     => $request['public_key'],
                        'access_token'     => $request['access_token'],
                    ]),
                    'updated_at' => now()
                ]
            );
        } elseif ($name == 'paymob_accept') {
            DB::table('business_settings')->updateOrInsert(['key' => 'paymob_accept'], [
                'value' => json_encode([
                    'status' => $request['status'],
                    'api_key' => $request['api_key'],
                    'iframe_id' => $request['iframe_id'],
                    'integration_id' => $request['integration_id'],
                    'hmac' => $request['hmac'],
                ]),
                'updated_at' => now()
            ]);
        } elseif ($name == 'liqpay') {
            DB::table('business_settings')->updateOrInsert(['key' => 'liqpay'], [
                'value' => json_encode([
                    'status' => $request['status'],
                    'public_key' => $request['public_key'],
                    'private_key' => $request['private_key']
                ]),
                'updated_at' => now()
            ]);
        } elseif ($name == 'paytm') {
            DB::table('business_settings')->updateOrInsert(['key' => 'paytm'], [
                'value' => json_encode([
                    'status' => $request['status'],
                    'paytm_merchant_key' => $request['paytm_merchant_key'],
                    'paytm_merchant_mid' => $request['paytm_merchant_mid'],
                    'paytm_merchant_website' => $request['paytm_merchant_website'],
                    'paytm_refund_url' => $request['paytm_refund_url'],
                ]),
                'updated_at' => now()
            ]);
        } elseif ($name == 'bkash') {
            DB::table('business_settings')->updateOrInsert(['key' => 'bkash'], [
                'value' => json_encode([
                    'status' => $request['status'],
                    'api_key' => $request['api_key'],
                    'api_secret' => $request['api_secret'],
                    'username' => $request['username'],
                    'password' => $request['password'],
                ]),
                'updated_at' => now()
            ]);
        } elseif ($name == 'paytabs') {
            DB::table('business_settings')->updateOrInsert(['key' => 'paytabs'], [
                'value' => json_encode([
                    'status' => $request['status'],
                    'profile_id' => $request['profile_id'],
                    'server_key' => $request['server_key'],
                    'base_url' => $request['base_url']
                ]),
                'updated_at' => now()
            ]);
        } elseif ($name == 'phonepe') {
            DB::table('business_settings')->updateOrInsert(['key' => 'phonepe'], [
                'value' => json_encode([
                    'status' => $request['status'],
                    'base_url' => $request['base_url'],
                    'merchant_id' => $request['merchant_id'],
                    'salt_key' => $request['salt_key'],
                    'salt_index' => $request['salt_index'],
                ]),
                'updated_at' => now()
            ]);
        }

        return back()->with('success',__('messages.payment_settings_updated'));
    }
    public function theme_settings()
    {
        return view('admin-views.business-settings.theme-settings');
    }
    public function update_theme_settings(Request $request)
    {
        if (env('APP_MODE') == 'demo') {
            return back()->with('info',__('messages.update_option_is_disable_for_demo') );
        }
        DB::table('business_settings')->updateOrInsert(['key' => 'theme'], [
            'value' => $request['theme']
        ]);
        return back()->with('success',__('theme_settings_updated') );
    }

    public function app_settings()
    {
        return view('admin-views.business-settings.app-settings');
    }

    public function update_app_settings(Request $request)
    {
        if (env('APP_MODE') == 'demo') {
            return back()->with('info',__('messages.update_option_is_disable_for_demo') );
        }
        DB::table('business_settings')->updateOrInsert(['key' => 'app_minimum_version_android_restaurant'], [
            'value' => $request['app_minimum_version_android_restaurant']
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'app_url_android_restaurant'], [
            'value' => $request['app_url_android_restaurant']
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'app_minimum_version_ios_restaurant'], [
            'value' => $request['app_minimum_version_ios_restaurant']
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'app_url_ios_restaurant'], [
            'value' => $request['app_url_ios_restaurant']
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'app_minimum_version_android_deliveryman'], [
            'value' => $request['app_minimum_version_android_deliveryman']
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'app_url_android_deliveryman'], [
            'value' => $request['app_url_android_deliveryman']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'app_minimum_version_android'], [
            'value' => $request['app_minimum_version_android']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'app_minimum_version_ios'], [
            'value' => $request['app_minimum_version_ios']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'app_url_android'], [
            'value' => $request['app_url_android']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'app_url_ios'], [
            'value' => $request['app_url_ios']
        ]);
        return back()->with('success',__('messages.app_settings_updated') );
    }

    public function landing_page_settings($tab)
    {
        if ($tab == 'index') {
            return view('admin-views.business-settings.landing-page-settings.index');
        } else if ($tab == 'links') {
            return view('admin-views.business-settings.landing-page-settings.links');
        } else if ($tab == 'speciality') {
            return view('admin-views.business-settings.landing-page-settings.speciality');
        } else if ($tab == 'testimonial') {
            return view('admin-views.business-settings.landing-page-settings.testimonial');
        } else if ($tab == 'feature') {
            return view('admin-views.business-settings.landing-page-settings.feature');
        } else if ($tab == 'image') {
            return view('admin-views.business-settings.landing-page-settings.image');
        } else if ($tab == 'backgroundChange') {
            return view('admin-views.business-settings.landing-page-settings.backgroundChange');
        }  else if ($tab == 'react') {
            return view('admin-views.business-settings.landing-page-settings.react');
        } else if ($tab == 'react-feature') {
            return view('admin-views.business-settings.landing-page-settings.react_feature');
        } else if ($tab == 'platform-order') {
            return view('admin-views.business-settings.landing-page-settings.our_platform');
        } else if ($tab == 'platform-restaurant') {
            return view('admin-views.business-settings.landing-page-settings.restaurant_platform');
        } else if ($tab == 'platform-delivery') {
            return view('admin-views.business-settings.landing-page-settings.delivery_platform');
        } else if ($tab == 'react-half-banner') {
            return view('admin-views.business-settings.landing-page-settings.react_half_banner');
        }
    }

    public function update_landing_page_settings(Request $request, $tab)
    {
        if (env('APP_MODE') == 'demo') {
            return back()->with('info',__('messages.update_option_is_disable_for_demo') );
        }

        if ($tab == 'text') {
            DB::table('business_settings')->updateOrInsert(['key' => 'landing_page_text'], [
                'value' => json_encode([
                    'header_title_1' => $request['header_title_1'],
                    'header_title_2' => $request['header_title_2'],
                    'header_title_3' => $request['header_title_3'],
                    'about_title' => $request['about_title'],
                    'why_choose_us' => $request['why_choose_us'],
                    'why_choose_us_title' => $request['why_choose_us_title'],
                    'testimonial_title' => $request['testimonial_title'],
                    'mobile_app_section_heading' => $request['mobile_app_section_heading'],
                    'mobile_app_section_text' => $request['mobile_app_section_text'],
                    'feature_section_description' => $request['feature_section_description'],
                    'feature_section_title' => $request['feature_section_title'],
                    'footer_article' => $request['footer_article'],

                    'join_us_title' => $request['join_us_title'],
                    'join_us_sub_title' => $request['join_us_sub_title'],
                    'join_us_article' => $request['join_us_article'],
                    'our_platform_title' => $request['our_platform_title'],
                    'our_platform_article' => $request['our_platform_article'],
                    'newsletter_title' => $request['newsletter_title'],
                    'newsletter_article' => $request['newsletter_article'],
                ])
            ]);
            Session::flash('success',__('messages.landing_page_text_updated') );
        } else if ($tab == 'links') {
            DB::table('business_settings')->updateOrInsert(['key' => 'landing_page_links'], [
                'value' => json_encode([
                    'app_url_android_status' => $request['app_url_android_status'],
                    'app_url_android' => $request['app_url_android'],
                    'app_url_ios_status' => $request['app_url_ios_status'],
                    'app_url_ios' => $request['app_url_ios'],
                    'web_app_url_status' => $request['web_app_url_status'],
                    'web_app_url' => $request['web_app_url'],
                    'order_now_url_status' => $request['order_now_url_status'],
                    'order_now_url' => $request['order_now_url']
                ])
            ]);

            Session::flash('success', __('messages.landing_page_links_updated'));
        } else if ($tab == 'speciality') {
            $data = [];
            $imageName = null;
            $speciality = BusinessSetting::where('key', 'speciality')->first();
            if ($speciality) {
                $data = json_decode($speciality->value, true);
            }
            if ($request->has('image')) {
                $validator = Validator::make($request->all(), [
                    'image' => 'required|max:2048',
                ]);
                if ($validator->fails()) {

                return back()->with('error',__('Image size must be within 2mb') );
                }

                $imageName = \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . ".png";
                $request->image->move(public_path('assets/landing/image'), $imageName);
            }
            array_push($data, [
                'img' => $imageName,
                'title' => $request->speciality_title
            ]);

            DB::table('business_settings')->updateOrInsert(['key' => 'speciality'], [
                'value' => json_encode($data)
            ]);

            Session::flash('success',__('messages.landing_page_speciality_updated') );
        } else if ($tab == 'feature') {
            $data = [];
            $imageName = null;
            $feature = BusinessSetting::where('key', 'feature')->first();
            if ($feature) {
                $data = json_decode($feature->value, true);
            }
            if ($request->has('image')) {
                $validator = Validator::make($request->all(), [
                    'image' => 'required|max:2048',
                ]);
                if ($validator->fails()) {

                return back()->with('error',__('Image size must be within 2mb') );
                }
                $imageName = \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . ".png";
                $request->image->move(public_path('assets/landing/image'), $imageName);
            }
            array_push($data, [
                'img' => $imageName,
                'title' => $request->feature_title,
                'feature_description' => $request->feature_description
            ]);

            DB::table('business_settings')->updateOrInsert(['key' => 'feature'], [
                'value' => json_encode($data)
            ]);

            Session::flash('success', __('messages.landing_page_feature_updated'));
        }
         else if ($tab == 'testimonial') {
            $data = [];
            $imageName = null;
            $speciality = BusinessSetting::where('key', 'testimonial')->first();
            if ($speciality) {
                $data = json_decode($speciality->value, true);
            }
            if ($request->has('image')) {
                $validator = Validator::make($request->all(), [
                    'image' => 'required|max:2048',
                ]);
                if ($validator->fails()) {
                return back()->with('error',__('Image size must be within 2mb') );
                }
                $imageName = \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . ".png";
                $request->image->move(public_path('assets/landing/image'), $imageName);
            }
            array_push($data, [
                'img' => $imageName,
                'name' => $request->reviewer_name,
                'position' => $request->reviewer_designation,
                'detail' => $request->review,
            ]);

            DB::table('business_settings')->updateOrInsert(['key' => 'testimonial'], [
                'value' => json_encode($data)
            ]);
            Session::flash('success', __('messages.landing_page_testimonial_updated'));
        }
        else if ($tab == 'image') {
            $data = [];
            $images = BusinessSetting::where('key', 'landing_page_images')->first();
            if ($images) {
                $data = json_decode($images->value, true);
            }
            if ($request->has('top_content_image')) {
                $validator = Validator::make($request->all(), [
                    'top_content_image' => 'required|max:2048',
                ]);
                if ($validator->fails()) {

                return back()->with('error',__('Image size must be within 2mb') );
                }
                $imageName = \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . ".png";
                $request->top_content_image->move(public_path('assets/landing/image'), $imageName);
                $data['top_content_image'] = $imageName;
            }
            if ($request->has('about_us_image')) {
                $validator = Validator::make($request->all(), [
                    'about_us_image' => 'required|max:2048',
                ]);
                if ($validator->fails()) {

                return back()->with('error',__translate('Image size must be within 2mb') );
                 }
                $imageName = \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . ".png";
                $request->about_us_image->move(public_path('assets/landing/image'), $imageName);
                $data['about_us_image'] = $imageName;
            }

            if ($request->has('feature_section_image')) {
                $validator = Validator::make($request->all(), [
                    'feature_section_image' => 'required|max:2048',
                ]);
                if ($validator->fails()) {

                return back()->with('error',__('Image size must be within 2mb') );
                    }
                $imageName = \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . ".png";
                $request->feature_section_image->move(public_path('assets/landing/image'), $imageName);
                $data['feature_section_image'] = $imageName;
            }
            if ($request->has('mobile_app_section_image')) {
                $validator = Validator::make($request->all(), [
                    'mobile_app_section_image' => 'required|max:2048',
                ]);
                if ($validator->fails()) {

                return back()->with('error',__('Image size must be within 2mb') );
                }
                $imageName = \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . ".png";
                $request->mobile_app_section_image->move(public_path('assets/landing/image'), $imageName);
                $data['mobile_app_section_image'] = $imageName;
            }
            DB::table('business_settings')->updateOrInsert(['key' => 'landing_page_images'], [
                'value' => json_encode($data)
            ]);

            Session::flash('success', __('messages.landing_page_image_updated'));
        } else if ($tab == 'background-change') {
            DB::table('business_settings')->updateOrInsert(['key' => 'backgroundChange'], [
                // 'value' => json_encode([
                //     'header-bg' => $request['header-bg'],
                //     'footer-bg' => $request['footer-bg'],
                //     'landing-page-bg' => $request['landing-page-bg']
                // ])
                'value' => json_encode([
                    'primary_1_hex' => $request['header-bg'],
                    'primary_1_rgb' => Helpers::hex_to_rbg($request['header-bg']),
                    'primary_2_hex' => $request['footer-bg'],
                    'primary_2_rgb' => Helpers::hex_to_rbg($request['footer-bg']),
                ])
            ]);

            Session::flash('success', __('messages.background_updated'));
        } else if ($tab == 'react_header') {
            $data = null;
            $image = BusinessSetting::where('key', 'react_header_banner')->first();
            if ($image) {
                $data = $image->value;
            }
            $image_name =$data ?? \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . ".png";
            if ($request->has('react_header_banner')) {
                // $image_name = ;
                $validator = Validator::make($request->all(), [
                    'react_header_banner' => 'required|max:2048',
                ]);
                if ($validator->fails()) {

                return back()->with('error',__('Image size must be within 2mb') );
                }
                // $data = Helpers::update('react_landing/', $image_name, 'png', $request->file('react_header_banner')) ?? null;
            }
            DB::table('business_settings')->updateOrInsert(['key' => 'react_header_banner'], [
                'value' => $data
            ]);

            Session::flash('success', __('Landing page header banner updated'));
        } else if ($tab == 'full-banner') {
            $data = [];
            $banner_section_full = BusinessSetting::where('key','banner_section_full')->first();
            $imageName = null;
            if($banner_section_full){
                $data = json_decode($banner_section_full->value, true);
                $imageName =$data['banner_section_img_full'] ?? null;
            }
            if ($request->has('banner_section_img_full'))   {
                $validator = Validator::make($request->all(), [
                    'banner_section_img_full' => 'required|max:2048',
                ]);
                if ($validator->fails()) {

                return back()->with('error',__('Image size must be within 2mb') );
                }

                if (empty($imageName)) {
                    $imageName = Helpers::upload('react_landing/', 'png', $request->file('banner_section_img_full'));
                    }  else{
                    $imageName= Helpers::update('react_landing/', $data['banner_section_img_full'], 'png', $request->file('banner_section_img_full')) ;
                    }
            }
            $data = [
                'banner_section_img_full' => $imageName,
                'full_banner_section_title' => $request->full_banner_section_title ?? $banner_section_full['full_banner_section_title'] ,
                'full_banner_section_sub_title' => $request->full_banner_section_sub_title ?? $banner_section_full['full_banner_section_sub_title'],
            ];
            DB::table('business_settings')->updateOrInsert(['key' => 'banner_section_full'], [
                'value' => json_encode($data)
            ]);

            Session::flash('success', __('messages.landing_page_banner_section_updated'));
        } else if ($tab == 'discount-banner') {
            $data = [];
            $discount_banner = BusinessSetting::where('key','discount_banner')->first();
            $imageName = null;
            if($discount_banner){
                $data = json_decode($discount_banner->value, true);
                $imageName =$data['img'] ?? null;
            }
            if ($request->has('img'))   {
                $validator = Validator::make($request->all(), [
                    'img' => 'required|max:2048',
                ]);
                if ($validator->fails()) {

                return back()->with('error',__('Image size must be within 2mb') );
                }
                if (empty($imageName)) {
                    // $imageName = Helpers::upload('react_landing/', 'png', $request->file('img'));
                    }  else{
                    // $imageName= Helpers::update('react_landing/', $data['img'], 'png', $request->file('img')) ;
                    }
            }
            $data = [
                'img' => $imageName,
                'title' => $request->title ?? $discount_banner['title'] ,
                'sub_title' => $request->sub_title ?? $discount_banner['sub_title'],
            ];
            DB::table('business_settings')->updateOrInsert(['key' => 'discount_banner'], [
                'value' => json_encode($data)
            ]);

            Session::flash('success', __('messages.landing_page_discount_banner_section_updated'));

        } else if ($tab == 'banner-section-half') {
            $data = [];
            $imageName = null;
            $banner_section_half = BusinessSetting::where('key', 'banner_section_half')->first();
            if ($banner_section_half) {
                $data = json_decode($banner_section_half->value, true);
            }

            if ($request->has('image')) {
                $validator = Validator::make($request->all(), [
                    'image' => 'required|max:2048',
                ]);
                if ($validator->fails()) {
                return back()->with('error',__('Image size must be within 2mb') );
                }
                // $imageName=Helpers::upload('react_landing/','png', $request->file('image')) ;
            }
            array_push($data, [
                'img' => $imageName,
                'title' => $request->title,
                'sub_title' => $request->sub_title
            ]);

            DB::table('business_settings')->updateOrInsert(['key' => 'banner_section_half'], [
                'value' => json_encode($data)
            ]);

            Session::flash('success', __('messages.landing_page_banner_section_updated'));
        } else if ($tab == 'app_section_image') {
            $data = null;
            $image = BusinessSetting::where('key', 'app_section_image')->first();
            if ($image) {
                $data = $image->value;
            }
            $image_name =$data ?? \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . ".png";
            if ($request->has('app_section_image')) {
                $validator = Validator::make($request->all(), [
                    'app_section_image' => 'required|max:2048',
                ]);
                if ($validator->fails()) {

                return back()->with('error',__('Image size must be within 2mb') );
                }
                // $data = Helpers::update('react_landing/', $image_name, 'png', $request->file('app_section_image')) ?? null;
            }
            DB::table('business_settings')->updateOrInsert(['key' => 'app_section_image'], [
                'value' => $data
            ]);

            Session::flash('success', __('App section image updated'));
        } else if ($tab == 'footer_logo') {
            $data = null;
            $image = BusinessSetting::where('key', 'footer_logo')->first();
            if ($image) {
                $data = $image->value;
            }
            $image_name =$data ?? \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . ".png";
            if ($request->has('footer_logo')) {
                $validator = Validator::make($request->all(), [
                    'footer_logo' => 'required|max:2048',
                ]);
                if ($validator->fails()) {

                return back()->with('error',__('Image size must be within 2mb') );
                }
                // $data = Helpers::update('react_landing/', $image_name, 'png', $request->file('footer_logo')) ?? null;
            }
            DB::table('business_settings')->updateOrInsert(['key' => 'footer_logo'], [
                'value' => $data
            ]);

            Session::flash('success', __('Footer logo updated'));
        }  else if ($tab == 'react-feature') {
            $data = [];
            $imageName = null;
            $feature = BusinessSetting::where('key', 'react_feature')->first();
            if ($feature) {
                $data = json_decode($feature->value, true);
            }
            if ($request->has('image')) {
                $validator = Validator::make($request->all(), [
                    'image' => 'required|max:2048',
                ]);
                if ($validator->fails()) {

                return back()->with('error',__('Image size must be within 2mb') );
                }
                $imageName=Helpers::upload('react_landing/feature/','png', $request->file('image')) ;
            }
            array_push($data, [
                'img' => $imageName,
                'title' => $request->feature_title,
                'feature_description' => $request->feature_description
            ]);

            DB::table('business_settings')->updateOrInsert(['key' => 'react_feature'], [
                'value' => json_encode($data)
            ]);

            Session::flash('success', __('messages.landing_page_feature_updated'));
        } else if ($tab == 'platform-main') {

            if($request->button == 'restaurant_platform'){
                $data = [];
                $imageName = null;
                $restaurant_platform = BusinessSetting::where('key', 'restaurant_platform')->first();
                if ($restaurant_platform) {
                    $data = json_decode($restaurant_platform->value, true);
                    $imageName = $data['image'] ?? null;
                }

                $image_name =$data['image'] ?? \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . ".png";
                if ($request->has('image')) {
                    $validator = Validator::make($request->all(), [
                        'image' => 'required|max:2048',
                    ]);
                    if ($validator->fails()) {

                    return back()->with('error',__('Image size must be within 2mb') );
                    }

                    // $imageName  = Helpers::update('landing/', $image_name, 'png', $request->file('image')) ?? null;
                }

                $data= [
                    'image' => $imageName,
                    'title' => $request->title,
                    'url' => $request->url,
                    'url_status' => $request->url_status ?? 0,
                ];

                DB::table('business_settings')->updateOrInsert(['key' => 'restaurant_platform'], [
                    'value' => json_encode($data)
                ]);
            }
            if($request->button == 'order_platform'){

                $data = [];
                $imageName = null;
                $order_platform = BusinessSetting::where('key', 'order_platform')->first();
                if ($order_platform) {
                    $data = json_decode($order_platform->value, true);
                    $imageName = $data['image'] ?? null;
                }
                $image_name =$data['image'] ?? \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . ".png";
                if ($request->has('image')) {
                    $validator = Validator::make($request->all(), [
                        'image' => 'required|max:2048',
                    ]);
                    if ($validator->fails()) {

                    return back()->with('error',__('Image size must be within 2mb') );
                    }
                    // $imageName  = Helpers::update('landing/', $image_name, 'png', $request->file('image')) ?? null;
                }
                $data= [
                    'image' => $imageName,
                    'title' => $request->title,
                    'url' => $request->url,
                    'url_status' => $request->url_status ?? 0,
                ];

                DB::table('business_settings')->updateOrInsert(['key' => 'order_platform'], [
                    'value' => json_encode($data)
                ]);
            }
            if($request->button == 'delivery_platform'){
                // dd($request->all());
                $data = [];
                $imageName = null;
                $delivery_platform = BusinessSetting::where('key', 'delivery_platform')->first();
                if ($delivery_platform) {
                    $data = json_decode($delivery_platform->value, true);
                    $imageName = $data['image'] ?? null;
                }
                $image_name =$data['image'] ?? \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . ".png";
                if ($request->has('image')) {
                    $validator = Validator::make($request->all(), [
                        'image' => 'required|max:2048',
                    ]);
                    if ($validator->fails()) {

                    return back()->with('error',__('Image size must be within 2mb') );
                    }
                    // $imageName  = Helpers::update('landing/', $image_name, 'png', $request->file('image')) ?? null;
                }
                $data= [
                    'image' => $imageName,
                    'title' => $request->title,
                    // 'sub_title' => $request->sub_title,
                    // 'detail' => $request->detail,
                    'url' => $request->url,
                    'url_status' => $request->url_status ?? 0,
                ];

                DB::table('business_settings')->updateOrInsert(['key' => 'delivery_platform'], [
                    'value' => json_encode($data)
                ]);
            }

            Session::flash('success', __('messages.landing_page_our_platform_updated'));
        }


        else if ($tab == 'platform-data') {
            if($request->button == 'platform_order_data'){
                $data = [];
                $imageName = null;
                $platform_order_data = BusinessSetting::where('key', 'platform_order_data')->first();
                if ($platform_order_data) {
                    $data = json_decode($platform_order_data->value, true);
                }
                array_push($data, [
                    'title' => $request->title,
                    'detail' => $request->detail,
                ]);
                DB::table('business_settings')->updateOrInsert(['key' => 'platform_order_data'], [
                    'value' => json_encode($data)
                ]);
                Session::flash('success', __('messages.landing_page_order_platform_data_added'));
            }
            if($request->button == 'platform_restaurant_data'){
                $data = [];
                $imageName = null;
                $platform_restaurant_data = BusinessSetting::where('key', 'platform_restaurant_data')->first();
                if ($platform_restaurant_data) {
                    $data = json_decode($platform_restaurant_data->value, true);
                }
                array_push($data, [
                    'title' => $request->title,
                    'detail' => $request->detail,
                ]);
                DB::table('business_settings')->updateOrInsert(['key' => 'platform_restaurant_data'], [
                    'value' => json_encode($data)
                ]);

                Session::flash('success', __('messages.landing_page_restaurant_platform_data_added'));
            }
            if($request->button == 'platform_delivery_data'){
                $data = [];
                $imageName = null;
                $platform_delivery_data = BusinessSetting::where('key', 'platform_delivery_data')->first();
                if ($platform_delivery_data) {
                    $data = json_decode($platform_delivery_data->value, true);
                }
                array_push($data, [
                    'title' => $request->title,
                    'detail' => $request->detail,
                ]);
                DB::table('business_settings')->updateOrInsert(['key' => 'platform_delivery_data'], [
                    'value' => json_encode($data)
                ]);

                Session::flash('success', __('messages.landing_page_delivary_platform_data_updated'));
            }

        }

        return back()->with('',__ );
    }

    public function delete_landing_page_settings($tab, $key)
    {
        if (env('APP_MODE') == 'demo') {

            return back()->with('info',__('messages.update_option_is_disable_for_demo') );
        }
        $item = BusinessSetting::where('key', $tab)->first();
        $data = $item ? json_decode($item->value, true) : null;
        if ($data && array_key_exists($key, $data)) {
            if($tab == 'react_feature' && isset($data[$key]['img']) && Storage::disk('public')->exists('react_landing/feature/'. $data[$key]['img'])){
                Storage::disk('public')->delete('react_landing/feature/'. $data[$key]['img']);
            }
            if ( $tab != 'react_feature' && isset($data[$key]['img']) && file_exists(public_path('assets/landing/image') . $data[$key]['img'])) {
                unlink(public_path('assets/landing/image') . $data[$key]['img']);
            }

            array_splice($data, $key, 1);

            $item->value = json_encode($data);
            $item->save();

            return back()->with('success',__('messages.' . $tab) . ' ' . __('messages.deleted') );
        }

        return back()->with('error',__('messages.not_found') );

    }

    public function currency_index()
    {
        return view('admin-views.business-settings.currency-index');
    }

    public function currency_store(Request $request)
    {
        $request->validate([
            'currency_code' => 'required|unique:currencies',
        ]);

        Currency::create([
            "country" => $request['country'],
            "currency_code" => $request['currency_code'],
            "currency_symbol" => $request['symbol'],
            "exchange_rate" => $request['exchange_rate'],
        ]);

        return back()->with('success',__('messages.currency_added_successfully') );
    }

    public function currency_edit($id)
    {
        $currency = Currency::find($id);
        return view('admin-views.business-settings.currency-update', compact('currency'));
    }

    public function currency_update(Request $request, $id)
    {
        Currency::where(['id' => $id])->update([
            "country" => $request['country'],
            "currency_code" => $request['currency_code'],
            "currency_symbol" => $request['symbol'],
            "exchange_rate" => $request['exchange_rate'],
        ]);

        return redirect('restaurant-panel/business-settings/currency-add')->with('success',('messages.currency_updated_successfully'));
    }

    public function currency_delete($id)
    {
        Currency::where(['id' => $id])->delete();

        return back()->with('success',__('messages.currency_deleted_successfully') );
    }

    public function terms_and_conditions()
    {
        $tnc = BusinessSetting::where(['key' => 'terms_and_conditions'])->first();
        if ($tnc == false) {
            BusinessSetting::insert([
                'key' => 'terms_and_conditions',
                'value' => ''
            ]);
        }
        return view('admin-views.business-settings.terms-and-conditions', compact('tnc'));
    }

    public function terms_and_conditions_update(Request $request)
    {
        BusinessSetting::where(['key' => 'terms_and_conditions'])->update([
            'value' => $request->tnc
        ]);


        return back()->with('success',__('messages.terms_and_condition_updated') );
    }

    public function privacy_policy()
    {
        $data = BusinessSetting::where(['key' => 'privacy_policy'])->first();
        if ($data == false) {
            $data = [
                'key' => 'privacy_policy',
                'value' => '',
            ];
            BusinessSetting::insert($data);
        }
        return view('admin-views.business-settings.privacy-policy', compact('data'));
    }

    public function privacy_policy_update(Request $request)
    {
        BusinessSetting::where(['key' => 'privacy_policy'])->update([
            'value' => $request->privacy_policy,
        ]);

        return back()->with('success',__('messages.privacy_policy_updated'));
    }

    public function refund_policy()
    {
        $data = BusinessSetting::where(['key' => 'refund_policy'])->first();
        if ($data == false) {

            $values= [
                'data' => '',
                'status' => 0,
            ];
            DB::table('business_settings')->updateOrInsert(['key' => 'refund_policy'], [
                'value' => json_encode($values)
            ]);
        }
        $data = json_decode(BusinessSetting::where(['key' => 'refund_policy'])->first()->value,true);
        return view('admin-views.business-settings.refund_policy', compact('data'));
    }

    public function refund_policy_update(Request $request)
    {
        $data = json_decode(BusinessSetting::where(['key' => 'refund_policy'])->first()->value,true);
        $values= [
            'data' => $request->refund_policy,
            'status' => $data['status'],
        ];
        BusinessSetting::where(['key' => 'refund_policy'])->update([
            'value' => $values,
        ]);

        return back()->with('success',__('messages.refund_policy_updated') );
    }
    public function refund_policy_status($status)
    {
        $data = json_decode(BusinessSetting::where(['key' => 'refund_policy'])->first()->value,true);
        $values= [
            'data' => $data['data'],
            'status' => $status ,
        ];
        BusinessSetting::where(['key' => 'refund_policy'])->update([
            'value' => $values,
        ]);

        return response()->json(['status'=>"changed"]);
    }

    public function shipping_policy()
    {
        $data = BusinessSetting::where(['key' => 'shipping_policy'])->first();
        if ($data == false) {

            $values= [
                'data' => '',
                'status' => 0,
            ];
            DB::table('business_settings')->updateOrInsert(['key' => 'shipping_policy'], [
                'value' => json_encode($values)
            ]);
        }
        $data = json_decode(BusinessSetting::where(['key' => 'shipping_policy'])->first()->value,true);
        return view('admin-views.business-settings.shipping_policy', compact('data'));
    }

    public function shipping_policy_update(Request $request)
    {
        $data = json_decode(BusinessSetting::where(['key' => 'shipping_policy'])->first()->value,true);
        $values= [
            'data' => $request->shipping_policy,
            'status' => $data['status'],
        ];
        BusinessSetting::where(['key' => 'shipping_policy'])->update([
            'value' => $values,
        ]);
        return back()->with('success',__('messages.shipping_policy_updated') );
    }


    public function shipping_policy_status($status)
    {
        $data = json_decode(BusinessSetting::where(['key' => 'shipping_policy'])->first()->value,true);
        $values= [
            'data' => $data['data'],
            'status' => $status,
        ];
        BusinessSetting::where(['key' => 'shipping_policy'])->update([
            'value' => $values,
        ]);
        return response()->json(['status'=>"changed"]);

    }

    public function cancellation_policy()
    {
        $data = BusinessSetting::where(['key' => 'cancellation_policy'])->first();
        if ($data == false) {
            $values= [
                'data' => '',
                'status' => 0,
            ];
            DB::table('business_settings')->updateOrInsert(['key' => 'cancellation_policy'], [
                'value' => json_encode($values)
            ]);
        }
        $data = json_decode(BusinessSetting::where(['key' => 'cancellation_policy'])->first()->value,true);
        return view('admin-views.business-settings.cancellation_policy', compact('data'));
    }

    public function cancellation_policy_update(Request $request)
    {
        $data = json_decode(BusinessSetting::where(['key' => 'cancellation_policy'])->first()->value,true);
        $values= [
            'data' => $request->cancellation_policy,
            'status' => $data['status'],
        ];
        BusinessSetting::where(['key' => 'cancellation_policy'])->update([
            'value' => $values,
        ]);

        return back()->with('success',__('messages.cancellation_policy_updated') );
    }

    public function cancellation_policy_status($status)
    {
        $data = json_decode(BusinessSetting::where(['key' => 'cancellation_policy'])->first()->value,true);
        $values= [
            'data' => $data['data'],
            'status' => $status,
        ];
        BusinessSetting::where(['key' => 'cancellation_policy'])->update([
            'value' => $values,
        ]);
        return response()->json(['status'=>"changed"]);
    }

    public function about_us()
    {
        $data = BusinessSetting::where(['key' => 'about_us'])->first();
        if ($data == false) {
            $data = [
                'key' => 'about_us',
                'value' => '',
            ];
            BusinessSetting::insert($data);
        }
        return view('admin-views.business-settings.about-us', compact('data'));
    }

    public function about_us_update(Request $request)
    {
        BusinessSetting::where(['key' => 'about_us'])->update([
            'value' => $request->about_us,
        ]);


        return back()->with('success',__('messages.about_us_updated') );
    }

    public function fcm_index()
    {
        $fcm_credentials = Helpers::get_business_settings('fcm_credentials');
        return view('admin-views.business-settings.fcm-index', compact('fcm_credentials'));
    }

    public function update_fcm(Request $request)
    {
        DB::table('business_settings')->updateOrInsert(['key' => 'fcm_project_id'], [
            'value' => $request['projectId']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'push_notification_key'], [
            'value' => $request['push_notification_key']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'fcm_credentials'], [
            'value' => json_encode([
                'apiKey'=> $request->apiKey,
                'authDomain'=> $request->authDomain,
                'projectId'=> $request->projectId,
                'storageBucket'=> $request->storageBucket,
                'messagingSenderId'=> $request->messagingSenderId,
                'appId'=> $request->appId,
                'measurementId'=> $request->measurementId
            ])
        ]);

        return back()->with('success',__('messages.settings_updated') );
    }

    public function update_fcm_messages(Request $request)
    {
        DB::table('business_settings')->updateOrInsert(['key' => 'order_pending_message'], [
            'value' => json_encode([
                'status' => $request['pending_status'] == 1 ? 1 : 0,
                'message' => $request['pending_message']
            ])
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'order_confirmation_msg'], [
            'value' => json_encode([
                'status' => $request['confirm_status'] == 1 ? 1 : 0,
                'message' => $request['confirm_message']
            ])
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'order_processing_message'], [
            'value' => json_encode([
                'status' => $request['processing_status'] == 1 ? 1 : 0,
                'message' => $request['processing_message']
            ])
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'out_for_delivery_message'], [
            'value' => json_encode([
                'status' => $request['out_for_delivery_status'] == 1 ? 1 : 0,
                'message' => $request['out_for_delivery_message']
            ])
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'order_delivered_message'], [
            'value' => json_encode([
                // 'status' => $request['delivered_status'] == 1 ? 1 : 0,
                'message' => $request['delivered_message']
            ])
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'delivery_boy_assign_message'], [
            'value' => json_encode([
                'status' => $request['delivery_boy_assign_status'] == 1 ? 1 : 0,
                'message' => $request['delivery_boy_assign_message']
            ])
        ]);

        // DB::table('business_settings')->updateOrInsert(['key' => 'delivery_boy_start_message'], [
        //     'value' => json_encode([
        //         'status' => $request['delivery_boy_start_status'] == 1 ? 1 : 0,
        //         'message' => $request['delivery_boy_start_message']
        //     ])
        // ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'delivery_boy_delivered_message'], [
            'value' => json_encode([
                'status' => $request['delivery_boy_delivered_status'] == 1 ? 1 : 0,
                'message' => $request['delivery_boy_delivered_message']
            ])
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'order_handover_message'], [
            'value' => json_encode([
                'status' => $request['order_handover_message_status'] == 1 ? 1 : 0,
                'message' => $request['order_handover_message']
            ])
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'order_cancled_message'], [
            'value' => json_encode([
                'status' => $request['order_cancled_message_status'] == 1 ? 1 : 0,
                'message' => $request['order_cancled_message']
            ])
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'order_refunded_message'], [
            'value' => json_encode([
                'status' => $request['order_refunded_message_status'] == 1 ? 1 : 0,
                'message' => $request['order_refunded_message']
            ])
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'refund_cancel_message'], [
            'value' => json_encode([
                'status' => $request['refund_cancel_message_status'] == 1 ? 1 : 0,
                'message' => $request['refund_cancel_message']
            ])
        ]);

        return back()->with('success',__('messages.message_updated') );
    }


    public function location_index()
    {
        return view('admin-views.business-settings.location-index');
    }

    public function location_setup(Request $request)
    {
        $restaurant = Helpers::get_restaurant_id();
        $restaurant->latitude = $request['latitude'];
        $restaurant->longitude = $request['longitude'];
        $restaurant->save();

        return back()->with('success',__('messages.settings_updated') );
    }

    public function config_setup()
    {
        return view('admin-views.business-settings.config');
    }

    public function config_update(Request $request)
    {
        DB::table('business_settings')->updateOrInsert(['key' => 'map_api_key'], [
            'value' => $request['map_api_key']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'map_api_key_server'], [
            'value' => $request['map_api_key_server']
        ]);

        return back()->with('success',__('messages.config_data_updated') );
    }

    public function toggle_settings($key, $value)
    {
        DB::table('business_settings')->updateOrInsert(['key' => $key], [
            'value' => $value
        ]);

        return back()->with('success',__('messages.app_settings_updated') );
    }

    public function viewSocialLogin()
    {
        $data = BusinessSetting::where('key', 'social_login')->first();
        if(! $data){
            Helpers::insert_business_settings_key('social_login','[{"login_medium":"google","client_id":"","client_secret":"","status":"0"},{"login_medium":"facebook","client_id":"","client_secret":"","status":""}]');
            $data = BusinessSetting::where('key', 'social_login')->first();
        }
        $apple = BusinessSetting::where('key', 'apple_login')->first();
        if (!$apple) {
            Helpers::insert_business_settings_key('apple_login', '[{"login_medium":"apple","client_id":"","client_secret":"","team_id":"","key_id":"","service_file":"","redirect_url":"","status":""}]');
            $apple = BusinessSetting::where('key', 'apple_login')->first();
        }
        $appleLoginServices = json_decode($apple->value, true);
        $socialLoginServices = json_decode($data->value, true);
        return view('admin-views.business-settings.social-login.view', compact('socialLoginServices','appleLoginServices'));
    }

    public function updateSocialLogin($service, Request $request)
    {
        $socialLogin = BusinessSetting::where('key', 'social_login')->first();
        $credential_array = [];
        foreach (json_decode($socialLogin['value'], true) as $key => $data) {
            if ($data['login_medium'] == $service) {
                $cred = [
                    'login_medium' => $service,
                    'client_id' => $request['client_id'],
                    'client_secret' => $request['client_secret'],
                    'status' => $request['status'],
                ];
                array_push($credential_array, $cred);
            } else {
                array_push($credential_array, $data);
            }
        }
        BusinessSetting::where('key', 'social_login')->update([
            'value' => $credential_array
        ]);


        return redirect()->back()->with('success',__('messages.credential_updated', ['service' => $service]));
    }
    public function updateAppleLogin($service, Request $request)
    {
        $appleLogin = BusinessSetting::where('key', 'apple_login')->first();
        $credential_array = [];
        if($request->hasfile('service_file')){
            // $fileName = Helpers::upload('apple-login/', 'p8', $request->file('service_file'));
        }
        foreach (json_decode($appleLogin['value'], true) as $key => $data) {
            if ($data['login_medium'] == $service) {
                $cred = [
                    'login_medium' => $service,
                    'client_id' => $request['client_id'],
                    'client_secret' => $request['client_secret'],
                    'status' => $request['status'],
                    'team_id' => $request['team_id'],
                    'key_id' => $request['key_id'],
                    'service_file' => isset($fileName)?$fileName:$data['service_file'],
                    'redirect_url' => $request['redirect_url'],
                ];
                array_push($credential_array, $cred);
            } else {
                array_push($credential_array, $data);
            }
        }
        BusinessSetting::where('key', 'apple_login')->update([
            'value' => $credential_array
        ]);
        return redirect()->back()->with('success',__('messages.credential_updated', ['service' => $service]));
    }

    //recaptcha
    public function recaptcha_index(Request $request)
    {
        return view('admin-views.business-settings.recaptcha-index');
    }

    public function recaptcha_update(Request $request)
    {
        DB::table('business_settings')->updateOrInsert(['key' => 'recaptcha'], [
            'key' => 'recaptcha',
            'value' => json_encode([
                'status' => $request['status'],
                'site_key' => $request['site_key'],
                'secret_key' => $request['secret_key']
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);


        return back()->with('success',__('messages.updated_successfully') );
    }

    public function send_mail(Request $request)
    {
        $response_flag = 0;
        try {

            Mail::to($request->email)->send(new \App\Mail\TestEmailSender());
            $response_flag = 1;
        } catch (\Exception $exception) {
            info($exception);
            $response_flag = 2;
        }

        return response()->json(['success' => $response_flag]);
    }

    public function react_setup()
    {
        Helpers::react_domain_status_check();
        return view('admin-views.business-settings.react-setup');
    }

    public function react_update(Request $request)
    {
        $request->validate([
            'react_license_code'=>'required',
            'react_domain'=>'required'
        ],[
            'react_license_code.required'=>__('messages.license_code_is_required'),
            'react_domain.required'=>__('messages.doamain_is_required'),
        ]);
        if(Helpers::activation_submit($request['react_license_code'])){
            DB::table('business_settings')->updateOrInsert(['key' => 'react_setup'], [
                'value' => json_encode([
                    'status'=>1,
                    'react_license_code'=>$request['react_license_code'],
                    'react_domain'=>$request['react_domain'],
                    'react_platform' => 'codecanyon'
                ])
            ]);


            return back()->with('success',__('messages.react_data_updated') );
        }
        elseif(Helpers::react_activation_check($request->react_domain, $request->react_license_code)){

            DB::table('business_settings')->updateOrInsert(['key' => 'react_setup'], [
                'value' => json_encode([
                    'status'=>1,
                    'react_license_code'=>$request['react_license_code'],
                    'react_domain'=>$request['react_domain'],
                    'react_platform' => 'iss'
                ])
            ]);

            return back()->with('success',__('messages.react_data_updated') );
        }

        return back()->withInput(['invalid-data'=>true])->with('error',('messages.Invalid_license_code_or_unregistered_domain'));
    }


    public function site_direction(Request $request){
        if (env('APP_MODE') == 'demo') {
            session()->put('site_direction', ($request->status == 1?'ltr':'rtl'));
            return response()->json();
        }
        if($request->status == 1){
            DB::table('business_settings')->updateOrInsert(['key' => 'site_direction'], [
                'value' => 'ltr'
            ]);
        } else
        {
            DB::table('business_settings')->updateOrInsert(['key' => 'site_direction'], [
                'value' => 'rtl'
            ]);
        }
        return ;
    }
}
