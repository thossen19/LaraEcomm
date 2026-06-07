@extends('layouts.app')

@section('title', 'Register - E-Commerce Store')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-purple-50 via-pink-50 to-indigo-50 py-12 px-4 sm:px-6 lg:px-8">
    <!-- Background Pattern -->
    <div class="fixed inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-r from-purple-400 to-pink-600"></div>
        <div class="absolute top-0 left-0 w-full h-full" style="background-image: url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%239C92AC" fill-opacity="0.4"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    </div>

    <div class="max-w-4xl w-full mx-auto relative">
        <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-2xl overflow-hidden">
            <div class="md:flex">
                <!-- Left Side - Welcome Info -->
                <div class="md:w-1/2 bg-gradient-to-br from-purple-600 to-pink-700 p-8 text-white">
                    <div class="h-full flex flex-col justify-center">
                        <div class="mb-8">
                            <div class="flex items-center mb-4">
                                <div class="bg-white/20 backdrop-blur-sm rounded-full p-3">
                                    <i class="fas fa-user-plus text-white text-2xl"></i>
                                </div>
                                <span class="ml-3 text-xl font-bold">{{ $siteSettings['site_name'] ?? 'E-Commerce Store' }}</span>
                            </div>
                            <h1 class="text-3xl font-bold mb-4">Join Us Today!</h1>
                            <p class="text-purple-100 mb-6">Create your account and unlock exclusive deals, personalized recommendations, and a seamless shopping experience.</p>
                        </div>

                        <!-- Benefits -->
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <div class="bg-white/20 backdrop-blur-sm rounded-full p-2 mr-3">
                                    <i class="fas fa-gift text-white"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold">Exclusive Deals</h3>
                                    <p class="text-sm text-purple-100">Member-only discounts</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="bg-white/20 backdrop-blur-sm rounded-full p-2 mr-3">
                                    <i class="fas fa-bell text-white"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold">Order Tracking</h3>
                                    <p class="text-sm text-purple-100">Real-time updates</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="bg-white/20 backdrop-blur-sm rounded-full p-2 mr-3">
                                    <i class="fas fa-heart text-white"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold">Wishlist</h3>
                                    <p class="text-sm text-purple-100">Save your favorites</p>
                                </div>
                            </div>
                        </div>

                        <!-- Stats -->
                        <div class="mt-8 grid grid-cols-3 gap-4">
                            <div class="text-center">
                                <div class="text-2xl font-bold">50K+</div>
                                <div class="text-xs text-purple-100">Happy Customers</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold">100+</div>
                                <div class="text-xs text-purple-100">Daily Deals</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold">24/7</div>
                                <div class="text-xs text-purple-100">Support</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Registration Form -->
                <div class="md:w-1/2 p-8">
                    <!-- Mobile Logo -->
                    <div class="md:hidden text-center mb-6">
                        <div class="inline-flex items-center justify-center bg-purple-600 rounded-full p-3 mb-4">
                            <i class="fas fa-user-plus text-white text-2xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">Create Account</h2>
                    </div>

                    <!-- Registration Form -->
                    <form class="space-y-6" method="POST" action="{{ route('register') }}">
                        @csrf
                        
                        <!-- Name Fields -->
                        <div class="grid grid-cols-2 gap-4">
                            <!-- First Name -->
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-user mr-2 text-purple-500"></i>First Name
                                </label>
                                <div class="relative">
                                    <input id="first_name" name="first_name" type="text" autocomplete="given-name" required
                                           class="appearance-none block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                           placeholder="Enter your first name"
                                           value="{{ old('first_name') }}">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <i class="fas fa-user text-gray-400"></i>
                                    </div>
                                </div>
                                @error('first_name')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Last Name -->
                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-user mr-2 text-purple-500"></i>Last Name
                                </label>
                                <div class="relative">
                                    <input id="last_name" name="last_name" type="text" autocomplete="family-name" required
                                           class="appearance-none block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                           placeholder="Enter your last name"
                                           value="{{ old('last_name') }}">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <i class="fas fa-user text-gray-400"></i>
                                    </div>
                                </div>
                                @error('last_name')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Email Address -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-envelope mr-2 text-purple-500"></i>Email Address
                            </label>
                            <div class="relative">
                                <input id="email" name="email" type="email" autocomplete="email" required
                                       class="appearance-none block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                       placeholder="Enter your email"
                                       value="{{ old('email') }}">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                </div>
                            </div>
                            @error('email')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-lock mr-2 text-purple-500"></i>Password
                            </label>
                            <div class="relative">
                                <input id="password" name="password" type="password" autocomplete="new-password" required
                                       class="appearance-none block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                       placeholder="Create a password">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <button type="button" class="text-gray-400 hover:text-gray-600 focus:outline-none" onclick="togglePassword('password')">
                                        <i id="password-toggle" class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            @error('password')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                            
                            <!-- Password Strength Indicator -->
                            <div class="mt-3">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-xs text-gray-500">Password strength:</span>
                                    <span id="strength-text" class="text-xs font-medium text-gray-500">Enter password</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div id="strength-bar" class="h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                                </div>
                                <!-- Password Requirements -->
                                <div class="mt-2 grid grid-cols-2 gap-2 text-xs">
                                    <div id="req-length" class="flex items-center text-gray-400">
                                        <i class="fas fa-times-circle mr-1"></i>
                                        <span>8+ characters</span>
                                    </div>
                                    <div id="req-uppercase" class="flex items-center text-gray-400">
                                        <i class="fas fa-times-circle mr-1"></i>
                                        <span>Uppercase</span>
                                    </div>
                                    <div id="req-lowercase" class="flex items-center text-gray-400">
                                        <i class="fas fa-times-circle mr-1"></i>
                                        <span>Lowercase</span>
                                    </div>
                                    <div id="req-number" class="flex items-center text-gray-400">
                                        <i class="fas fa-times-circle mr-1"></i>
                                        <span>Number</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-lock mr-2 text-purple-500"></i>Confirm Password
                            </label>
                            <div class="relative">
                                <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required
                                       class="appearance-none block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                       placeholder="Confirm your password">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <button type="button" class="text-gray-400 hover:text-gray-600 focus:outline-none" onclick="togglePassword('password_confirmation')">
                                        <i id="password-confirm-toggle" class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            @error('password_confirmation')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="flex items-start">
                            <input id="terms" name="terms" type="checkbox" required
                                   class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded mt-1">
                            <label for="terms" class="ml-2 block text-sm text-gray-700">
                                I agree to the <a href="#" class="text-purple-600 hover:text-purple-500 font-medium">Terms and Conditions</a> and <a href="#" class="text-purple-600 hover:text-purple-500 font-medium">Privacy Policy</a>
                            </label>
                        </div>

                        <!-- Register Button -->
                        <div>
                            <button type="submit" 
                                    class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-200 transform hover:scale-[1.02]">
                                <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                    <i class="fas fa-user-plus group-hover:text-purple-200"></i>
                                </span>
                                Create Account
                            </button>
                        </div>
                    </form>

                    <!-- Social Registration Divider -->
                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-white text-gray-500">Or sign up with</span>
                        </div>
                    </div>

                    <!-- Social Registration Buttons -->
                    <div class="grid grid-cols-3 gap-3">
                        <!-- Google Registration -->
                        <button class="group w-full inline-flex justify-center py-3 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition-all duration-200 hover:shadow-md">
                            <i class="fab fa-google text-red-500 group-hover:scale-110 transition-transform"></i>
                        </button>

                        <!-- Facebook Registration -->
                        <button class="group w-full inline-flex justify-center py-3 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition-all duration-200 hover:shadow-md">
                            <i class="fab fa-facebook-f text-blue-600 group-hover:scale-110 transition-transform"></i>
                        </button>

                        <!-- Apple Registration -->
                        <button class="group w-full inline-flex justify-center py-3 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition-all duration-200 hover:shadow-md">
                            <i class="fab fa-apple text-gray-800 group-hover:scale-110 transition-transform"></i>
                        </button>
                    </div>

                    <!-- Login Link -->
                    <div class="text-center mt-6">
                        <p class="text-sm text-gray-600">
                            Already have an account?
                            <a href="{{ route('login') }}" class="font-medium text-purple-600 hover:text-purple-500 transition-colors">
                                Sign in here
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword(fieldId) {
    const passwordInput = document.getElementById(fieldId);
    const toggleIcon = document.getElementById(fieldId + '-toggle');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}

