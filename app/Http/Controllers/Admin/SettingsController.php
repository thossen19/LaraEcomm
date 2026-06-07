<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class SettingsController extends Controller
{
    public function general()
    {
        $settings = [
            'site_name' => \App\Models\Setting::get('site_name', 'Laravel E-Commerce'),
            'site_description' => \App\Models\Setting::get('site_description', ''),
            'site_keywords' => \App\Models\Setting::get('site_keywords', ''),
            'site_logo' => \App\Models\Setting::get('site_logo', ''),
            'site_favicon' => \App\Models\Setting::get('site_favicon', ''),
            'contact_email' => \App\Models\Setting::get('contact_email', ''),
            'contact_phone' => \App\Models\Setting::get('contact_phone', ''),
            'contact_address' => \App\Models\Setting::get('contact_address', ''),
            'currency' => \App\Models\Setting::get('currency', 'USD'),
            'timezone' => \App\Models\Setting::get('timezone', 'UTC'),
            'date_format' => \App\Models\Setting::get('date_format', 'Y-m-d'),
            'language' => \App\Models\Setting::get('language', 'en'),
            'google_analytics_id' => \App\Models\Setting::get('google_analytics_id', ''),
            'facebook_url' => \App\Models\Setting::get('facebook_url', ''),
            'twitter_url' => \App\Models\Setting::get('twitter_url', ''),
            'instagram_url' => \App\Models\Setting::get('instagram_url', ''),
            'linkedin_url' => \App\Models\Setting::get('linkedin_url', ''),
            'maintenance_mode' => \App\Models\Setting::get('maintenance_mode', false),
            'maintenance_message' => \App\Models\Setting::get('maintenance_message', 'We are currently performing maintenance. Please check back soon.'),
        ];

        return view('admin.settings.general', compact('settings'));
    }

    public function updateGeneral(Request $request)
    {
        // Debug: Log that method was called
        \Log::info('=== updateGeneral method called ===');
        \Log::info('Request method: ' . $request->method());
        \Log::info('Request data: ', $request->all());
        
        try {
            $validated = $request->validate([
                'site_name' => 'required|string|max:255',
                'site_description' => 'nullable|string|max:500',
                'site_keywords' => 'nullable|string|max:255',
                'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'site_favicon' => 'nullable|image|mimes:ico,png|max:1024',
                'contact_email' => 'nullable|email',
                'contact_phone' => 'nullable|string|max:20',
                'contact_address' => 'nullable|string|max:500',
                'currency' => 'required|string|in:USD,EUR,GBP,JPY,CAD,AUD,BDT',
                'timezone' => 'required|string',
                'date_format' => 'required|string',
                'language' => 'required|string',
                'google_analytics_id' => 'nullable|string|max:50',
                'facebook_url' => 'nullable|url|max:255',
                'twitter_url' => 'nullable|url|max:255',
                'instagram_url' => 'nullable|url|max:255',
                'linkedin_url' => 'nullable|url|max:255',
                'maintenance_mode' => 'boolean',
                'maintenance_message' => 'nullable|string|max:500',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed: ' . $e->getMessage());
            \Log::error('Validation errors: ', $e->errors());
            throw $e;
        }

        // Debug: Log validated data
        \Log::info('General Settings Update Request:', $validated);

        // Save settings to database
        foreach ($validated as $key => $value) {
            if ($request->hasFile($key)) {
                // Handle file uploads
                \Log::info("Processing file upload for: $key");
                $file = $request->file($key);
                \Log::info("File details: " . $file->getClientOriginalName() . " (" . $file->getSize() . " bytes)");
                
                $filename = time() . '_' . $file->getClientOriginalName();
                
                // Check if settings directory exists
                $settingsPath = storage_path('app/public/settings');
                if (!is_dir($settingsPath)) {
                    \Log::info("Creating settings directory: $settingsPath");
                    mkdir($settingsPath, 0755, true);
                }
                
                try {
                    $file->storeAs('settings', $filename, 'public');
                    \App\Models\Setting::set($key, $filename);
                    \Log::info("Successfully saved file setting: $key = $filename");
                } catch (\Exception $e) {
                    \Log::error("Failed to save file $key: " . $e->getMessage());
                    throw $e;
                }
            } else {
                \App\Models\Setting::set($key, $value);
                \Log::info("Saved setting: $key = $value");
            }
        }

        Cache::flush();

        return back()->with('success', 'General settings updated successfully.');
    }

    public function payment()
    {
        $gatewaysRaw = \App\Models\Setting::get('gateways');
        $settings = (object) [
            'gateways' => $gatewaysRaw ? json_decode($gatewaysRaw, true) : [],
            'default_gateway' => \App\Models\Setting::get('default_gateway', ''),
            'payment_timeout' => \App\Models\Setting::get('payment_timeout', 15),
            'require_payment_verification' => filter_var(\App\Models\Setting::get('require_payment_verification', true), FILTER_VALIDATE_BOOLEAN),
            'auto_refund_failed_payments' => filter_var(\App\Models\Setting::get('auto_refund_failed_payments', true), FILTER_VALIDATE_BOOLEAN),
            'enable_partial_payments' => filter_var(\App\Models\Setting::get('enable_partial_payments', false), FILTER_VALIDATE_BOOLEAN),
            'currencies' => \App\Models\Setting::get('currencies') ? json_decode(\App\Models\Setting::get('currencies'), true) : ['USD'],
            'exchange_rate_source' => \App\Models\Setting::get('exchange_rate_source', 'manual'),
        ];

        return view('admin.settings.payment', compact('settings'));
    }

    public function updatePayment(Request $request)
    {
        $validated = $request->validate([
            // Gateways validation
            'gateways' => 'nullable|array',
            'gateways.stripe.enabled' => 'boolean',
            'gateways.stripe.publishable_key' => 'nullable|string|max:255',
            'gateways.stripe.secret_key' => 'nullable|string|max:255',
            'gateways.stripe.webhook_secret' => 'nullable|string|max:255',
            'gateways.stripe.mode' => 'nullable|in:test,live',
            'gateways.paypal.enabled' => 'boolean',
            'gateways.paypal.client_id' => 'nullable|string|max:255',
            'gateways.paypal.client_secret' => 'nullable|string|max:255',
            'gateways.paypal.environment' => 'nullable|in:sandbox,live',
            'gateways.cod.enabled' => 'boolean',
            'gateways.cod.min_amount' => 'nullable|numeric|min:0',
            'gateways.cod.max_amount' => 'nullable|numeric|min:0',
            'gateways.sslcommerz.enabled' => 'boolean',
            'gateways.sslcommerz.store_id' => 'nullable|string|max:255',
            'gateways.sslcommerz.store_password' => 'nullable|string|max:255',
            'gateways.sslcommerz.secret_key' => 'nullable|string|max:255',
            'gateways.sslcommerz.environment' => 'nullable|in:sandbox,live',
            'gateways.sslcommerz.success_url' => 'nullable|url|max:500',
            'gateways.sslcommerz.fail_url' => 'nullable|url|max:500',
            'gateways.sslcommerz.cancel_url' => 'nullable|url|max:500',
            'gateways.sslcommerz.ipn_url' => 'nullable|url|max:500',
            'gateways.sslcommerz.multi_currency' => 'boolean',

            // Standalone boolean fields (checkboxes)
            'require_payment_verification' => 'boolean',
            'auto_refund_failed_payments' => 'boolean',
            'enable_partial_payments' => 'boolean',

            // Other fields
            'default_gateway' => 'nullable|in:stripe,paypal,sslcommerz,cod',
            'payment_timeout' => 'nullable|integer|min:5|max:60',
            'currencies' => 'nullable|array',
            'currencies.*' => 'string|max:10',
            'exchange_rate_source' => 'nullable|string|max:50',
        ]);

        // Ensure unchecked checkboxes are saved as false
        foreach (['require_payment_verification', 'auto_refund_failed_payments', 'enable_partial_payments'] as $field) {
            if (!array_key_exists($field, $validated)) {
                $validated[$field] = false;
            }
        }

        // Ensure gateway enabled states are set
        if (isset($validated['gateways'])) {
            foreach (['stripe', 'paypal', 'cod', 'sslcommerz'] as $gw) {
                if (!isset($validated['gateways'][$gw]['enabled'])) {
                    $validated['gateways'][$gw]['enabled'] = false;
                }
            }
            if (!isset($validated['gateways']['sslcommerz']['multi_currency'])) {
                $validated['gateways']['sslcommerz']['multi_currency'] = false;
            }
        }

        // Ensure currencies is an array
        if (!isset($validated['currencies'])) {
            $validated['currencies'] = [];
        }

        // Save settings to database
        foreach ($validated as $key => $value) {
            \App\Models\Setting::set($key, is_array($value) ? json_encode($value) : $value);
        }

        Cache::flush();

        return back()->with('success', 'Payment settings updated successfully.');
    }

    public function email()
    {
        $settings = [
            'mail_driver' => 'smtp',
            'mail_host' => '',
            'mail_port' => 587,
            'mail_username' => '',
            'mail_password' => '',
            'mail_encryption' => 'tls',
            'mail_from_address' => '',
            'mail_from_name' => '',
            'enable_order_confirmation' => true,
            'enable_shipping_confirmation' => true,
            'enable_password_reset' => true,
            'enable_newsletter' => false,
        ];

        return view('admin.settings.email', compact('settings'));
    }

    public function updateEmail(Request $request)
    {
        $validated = $request->validate([
            'mail_driver' => 'required|in:smtp,mailgun,ses,sendmail',
            'mail_host' => 'required|string|max:255',
            'mail_port' => 'required|integer|min:1|max:65535',
            'mail_username' => 'required|string|max:255',
            'mail_password' => 'required|string|max:255',
            'mail_encryption' => 'required|in:tls,ssl',
            'mail_from_address' => 'required|email|max:255',
            'mail_from_name' => 'required|string|max:255',
        ]);

        Cache::flush();

        return back()->with('success', 'Email settings updated successfully.');
    }

    public function testEmail(Request $request)
    {
        $validated = $request->validate([
            'test_email' => 'required|email'
        ]);

        try {
            Mail::raw('This is a test email from your e-commerce application.', function ($message) use ($validated) {
                $message->to($validated['test_email'])
                       ->subject('Test Email');
            });

            return back()->with('success', 'Test email sent successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send test email: ' . $e->getMessage());
        }
    }

    public function shipping()
    {
        $settings = [
            'free_shipping_threshold' => \App\Models\Setting::get('free_shipping_threshold', 0),
            'flat_shipping_rate' => \App\Models\Setting::get('flat_shipping_rate', 0),
            'default_shipping_method' => \App\Models\Setting::get('default_shipping_method', 'flat_rate'),
            'processing_time' => \App\Models\Setting::get('processing_time', 1),
            'domestic_rate' => \App\Models\Setting::get('domestic_rate', 10),
            'international_rate' => \App\Models\Setting::get('international_rate', 25),
            'express_rate' => \App\Models\Setting::get('express_rate', 35),
            'weight_0_1_rate' => \App\Models\Setting::get('weight_0_1_rate', 5),
            'weight_1_5_rate' => \App\Models\Setting::get('weight_1_5_rate', 10),
            'weight_5_10_rate' => \App\Models\Setting::get('weight_5_10_rate', 20),
            'weight_10_plus_rate' => \App\Models\Setting::get('weight_10_plus_rate', 30),
            'enable_standard_shipping' => filter_var(\App\Models\Setting::get('enable_standard_shipping', true), FILTER_VALIDATE_BOOLEAN),
            'enable_express_shipping' => filter_var(\App\Models\Setting::get('enable_express_shipping', true), FILTER_VALIDATE_BOOLEAN),
            'enable_pickup' => filter_var(\App\Models\Setting::get('enable_pickup', false), FILTER_VALIDATE_BOOLEAN),
            'enable_local_delivery' => filter_var(\App\Models\Setting::get('enable_local_delivery', false), FILTER_VALIDATE_BOOLEAN),
            'shipping_zones' => \App\Models\Setting::get('shipping_zones') ? json_decode(\App\Models\Setting::get('shipping_zones'), true) : [],
        ];

        return view('admin.settings.shipping', compact('settings'));
    }

    public function updateShipping(Request $request)
    {
        $validated = $request->validate([
            'free_shipping_threshold' => 'nullable|numeric|min:0',
            'flat_shipping_rate' => 'nullable|numeric|min:0',
            'default_shipping_method' => 'nullable|in:flat_rate,weight_based,price_based',
            'processing_time' => 'nullable|integer|min:1|max:30',
            'domestic_rate' => 'nullable|numeric|min:0',
            'international_rate' => 'nullable|numeric|min:0',
            'express_rate' => 'nullable|numeric|min:0',
            'weight_0_1_rate' => 'nullable|numeric|min:0',
            'weight_1_5_rate' => 'nullable|numeric|min:0',
            'weight_5_10_rate' => 'nullable|numeric|min:0',
            'weight_10_plus_rate' => 'nullable|numeric|min:0',
            'enable_standard_shipping' => 'boolean',
            'enable_express_shipping' => 'boolean',
            'enable_pickup' => 'boolean',
            'enable_local_delivery' => 'boolean',
            'shipping_zones' => 'nullable|array',
        ]);

        // Ensure unchecked checkboxes are saved as false
        foreach (['enable_standard_shipping', 'enable_express_shipping', 'enable_pickup', 'enable_local_delivery'] as $field) {
            if (!array_key_exists($field, $validated)) {
                $validated[$field] = false;
            }
        }

        // Save settings to database
        foreach ($validated as $key => $value) {
            \App\Models\Setting::set($key, is_array($value) ? json_encode($value) : $value);
        }

        Cache::flush();

        return back()->with('success', 'Shipping settings updated successfully.');
    }

    public function ai()
    {
        $settings = Cache::get('ai_settings', [
            'ai_chat_enabled' => false,
            'ai_provider' => 'openai',
            'ai_chat_model' => 'gpt-3.5-turbo',
            'ai_chat_api_key' => '',
            'ai_chat_max_tokens' => 1000,
            'ai_chat_temperature' => 0.7,
            'ai_product_descriptions' => false,
            'ai_category_descriptions' => false,
            'ai_blog_posts' => false,
            'ai_email_templates' => false,
            'ai_product_recommendations' => false,
            'ai_search_suggestions' => false,
            'ai_price_optimization' => false,
            'ai_sentiment_analysis' => false,
            'ai_sales_forecasting' => false,
            'ai_inventory_prediction' => false,
        ]);

        return view('admin.settings.ai', compact('settings'));
    }

    public function updateAi(Request $request)
    {
        // Debug: Log the request data
        \Log::info('AI Settings Update Request:', $request->all());
        
        $validated = $request->validate([
            'ai_chat_enabled' => 'boolean',
            'ai_provider' => 'required|in:openai,anthropic,google',
            'ai_chat_model' => 'required|string|max:100',
            'ai_chat_api_key' => 'nullable|string|max:255',
            'ai_chat_max_tokens' => 'required|integer|min:1|max:4000',
            'ai_chat_temperature' => 'required|numeric|min:0|max:2',
            'ai_product_descriptions' => 'boolean',
            'ai_category_descriptions' => 'boolean',
            'ai_blog_posts' => 'boolean',
            'ai_email_templates' => 'boolean',
            'ai_product_recommendations' => 'boolean',
            'ai_search_suggestions' => 'boolean',
            'ai_price_optimization' => 'boolean',
            'ai_sentiment_analysis' => 'boolean',
            'ai_sales_forecasting' => 'boolean',
            'ai_inventory_prediction' => 'boolean',
        ]);

        // Debug: Log validated data
        \Log::info('Validated AI Settings:', $validated);

        // Store settings in cache (in production, you might want to use database)
        $aiSettings = [
            'ai_chat_enabled' => $request->input('ai_chat_enabled', false),
            'ai_provider' => $validated['ai_provider'],
            'ai_chat_model' => $validated['ai_chat_model'],
            'ai_chat_api_key' => $validated['ai_chat_api_key'],
            'ai_chat_max_tokens' => $validated['ai_chat_max_tokens'],
            'ai_chat_temperature' => $validated['ai_chat_temperature'],
            'ai_product_descriptions' => $request->input('ai_product_descriptions', false),
            'ai_category_descriptions' => $request->input('ai_category_descriptions', false),
            'ai_blog_posts' => $request->input('ai_blog_posts', false),
            'ai_email_templates' => $request->input('ai_email_templates', false),
            'ai_product_recommendations' => $request->input('ai_product_recommendations', false),
            'ai_search_suggestions' => $request->input('ai_search_suggestions', false),
            'ai_price_optimization' => $request->input('ai_price_optimization', false),
            'ai_sentiment_analysis' => $request->input('ai_sentiment_analysis', false),
            'ai_sales_forecasting' => $request->input('ai_sales_forecasting', false),
            'ai_inventory_prediction' => $request->input('ai_inventory_prediction', false),
        ];

        // Debug: Log final settings before saving
        \Log::info('Final AI Settings to Save:', $aiSettings);

        Cache::put('ai_settings', $aiSettings);

        // Debug: Verify cache was saved
        \Log::info('AI Settings saved to cache:', Cache::get('ai_settings'));

        // Don't flush cache after saving AI settings
        // Cache::flush();  // This was clearing the settings we just saved

        return back()->with('success', 'AI settings updated successfully.');
    }

    public function sms()
    {
        $settings = [
            'sms_enabled' => false,
            'sms_provider' => 'twilio',
            'sms_from_number' => '',
            'sms_api_key' => '',
            'sms_api_secret' => '',
            'sms_order_confirmation' => true,
            'sms_order_shipped' => true,
            'sms_order_delivered' => true,
            'sms_payment_confirmation' => false,
            'sms_password_reset' => true,
            'sms_marketing' => false,
            'sms_order_confirmation_template' => 'Hi {customer_name}, your order #{order_id} has been confirmed. Total: {order_total}. Track at {tracking_url}',
            'sms_order_shipped_template' => 'Your order #{order_id} has been shipped! Tracking: {tracking_number}. Expected delivery: {delivery_date}',
            'sms_password_reset_template' => 'Your password reset code is: {reset_code}. Valid for {expiry_minutes} minutes.',
            'sms_daily_limit' => 1000,
            'sms_rate_limit' => 10,
            'sms_retry_failed' => 3,
            'sms_retry_interval' => 5,
        ];

        return view('admin.settings.sms', compact('settings'));
    }

    public function updateSms(Request $request)
    {
        $validated = $request->validate([
            'sms_enabled' => 'boolean',
            'sms_provider' => 'required|in:twilio,nexmo,aws_sns',
            'sms_api_key' => 'nullable|string|max:255',
            'sms_api_secret' => 'nullable|string|max:255',
            'sms_from_number' => 'nullable|string|max:20',
        ]);

        Cache::flush();

        return back()->with('success', 'SMS settings updated successfully.');
    }
}
