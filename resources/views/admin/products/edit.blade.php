@extends('layouts.admin')

@section('title', 'Edit Product')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Edit Product</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
        <li class="breadcrumb-item active">Edit: {{ $product->name }}</li>
    </ol>
    
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            Please fix the following errors:
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-edit me-1"></i>
            Edit Product: {{ $product->name }}
        </div>
        <div class="card-body">
            <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-8">
                        <!-- Product Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Product Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $product->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Price & Sale Price -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Price *</label>
                                    <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" 
                                           id="price" name="price" value="{{ old('price', $product->price) }}" required>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sale_price" class="form-label">Sale Price</label>
                                    <input type="number" step="0.01" class="form-control @error('sale_price') is-invalid @enderror" 
                                           id="sale_price" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}">
                                    @error('sale_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Leave empty if not on sale</small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Stock & Rating -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="stock" class="form-label">
                                        Stock Quantity 
                                        <span class="text-muted small">(Current: {{ $product->stock ?? 0 }})</span>
                                    </label>
                                    <input type="number" class="form-control @error('stock') is-invalid @enderror" 
                                        id="stock" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" min="0">
                                    @error('stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="stock-info-box mt-2">
                                        <i class="fas fa-info-circle text-primary me-1"></i>
                                        <span class="info-text">
                                            <strong>Stock: {{ $product->stock ?? 0 }}</strong>
                                            <br>• Set to <strong>0</strong> = Out of Stock (tidak tampil di home)
                                            <br>• Set to <strong>1-5</strong> = Low Stock (tampil dengan badge kuning)
                                            <br>• Set to <strong>6+</strong> = In Stock (tampil dengan badge hijau)
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="rating" class="form-label">Rating (1-5)</label>
                                    <input type="number" min="1" max="5" class="form-control @error('rating') is-invalid @enderror" 
                                           id="rating" name="rating" value="{{ old('rating', $product->rating ?? 5) }}">
                                    @error('rating')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Rating from 1 to 5 stars</small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Options -->
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="is_sale" name="is_sale" 
                                           {{ old('is_sale', $product->is_sale) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_sale">
                                        <i class="fas fa-tag text-danger me-1"></i>On Sale
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="is_popular" name="is_popular" 
                                           {{ old('is_popular', $product->is_popular) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_popular">
                                        <i class="fas fa-star text-warning me-1"></i>Popular
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" 
                                           {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        <i class="fas fa-check-circle text-success me-1"></i>Active
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right Column - Image -->
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Current Image</label>
                            <div class="image-preview">
                                @if($product->image_url)
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="current-image" width="280">
                                    <div class="mt-2">
                                        <span class="badge bg-secondary">Current Image</span>
                                    </div>
                                @else
                                    <i class="fas fa-image placeholder-icon"></i>
                                    <p class="text-muted">No image</p>
                                @endif
                            </div>
                            @if($product->image_url && !filter_var($product->image_url, FILTER_VALIDATE_URL))
                                <div class="mt-2 text-center">
                                    <form action="{{ route('admin.products.delete-image', $product) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Are you sure you want to delete this image?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash me-1"></i> Delete Image
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                        
                        <div class="mb-3">
                            <label for="image" class="form-label">Change Image</label>
                            <div class="border rounded p-3 text-center">
                                <div id="imagePreview" class="mb-3">
                                    <i class="fas fa-image fa-4x text-muted"></i>
                                    <p class="text-muted">Select new image</p>
                                </div>
                                <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                       id="image" name="image" accept="image/*" onchange="previewImage(event)">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Max size: 2MB. JPG, PNG, GIF, SVG</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Update Product
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewImage(event) {
    const reader = new FileReader();
    const imagePreview = document.getElementById('imagePreview');
    
    reader.onload = function() {
        imagePreview.innerHTML = `
            <img src="${reader.result}" class="img-fluid" style="max-height: 200px; object-fit: cover; border-radius: 8px;">
        `;
    }
    
    if (event.target.files[0]) {
        reader.readAsDataURL(event.target.files[0]);
    }
}
</script>
@endsection