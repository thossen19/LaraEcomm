@extends('admin.layouts.app')

@section('title', 'Payment Settings')

@section('header', 'Payment Settings')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6 lg:p-8">
            <!-- Settings Navigation -->
            <div class="border-b border-gray-200 mb-8">
                <nav class="-mb-px flex space-x-8">
                    <a href="{{ route('admin.settings.general') }}" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                        General
                    </a>
                    <a href="{{ route('admin.settings.payment') }}" class="border-blue-500 text-blue-600 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                        Payment
                    </a>
                    <a href="{{ route('admin.settings.email') }}" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                        Email
                    </a>
                    <a href="{{ route('admin.settings.shipping') }}" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                        Shipping
                    </a>
                    <a href="{{ route('admin.settings.ai') }}" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                        AI Chat
                    </a>
                    <a href="{{ route('admin.settings.sms') }}" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                        SMS
                    </a>
                </nav>
            </div>

            <form action="{{ route('admin.settings.payment.update.post') }}" method="POST" class="space-y-8">
                @csrf
                
                <!-- Payment Gateways -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Gateways</h3>
                    
                    <!-- Stripe -->
                    <div class="border rounded-lg p-4 mb-4">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <i class="fab fa-stripe text-2xl text-blue-600 mr-3"></i>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900">Stripe</h4>
                                    <p class="text-xs text-gray-500">Credit card processing</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="gateways[stripe][enabled]" id="stripe_enabled" value="1"
                                       {{ ($settings->gateways['stripe']['enabled'] ?? false) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="stripe_enabled" class="ml-2 block text-sm text-gray-900">Enable</label>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Publishable Key</label>
                                <input type="text" name="gateways[stripe][publishable_key]"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       value="{{ old('gateways.stripe.publishable_key', $settings->gateways['stripe']['publishable_key'] ?? '') }}"
                                       placeholder="pk_test_...">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Secret Key</label>
                                <input type="password" name="gateways[stripe][secret_key]"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       value="{{ old('gateways.stripe.secret_key', $settings->gateways['stripe']['secret_key'] ?? '') }}"
                                       placeholder="sk_test_...">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Webhook Secret</label>
                                <input type="password" name="gateways[stripe][webhook_secret]"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       value="{{ old('gateways.stripe.webhook_secret', $settings->gateways['stripe']['webhook_secret'] ?? '') }}"
                                       placeholder="whsec_...">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Mode</label>
                                <select name="gateways[stripe][mode]"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="test" {{ ($settings->gateways['stripe']['mode'] ?? 'test') == 'test' ? 'selected' : '' }}>Test Mode</option>
                                    <option value="live" {{ ($settings->gateways['stripe']['mode'] ?? 'test') == 'live' ? 'selected' : '' }}>Live Mode</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- PayPal -->
                    <div class="border rounded-lg p-4 mb-4">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <i class="fab fa-paypal text-2xl text-blue-500 mr-3"></i>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900">PayPal</h4>
                                    <p class="text-xs text-gray-500">PayPal and credit card payments</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="gateways[paypal][enabled]" id="paypal_enabled" value="1"
                                       {{ ($settings->gateways['paypal']['enabled'] ?? false) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="paypal_enabled" class="ml-2 block text-sm text-gray-900">Enable</label>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Client ID</label>
                                <input type="text" name="gateways[paypal][client_id]"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       value="{{ old('gateways.paypal.client_id', $settings->gateways['paypal']['client_id'] ?? '') }}">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Client Secret</label>
                                <input type="password" name="gateways[paypal][client_secret]"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       value="{{ old('gateways.paypal.client_secret', $settings->gateways['paypal']['client_secret'] ?? '') }}">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Environment</label>
                                <select name="gateways[paypal][environment]"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="sandbox" {{ ($settings->gateways['paypal']['environment'] ?? 'sandbox') == 'sandbox' ? 'selected' : '' }}>Sandbox</option>
                                    <option value="live" {{ ($settings->gateways['paypal']['environment'] ?? 'sandbox') == 'live' ? 'selected' : '' }}>Live</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Cash on Delivery -->
                    <div class="border rounded-lg p-4">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <i class="fas fa-money-bill-wave text-2xl text-green-600 mr-3"></i>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900">Cash on Delivery</h4>
                                    <p class="text-xs text-gray-500">Pay when you receive your order</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="gateways[cod][enabled]" id="cod_enabled" value="1"
                                       {{ ($settings->gateways['cod']['enabled'] ?? false) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="cod_enabled" class="ml-2 block text-sm text-gray-900">Enable</label>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Minimum Order Amount</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">$</span>
                                    </div>
                                    <input type="number" name="gateways[cod][min_amount]" min="0" step="0.01"
                                           class="pl-7 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                           value="{{ old('gateways.cod.min_amount', $settings->gateways['cod']['min_amount'] ?? '') }}"
                                           placeholder="0.00">
                                </div>
                                <p class="mt-1 text-sm text-gray-500">Leave empty for no minimum</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Maximum Order Amount</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">$</span>
                                    </div>
                                    <input type="number" name="gateways[cod][max_amount]" min="0" step="0.01"
                                           class="pl-7 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                           value="{{ old('gateways.cod.max_amount', $settings->gateways['cod']['max_amount'] ?? '') }}"
                                           placeholder="0.00">
                                </div>
                                <p class="mt-1 text-sm text-gray-500">Leave empty for no maximum</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- SSLcommerz -->
                    <div class="border rounded-lg p-4">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <i class="fas fa-credit-card text-2xl text-blue-600 mr-3"></i>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900">SSLcommerz</h4>
                                    <p class="text-xs text-gray-500">Bangladesh Payment Gateway</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="gateways[sslcommerz][enabled]" id="sslcommerz_enabled" value="1"
                                       {{ ($settings->gateways['sslcommerz']['enabled'] ?? false) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="sslcommerz_enabled" class="ml-2 block text-sm text-gray-900">Enable</label>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Store ID</label>
                                <input type="text" name="gateways[sslcommerz][store_id]"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       value="{{ old('gateways.sslcommerz.store_id', $settings->gateways['sslcommerz']['store_id'] ?? '') }}"
                                       placeholder="your_store_id">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Store Password</label>
                                <input type="password" name="gateways[sslcommerz][store_password]"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       value="{{ old('gateways.sslcommerz.store_password', $settings->gateways['sslcommerz']['store_password'] ?? '') }}"
                                       placeholder="your_store_password">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Secret Key</label>
                                <input type="password" name="gateways[sslcommerz][secret_key]"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       value="{{ old('gateways.sslcommerz.secret_key', $settings->gateways['sslcommerz']['secret_key'] ?? '') }}"
                                       placeholder="your_secret_key">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Environment</label>
                                <select name="gateways[sslcommerz][environment]"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="sandbox" {{ ($settings->gateways['sslcommerz']['environment'] ?? 'sandbox') == 'sandbox' ? 'selected' : '' }}>Sandbox</option>
                                    <option value="live" {{ ($settings->gateways['sslcommerz']['environment'] ?? 'sandbox') == 'live' ? 'selected' : '' }}>Live</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Success URL</label>
                                <input type="url" name="gateways[sslcommerz][success_url]"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       value="{{ old('gateways.sslcommerz.success_url', $settings->gateways['sslcommerz']['success_url'] ?? route('checkout.success', ['order' => ':order_id'])) }}"
                                       placeholder="{{ route('checkout.success', ['order' => ':order_id']) }}">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Fail URL</label>
                                <input type="url" name="gateways[sslcommerz][fail_url]"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       value="{{ old('gateways.sslcommerz.fail_url', $settings->gateways['sslcommerz']['fail_url'] ?? route('checkout.index')) }}"
                                       placeholder="{{ route('checkout.index') }}">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Cancel URL</label>
                                <input type="url" name="gateways[sslcommerz][cancel_url]"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       value="{{ old('gateways.sslcommerz.cancel_url', $settings->gateways['sslcommerz']['cancel_url'] ?? route('checkout.index')) }}"
                                       placeholder="{{ route('checkout.index') }}">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">IPN URL</label>
                                <input type="url" name="gateways[sslcommerz][ipn_url]"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       value="{{ old('gateways.sslcommerz.ipn_url', $settings->gateways['sslcommerz']['ipn_url'] ?? url('/payment/ipn/sslcommerz')) }}"
                                       placeholder="{{ url('/payment/ipn/sslcommerz') }}">
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <div class="flex items-center">
                                <input type="checkbox" name="gateways[sslcommerz][multi_currency]" id="sslcommerz_multi_currency" value="1"
                                       {{ ($settings->gateways['sslcommerz']['multi_currency'] ?? true) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="sslcommerz_multi_currency" class="ml-2 block text-sm text-gray-900">
                                    Enable Multi-Currency Support
                                </label>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Allow payments in multiple currencies (BDT, USD, EUR)</p>
                        </div>
                    </div>
                </div>
                
                <!-- Payment Settings -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Settings</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Default Payment Gateway</label>
                            <select name="default_gateway"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Select Default</option>
                                <option value="stripe" {{ ($settings->default_gateway ?? '') == 'stripe' ? 'selected' : '' }}>Stripe</option>
                                <option value="paypal" {{ ($settings->default_gateway ?? '') == 'paypal' ? 'selected' : '' }}>PayPal</option>
                                <option value="sslcommerz" {{ ($settings->default_gateway ?? '') == 'sslcommerz' ? 'selected' : '' }}>SSLcommerz</option>
                                <option value="cod" {{ ($settings->default_gateway ?? '') == 'cod' ? 'selected' : '' }}>Cash on Delivery</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Order Confirmation Timeout (minutes)</label>
                            <input type="number" name="payment_timeout" min="5" max="60"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   value="{{ old('payment_timeout', $settings->payment_timeout ?? 15) }}">
                            <p class="mt-1 text-sm text-gray-500">Time to complete payment before order is cancelled</p>
                        </div>
                    </div>
                    
                    <div class="space-y-4 mt-6">
                        <div class="flex items-center">
                            <input type="checkbox" name="require_payment_verification" id="require_payment_verification" value="1"
                                   {{ ($settings->require_payment_verification ?? true) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="require_payment_verification" class="ml-2 block text-sm text-gray-900">
                                Require payment verification for high-value orders
                            </label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" name="auto_refund_failed_payments" id="auto_refund_failed_payments" value="1"
                                   {{ ($settings->auto_refund_failed_payments ?? true) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="auto_refund_failed_payments" class="ml-2 block text-sm text-gray-900">
                                Automatically refund failed payments
                            </label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" name="enable_partial_payments" id="enable_partial_payments" value="1"
                                   {{ ($settings->enable_partial_payments ?? false) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="enable_partial_payments" class="ml-2 block text-sm text-gray-900">
                                Enable partial payments (deposits)
                            </label>
                        </div>
                    </div>
                </div>
                
                <!-- Currency Settings -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Currency Settings</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Supported Currencies</label>
                            <div class="mt-2 space-y-2">
                                <label class="flex items-center">
                                    <input type="checkbox" name="currencies[USD]" value="USD"
                                           {{ in_array('USD', $settings->currencies ?? ['USD']) ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-900">USD - US Dollar</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="currencies[EUR]" value="EUR"
                                           {{ in_array('EUR', $settings->currencies ?? ['USD']) ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-900">EUR - Euro</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="currencies[GBP]" value="GBP"
                                           {{ in_array('GBP', $settings->currencies ?? ['USD']) ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-900">GBP - British Pound</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="currencies[BDT]" value="BDT"
                                           {{ in_array('BDT', $settings->currencies ?? ['USD']) ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-900">BDT - Bangladeshi Taka</span>
                                </label>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Currency Exchange Rate Source</label>
                            <select name="exchange_rate_source"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="manual" {{ ($settings->exchange_rate_source ?? 'manual') == 'manual' ? 'selected' : '' }}>Manual</option>
                                <option value="fixer" {{ ($settings->exchange_rate_source ?? 'manual') == 'fixer' ? 'selected' : '' }}>Fixer.io API</option>
                                <option value="openexchange" {{ ($settings->exchange_rate_source ?? 'manual') == 'openexchange' ? 'selected' : '' }}>Open Exchange Rates</option>
                            </select>
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
                        <i class="fas fa-save mr-2"></i>
                        Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
