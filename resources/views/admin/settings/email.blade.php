@extends('admin.layouts.app')

@section('title', 'Email Settings')

@section('header', 'Email SMTP Settings')

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
                    <a href="{{ route('admin.settings.payment') }}" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                        Payment
                    </a>
                    <a href="{{ route('admin.settings.email') }}" class="border-blue-500 text-blue-600 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
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

            <form action="{{ route('admin.settings.email.update') }}" method="POST" class="space-y-8">
                @csrf
                
                <!-- SMTP Configuration -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">SMTP Configuration</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Mail Driver</label>
                            <select name="mail_driver"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="smtp" {{ ($settings['mail_driver'] ?? 'smtp') == 'smtp' ? 'selected' : '' }}>SMTP</option>
                                <option value="mail" {{ ($settings['mail_driver'] ?? 'smtp') == 'mail' ? 'selected' : '' }}>PHP Mail</option>
                                <option value="sendmail" {{ ($settings['mail_driver'] ?? 'smtp') == 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                                <option value="mailgun" {{ ($settings['mail_driver'] ?? 'smtp') == 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                                <option value="ses" {{ ($settings['mail_driver'] ?? 'smtp') == 'ses' ? 'selected' : '' }}>Amazon SES</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Mail Host</label>
                            <input type="text" name="mail_host"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   value="{{ old('mail_host', $settings['mail_host'] ?? 'smtp.gmail.com') }}"
                                   placeholder="smtp.gmail.com">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Mail Port</label>
                            <input type="number" name="mail_port"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   value="{{ old('mail_port', $settings['mail_port'] ?? 587) }}"
                                   placeholder="587">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Encryption</label>
                            <select name="mail_encryption"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="tls" {{ ($settings['mail_encryption'] ?? 'tls') == 'tls' ? 'selected' : '' }}>TLS</option>
                                <option value="ssl" {{ ($settings['mail_encryption'] ?? 'tls') == 'ssl' ? 'selected' : '' }}>SSL</option>
                                <option value="" {{ ($settings['mail_encryption'] ?? 'tls') == '' ? 'selected' : '' }}>None</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Mail Username</label>
                            <input type="email" name="mail_username"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   value="{{ old('mail_username', $settings['mail_username']) }}"
                                   placeholder="your-email@gmail.com">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Mail Password</label>
                            <input type="password" name="mail_password"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   value="{{ old('mail_password', $settings['mail_password']) }}"
                                   placeholder="Your app password">
                            <p class="mt-1 text-sm text-gray-500">Use app-specific password for Gmail</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">From Email</label>
                            <input type="email" name="mail_from_address"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   value="{{ old('mail_from_address', $settings['mail_from_address'] ?? 'noreply@example.com') }}"
                                   placeholder="noreply@example.com">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">From Name</label>
                            <input type="text" name="mail_from_name"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   value="{{ old('mail_from_name', $settings['mail_from_name'] ?? 'My E-Commerce Store') }}"
                                   placeholder="My E-Commerce Store">
                        </div>
                    </div>
                </div>
                
                <!-- Email Templates -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Email Templates Settings</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <input type="checkbox" name="enable_order_confirmation" id="enable_order_confirmation" value="1"
                                   {{ ($settings['enable_order_confirmation'] ?? true) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="enable_order_confirmation" class="ml-2 block text-sm text-gray-900">
                                Send order confirmation emails
                            </label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" name="enable_shipping_confirmation" id="enable_shipping_confirmation" value="1"
                                   {{ ($settings['enable_shipping_confirmation'] ?? true) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="enable_shipping_confirmation" class="ml-2 block text-sm text-gray-900">
                                Send shipping confirmation emails
                            </label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" name="enable_password_reset" id="enable_password_reset" value="1"
                                   {{ ($settings['enable_password_reset'] ?? true) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="enable_password_reset" class="ml-2 block text-sm text-gray-900">
                                Send password reset emails
                            </label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" name="enable_newsletter" id="enable_newsletter" value="1"
                                   {{ ($settings['enable_newsletter'] ?? false) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="enable_newsletter" class="ml-2 block text-sm text-gray-900">
                                Enable newsletter subscription
                            </label>
                        </div>
                    </div>
                </div>
                
                <!-- Email Queue Settings -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Email Queue Settings</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Queue Connection</label>
                            <select name="mail_queue_connection"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="database" {{ ($settings->mail_queue_connection ?? 'database') == 'database' ? 'selected' : '' }}>Database</option>
                                <option value="redis" {{ ($settings->mail_queue_connection ?? 'database') == 'redis' ? 'selected' : '' }}>Redis</option>
                                <option value="sqs" {{ ($settings->mail_queue_connection ?? 'database') == 'sqs' ? 'selected' : '' }}>Amazon SQS</option>
                                <option value="sync" {{ ($settings->mail_queue_connection ?? 'database') == 'sync' ? 'selected' : '' }}>Sync (No Queue)</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Retry After (seconds)</label>
                            <input type="number" name="mail_retry_after" min="60" max="3600"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   value="{{ old('mail_retry_after', $settings->mail_retry_after ?? 300) }}">
                            <p class="mt-1 text-sm text-gray-500">Time to wait before retrying failed emails</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Max Retries</label>
                            <input type="number" name="mail_max_retries" min="1" max="10"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   value="{{ old('mail_max_retries', $settings->mail_max_retries ?? 3) }}">
                            <p class="mt-1 text-sm text-gray-500">Maximum retry attempts for failed emails</p>
                        </div>
                    </div>
                </div>
                
                <!-- Test Email -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Test Email Configuration</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Test Email Address</label>
                            <input type="email" id="test_email" name="test_email"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   placeholder="test@example.com">
                        </div>
                        
                        <div class="flex items-end">
                            <button type="button" onclick="sendTestEmail()" 
                                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Send Test Email
                            </button>
                        </div>
                    </div>
                    
                    <div id="test_email_result" class="mt-4 hidden">
                        <!-- Test email result will be shown here -->
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

@push('scripts')
<script>
    function sendTestEmail() {
        const email = document.getElementById('test_email').value;
        const resultDiv = document.getElementById('test_email_result');
        
        if (!email) {
            alert('Please enter a test email address');
            return;
        }
        
        resultDiv.innerHTML = '<div class="text-blue-600">Sending test email...</div>';
        resultDiv.classList.remove('hidden');
        
        fetch('/admin/settings/email/test', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ email: email })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                resultDiv.innerHTML = '<div class="text-green-600">Test email sent successfully!</div>';
            } else {
                resultDiv.innerHTML = '<div class="text-red-600">Failed to send test email: ' + data.message + '</div>';
            }
        })
        .catch(error => {
            resultDiv.innerHTML = '<div class="text-red-600">Error: ' + error.message + '</div>';
        });
    }
</script>
@endpush
@endsection
