@extends('dashboard.admin')

@section('title', 'Add Products to Flash Sale')

@section('content')
<style>
/* Enhanced Flash Sale Create Styles */
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

.product-selector-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    overflow: hidden;
    transition: all 0.3s ease;
}

.product-selector-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.15);
}

.product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
    max-height: 600px;
    overflow-y: auto;
    padding: 1rem;
}

.product-item {
    border: 2px solid #e9ecef;
    border-radius: 12px;
    padding: 1rem;
    transition: all 0.3s ease;
    cursor: pointer;
    position: relative;
    background: white;
}

.product-item:hover {
    border-color: #667eea;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.1);
}

.product-item.selected {
    border-color: #667eea;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
    box-shadow: 0 5px 20px rgba(102, 126, 234, 0.2);
}

.product-checkbox {
    position: absolute;
    top: 1rem;
    right: 1rem;
    width: 20px;
    height: 20px;
    cursor: pointer;
}

.product-image {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
    margin-bottom: 1rem;
}

.product-name {
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 0.5rem;
    font-size: 1rem;
}

.product-sku {
    color: #7f8c8d;
    font-size: 0.85rem;
    margin-bottom: 0.5rem;
}

.product-category {
    display: inline-block;
    background: #f8f9fa;
    color: #6c757d;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    margin-bottom: 1rem;
}

.price-section {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 1rem;
}

.current-price {
    font-size: 1.2rem;
    font-weight: 800;
    color: #27ae60;
}

.flash-price-input {
    width: 100px;
    padding: 0.5rem;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    text-align: center;
    font-weight: 600;
    transition: all 0.3s ease;
}

.flash-price-input:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.discount-badge {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
    margin-top: 0.5rem;
}

.discount-badge.good {
    background: #d4edda;
    color: #155724;
}

.discount-badge.excellent {
    background: #cce5ff;
    color: #004085;
}

.discount-badge.invalid {
    background: #f8d7da;
    color: #721c24;
}

.selection-summary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1.5rem;
    border-radius: 12px;
    position: sticky;
    bottom: 0;
    z-index: 10;
}

