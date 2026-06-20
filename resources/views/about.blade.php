@extends('layouts.app')

@section('title', 'About Us - Toko Online')

@section('content')
<!-- About Section -->
    <div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="page-content">
                <div class="heading-section text-center">
                    <h4><em>TENTANG</em> KAMI</h4>
                </div>

                <!-- ***** About Section Start ***** -->
                <div class="about-section">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="about-image">
                                <img src="{{ asset('template_web/assets/images/about.jpg') }}" alt="About Us" 
                                     onerror="this.src='https://dummyimage.com/600x400/27292a/ec6090.jpg&text=About+Us'">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="about-content">
                                <p>
                                    Founded in 2024, <strong>AzazeL Warehouse</strong> is dedicated to providing 
                                    the best shopping experience for our customers. We believe in quality products, 
                                    excellent customer service, and making online shopping accessible to everyone.
                                </p>
                                <p>
                                    Our team works tirelessly to curate the best items from around the world and 
                                    bring them directly to your doorstep. We pride ourselves on our commitment to 
                                    quality and customer satisfaction.
                                </p>
                                
                                <div class="about-features">
                                    <div class="feature-item">
                                        <i class="fa fa-truck"></i>
                                        <div class="feature-text">
                                            <h5>Fast Shipping</h5>
                                            <span>Free shipping on orders over Rp. 100.000</span>
                                        </div>
                                    </div>
                                    <div class="feature-item">
                                        <i class="fa fa-shield"></i>
                                        <div class="feature-text">
                                            <h5>Secure Payment</h5>
                                            <span>100% secure transactions</span>
                                        </div>
                                    </div>
                                    <div class="feature-item">
                                        <i class="fa fa-rotate-left"></i>
                                        <div class="feature-text">
                                            <h5>Easy Returns</h5>
                                            <span>30-day return policy</span>
                                        </div>
                                    </div>
                                    <div class="feature-item">
                                        <i class="fa fa-headset"></i>
                                        <div class="feature-text">
                                            <h5>24/7 Support</h5>
                                            <span>Dedicated customer service</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="main-button">
                                    <a href="{{ route('shop.all') }}">Explore Our Products</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ***** About Section End ***** -->

                <!-- ***** Stats Section Start ***** -->
                <div class="stats-section">
                    <div class="heading-section">
                        <h4><em>Our</em> Statistics</h4>
                    </div>
                    <div class="stats-grid">
                        <div class="stat-item">
                            <i class="fa fa-users"></i>
                            <span class="stat-number" data-count="1000">0</span>
                            <span class="stat-label">Happy Customers</span>
                        </div>
                        <div class="stat-item">
                            <i class="fa fa-box"></i>
                            <span class="stat-number" data-count="5000">0</span>
                            <span class="stat-label">Products Sold</span>
                        </div>
                        <div class="stat-item">
                            <i class="fa fa-star"></i>
                            <span class="stat-number" data-count="4.8">0</span>
                            <span class="stat-label">Average Rating</span>
                        </div>
                        <div class="stat-item">
                            <i class="fa fa-globe"></i>
                            <span class="stat-number" data-count="20">0</span>
                            <span class="stat-label">Countries</span>
                        </div>
                    </div>
                </div>
                <!-- ***** Stats Section End ***** -->

                <!-- ***** Team Section Start ***** -->
                <div class="team-section">
                    <div class="heading-section">
                        <h4><em>Our</em> Team</h4>
                    </div>
                    <div class="team-grid">
                        <div class="social-section">
                            <div class="team-member">
                                <img src="{{ asset('template_web/assets/images/team-1.jpg') }}" alt="Team Member"
                                 onerror="this.src='https://dummyimage.com/100x100/27292a/ec6090.jpg&text=A'">
                                <h5>AzazeL</h5>
                                <span>CEO & Founder</span>
                                <div class="social-links">
                                    <a href="https://www.instagram.com/dnnsprytmn_" class="instagram" title="Instagram">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                    <a href="https://www.tiktok.com/@dnnsprytmn_" class="tiktok" title="TikTok">
                                        <i class="fab fa-tiktok"></i>
                                    </a>
                                    <a href="#" class="facebook" title="Facebook">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                    <a href="#" class="whatsapp" title="WhatsApp">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="social-section">
                            <div class="team-member">
                                <img src="{{ asset('template_web/assets/images/team-1.jpg') }}" alt="Team Member"
                                 onerror="this.src='https://dummyimage.com/100x100/27292a/ec6090.jpg&text=A'">
                                <h5>AzazeL</h5>
                                <span>CEO & Founder</span>
                                <div class="social-links">
                                    <a href="#" class="instagram" title="Instagram">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                    <a href="#" class="tiktok" title="TikTok">
                                        <i class="fab fa-tiktok"></i>
                                    </a>
                                    <a href="#" class="facebook" title="Facebook">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                    <a href="#" class="whatsapp" title="WhatsApp">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                </div>
                            </div>
                        </div> --}}
                        
                    </div>
                </div>
                <!-- ***** Team Section End ***** -->

            </div>
        </div>
    </div>
@endsection