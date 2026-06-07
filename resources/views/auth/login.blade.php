@extends('layouts.app')

@section('title', 'Login - E-Commerce Store')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 py-12 px-4 sm:px-6 lg:px-8">
    <!-- Background Pattern -->
    <div class="fixed inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-r from-blue-400 to-purple-600"></div>
        <div class="absolute top-0 left-0 w-full h-full" style="background-image: url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%239C92AC" fill-opacity="0.4"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    </div>

    <div class="max-w-4xl w-full mx-auto relative">
        <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-2xl overflow-hidden">
            <div class="md:flex">
                <!-- Left Side - Welcome Info -->
                <div class="md:w-1/2 bg-gradient-to-br from-blue-600 to-purple-700 p-8 text-white">
                    <div class="h-full flex flex-col justify-center">
                        <div class="mb-8">
                            <div class="flex items-center mb-4">
                                <div class="bg-white/20 backdrop-blur-sm rounded-full p-3">
                                    <i class="fas fa-shopping-bag text-white text-2xl"></i>
                                </div>
                                <span class="ml-3 text-xl font-bold">{{ $siteSettings['site_name'] ?? 'E-Commerce Store' }}</span>
                            </div>
                            <h1 class="text-3xl font-bold mb-4">Welcome Back!</h1>
                            <p class="text-blue-100 mb-6">Sign in to access your personalized shopping experience, track orders, and get exclusive deals.</p>
                        </div>

                        <!-- Features -->
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <div class="bg-white/20 backdrop-blur-sm rounded-full p-2 mr-3">
                                    <i class="fas fa-truck text-white"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold">Fast Delivery</h3>
                                    <p class="text-sm text-blue-100">Quick and reliable shipping</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="bg-white/20 backdrop-blur-sm rounded-full p-2 mr-3">
                                    <i class="fas fa-shield-alt text-white"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold">Secure Payment</h3>
                                    <p class="text-sm text-blue-100">100% secure transactions</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="bg-white/20 backdrop-blur-sm rounded-full p-2 mr-3">
                                    <i class="fas fa-headset text-white"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold">24/7 Support</h3>
                                    <p class="text-sm text-blue-100">Always here to help</p>
                                </div>
                            </div>
                        </div>

                        <!-- Testimonial -->
                        <div class="mt-8 p-4 bg-white/10 backdrop-blur-sm rounded-lg">
                            <div class="flex items-center mb-2">
                                <img src="https://picsum.photos/seed/user1/40/40" alt="User" class="w-10 h-10 rounded-full mr-3">
                                <div>
                                    <p class="font-semibold">Sarah Johnson</p>
                                    <div class="flex text-yellow-300 text-xs">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="text-sm text-blue-100">"Best shopping experience ever! Fast delivery and amazing customer service."</p>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Login Form -->
                <div class="md:w-1/2 p-8">
                    <!-- Mobile Logo -->
                    <div class="md:hidden text-center mb-6">
                        <div class="inline-flex items-center justify-center bg-blue-600 rounded-full p-3 mb-4">
                            <i class="fas fa-shopping-bag text-white text-2xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">Welcome Back</h2>
                    </div>

                    <!-- Session Status -->
                    @if (session('status'))
                        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- Login Form -->
                    <form class="space-y-6" method="POST" action="{{ route('login') }}">
                        @csrf
                        <input type="hidden" name="remember" id="remember-me" value="off">
                        
                        <!-- Email Address -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-envelope mr-2 text-blue-500"></i>Email Address
                            </label>
                            <div class="relative">
                                <input id="email" name="email" type="email" autocomplete="email" required
                                       class="appearance-none block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
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
                                <i class="fas fa-lock mr-2 text-blue-500"></i>Password
                            </label>
                            <div class="relative">
                                <input id="password" name="password" type="password" autocomplete="current-password" required
                                       class="appearance-none block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                       placeholder="Enter your password">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <button type="button" class="text-gray-400 hover:text-gray-600 focus:outline-none" onclick="togglePassword()">
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
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input id="remember" name="remember" type="checkbox" 
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="remember" class="ml-2 block text-sm text-gray-700">
                                    Remember me
                                </label>
                            </div>

                            @if (Route::has('password.request'))
                                <div class="text-sm">
                                    <a href="{{ route('password.request') }}" class="font-medium text-blue-600 hover:text-blue-500 transition-colors">
                                        Forgot password?
                                    </a>
                                </div>
                            @endif
                        </div>

                        <!-- Login Button -->
                        <div>
                            <button type="submit" 
                                    class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-[1.02]">
                                <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                    <i class="fas fa-sign-in-alt group-hover:text-blue-200"></i>
                                </span>
                                Sign In
                            </button>
                        </div>
                    </form>

                    <!-- Social Login Divider -->
                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-white text-gray-500">Or continue with</span>
                        </div>
                    </div>

                    <!-- Social Login Buttons -->
                    <div class="grid grid-cols-3 gap-3">
                        <!-- Google Login -->
                        <button class="group w-full inline-flex justify-center py-3 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition-all duration-200 hover:shadow-md">
                            <i class="fab fa-google text-red-500 group-hover:scale-110 transition-transform"></i>
                        </button>

                        <!-- Facebook Login -->
                        <button class="group w-full inline-flex justify-center py-3 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition-all duration-200 hover:shadow-md">
                            <i class="fab fa-facebook-f text-blue-600 group-hover:scale-110 transition-transform"></i>
                        </button>

                        <!-- Apple Login -->
                        <button class="group w-full inline-flex justify-center py-3 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition-all duration-200 hover:shadow-md">
                            <i class="fab fa-apple text-gray-800 group-hover:scale-110 transition-transform"></i>
                        </button>
                    </div>

                    <!-- Sign Up Link -->
                    <div class="text-center mt-6">
                        <p class="text-sm text-gray-600">
                            Don't have an account?
                            <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-500 transition-colors">
                                Sign up now
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const passwordToggle = document.getElementById('password-toggle');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        passwordToggle.classList.remove('fa-eye');
        passwordToggle.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        passwordToggle.classList.remove('fa-eye-slash');
        passwordToggle.classList.add('fa-eye');
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
</script>
@endsection