.selection-stats {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.quick-actions {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.quick-btn {
    padding: 0.5rem 1rem;
    border: 2px solid rgba(255,255,255,0.3);
    background: rgba(255,255,255,0.1);
    color: white;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.9rem;
}

.quick-btn:hover {
    background: rgba(255,255,255,0.2);
    border-color: rgba(255,255,255,0.5);
}

.search-filter {
    background: white;
    border-radius: 12px;
    padding: 1rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
}

.search-input {
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 0.75rem 1rem;
    width: 100%;
    transition: all 0.3s ease;
}

.search-input:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.filter-tags {
    display: flex;
    gap: 0.5rem;
    margin-top: 1rem;
    flex-wrap: wrap;
}

.filter-tag {
    padding: 0.5rem 1rem;
    background: #f8f9fa;
    border: 2px solid #e9ecef;
    border-radius: 20px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.85rem;
}

.filter-tag:hover,
.filter-tag.active {
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
}
</style>

<div class="main-content">
    <div class="container-fluid">
        <!-- Enhanced Header -->
        <div class="flash-sale-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1><i class="fas fa-bolt me-2"></i>Add Flash Sale Products</h1>
                    <p>Select products and set attractive flash sale prices to boost your sales</p>
                </div>
                <div>
                    <a href="{{ route('admin.flash-sale.index') }}" class="btn btn-light btn-lg">
                        <i class="fas fa-arrow-left me-2"></i>Back to Flash Sale
                    </a>
                </div>
            </div>
        </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Select Products for Flash Sale</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.flash-sale.store') }}">
                @csrf
                
                @if($products->count() > 0)
                    <div class="table-responsive mb-3">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">
                                        <input type="checkbox" id="selectAll" class="form-check-input">
                                    </th>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Current Price</th>
                                    <th>Flash Sale Price</th>
                                    <th>Discount %</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="product_ids[]" value="{{ $product->id }}" class="form-check-input product-checkbox" 
                                                   @if($product->compare_price && $product->compare_price > 0) disabled @endif>
                                        </td>
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
                                                    @if($product->compare_price && $product->compare_price > 0)
                                                        <div><span class="badge bg-warning">Already in Flash Sale</span></div>
                                                    @endif
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
                                        <td>
                                            <span class="fw-bold">${{ number_format($product->price, 2) }}</span>
                                        </td>
                                        <td>
                                            <div class="input-group input-group-sm" style="width: 150px;">
                                                <span class="input-group-text">$</span>
                                                <input type="number" 
                                                       name="compare_prices[{{ $product->id }}]" 
                                                       class="form-control compare-price-input" 
                                                       data-base-price="{{ $product->price }}"
                                                       value="{{ $product->compare_price ?? '' }}"
                                                       placeholder="Flash price"
                                                       step="0.01"
                                                       min="0"
                                                       @if($product->compare_price && $product->compare_price > 0) disabled @endif>
                                            </div>
                                        </td>
                                        <td>
                                            @if($product->compare_price && $product->compare_price > 0)
                                                @php
                                                    $discount = round(($product->compare_price - $product->price) / $product->compare_price * 100, 1);
                                                @endphp
                                                <span class="discount-percentage badge bg-success">{{ $discount }}%</span>
                                            @else
                                                <span class="discount-percentage badge bg-secondary">0%</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">
                                Selected: <span id="selectedCount">0</span> products
                            </small>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                                <i class="fas fa-bolt"></i> Add to Flash Sale
                            </button>
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-box fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No Active Products Found</h5>
                        <p class="text-muted">There are no active products available. Please create some products first.</p>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-plus"></i> Create New Product
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const productCheckboxes = document.querySelectorAll('.product-checkbox');
    const comparePriceInputs = document.querySelectorAll('.compare-price-input');
    const submitBtn = document.getElementById('submitBtn');
    const selectedCountSpan = document.getElementById('selectedCount');
    
    function updateSelectedCount() {
        const checkedBoxes = document.querySelectorAll('.product-checkbox:checked:not(:disabled)');
        selectedCountSpan.textContent = checkedBoxes.length;
        submitBtn.disabled = checkedBoxes.length === 0;
    }
    
    function updateDiscount(input) {
        const basePrice = parseFloat(input.dataset.basePrice);
        const flashPrice = parseFloat(input.value);
        const discountBadge = input.closest('tr').querySelector('.discount-percentage');
        
        if (flashPrice > basePrice) {
            const discount = ((flashPrice - basePrice) / flashPrice * 100).toFixed(1);
            discountBadge.textContent = discount + '%';
            discountBadge.className = 'discount-percentage badge bg-success';
        } else if (flashPrice > 0) {
            discountBadge.textContent = 'Invalid';
            discountBadge.className = 'discount-percentage badge bg-danger';
        } else {
            discountBadge.textContent = '0%';
            discountBadge.className = 'discount-percentage badge bg-secondary';
        }
    }
    
    // Select all functionality
    selectAllCheckbox.addEventListener('change', function() {
        productCheckboxes.forEach(checkbox => {
            if (!checkbox.disabled) {
                checkbox.checked = this.checked;
            }
        });
        updateSelectedCount();
    });
    
    // Individual checkbox changes
    productCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });
    
    // Compare price input changes
    comparePriceInputs.forEach(input => {
        input.addEventListener('input', function() {
            updateDiscount(this);
        });
        
        // Auto-calculate 20% discount on focus
        input.addEventListener('focus', function() {
            if (!this.value) {
                const basePrice = parseFloat(this.dataset.basePrice);
                const suggestedPrice = basePrice * 1.2;
                this.value = suggestedPrice.toFixed(2);
                updateDiscount(this);
            }
        });
    });
    
    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const checkedBoxes = document.querySelectorAll('.product-checkbox:checked:not(:disabled)');
        let valid = true;
        
        if (checkedBoxes.length === 0) {
            e.preventDefault();
            alert('Please select at least one product to add to flash sale.');
            return;
        }
        
        checkedBoxes.forEach(checkbox => {
            const productId = checkbox.value;
            const priceInput = document.querySelector(`input[name="compare_prices[${productId}]"]`);
            const basePrice = parseFloat(priceInput.dataset.basePrice);
            const flashPrice = parseFloat(priceInput.value);
            
            if (!flashPrice || flashPrice <= basePrice) {
                valid = false;
                priceInput.classList.add('is-invalid');
            } else {
                priceInput.classList.remove('is-invalid');
            }
        });
        
        if (!valid) {
            e.preventDefault();
            alert('Please enter valid flash sale prices greater than the regular prices.');
        }
    });
});
</script>
</div>
</div>
@endsection
