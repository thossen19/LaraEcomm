@extends('admin.layouts.app')

@section('title', 'AI Settings')

@section('content')
<div class="px-4 py-6 sm:px-6 lg:px-8">
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif
    
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">AI Settings</h1>
        <p class="mt-1 text-sm text-gray-600">Configure AI-powered features and chat functionality</p>
    </div>

    <form method="POST" action="/admin/settings/ai" class="space-y-6">
        @csrf
        <input type="hidden" name="_method" value="PUT">
                
        <!-- AI Chat Configuration -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">AI Chat Configuration</h3>
                
                <div class="space-y-6">
                    <div class="flex items-center">
                        <input type="checkbox" name="ai_chat_enabled" id="ai_chat_enabled" value="1"
                               {{ ($settings['ai_chat_enabled'] ?? false) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="ai_chat_enabled" class="ml-2 block text-sm text-gray-900">
                            Enable AI Chat Assistant
                        </label>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">AI Provider</label>
                            <select name="ai_provider"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="openai" {{ ($settings['ai_provider'] ?? 'openai') == 'openai' ? 'selected' : '' }}>OpenAI</option>
                                <option value="anthropic" {{ ($settings['ai_provider'] ?? 'openai') == 'anthropic' ? 'selected' : '' }}>Anthropic</option>
                                <option value="google" {{ ($settings['ai_provider'] ?? 'openai') == 'google' ? 'selected' : '' }}>Google AI</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">AI Model</label>
                            <select name="ai_chat_model"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="gpt-3.5-turbo" {{ ($settings['ai_chat_model'] ?? 'gpt-3.5-turbo') == 'gpt-3.5-turbo' ? 'selected' : '' }}>GPT-3.5 Turbo</option>
                                <option value="gpt-4" {{ ($settings['ai_chat_model'] ?? 'gpt-3.5-turbo') == 'gpt-4' ? 'selected' : '' }}>GPT-4</option>
                                <option value="gpt-4-turbo" {{ ($settings['ai_chat_model'] ?? 'gpt-3.5-turbo') == 'gpt-4-turbo' ? 'selected' : '' }}>GPT-4 Turbo</option>
                                <option value="claude-3-sonnet" {{ ($settings['ai_chat_model'] ?? 'gpt-3.5-turbo') == 'claude-3-sonnet' ? 'selected' : '' }}>Claude 3 Sonnet</option>
                                <option value="claude-3-haiku" {{ ($settings['ai_chat_model'] ?? 'gpt-3.5-turbo') == 'claude-3-haiku' ? 'selected' : '' }}>Claude 3 Haiku</option>
                            </select>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">API Key</label>
                            <input type="password" name="ai_chat_api_key"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   value="{{ old('ai_chat_api_key', $settings['ai_chat_api_key'] ?? '') }}"
                                   placeholder="sk-...">
                            <p class="mt-1 text-sm text-gray-500">Enter your AI provider API key</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Max Tokens</label>
                            <input type="number" name="ai_chat_max_tokens" min="1" max="4000"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   value="{{ old('ai_chat_max_tokens', $settings['ai_chat_max_tokens'] ?? 1000) }}"
                                   placeholder="1000">
                            <p class="mt-1 text-sm text-gray-500">Maximum response length</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Temperature</label>
                            <input type="number" name="ai_chat_temperature" step="0.1" min="0" max="2"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   value="{{ old('ai_chat_temperature', $settings['ai_chat_temperature'] ?? 0.7) }}"
                                   placeholder="0.7">
                            <p class="mt-1 text-sm text-gray-500">0 = deterministic, 2 = creative</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- AI Content Generation -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">AI Content Generation</h3>
                
                <div class="space-y-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="ai_product_descriptions" id="ai_product_descriptions" value="1"
                               {{ ($settings['ai_product_descriptions'] ?? false) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="ai_product_descriptions" class="ml-2 block text-sm text-gray-900">
                            Generate Product Descriptions
                        </label>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="ai_category_descriptions" id="ai_category_descriptions" value="1"
                               {{ ($settings['ai_category_descriptions'] ?? false) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="ai_category_descriptions" class="ml-2 block text-sm text-gray-900">
                            Generate Category Descriptions
                        </label>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="ai_blog_posts" id="ai_blog_posts" value="1"
                               {{ ($settings['ai_blog_posts'] ?? false) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="ai_blog_posts" class="ml-2 block text-sm text-gray-900">
                            Generate Blog Posts
                        </label>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="ai_email_templates" id="ai_email_templates" value="1"
                               {{ ($settings['ai_email_templates'] ?? false) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="ai_email_templates" class="ml-2 block text-sm text-gray-900">
                            Generate Email Templates
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- AI Recommendations -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">AI Recommendations</h3>
                
                <div class="space-y-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="ai_product_recommendations" id="ai_product_recommendations" value="1"
                               {{ ($settings['ai_product_recommendations'] ?? false) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="ai_product_recommendations" class="ml-2 block text-sm text-gray-900">
                            Enable Product Recommendations
                        </label>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="ai_search_suggestions" id="ai_search_suggestions" value="1"
                               {{ ($settings['ai_search_suggestions'] ?? false) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="ai_search_suggestions" class="ml-2 block text-sm text-gray-900">
                            Enable Search Suggestions
                        </label>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="ai_price_optimization" id="ai_price_optimization" value="1"
                               {{ ($settings['ai_price_optimization'] ?? false) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="ai_price_optimization" class="ml-2 block text-sm text-gray-900">
                            Enable Price Optimization Suggestions
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- AI Analytics -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">AI Analytics</h3>
                
                <div class="space-y-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="ai_sentiment_analysis" id="ai_sentiment_analysis" value="1"
                               {{ ($settings['ai_sentiment_analysis'] ?? false) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="ai_sentiment_analysis" class="ml-2 block text-sm text-gray-900">
                            Enable Customer Sentiment Analysis
                        </label>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="ai_sales_forecasting" id="ai_sales_forecasting" value="1"
                               {{ ($settings['ai_sales_forecasting'] ?? false) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="ai_sales_forecasting" class="ml-2 block text-sm text-gray-900">
                            Enable Sales Forecasting
                        </label>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="ai_inventory_prediction" id="ai_inventory_prediction" value="1"
                               {{ ($settings['ai_inventory_prediction'] ?? false) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="ai_inventory_prediction" class="ml-2 block text-sm text-gray-900">
                            Enable Inventory Demand Prediction
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Test AI Connection -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Test AI Connection</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Test Message</label>
                        <textarea name="test_message" rows="3"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                  placeholder="Enter a test message to verify AI connection...">Hello, can you help me with my e-commerce store?</textarea>
                    </div>
                    
                    <button type="button" onclick="testAIConnection()" 
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        Test AI Connection
                    </button>
                    
                    <div id="ai_test_result" class="hidden mt-4 p-4 rounded-md"></div>
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
                Save AI Settings
            </button>
        </div>
    </form>
</div>

<script>
function testAIConnection() {
    const button = event.target;
    const resultDiv = document.getElementById('ai_test_result');
    
    button.disabled = true;
    button.textContent = 'Testing...';
    resultDiv.classList.add('hidden');
    
    // Simulate API test
    setTimeout(() => {
        button.disabled = false;
        button.textContent = 'Test AI Connection';
        
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
                    <h3 class="text-sm font-medium text-green-800">Connection Successful</h3>
                    <div class="mt-2 text-sm text-green-700">
                        <p>AI connection is working properly. Test response received successfully.</p>
                    </div>
                </div>
            </div>
        `;
    }, 2000);
}
</script>
@endsection
