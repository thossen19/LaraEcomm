@extends('layouts.app')

@section('title', 'My Wishlist')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">My Wishlist</h1>
        <p class="text-gray-600">Save your favorite items for later</p>
    </div>

    @if($wishlistItems->count() > 0)
        <!-- Wishlist Items Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @foreach($wishlistItems as $item)
                <div class="wishlist-item bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300" data-item-id="{{ $item->id }}">
                    <!-- Product Image -->
                    <div class="relative h-48 bg-gray-100">
                        <img src="{{ $item->image }}" 
                             alt="{{ $item->display_name }}" 
                             class="w-full h-full object-cover">
                        
                        <!-- Quick Actions -->
                        <div class="absolute top-2 right-2 flex space-x-2">
                            <button onclick="moveToCart({{ $item->id }})" 
                                    class="bg-blue-600 text-white p-2 rounded-full hover:bg-blue-700 transition-colors duration-200"
                                    title="Move to Cart">
                                <i class="fas fa-shopping-cart text-sm"></i>
                            </button>
                            <button onclick="removeFromWishlist({{ $item->id }})" 
                                    class="bg-red-600 text-white p-2 rounded-full hover:bg-red-700 transition-colors duration-200"
                                    title="Remove from Wishlist">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </div>
                        
                        <!-- Stock Status -->
                        @if(!$item->is_in_stock)
                            <div class="absolute bottom-2 left-2 bg-red-600 text-white px-2 py-1 rounded text-xs font-semibold">
                                Out of Stock
                            </div>
                        @endif
                    </div>
                    
                    <!-- Product Details -->
                    <div class="p-4">
                        <div class="flex items-start justify-between mb-2">
                            <h3 class="text-lg font-semibold text-gray-900 flex-1">{{ $item->display_name }}</h3>
                            <div class="text-right">
                                <p class="text-sm text-gray-500">{{ $item->formatted_unit_price }}</p>
                                <p class="text-lg font-bold text-gray-900">{{ $item->formatted_total_price }}</p>
                            </div>
                        </div>
                        
                        <!-- Quantity and Notes -->
                        <div class="space-y-3">
                            <div class="flex items-center space-x-3">
                                <label class="text-sm font-medium text-gray-700">Quantity:</label>
                                <form onsubmit="updateWishlistItem(event, {{ $item->id }})" class="flex items-center space-x-2">
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" 
                                           min="1" max="10" 
                                           class="w-20 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <button type="submit" 
                                            class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors duration-200">
                                        Update
                                    </button>
                                </form>
                            </div>
                            
                            @if($item->notes)
                                <div>
                                    <label class="text-sm font-medium text-gray-700">Notes:</label>
                                    <form onsubmit="updateWishlistItem(event, {{ $item->id }})" class="flex items-center space-x-2">
                                        <input type="text" name="notes" value="{{ $item->notes }}" 
                                               placeholder="Add notes about this item..."
                                               class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <button type="submit" 
                                                class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors duration-200">
                                            Update
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Product Link -->
                        <div class="mt-4">
                            <a href="{{ route('products.show', $item->product->slug) }}" 
                               class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                <i class="fas fa-eye mr-1"></i>
                                View Product Details
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $wishlistItems->links() }}
        </div>
    @else
        <!-- Empty Wishlist -->
        <div class="text-center py-16">
            <div class="max-w-md mx-auto">
                <i class="fas fa-heart text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Your wishlist is empty</h3>
                <p class="text-gray-600 mb-6">Start adding items you love to keep track of them here!</p>
                <a href="{{ route('home') }}" 
                   class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200">
                    <i class="fas fa-shopping-bag mr-2"></i>
                    Start Shopping
                </a>
            </div>
        </div>
    @endif
</div>

<!-- Wishlist JavaScript -->
<script>
let wishlistCount = 0;

function updateWishlistItem(event, itemId) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    
    fetch(`/customer/wishlist/${itemId}`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(Object.fromEntries(formData))
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            if (data.formatted_total_price) {
                // Update price display if needed
                const priceElement = document.querySelector(`[data-item-id="${itemId}"] .text-gray-900`);
                if (priceElement) {
                    priceElement.textContent = data.formatted_total_price;
                }
            }
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error updating wishlist item:', error);
        showNotification('Error updating item', 'error');
    });
}

function removeFromWishlist(itemId) {
    if (confirm('Are you sure you want to remove this item from your wishlist?')) {
        fetch(`/customer/wishlist/${itemId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                updateWishlistCount(data.wishlist_count);
                
                // Remove item from DOM
                const itemElement = document.querySelector(`[data-item-id="${itemId}"]`);
                if (itemElement) {
                    itemElement.remove();
                }
                
                // Show empty state if needed
                const gridElement = document.querySelector('.grid');
                if (gridElement && gridElement.children.length === 0) {
                    location.reload();
                }
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error removing from wishlist:', error);
            showNotification('Error removing item', 'error');
        });
    }
}

function moveToCart(itemId) {
    if (confirm('Move this item to cart?')) {
        fetch(`/customer/wishlist/move-to-cart/${itemId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                updateWishlistCount(data.wishlist_count);
                
                // Remove item from DOM
                const itemElement = document.querySelector(`[data-item-id="${itemId}"]`);
                if (itemElement) {
                    itemElement.remove();
                }
                
                // Show empty state if needed
                const gridElement = document.querySelector('.grid');
                if (gridElement && gridElement.children.length === 0) {
                    location.reload();
                }
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error moving to cart:', error);
            showNotification('Error moving to cart', 'error');
        });
    }
}

function updateWishlistCount(count) {
    wishlistCount = count;
    
    // Update all wishlist count displays
    const countElements = document.querySelectorAll('[data-wishlist-count]');
    countElements.forEach(element => {
        element.textContent = count;
    });
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300 translate-x-full`;
    
    // Set color based on type
    if (type === 'success') {
        notification.classList.add('bg-green-500', 'text-white');
    } else if (type === 'error') {
        notification.classList.add('bg-red-500', 'text-white');
    } else {
        notification.classList.add('bg-blue-500', 'text-white');
    }
    
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'} mr-2"></i>
            <span>${message}</span>
        </div>
    `;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
        notification.classList.add('translate-x-0');
    }, 100);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.classList.remove('translate-x-0');
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}
</script>



</script>
@endsection
