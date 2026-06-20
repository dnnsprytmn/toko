@extends('layouts.app')

@section('title', $title ?? 'Shop - AzazeL Warehouse')


@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="page-content">
                <div class="heading-section text-center">
                    <h4><em>{{ $title ?? 'All' }}</em> Products</h4>
                </div>

                <!-- ***** Shop Content Start ***** -->
                <div class="most-popular">
                    <div class="row">
                        <div class="col-lg-12">
                            
                            <!-- ***** Filter Bar Start ***** -->
                            <div class="filter-shop">
                                <div class="filter-left">
                                    <span class="filter-label">
                                        <i class="fa fa-filter"></i> Filter:
                                    </span>
                                    <div class="btn-group">
                                        <a href="{{ route('shop.all') }}" 
                                        class="btn-filter {{ request()->routeIs('shop.all') ? 'active' : '' }}">
                                            <i class="fa fa-th-large"></i> All
                                        </a>
                                        <a href="{{ route('shop.popular') }}" 
                                        class="btn-filter btn-popular {{ request()->routeIs('shop.popular') ? 'active' : '' }}">
                                            <i class="fa fa-star"></i> Popular
                                        </a>
                                        <a href="{{ route('shop.new') }}" 
                                        class="btn-filter btn-new {{ request()->routeIs('shop.new') ? 'active' : '' }}">
                                            <i class="fa fa-clock"></i> New
                                        </a>
                                        <a href="{{ route('shop.sale') }}" 
                                        class="btn-filter btn-soldout {{ request()->routeIs('shop.sale') ? 'active' : '' }}">
                                            <i class="fa fa-tag"></i> Sale
                                        </a>
                                        <a href="{{ route('shop.soldout') }}" 
                                        class="btn-filter btn-soldout {{ request()->routeIs('shop.soldout') ? 'active' : '' }}">
                                            <i class="fa fa-times-circle"></i> Sold Out
                                        </a>
                                    </div>
                                </div>
                                <div class="filter-right">
                                    <div class="product-count">
                                        <i class="fa fa-box"></i> 
                                        <strong>{{ $products->count() }} product(s)</strong> 
                                    </div>
                                </div>
                            </div>
                            <!-- ***** Filter Bar End ***** -->

                            <!-- Products Grid -->
                            @if($products->count() > 0)
                                <div class="row">
                                    @foreach($products as $product)
                                    <div class="col-lg-3 col-sm-6">
                                        <div class="item">
                                            <!-- Badges -->
                                            @if($product->stock <= 0)
                                                <div class="badge-soldout">
                                                    <i class="fa fa-times-circle me-1"></i>Sold Out
                                                </div>
                                                <div class="sold-out-overlay">
                                                    <div class="text">Sold Out</div>
                                                </div>
                                            @else
                                                @if($product->is_sale)
                                                    <div class="badge-sale">
                                                        <i class="fa fa-tag me-1"></i>Sale
                                                    </div>
                                                @endif
                                                @if($product->is_popular)
                                                    <div class="badge-popular">
                                                        <i class="fa fa-star me-1"></i>Popular
                                                    </div>
                                                @endif
                                                @if($product->created_at >= now()->subDays(7) && !$product->is_popular && !$product->is_sale)
                                                    <div class="badge-new">
                                                        <i class="fa fa-clock me-1"></i>New
                                                    </div>
                                                @endif
                                            @endif

                                            <!-- Image -->
                                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy">
                                            
                                            <!-- Product Info -->
                                            <h4>{{ $product->name }}<br>
                                                <span>
                                                    @if($product->sale_price)
                                                        <span class="text-decoration-line-through">Rp. {{ number_format($product->price, 0, ',', '.') }}</span>
                                                        
                                                        <span class="text-danger">Rp. {{ number_format($product->sale_price, 0, ',', '.') }}</span>
                                                    @else
                                                        <span class="text-white">
                                                            Rp. {{ number_format($product->price, 0, ',', '.') }}
                                                        </span>
                                                    @endif
                                                </span>
                                            </h4>
                                            
                                            <!-- Rating & Stock -->
                                            <ul class="garis">
                                                <li><i class="fa fa-star"></i> {{ $product->rating ?? 4.5 }}</li>
                                                <li>
                                                    @if($product->stock > 0)
                                                        @if($product->stock <= 5)
                                                            <span class="stock-badge low-stock">
                                                                <i class="fa fa-warning"></i> 
                                                                {{ $product->stock }} Stock (Hurry UP!)
                                                            </span>
                                                        @else
                                                            <span class="stock-badge in-stock">
                                                                <i class="fa fa-check-circle"></i> 
                                                                In Stock
                                                            </span>
                                                        @endif
                                                    @else
                                                        <span class="stock-badge out-of-stock">
                                                            <i class="fa fa-times-circle"></i> 
                                                            Sold Out
                                                        </span>
                                                    @endif
                                                </li>
                                            </ul>
                                            
                                            <!-- Add to Cart Button -->
                                            <div class="main-button mt-2">
                                                @if($product->stock > 0)
                                                    <a href="{{ route('product.add-to-cart', $product->id) }}" class="btn btn-sm btn-primary w-100">
                                                        <i class="fa fa-cart-plus me-1"></i> Add to Cart
                                                    </a>
                                                @else
                                                    <button class="btn btn-sm btn-danger w-100" disabled>
                                                        <i class="fa fa-times-circle me-1"></i> Sold Out
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                
                                <!-- Load More Button -->
                                <div class="col-lg-12">
                                    <div class="main-button mt-4">
                                        <a href="{{ route('shop.all') }}">Discover More Products</a>
                                    </div>
                                </div>
                            @else
                                <div class="no-products">
                                    <div class="no-products-icon">
                                        <i class="fa fa-box-open"></i>
                                    </div>
                                    <h3 class="no-products-title">No Products Found</h3>
                                    <p class="no-products-text">We couldn't find any products matching your criteria.</p>
                                    <div class="no-products-actions">
                                        <a href="{{ route('shop.all') }}" class="btn btn-primary">
                                            <i class="fa fa-arrow-left me-2"></i>View All Products
                                        </a>
                                        @if(request('search'))
                                            <a href="{{ route('shop.all') }}" class="btn btn-outline-secondary">
                                                <i class="fa fa-times me-2"></i>Clear Search
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- ***** Shop Content End ***** -->
                
            </div>
        </div>
    </div>
</div>
@endsection