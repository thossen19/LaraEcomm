@extends('layouts.app')

@section('title', 'Reviews')

@section('content')
<div class="dashboard-container">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <h2>My Account</h2>
        </div>
        <ul class="sidebar-menu">
            <li><a href="{{ route('customer.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="{{ route('profile.show') }}"><i class="fas fa-user"></i> Profile</a></li>
            <li><a href="{{ route('orders.index') }}"><i class="fas fa-shopping-cart"></i> My Orders</a></li>
            <li><a href="{{ route('wishlist.index') }}"><i class="fas fa-heart"></i> Wishlist</a></li>
            <li><a href="{{ route('addresses.index') }}"><i class="fas fa-map-marker-alt"></i> Addresses</a></li>
            <li><a href="{{ route('payments.index') }}"><i class="fas fa-credit-card"></i> Payment Methods</a></li>
            <li><a href="{{ route('reviews.index') }}" class="active"><i class="fas fa-star"></i> Reviews</a></li>
            <li><a href="{{ route('notifications.index') }}"><i class="fas fa-bell"></i> Notifications</a></li>
            <li><a href="{{ route('settings.index') }}"><i class="fas fa-cog"></i> Settings</a></li>
        </ul>
    </aside>
    
    <!-- Main Content -->
    <main class="main-content">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        <!-- Header -->
        <header class="header">
            <h1>Reviews</h1>
            <div class="user-info">
                <span>Welcome, {{ Auth::user()->name }}</span>
                <div class="user-avatar">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </header>
        
        <div class="content-card">
            <h2>My Reviews</h2>
            <p>Your submitted reviews will be displayed here.</p>
        </div>
    </main>
</div>
@endsection