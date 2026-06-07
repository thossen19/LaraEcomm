@extends('layouts.app')

@section('title', 'Contact Us - E-Commerce Store')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Hero Section -->
    <div class="bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="text-center">
                <h1 class="text-4xl font-bold mb-4">Get in Touch</h1>
                <p class="text-xl text-blue-100 max-w-3xl mx-auto">
                    We're here to help! Reach out to us for any questions, concerns, or feedback.
                </p>
            </div>
        </div>
    </div>

    <!-- Contact Info Cards -->
    <div class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white rounded-xl shadow-lg p-8 text-center">
                    <div class="bg-blue-100 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                        <i class="fas fa-phone text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Call Us</h3>
                    <p class="text-gray-600 mb-4">Speak directly with our support team</p>
                    <div class="space-y-2">
                        <p class="text-lg font-semibold text-gray-900">+1 (800) 123-4567</p>
                        <p class="text-sm text-gray-600">Mon-Fri: 9AM-8PM EST</p>
                        <p class="text-sm text-gray-600">Sat-Sun: 10AM-6PM EST</p>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-lg p-8 text-center">
                    <div class="bg-green-100 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                        <i class="fas fa-envelope text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Email Us</h3>
                    <p class="text-gray-600 mb-4">Get responses within 24 hours</p>
                    <div class="space-y-2">
                        <a href="mailto:support@ecommerce.com" class="text-lg font-semibold text-blue-600 hover:text-blue-700">
                            support@ecommerce.com
                        </a>
                        <a href="mailto:sales@ecommerce.com" class="text-lg font-semibold text-blue-600 hover:text-blue-700">
                            sales@ecommerce.com
                        </a>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-lg p-8 text-center">
                    <div class="bg-purple-100 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                        <i class="fas fa-comments text-purple-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Live Chat</h3>
                    <p class="text-gray-600 mb-4">Instant support when you need it</p>
                    <button class="bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700 transition-colors">
                        Start Chat
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Form & Map -->
    <div class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Contact Form -->
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">Send us a Message</h2>
                    
                    <form class="space-y-6" method="POST" action="{{ route('contact.submit') }}">
                        @csrf
                        
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user text-gray-400"></i>
                                </div>
                                <input id="name" name="name" type="text" required
                                       class="appearance-none relative block w-full pl-10 pr-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                       placeholder="Enter your full name">
                            </div>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                </div>
                                <input id="email" name="email" type="email" required
                                       class="appearance-none relative block w-full pl-10 pr-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                       placeholder="Enter your email">
                            </div>
                        </div>

                        <!-- Subject -->
                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-tag text-gray-400"></i>
                                </div>
                                <select id="subject" name="subject" required
                                        class="appearance-none relative block w-full pl-10 pr-3 py-3 border border-gray-300 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <option value="">Select a topic</option>
                                    <option value="general">General Inquiry</option>
                                    <option value="order">Order Issue</option>
                                    <option value="return">Return & Refund</option>
                                    <option value="technical">Technical Support</option>
                                    <option value="partnership">Partnership</option>
                                    <option value="feedback">Feedback</option>
                                </select>
                            </div>
                        </div>

                        <!-- Message -->
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                            <div class="relative">
                                <textarea id="message" name="message" rows="5" required
                                          class="appearance-none relative block w-full pr-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                          placeholder="Tell us how we can help..."></textarea>
                            </div>
                        </div>

                        <!-- Priority -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                            <div class="flex space-x-4">
                                <label class="flex items-center">
                                    <input type="radio" name="priority" value="low" class="mr-2">
                                    <span class="text-gray-700">Low</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="priority" value="medium" class="mr-2" checked>
                                    <span class="text-gray-700">Medium</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="priority" value="high" class="mr-2">
                                    <span class="text-gray-700">High</span>
                                </label>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <button type="submit" 
                                    class="w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Send Message
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Map & Office Info -->
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">Visit Our Office</h2>
                    
                    <!-- Interactive Map -->
                    <div class="relative rounded-xl overflow-hidden shadow-lg mb-6">
                        <img src="https://images.unsplash.com/photo-1486312338739-2ec8be0a443a?w=600&h=400&fit=crop" 
                             alt="Office Location" 
                             class="w-full h-64 object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center">
                            <button class="bg-white text-gray-900 px-4 py-2 rounded-lg hover:bg-gray-100 transition-colors">
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                View on Google Maps
                            </button>
                        </div>
                    </div>

                    <!-- Office Information -->
                    <div class="bg-gray-50 rounded-xl p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Headquarters</h3>
                        <div class="space-y-3">
                            <div class="flex items-start">
                                <i class="fas fa-map-marker-alt text-blue-600 mt-1 mr-3 w-5"></i>
                                <div>
                                    <p class="font-semibold text-gray-900">E-Commerce Store Inc.</p>
                                    <p class="text-gray-600">123 Commerce Street, Suite 100</p>
                                    <p class="text-gray-600">New York, NY 10001</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <i class="fas fa-clock text-blue-600 mr-3 w-5"></i>
                                <div>
                                    <p class="font-semibold text-gray-900">Business Hours</p>
                                    <p class="text-gray-600">Monday - Friday: 9:00 AM - 6:00 PM</p>
                                    <p class="text-gray-600">Saturday: 10:00 AM - 4:00 PM</p>
                                    <p class="text-gray-600">Sunday: Closed</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <i class="fas fa-globe text-blue-600 mr-3 w-5"></i>
                                <div>
                                    <p class="font-semibold text-gray-900">Service Areas</p>
                                    <p class="text-gray-600">United States, Canada, UK, Australia</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="bg-gray-50 py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Frequently Asked Questions</h2>
                <p class="text-xl text-gray-600">Quick answers to common questions</p>
            </div>
            
            <div class="space-y-6">
                <!-- FAQ Item 1 -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <button class="w-full text-left flex items-center justify-between" onclick="toggleFAQ('faq1')">
                        <h3 class="text-lg font-semibold text-gray-900">How can I track my order?</h3>
                        <i id="faq1-icon" class="fas fa-chevron-down text-blue-600 transition-transform"></i>
                    </button>
                    <div id="faq1" class="hidden mt-4 text-gray-600">
                        <p>You can track your order by logging into your account and visiting the "My Orders" section. 
                        Enter your order number or tracking ID to see real-time updates on your shipment.</p>
                    </div>
                </div>

                <!-- FAQ Item 2 -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <button class="w-full text-left flex items-center justify-between" onclick="toggleFAQ('faq2')">
                        <h3 class="text-lg font-semibold text-gray-900">What is your return policy?</h3>
                        <i id="faq2-icon" class="fas fa-chevron-down text-blue-600 transition-transform"></i>
                    </button>
                    <div id="faq2" class="hidden mt-4 text-gray-600">
                        <p>We offer a 30-day return policy for most items. Products must be unused and in original packaging. 
                        Simply initiate a return from your account and follow the instructions for a full refund.</p>
                    </div>
                </div>

                <!-- FAQ Item 3 -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <button class="w-full text-left flex items-center justify-between" onclick="toggleFAQ('faq3')">
                        <h3 class="text-lg font-semibold text-gray-900">How long does shipping take?</h3>
                        <i id="faq3-icon" class="fas fa-chevron-down text-blue-600 transition-transform"></i>
                    </button>
                    <div id="faq3" class="hidden mt-4 text-gray-600">
                        <p>Standard shipping takes 5-7 business days. Express shipping (2-3 business days) is available for most locations. 
                        International shipping typically takes 10-15 business days.</p>
                    </div>
                </div>

                <!-- FAQ Item 4 -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <button class="w-full text-left flex items-center justify-between" onclick="toggleFAQ('faq4')">
                        <h3 class="text-lg font-semibold text-gray-900">Do you offer international shipping?</h3>
                        <i id="faq4-icon" class="fas fa-chevron-down text-blue-600 transition-transform"></i>
                    </button>
                    <div id="faq4" class="hidden mt-4 text-gray-600">
                        <p>Yes! We ship to over 25 countries worldwide. International shipping rates and delivery times vary by location. 
                        Check our shipping page for detailed information about your specific country.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleFAQ(faqId) {
    const faqContent = document.getElementById(faqId);
    const faqIcon = document.getElementById(faqId + '-icon');
    
    if (faqContent.classList.contains('hidden')) {
        faqContent.classList.remove('hidden');
        faqIcon.classList.remove('fa-chevron-down');
        faqIcon.classList.add('fa-chevron-up');
        faqIcon.style.transform = 'rotate(180deg)';
    } else {
        faqContent.classList.add('hidden');
        faqIcon.classList.remove('fa-chevron-up');
        faqIcon.classList.add('fa-chevron-down');
        faqIcon.style.transform = 'rotate(0deg)';
    }
}
</script>
@endsection
