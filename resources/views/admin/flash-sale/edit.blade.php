@extends('dashboard.admin')

@section('title', 'Edit Flash Sale Product')

@section('content')
<div class="main-content">
    <div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Edit Flash Sale Product</h1>
        <div>
            <a href="{{ route('admin.flash-sale.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Flash Sale
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Edit Flash Sale Settings</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.flash-sale.update', $product) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Product Name</label>
                                <input type="text" class="form-control" value="{{ $product->name }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">SKU</label>
                                <input type="text" class="form-control" value="{{ $product->sku }}" readonly>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Regular Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" value="{{ number_format($product->price, 2) }}" readonly step="0.01">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Current Flash Sale Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" value="{{ number_format($product->compare_price, 2) }}" readonly step="0.01">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="compare_price" class="form-label">New Flash Sale Price <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" 
                                           id="compare_price"
                                           name="compare_price" 
                                           class="form-control @error('compare_price') is-invalid @enderror" 
                                           value="{{ old('compare_price', number_format($product->compare_price, 2)) }}"
                                           placeholder="Enter flash sale price"
                                           step="0.01"
                                           min="0"
                                           data-base-price="{{ $product->price }}">
                                </div>
                                @error('compare_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Discount Percentage</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="discountDisplay" readonly>
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Category</label>
                                <input type="text" class="form-control" 
                                       value="{{ $product->categories && $product->categories->count() > 0 ? $product->categories->first()->name : 'No category' }}" 
                                       readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <div>
                                    @if($product->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Product Description</label>
                            <textarea class="form-control" rows="3" readonly>{{ $product->description ?? 'No description available' }}</textarea>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Flash Sale
                                </button>
                                <a href="{{ route('admin.flash-sale.index') }}" class="btn btn-outline-secondary ms-2">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </div>
                            <div>
                                <form action="{{ route('admin.flash-sale.destroy', $product) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to remove this product from flash sale?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger">
                                        <i class="fas fa-trash"></i> Remove from Flash Sale
                                    </button>
                                </form>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Product Preview</h5>
                </div>
                <div class="card-body text-center">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" 
                             alt="{{ $product->name }}" 
                             class="img-fluid rounded mb-3" 
                             style="max-height: 200px; object-fit: cover;">
                    @else
                        <div class="bg-light rounded d-flex align-items-center justify-content-center mb-3" style="height: 200px;">
                            <i class="fas fa-box fa-3x text-muted"></i>
                        </div>
                    @endif
                    
                    <h6 class="fw-bold">{{ $product->name }}</h6>
                    <p class="text-muted">{{ Str::limit($product->description ?? 'No description', 100) }}</p>
                    
                    <div class="d-flex justify-content-center align-items-center gap-2 mb-3">
                        <span class="badge bg-danger">{{ $product->discount_percentage }}% OFF</span>
                        @if($product->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </div>
                    
                    <div class="text-center">
                        <div class="text-decoration-line-through text-muted">${{ number_format($product->price, 2) }}</div>
                        <div class="h4 text-danger">${{ number_format($product->compare_price, 2) }}</div>
                        <div class="text-success">You save ${{ number_format($product->compare_price - $product->price, 2) }}</div>
                    </div>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-outline-primary">
                            <i class="fas fa-edit"></i> Edit Product Details
                        </a>
                        <a href="{{ route('products.show', $product) }}" target="_blank" class="btn btn-outline-info">
                            <i class="fas fa-eye"></i> View on Frontend
                        </a>
                        <form action="{{ route('admin.flash-sale.toggle', $product) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-warning w-100">
                                <i class="fas fa-bolt-slash"></i> Toggle Flash Sale Status
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const comparePriceInput = document.getElementById('compare_price');
    const discountDisplay = document.getElementById('discountDisplay');
    const basePrice = parseFloat(comparePriceInput.dataset.basePrice);
    
    function updateDiscount() {
        const flashPrice = parseFloat(comparePriceInput.value);
        
        if (flashPrice > basePrice) {
            const discount = ((flashPrice - basePrice) / flashPrice * 100).toFixed(1);
            discountDisplay.value = discount;
            discountDisplay.classList.remove('is-invalid');
            comparePriceInput.classList.remove('is-invalid');
        } else if (flashPrice > 0) {
            discountDisplay.value = 'Invalid';
            discountDisplay.classList.add('is-invalid');
            comparePriceInput.classList.add('is-invalid');
        } else {
            discountDisplay.value = '0';
            discountDisplay.classList.remove('is-invalid');
            comparePriceInput.classList.remove('is-invalid');
        }
    }
    
    // Initialize discount display
    updateDiscount();
    
    // Update discount on input change
    comparePriceInput.addEventListener('input', updateDiscount);
    
    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const flashPrice = parseFloat(comparePriceInput.value);
        
        if (!flashPrice || flashPrice <= basePrice) {
            e.preventDefault();
            alert('Flash sale price must be greater than the regular price.');
            comparePriceInput.focus();
        }
    });
});
</script>
</div>
</div>
@endsection
