@extends('layouts.app')

@section('title', 'About Us - E-Commerce Store')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Hero Section -->
    <div class="bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center">
                <h1 class="text-5xl font-bold mb-6">About E-Commerce Store</h1>
                <p class="text-xl text-blue-100 max-w-3xl mx-auto">
                    Your trusted online shopping destination since 2020, bringing quality products and exceptional service to millions of customers worldwide.
                </p>
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div class="p-6">
                    <div class="bg-blue-100 rounded-full p-4 w-20 h-20 mx-auto mb-4 flex items-center justify-center">
                        <i class="fas fa-users text-blue-600 text-3xl"></i>
                    </div>
                    <div class="text-4xl font-bold text-gray-900 mb-2">10M+</div>
                    <div class="text-gray-600">Happy Customers</div>
                </div>
                
                <div class="p-6">
                    <div class="bg-green-100 rounded-full p-4 w-20 h-20 mx-auto mb-4 flex items-center justify-center">
                        <i class="fas fa-box text-green-600 text-3xl"></i>
                    </div>
                    <div class="text-4xl font-bold text-gray-900 mb-2">50K+</div>
                    <div class="text-gray-600">Products</div>
                </div>
                
                <div class="p-6">
                    <div class="bg-purple-100 rounded-full p-4 w-20 h-20 mx-auto mb-4 flex items-center justify-center">
                        <i class="fas fa-store text-purple-600 text-3xl"></i>
                    </div>
                    <div class="text-4xl font-bold text-gray-900 mb-2">1,000+</div>
                    <div class="text-gray-600">Sellers</div>
                </div>
                
                <div class="p-6">
                    <div class="bg-orange-100 rounded-full p-4 w-20 h-20 mx-auto mb-4 flex items-center justify-center">
                        <i class="fas fa-globe text-orange-600 text-3xl"></i>
                    </div>
                    <div class="text-4xl font-bold text-gray-900 mb-2">25+</div>
                    <div class="text-gray-600">Countries</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Story Section -->
    <div class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">Our Story</h2>
                    <div class="space-y-4 text-gray-600">
                        <p>
                            Founded in 2020 with a simple mission: to make quality products accessible to everyone, everywhere. 
                            What started as a small online store has grown into a trusted marketplace serving millions of customers worldwide.
                        </p>
                        <p>
                            We believe in the power of choice, quality, and exceptional customer service. Our platform connects 
                            buyers with trusted sellers, offering everything from everyday essentials to luxury items.
                        </p>
                        <p>
                            Today, we're proud to be one of the fastest-growing e-commerce platforms, 
                            continuously innovating to improve your shopping experience.
                        </p>
                    </div>
                </div>
                <div class="relative">
                    <img src="https://images.unsplash.com/photo-1600880292203-757bb62b4baf?w=600&h=400&fit=crop" 
                         alt="Our Story" 
                         class="rounded-2xl shadow-2xl">
                    <div class="absolute -bottom-6 -right-6 bg-white rounded-xl shadow-xl p-4">
                        <div class="flex items-center space-x-3">
                            <div class="bg-green-100 rounded-full p-2">
                                <i class="fas fa-check text-green-600"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">Trusted by Millions</p>
                                <p class="text-sm text-gray-600">4.8★ Customer Rating</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Values Section -->
    <div class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Our Values</h2>
                <p class="text-xl text-gray-600">The principles that guide everything we do</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center p-6 bg-blue-50 rounded-xl">
                    <div class="bg-blue-600 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                        <i class="fas fa-shield-alt text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Trust & Security</h3>
                    <p class="text-gray-600">Your data and transactions are protected with industry-leading security measures.</p>
                </div>
                
                <div class="text-center p-6 bg-green-50 rounded-xl">
                    <div class="bg-green-600 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                        <i class="fas fa-leaf text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Sustainability</h3>
                    <p class="text-gray-600">Committed to eco-friendly practices and sustainable business operations.</p>
                </div>
                
                <div class="text-center p-6 bg-purple-50 rounded-xl">
                    <div class="bg-purple-600 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                        <i class="fas fa-heart text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Customer First</h3>
                    <p class="text-gray-600">Every decision is made with your best interests at heart.</p>
                </div>
                
                <div class="text-center p-6 bg-orange-50 rounded-xl">
                    <div class="bg-orange-600 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                        <i class="fas fa-rocket text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Innovation</h3>
                    <p class="text-gray-600">Constantly improving our platform to serve you better.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Team Section -->
    <div class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Meet Our Team</h2>
                <p class="text-xl text-gray-600">The passionate people behind your shopping experience</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center bg-white rounded-xl shadow-lg p-6">
                    <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=200&h=200&fit=crop&crop=face" 
                         alt="Team Member" 
                         class="w-32 h-32 rounded-full mx-auto mb-4 object-cover">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Sarah Johnson</h3>
                    <p class="text-gray-600 mb-2">CEO & Founder</p>
                    <div class="flex justify-center space-x-3">
                        <a href="#" class="text-blue-600 hover:text-blue-700">
                            <i class="fab fa-linkedin text-xl"></i>
                        </a>
                        <a href="#" class="text-blue-400 hover:text-blue-500">
                            <i class="fab fa-twitter text-xl"></i>
                        </a>
                    </div>
                </div>
                
                <div class="text-center bg-white rounded-xl shadow-lg p-6">
                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&h=200&fit=crop&crop=face" 
                         alt="Team Member" 
                         class="w-32 h-32 rounded-full mx-auto mb-4 object-cover">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Michael Chen</h3>
                    <p class="text-gray-600 mb-2">CTO</p>
                    <div class="flex justify-center space-x-3">
                        <a href="#" class="text-blue-600 hover:text-blue-700">
                            <i class="fab fa-linkedin text-xl"></i>
                        </a>
                        <a href="#" class="text-blue-400 hover:text-blue-500">
                            <i class="fab fa-twitter text-xl"></i>
                        </a>
                    </div>
                </div>
                
                <div class="text-center bg-white rounded-xl shadow-lg p-6">
                    <img src="https://images.unsplash.com/photo-1494790108750-be9c38b27af?w=200&h=200&fit=crop&crop=face" 
                         alt="Team Member" 
                         class="w-32 h-32 rounded-full mx-auto mb-4 object-cover">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Emily Davis</h3>
                    <p class="text-gray-600 mb-2">Head of Marketing</p>
                    <div class="flex justify-center space-x-3">
                        <a href="#" class="text-blue-600 hover:text-blue-700">
                            <i class="fab fa-linkedin text-xl"></i>
                        </a>
                        <a href="#" class="text-pink-600 hover:text-pink-700">
                            <i class="fab fa-instagram text-xl"></i>
                        </a>
                    </div>
                </div>
                
                <div class="text-center bg-white rounded-xl shadow-lg p-6">
                    <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?w=200&h=200&fit=crop&crop=face" 
                         alt="Team Member" 
                         class="w-32 h-32 rounded-full mx-auto mb-4 object-cover">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Alex Rodriguez</h3>
                    <p class="text-gray-600 mb-2">Head of Operations</p>
                    <div class="flex justify-center space-x-3">
                        <a href="#" class="text-blue-600 hover:text-blue-700">
                            <i class="fab fa-linkedin text-xl"></i>
                        </a>
                        <a href="#" class="text-blue-400 hover:text-blue-500">
                            <i class="fab fa-twitter text-xl"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold mb-4">Ready to Join Our Community?</h2>
            <p class="text-xl text-indigo-100 mb-8">
                Start shopping today and discover why millions of customers trust us for their online shopping needs.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="bg-white text-indigo-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                    Sign Up Now
                </a>
                <a href="{{ route('products.index') }}" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-indigo-600 transition-colors">
                    Start Shopping
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
