@extends('dashboard.admin')

@section('title', 'Flash Sale Management')

@section('content')
<style>
/* Enhanced Flash Sale Styles */
.flash-sale-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem;
    border-radius: 15px;
    margin-bottom: 2rem;
    position: relative;
    overflow: hidden;
}

.flash-sale-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: pulse 3s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

.flash-sale-header h1 {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
    position: relative;
    z-index: 1;
}

.flash-sale-header p {
    font-size: 1.1rem;
    opacity: 0.9;
    position: relative;
    z-index: 1;
}

.stats-card {
    border: none;
    border-radius: 15px;
    padding: 1.5rem;
    position: relative;
    overflow: hidden;
    color:#000;
    transition: all 0.3s ease;
    cursor: pointer;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}

.stats-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--card-color) 0%, var(--card-color-light) 100%);
}

.stats-card.danger { --card-color: #e74c3c; --card-color-light: #c0392b; }
.stats-card.success { --card-color: #27ae60; --card-color-light: #229954; }
.stats-card.info { --card-color: #3498db; --card-color-light: #2980b9; }
.stats-card.warning { --card-color: #f39c12; --card-color-light: #e67e22; }

.stats-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    margin-bottom: 1rem;
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(10px);
}

.stats-number {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
    line-height: 1;
}

.stats-label {
    font-size: 0.9rem;
    opacity: 0.8;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.filter-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    padding: 1.5rem;
}

.form-control, .form-select {
    border-radius: 10px;
    border: 2px solid #e9ecef;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-top: 2rem;
}

.product-card {
    border: none;
    border-radius: 15px;
    overflow: hidden;
    transition: all 0.3s ease;
    cursor: pointer;
    position: relative;
    background: white;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.15);
}

.product-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
    background: #f8f9fa;
}

.discount-badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: linear-gradient(135deg, #e74c3c, #c0392b);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 25px;
    font-weight: 700;
    font-size: 0.9rem;
    box-shadow: 0 5px 15px rgba(231, 76, 60, 0.3);
    animation: bounce 2s infinite;
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
    40% { transform: translateY(-10px); }
    60% { transform: translateY(-5px); }
}

.product-details {
    padding: 1.5rem;
}

.product-name {
    font-size: 1.1rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: #2c3e50;
}

.product-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.price-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.current-price {
    font-size: 1.3rem;
    font-weight: 800;
    color: #27ae60;
}

.compare-price {
    font-size: 1rem;
    color: #7f8c8d;
    text-decoration: line-through;
}

.product-actions {
    display: flex;
    gap: 0.5rem;
    padding: 0 1.5rem 1.5rem;
}

.btn-action {
    flex: 1;
    padding: 0.75rem;
    border-radius: 10px;
    border: none;
    font-weight: 600;
    transition: all 0.3s ease;
    cursor: pointer;
}

.btn-edit {
    background: #3498db;
    color: white;
}

.btn-edit:hover {
    background: #2980b9;
}

.btn-toggle {
    background: #f39c12;
    color: white;
}

.btn-toggle:hover {
    background: #e67e22;
}

.btn-delete {
    background: #e74c3c;
    color: white;
}

.btn-delete:hover {
    background: #c0392b;
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 15px;
    margin: 2rem 0;
}

.empty-state-icon {
    font-size: 4rem;
    color: #bdc3c7;
    margin-bottom: 1rem;
}

.search-highlight {
    background: #fff3cd;
    padding: 2px 4px;
    border-radius: 3px;
}

.countdown-timer {
    background: linear-gradient(135deg, #e74c3c, #c0392b);
    color: white;
    padding: 1rem;
    border-radius: 10px;
    text-align: center;
    margin-bottom: 2rem;
    font-weight: 600;
}

.countdown-timer .time {
    font-size: 1.5rem;
    font-weight: 800;
    margin: 0 0.5rem;
}

.view-toggle {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.view-btn {
    padding: 0.5rem 1rem;
    border-radius: 8px;
    border: 2px solid #e9ecef;
    background: white;
    cursor: pointer;
    transition: all 0.3s ease;
}

.view-btn.active {
    background: #667eea;
    color: white;
    border-color: #667eea;
}

@media (max-width: 768px) {
    .product-grid {
        grid-template-columns: 1fr;
    }
    
    .flash-sale-header h1 {
        font-size: 2rem;
    }
    
    .stats-number {
        font-size: 2rem;
    }
}
</style>
<div class="main-content">
    <div class="container-fluid">
        <!-- Enhanced Header -->
        <div class="flash-sale-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1><i class="fas fa-bolt me-2"></i>Flash Sale Management</h1>
                    <p>Manage and optimize your flash sale products with smart insights</p>
                </div>
                <div>
                    <a href="{{ route('admin.flash-sale.create') }}" class="btn btn-light btn-lg">
                        <i class="fas fa-plus me-2"></i>Add Products
                    </a>
                </div>
            </div>
        </div>

        <!-- Countdown Timer -->
        <div class="countdown-timer">
            <i class="fas fa-clock me-2"></i>
            <span>Next Flash Sale Ends In:</span>
            <span class="time" id="countdown">24:00:00</span>
        </div>

    <!-- Enhanced Flash Sale Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stats-card danger text-white">
                <div class="stats-icon">
                    <i class="fas fa-bolt"></i>
                </div>
                <div class="stats-number">{{ $products->total() }}</div>
                <div class="stats-label">Total Flash Items</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card success text-white">
                <div class="stats-icon">
                    <i class="fas fa-percentage"></i>
                </div>
                <div class="stats-number">{{ $products->avg('discount_percentage') ? number_format($products->avg('discount_percentage'), 1) : '0' }}%</div>
                <div class="stats-label">Avg Discount</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card info text-white">
                <div class="stats-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stats-number">{{ $products->where('is_active', true)->count() }}</div>
                <div class="stats-label">Active Products</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card warning text-white">
                <div class="stats-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stats-number">${{ number_format($products->sum(function($p) { return $p->compare_price - $p->price; }), 0) }}</div>
                <div class="stats-label">Total Savings</div>
            </div>
        </div>
    </div>

    <!-- Add New Flash Product Button -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">Flash Sale Products</h4>
            <small class="text-muted">Manage your flash sale inventory and pricing</small>
        </div>
        <div>
            <a href="{{ route('admin.flash-sale.create') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus me-2"></i>Add New Flash Product
            </a>
        </div>
    </div>

    <!-- Enhanced Filters -->
    <div class="card filter-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.flash-sale.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label fw-bold">
                        <i class="fas fa-search me-1"></i>Search Products
                    </label>
                    <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Search by name or SKU...">
                </div>
                <div class="col-md-3">
                    <label for="category" class="form-label fw-bold">
                        <i class="fas fa-tags me-1"></i>Category
                    </label>
                    <select class="form-select" id="category" name="category">
                        <option value="">All Categories</option>
                        @foreach(\App\Models\Category::orderBy('name')->get() as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="shop" class="form-label fw-bold">
                        <i class="fas fa-store me-1"></i>Shop
                    </label>
                    <select class="form-select" id="shop" name="shop">
                        <option value="">All Shops</option>
                        @foreach(\App\Models\Shop::orderBy('name')->get() as $shop)
                            <option value="{{ $shop->id }}" {{ request('shop') == $shop->id ? 'selected' : '' }}>
                                {{ $shop->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label d-block fw-bold">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        @if(request()->hasAny(['search', 'category', 'shop']))
                            <a href="{{ route('admin.flash-sale.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> Clear
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- View Toggle -->
    <div class="view-toggle">
        <button class="view-btn active" onclick="setView('grid')">
            <i class="fas fa-th me-1"></i> Grid View
        </button>
        <button class="view-btn" onclick="setView('table')">
            <i class="fas fa-list me-1"></i> Table View
        </button>
    </div>

    <!-- Products Display -->
    <div id="products-container">
        @if($products->count() > 0)
            <!-- Grid View -->
            <div id="grid-view" class="product-grid">
                @foreach($products as $product)
                    <div class="product-card">
                        <div class="position-relative">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="product-image">
                            @else
                                <div class="product-image d-flex align-items-center justify-content-center bg-light">
                                    <i class="fas fa-box fa-3x text-muted"></i>
                                </div>
                            @endif
                            <div class="discount-badge">
                                <i class="fas fa-percentage me-1"></i>{{ $product->discount_percentage }}% OFF
                            </div>
                        </div>
                        <div class="product-details">
                            <div class="product-name">{{ $product->name }}</div>
                            <div class="text-muted small mb-2">SKU: {{ $product->sku }}</div>
                            <div class="product-meta">
                                <div class="price-info">
                                    <span class="current-price">${{ number_format($product->price, 2) }}</span>
                                    <span class="compare-price">${{ number_format($product->compare_price, 2) }}</span>
                                </div>
                                <div>
                                    @if($product->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </div>
                            </div>
                            <div class="text-muted small mb-2">
                                @if($product->categories && $product->categories->count() > 0)
                                    <i class="fas fa-tag me-1"></i>{{ $product->categories->first()->name }}
                                @else
                                    <i class="fas fa-tag me-1"></i>No category
                                @endif
                            </div>
                        </div>
                        <div class="product-actions">
                            <a href="{{ route('admin.flash-sale.edit', $product) }}" class="btn-action btn-edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('admin.flash-sale.toggle', $product) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn-action btn-toggle" title="Toggle flash sale">
                                    <i class="fas fa-bolt-slash"></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.flash-sale.destroy', $product) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to remove this product from flash sale?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Table View (Hidden by default) -->
            <div id="table-view" class="card" style="display: none;">
                <div class="card-header">
                    <h5 class="card-title mb-0">Flash Sale Products ({{ $products->count() }} of {{ $products->total() }})</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Regular Price</th>
                                    <th>Compare Price</th>
                                    <th>Discount</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($product->image)
                                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                                @else
                                                    <div class="rounded me-2 bg-light d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                        <i class="fas fa-box text-muted"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="fw-bold">{{ $product->name }}</div>
                                                    <small class="text-muted">SKU: {{ $product->sku }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($product->categories && $product->categories->count() > 0)
                                                {{ $product->categories->first()->name }}
                                            @else
                                                <span class="text-muted">No category</span>
                                            @endif
                                        </td>
                                        <td>${{ number_format($product->price, 2) }}</td>
                                        <td>${{ number_format($product->compare_price, 2) }}</td>
                                        <td>
                                            <span class="badge bg-success">
                                                {{ $product->discount_percentage }}% OFF
                                            </span>
                                        </td>
                                        <td>
                                            @if($product->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.flash-sale.edit', $product) }}" class="btn btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.flash-sale.toggle', $product) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-warning" title="Remove from flash sale">
                                                        <i class="fas fa-bolt-slash"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.flash-sale.destroy', $product) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to remove this product from flash sale?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
                
            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} entries
                </div>
                {{ $products->links() }}
            </div>
        @else
            <!-- Enhanced Empty State -->
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-bolt"></i>
                </div>
                <h3 class="text-muted mb-3">No Flash Sale Products Found</h3>
                <p class="text-muted mb-4">Start by adding products to the flash sale to attract more customers with amazing deals!</p>
                <a href="{{ route('admin.flash-sale.create') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus me-2"></i>Add Products to Flash Sale
                </a>
            </div>
        @endif
    </div>
</div>

<script>
// Countdown Timer
function updateCountdown() {
    const countdownElement = document.getElementById('countdown');
    if (!countdownElement) return;
    
    // Set end time to 24 hours from now
    const endTime = new Date();
    endTime.setHours(endTime.getHours() + 24);
    
    const now = new Date();
    const timeLeft = endTime - now;
    
    if (timeLeft > 0) {
        const hours = Math.floor(timeLeft / (1000 * 60 * 60));
        const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);
        
        countdownElement.textContent = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    } else {
        countdownElement.textContent = '00:00:00';
    }
}

// Update countdown every second
setInterval(updateCountdown, 1000);
updateCountdown(); // Initial call

// View Toggle Functionality
function setView(viewType) {
    const gridView = document.getElementById('grid-view');
    const tableView = document.getElementById('table-view');
    const viewButtons = document.querySelectorAll('.view-btn');
    
    viewButtons.forEach(btn => btn.classList.remove('active'));
    
    if (viewType === 'grid') {
        gridView.style.display = 'grid';
        tableView.style.display = 'none';
        viewButtons[0].classList.add('active');
    } else {
        gridView.style.display = 'none';
        tableView.style.display = 'block';
        viewButtons[1].classList.add('active');
    }
    
    // Save preference to localStorage
    localStorage.setItem('flashSaleView', viewType);
}

// Load saved view preference
document.addEventListener('DOMContentLoaded', function() {
    const savedView = localStorage.getItem('flashSaleView');
    if (savedView) {
        setView(savedView);
    }
    
    // Add search highlighting
    const searchInput = document.getElementById('search');
    if (searchInput && searchInput.value) {
        highlightSearchTerms(searchInput.value);
    }
});

// Search highlighting function
function highlightSearchTerms(searchTerm) {
    if (!searchTerm) return;
    
    const productNames = document.querySelectorAll('.product-name');
    const regex = new RegExp(`(${searchTerm})`, 'gi');
    
    productNames.forEach(element => {
        const text = element.textContent;
        if (text.toLowerCase().includes(searchTerm.toLowerCase())) {
            element.innerHTML = text.replace(regex, '<span class="search-highlight">$1</span>');
        }
    });
}

// Add smooth scroll behavior
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({ behavior: 'smooth' });
        }
    });
});

// Add loading states for forms
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function() {
        const submitBtn = this.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
        }
    });
});
</script>
@endsection
