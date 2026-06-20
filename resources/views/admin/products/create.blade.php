@extends('layouts.admin')

@section('title', 'Add New Product')

@section('styles')
<style>
    .stock-info-box {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        border-left: 4px solid #0d6efd;
        margin-top: 10px;
    }
    .stock-info-box .info-text {
        font-size: 14px;
        color: #6c757d;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Add New Product</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
        <li class="breadcrumb-item active">Add New</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-plus me-1"></i>
            Product Information
        </div>
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="name" class="form-label">Product Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4">{{ old('description') }}</textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Price *</label>
                                    <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" 
                                           id="price" name="price" value="{{ old('price') }}" required>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sale_price" class="form-label">Sale Price</label>
                                    <input type="number" step="0.01" class="form-control" 
                                           id="sale_price" name="sale_price" value="{{ old('sale_price') }}">
                                    <small class="text-muted">Leave empty if not on sale</small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- ===== STOCK INPUT ===== -->
                        <div class="row">
                            <div class="col-md-6">
    <div class="mb-3">
        <label for="stock" class="form-label">
            Stock Quantity 
            <span class="text-muted small">(Default: 1)</span>
        </label>
        <input type="number" class="form-control @error('stock') is-invalid @enderror" 
               id="stock" name="stock" value="{{ old('stock', 1) }}" min="0">
        @error('stock')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <div class="stock-info-box">
            <i class="fas fa-info-circle text-primary me-1"></i>
            <span class="info-text">
                • Set to <strong>0</strong> = Out of Stock (tidak tampil di home)
                <br>• Set to <strong>1-5</strong> = Low Stock (tampil dengan badge kuning)
                <br>• Set to <strong>6+</strong> = In Stock (tampil dengan badge hijau)
                <br>Default stock is <strong>1</strong> if left empty.
            </span>
        </div>
    </div>
</div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="rating" class="form-label">Rating (1-5)</label>
                                    <input type="number" min="1" max="5" class="form-control" 
                                           id="rating" name="rating" value="{{ old('rating', 5) }}">
                                </div>
                            </div>
                        </div>

                        <!-- Options -->
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="is_sale" name="is_sale" 
                                           {{ old('is_sale') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_sale">
                                        <i class="fas fa-tag text-danger me-1"></i>On Sale
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="is_popular" name="is_popular" 
                                           {{ old('is_popular') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_popular">
                                        <i class="fas fa-star text-warning me-1"></i>Popular
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="is_special" name="is_special" 
                                           {{ old('is_special') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_special">
                                        <i class="fas fa-gem text-primary me-1"></i>Special
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" 
                                           {{ old('is_active') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        <i class="fas fa-check-circle text-success me-1"></i>Active
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                        
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="image" class="form-label">Product Image</label>
                            <div class="border rounded p-3 text-center">
                                <div id="imagePreview" class="mb-3">
                                    <i class="fas fa-image fa-4x text-muted"></i>
                                    <p class="text-muted">No image selected</p>
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
                
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save Product
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
            <img src="${reader.result}" class="img-fluid" style="max-height: 200px; object-fit: cover;">
        `;
    }
    
    if (event.target.files[0]) {
        reader.readAsDataURL(event.target.files[0]);
    }
}
</script>
@endsection