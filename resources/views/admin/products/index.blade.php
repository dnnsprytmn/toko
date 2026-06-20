@extends('layouts.admin')

@section('title', 'Products Management')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Products</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Products Management</li>
    </ol>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-table me-1"></i>
                    Products List
                    <span class="badge bg-primary ms-2">{{ $products->total() }} Total</span>
                </div>
                <a href="{{ route('admin.products.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i> Add New Product
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- ===== PER PAGE SELECTOR ===== -->
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <!-- ===== FILTER BUTTONS ===== -->
            @if(isset($counts))
            <div class="filter-buttons">
                <a href="{{ route('admin.products.index', ['status' => 'all']) }}" 
                   class="btn-filter {{ $status == 'all' ? 'active' : '' }}">
                    All <span class="badge bg-secondary">{{ $counts['all'] }}</span>
                </a>
                <a href="{{ route('admin.products.index', ['status' => 'active']) }}" 
                   class="btn-filter {{ $status == 'active' ? 'active' : '' }}">
                    Active <span class="badge bg-success">{{ $counts['active'] }}</span>
                </a>
                <a href="{{ route('admin.products.index', ['status' => 'inactive']) }}" 
                   class="btn-filter {{ $status == 'inactive' ? 'active' : '' }}">
                    Inactive <span class="badge bg-secondary">{{ $counts['inactive'] }}</span>
                </a>
                <a href="{{ route('admin.products.index', ['status' => 'low_stock']) }}" 
                   class="btn-filter {{ $status == 'low_stock' ? 'active' : '' }}">
                    Low Stock <span class="badge bg-warning text-dark">{{ $counts['low_stock'] }}</span>
                </a>
                <a href="{{ route('admin.products.index', ['status' => 'out_of_stock']) }}" 
                   class="btn-filter {{ $status == 'out_of_stock' ? 'active' : '' }}">
                    Out of Stock <span class="badge bg-danger">{{ $counts['out_of_stock'] }}</span>
                </a>
            </div>
            @endif
            </div>
            
            <!-- ===== TABLE ===== -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th >Price</th>
                            <th>Sale Price</th>
                            <th>Stok</th>
                            <th>Rating</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $index => $product)
                        <tr>
                            <td class="text-center">
                                {{ $products->firstItem() + $index }}
                            </td>
                            <td>
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-image">
                            </td>
                            <td>
                                {{ $product->name }}
                                @if(!$product->is_active)
                                    <span class="badge bg-secondary ms-1">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <strong>Rp. {{ number_format($product->price, 0, ',', '.') }}</strong>
                            </td>
                            <td>
                                <strong>
                                    @if($product->sale_price)
                                        <span class="text-danger">Rp. {{ number_format($product->sale_price, 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </strong>
                            </td>
                            <!-- Stock Column -->
                            <td class="text-center">
                                @if(isset($product->stock))
                                    @if($product->stock <= 0)
                                        <span class="stock-badge out-of-stock">
                                            <i class="fas fa-times-circle me-1"></i>
                                            {{ $product->stock }}
                                        </span>
                                    @elseif($product->stock <= 5)
                                        <span class="stock-badge low-stock">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            {{ $product->stock }}
                                        </span>
                                    @else
                                        <span class="stock-badge in-stock">
                                            <i class="fas fa-check-circle me-1"></i>
                                            {{ $product->stock }}
                                        </span>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $product->rating)
                                        <i class="fas fa-star text-warning"></i>
                                    @else
                                        <i class="far fa-star text-warning"></i>
                                    @endif
                                @endfor
                            </td>
                            <td>
                                <div class="btn-group-sm">
                                    <a href="{{ route('admin.products.edit', $product) }}" 
                                       class="btn btn-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" 
                                          method="POST" 
                                          style="display: inline-block;"
                                          onsubmit="return confirm('Are you sure you want to delete this product?\n\nThis will also delete the product image.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="fas fa-box fa-3x text-muted mb-3 d-block"></i>
                                <h5>No products found</h5>
                                <p class="text-muted">Start by adding your first product</p>
                                <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i> Add Product
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class=" flex-wrap justify-content-between align-items-center mt-3 pt-2 border-top">
                <div>
                    @if($products->hasPages())
                        {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>


@endsection