@extends('layouts.app')

@section('title', 'Home - AzazeL Warehouse')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="page-content">

                <!-- ***** Banner Start ***** -->
                <div class="main-banner">
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="header-text">
                                <h6>Welcome To AzazeL Warehouse</h6>
                                <h4><em>Browse</em> Our Popular Products Here</h4>
                                <div class="main-button">
                                    <a href="{{ route('shop.all') }}">Shop Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ***** Banner End ***** -->

                <!-- ***** Most Popular Start ***** -->
                <div class="most-popular">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="heading-section">
                                <h4><em>Most Popular</em> Products</h4>
                            </div>
                            <div class="row">
                                @forelse($products as $product)
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
                                        <h4>
                                            {{ $product->name }}
                                            <span>
                                                @if($product->sale_price)
                                                    <span class="text-decoration-line-through">Rp. {{ number_format($product->price, 0, ',', '.') }}</span>
                                                    
                                                    <span class="text-danger">Rp. {{ number_format($product->sale_price, 0, ',', '.') }}</span>
                                                @else
                                                    <span class="text-white">Rp. {{ number_format($product->price, 0, ',', '.') }}</span>
                                                @endif
                                            </span>
                                        </h4>
                                        
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
                                                                {{ $product->stock }} In Stock
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
                                                <button class="btn btn-sm btn-secondary w-100" disabled>
                                                    <i class="fa fa-times-circle me-1"></i> Sold Out
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="col-12 text-center py-5">
                                    <i class="fa fa-box fa-4x text-muted mb-3 d-block"></i>
                                    <h4>No products found</h4>
                                    <p class="text-muted">Check back later for new products.</p>
                                </div>
                                @endforelse
                            </div>
                            
                            @if($products->count() > 0)
                                <div class="col-lg-12">
                                    <div class="main-button mt-3">
                                        <a href="{{ route('shop.all') }}">Discover More Products</a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- ***** Most Popular End ***** -->

                <!-- ***** Categories / Gaming Library Start ***** -->
                <div class="gaming-library">
                    <div class="col-lg-12">
                        <div class="heading-section">
                            <h4><em>Shop</em> Categories</h4>
                        </div>
                        <div class="item">
                            <ul>
                                <li><i class="fa fa-tag fa-2x text-primary"></i></li>
                                <li><h4>All Products</h4><span>Browse all products</span></li>
                                <li><h4>Items</h4><span>{{ $products->count() }} Products</span></li>
                                <li><h4>Popular</h4><span>Best Sellers</span></li>
                                <li><h4>Available</h4><span>In Stock</span></li>
                                <li><div class="main-border-button"><a href="{{ route('shop.all') }}">Shop Now</a></div></li>
                            </ul>
                        </div>
                        <div class="item">
                            <ul>
                                <li><i class="fa fa-star fa-2x text-warning"></i></li>
                                <li><h4>Popular Items</h4><span>Most loved products</span></li>
                                <li><h4>Top Rated</h4><span>High Ratings</span></li>
                                <li><h4>Best Sellers</h4><span>Most Sold</span></li>
                                <li><h4>Featured</h4><span>Editor's Pick</span></li>
                                <li><div class="main-border-button"><a href="{{ route('shop.popular') }}">View Popular</a></div></li>
                            </ul>
                        </div>
                        <div class="item last-item">
                            <ul>
                                <li><i class="fa fa-tag fa-2x text-danger"></i></li>
                                <li><h4>Sale Items</h4><span>Special discounts</span></li>
                                <li><h4>On Sale</h4><span>Limited Time</span></li>
                                <li><h4>Discount</h4><span>Up to 50%</span></li>
                                <li><h4>Hurry Up!</h4><span>Limited Stock</span></li>
                                <li><div class="main-border-button"><a href="{{ route('shop.sale') }}">View Sales</a></div></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="main-button">
                            <a href="{{ route('shop.all') }}">View All Products</a>
                        </div>
                    </div>
                </div>
                <!-- ***** Gaming Library End ***** -->
                
            </div>
        </div>
    </div>
</div>
@endsection