// Enhanced Password Strength Checker
document.getElementById('password').addEventListener('input', function(e) {
    const password = e.target.value;
    const strengthBar = document.getElementById('strength-bar');
    const strengthText = document.getElementById('strength-text');
    
    // Check requirements
    const requirements = {
        length: password.length >= 8,
        uppercase: /[A-Z]/.test(password),
        lowercase: /[a-z]/.test(password),
        number: /[0-9]/.test(password),
        special: /[^A-Za-z0-9]/.test(password)
    };
    
    // Update requirement indicators
    updateRequirement('req-length', requirements.length);
    updateRequirement('req-uppercase', requirements.uppercase);
    updateRequirement('req-lowercase', requirements.lowercase);
    updateRequirement('req-number', requirements.number);
    
    // Calculate strength
    let strength = 0;
    if (requirements.length) strength++;
    if (requirements.uppercase) strength++;
    if (requirements.lowercase) strength++;
    if (requirements.number) strength++;
    if (requirements.special) strength++;
    
    const strengthPercentage = (strength / 5) * 100;
    strengthBar.style.width = strengthPercentage + '%';
    
    // Update strength text and color
    if (password.length === 0) {
        strengthText.textContent = 'Enter password';
        strengthText.className = 'text-xs font-medium text-gray-500';
        strengthBar.className = 'h-2 rounded-full transition-all duration-300 bg-gray-300';
    } else if (strength <= 2) {
        strengthText.textContent = 'Weak';
        strengthText.className = 'text-xs font-medium text-red-500';
        strengthBar.className = 'h-2 rounded-full transition-all duration-300 bg-red-500';
    } else if (strength <= 3) {
        strengthText.textContent = 'Medium';
        strengthText.className = 'text-xs font-medium text-yellow-500';
        strengthBar.className = 'h-2 rounded-full transition-all duration-300 bg-yellow-500';
    } else if (strength <= 4) {
        strengthText.textContent = 'Strong';
        strengthText.className = 'text-xs font-medium text-blue-500';
        strengthBar.className = 'h-2 rounded-full transition-all duration-300 bg-blue-500';
    } else {
        strengthText.textContent = 'Very Strong';
        strengthText.className = 'text-xs font-medium text-green-500';
        strengthBar.className = 'h-2 rounded-full transition-all duration-300 bg-green-500';
    }
});

function updateRequirement(elementId, met) {
    const element = document.getElementById(elementId);
    const icon = element.querySelector('i');
    
    if (met) {
        element.classList.remove('text-gray-400');
        element.classList.add('text-green-500');
        icon.classList.remove('fa-times-circle');
        icon.classList.add('fa-check-circle');
    } else {
        element.classList.remove('text-green-500');
        element.classList.add('text-gray-400');
        icon.classList.remove('fa-check-circle');
        icon.classList.add('fa-times-circle');
    }
}

// Add input animations
document.querySelectorAll('input').forEach(input => {
    input.addEventListener('focus', function() {
        this.parentElement.classList.add('scale-[1.02]');
    });
    
    input.addEventListener('blur', function() {
        this.parentElement.classList.remove('scale-[1.02]');
    });
});

// Password confirmation validation
document.getElementById('password_confirmation').addEventListener('input', function(e) {
    const password = document.getElementById('password').value;
    const confirmPassword = e.target.value;
    
    if (confirmPassword && password !== confirmPassword) {
        e.target.setCustomValidity('Passwords do not match');
    } else {
        e.target.setCustomValidity('');
    }
});
</script>
@endsection
