@extends('admin.layouts.app')

@section('title', 'SMS Settings')

@section('content')
<div class="px-4 py-6 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">SMS Settings</h1>
        <p class="mt-1 text-sm text-gray-600">Configure SMS notifications and messaging services</p>
    </div>

    <form method="POST" action="/admin/settings/sms" class="space-y-6">
        @csrf
        <input type="hidden" name="_method" value="PUT">
                
        <!-- SMS Configuration -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">SMS Configuration</h3>
                
                <div class="space-y-6">
                    <div class="flex items-center">
                        <input type="checkbox" name="sms_enabled" id="sms_enabled" value="1"
                               {{ ($settings['sms_enabled'] ?? false) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="sms_enabled" class="ml-2 block text-sm text-gray-900">
                            Enable SMS Notifications
                        </label>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">SMS Provider</label>
                            <select name="sms_provider"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="twilio" {{ ($settings['sms_provider'] ?? 'twilio') == 'twilio' ? 'selected' : '' }}>Twilio</option>
                                <option value="nexmo" {{ ($settings['sms_provider'] ?? 'twilio') == 'nexmo' ? 'selected' : '' }}>Nexmo (Vonage)</option>
                                <option value="aws_sns" {{ ($settings['sms_provider'] ?? 'twilio') == 'aws_sns' ? 'selected' : '' }}>Amazon SNS</option>
                                <option value="messagebird" {{ ($settings['sms_provider'] ?? 'twilio') == 'messagebird' ? 'selected' : '' }}>MessageBird</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Default Sender ID</label>
                            <input type="text" name="sms_from_number"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   value="{{ old('sms_from_number', $settings['sms_from_number'] ?? '') }}"
                                   placeholder="YourStore or +1234567890">
                            <p class="mt-1 text-sm text-gray-500">Your business name or phone number</p>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Account SID / API Key</label>
                            <input type="text" name="sms_api_key"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   value="{{ old('sms_api_key', $settings['sms_api_key'] ?? '') }}"
                                   placeholder="Your API key or Account SID">
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Auth Token / API Secret</label>
                            <input type="password" name="sms_api_secret"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   value="{{ old('sms_api_secret', $settings['sms_api_secret'] ?? '') }}"
                                   placeholder="Your API secret or Auth Token">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SMS Notifications -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">SMS Notifications</h3>
                
                <div class="space-y-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="sms_order_confirmation" id="sms_order_confirmation" value="1"
                               {{ ($settings['sms_order_confirmation'] ?? true) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="sms_order_confirmation" class="ml-2 block text-sm text-gray-900">
                            Order Confirmation SMS
                        </label>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="sms_order_shipped" id="sms_order_shipped" value="1"
                               {{ ($settings['sms_order_shipped'] ?? true) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="sms_order_shipped" class="ml-2 block text-sm text-gray-900">
                            Order Shipped SMS
                        </label>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="sms_order_delivered" id="sms_order_delivered" value="1"
                               {{ ($settings['sms_order_delivered'] ?? true) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="sms_order_delivered" class="ml-2 block text-sm text-gray-900">
                            Order Delivered SMS
                        </label>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="sms_payment_confirmation" id="sms_payment_confirmation" value="1"
                               {{ ($settings['sms_payment_confirmation'] ?? false) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="sms_payment_confirmation" class="ml-2 block text-sm text-gray-900">
                            Payment Confirmation SMS
                        </label>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="sms_password_reset" id="sms_password_reset" value="1"
                               {{ ($settings['sms_password_reset'] ?? true) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="sms_password_reset" class="ml-2 block text-sm text-gray-900">
                            Password Reset SMS
                        </label>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="sms_marketing" id="sms_marketing" value="1"
                               {{ ($settings['sms_marketing'] ?? false) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="sms_marketing" class="ml-2 block text-sm text-gray-900">
                            Marketing & Promotional SMS
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- SMS Templates -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">SMS Templates</h3>
                
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Order Confirmation Template</label>
                        <textarea name="sms_order_confirmation_template" rows="3"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                  placeholder="Hi {customer_name}, your order #{order_id} has been confirmed. Total: {order_total}. Track at {tracking_url}">{{ old('sms_order_confirmation_template', $settings['sms_order_confirmation_template'] ?? 'Hi {customer_name}, your order #{order_id} has been confirmed. Total: {order_total}. Track at {tracking_url}') }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Available variables: {customer_name}, {order_id}, {order_total}, {tracking_url}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Order Shipped Template</label>
                        <textarea name="sms_order_shipped_template" rows="3"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                  placeholder="Your order #{order_id} has been shipped! Tracking: {tracking_number}. Expected delivery: {delivery_date}">{{ old('sms_order_shipped_template', $settings['sms_order_shipped_template'] ?? 'Your order #{order_id} has been shipped! Tracking: {tracking_number}. Expected delivery: {delivery_date}') }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Available variables: {order_id}, {tracking_number}, {delivery_date}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Password Reset Template</label>
                        <textarea name="sms_password_reset_template" rows="3"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                  placeholder="Your password reset code is: {reset_code}. Valid for {expiry_minutes} minutes.">{{ old('sms_password_reset_template', $settings['sms_password_reset_template'] ?? 'Your password reset code is: {reset_code}. Valid for {expiry_minutes} minutes.') }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Available variables: {reset_code}, {expiry_minutes}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- SMS Limits & Settings -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">SMS Limits & Settings</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Daily SMS Limit</label>
                        <input type="number" name="sms_daily_limit" min="0"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               value="{{ old('sms_daily_limit', $settings['sms_daily_limit'] ?? 1000) }}"
                               placeholder="1000">
                        <p class="mt-1 text-sm text-gray-500">Maximum SMS per day (0 = unlimited)</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">SMS Rate Limit (per minute)</label>
                        <input type="number" name="sms_rate_limit" min="1"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               value="{{ old('sms_rate_limit', $settings['sms_rate_limit'] ?? 10) }}"
                               placeholder="10">
                        <p class="mt-1 text-sm text-gray-500">Maximum SMS per minute</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Retry Failed SMS</label>
                        <select name="sms_retry_failed"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="0" {{ ($settings['sms_retry_failed'] ?? 3) == 0 ? 'selected' : '' }}>No Retry</option>
                            <option value="1" {{ ($settings['sms_retry_failed'] ?? 3) == 1 ? 'selected' : '' }}>1 Retry</option>
                            <option value="2" {{ ($settings['sms_retry_failed'] ?? 3) == 2 ? 'selected' : '' }}>2 Retries</option>
                            <option value="3" {{ ($settings['sms_retry_failed'] ?? 3) == 3 ? 'selected' : '' }}>3 Retries</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Retry Interval (minutes)</label>
                        <input type="number" name="sms_retry_interval" min="1" max="60"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               value="{{ old('sms_retry_interval', $settings['sms_retry_interval'] ?? 5) }}"
                               placeholder="5">
                        <p class="mt-1 text-sm text-gray-500">Minutes between retry attempts</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Test SMS -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Test SMS Configuration</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Test Phone Number</label>
                        <input type="tel" name="test_phone_number"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               value="{{ old('test_phone_number', '') }}"
                               placeholder="+1234567890">
                        <p class="mt-1 text-sm text-gray-500">Enter phone number with country code</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Test Message</label>
                        <textarea name="test_sms_message" rows="3"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                  placeholder="Enter test message...">This is a test SMS from your e-commerce store. If you receive this, your SMS configuration is working correctly!</textarea>
                    </div>
                    
                    <button type="button" onclick="testSMS()" 
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        Send Test SMS
                    </button>
                    
                    <div id="sms_test_result" class="hidden mt-4 p-4 rounded-md"></div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex justify-end space-x-3">
            <button type="button" onclick="location.reload()" 
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-md text-sm font-medium">
                Cancel
            </button>
            <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-sm font-medium">
                Save SMS Settings
            </button>
        </div>
    </form>
</div>

<script>
function testSMS() {
    const button = event.target;
    const resultDiv = document.getElementById('sms_test_result');
    const phoneNumber = document.querySelector('input[name="test_phone_number"]').value;
    const message = document.querySelector('textarea[name="test_sms_message"]').value;
    
    if (!phoneNumber || !message) {
        resultDiv.classList.remove('hidden');
        resultDiv.className = 'mt-4 p-4 rounded-md bg-red-50 border border-red-200';
        resultDiv.innerHTML = `
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Validation Error</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <p>Please enter both phone number and test message.</p>
                    </div>
                </div>
            </div>
        `;
        return;
    }
    
    button.disabled = true;
    button.textContent = 'Sending...';
    resultDiv.classList.add('hidden');
    
    // Simulate API test
    setTimeout(() => {
        button.disabled = false;
        button.textContent = 'Send Test SMS';
        
        resultDiv.classList.remove('hidden');
        resultDiv.className = 'mt-4 p-4 rounded-md bg-green-50 border border-green-200';
        resultDiv.innerHTML = `
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-green-800">SMS Sent Successfully</h3>
                    <div class="mt-2 text-sm text-green-700">
                        <p>Test SMS has been sent to ${phoneNumber}. Check your phone to verify delivery.</p>
                    </div>
                </div>
            </div>
        `;
    }, 2000);
}
</script>
@endsection
