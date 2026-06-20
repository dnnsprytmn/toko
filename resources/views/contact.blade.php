@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="page-content">
                <div class="heading-section text-center">
                    <h4><em>HUBUNGI</em> KAMI</h4>
                </div>

                <!-- ***** Contact Section Start ***** -->
                <div class="contact-section">
                    <!-- Contact Info -->
                    <div class="contact-info">
                        <div class="info-item">
                            <i class="fa fa-map-marker-alt"></i>
                            <h5>Address</h5>
                            <p>Jl. Ampera Komp. Graha Ampera Permai No. 22<br>Kalimantan Barat, Indonesia</p>
                        </div>
                        <div class="info-item">
                            <i class="fa fa-envelope"></i>
                            <h5>Email</h5>
                            <p>
                                <a href="thriftntrend1502@gmail.com">thriftntrend1502@gmail.com</a><br>
                                <a href="dpriyatman@gmail.com">dpriyatman@gmail.com</a>
                            </p>
                        </div>
                        <div class="info-item">
                            <i class="fa fa-phone"></i>
                            <h5>Phone</h5>
                            <p>
                                <a href="tel:+6289694034079">+62 89694034079</a><br>
                                <a href="tel:+6285190076699">+62 85190076699</a>
                            </p>
                        </div>
                        <div class="info-item">
                            <i class="fa fa-clock"></i>
                            <h5>Working Hours</h5>
                            <p>Everyday: 24 Jam<br>Tutup Hanya Pas Kiamat.</p>
                        </div>
                    </div>

                    <!-- Alert Messages -->
                    @if(session('success'))
                        <div class="alert-custom alert-success">
                            <i class="fa fa-check-circle"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert-custom alert-danger">
                            <i class="fa fa-exclamation-circle"></i>
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert-custom alert-danger">
                            <i class="fa fa-exclamation-circle"></i>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Contact Form -->
                    <div class="contact-form-wrapper">
                        <div class="row">
                            <div class="col-lg-7">
                                <h4 style="color: #ec6090; margin-bottom: 20px;">
                                    <em style="color: #fff; font-style: normal;">Send Us</em> a Message
                                </h4>
                                <form action="{{ route('contact.send') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="name">Full Name <span class="required">*</span></label>
                                        <input type="text" 
                                               class="form-control @error('name') is-invalid @enderror" 
                                               id="name" 
                                               name="name" 
                                               value="{{ old('name') }}"
                                               placeholder="Enter your full name"
                                               required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email Address <span class="required">*</span></label>
                                        <input type="email" 
                                               class="form-control @error('email') is-invalid @enderror" 
                                               id="email" 
                                               name="email" 
                                               value="{{ old('email') }}"
                                               placeholder="Enter your email address"
                                               required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="subject">Subject <span class="required">*</span></label>
                                        <input type="text" 
                                               class="form-control @error('subject') is-invalid @enderror" 
                                               id="subject" 
                                               name="subject" 
                                               value="{{ old('subject') }}"
                                               placeholder="What is this about?"
                                               required>
                                        @error('subject')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="message">Message <span class="required">*</span></label>
                                        <textarea class="form-control @error('message') is-invalid @enderror" 
                                                  id="message" 
                                                  name="message" 
                                                  rows="5" 
                                                  placeholder="Write your message here..."
                                                  required>{{ old('message') }}</textarea>
                                        @error('message')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn-submit" id="submitBtn">
                                        <i class="fa fa-paper-plane"></i>
                                        Send Message
                                    </button>
                                </form>
                            </div>
                            <div class="col-lg-5">
                                <div style="background: #1f2122; padding: 20px; border-radius: 12px; height: 100%;">
                                    <h5 style="color: #fff; margin-bottom: 15px;">
                                        <i class="fa fa-info-circle" style="color: #ec6090;"></i>
                                        Information
                                    </h5>
                                    <p style="color: #666; font-size: 14px; line-height: 1.8;">
                                        We'd love to hear from you. Please fill out the form or reach us through the contact information provided.
                                    </p>
                                    <hr style="border-color: #3a3c3d;">
                                    <div style="margin-top: 15px;">
                                        <p style="color: #666; font-size: 14px; margin-bottom: 8px;">
                                            <i class="fa fa-reply" style="color: #ec6090; width: 20px;"></i>
                                            Response within 24 hours
                                        </p>
                                        <p style="color: #666; font-size: 14px; margin-bottom: 8px;">
                                            <i class="fa fa-lock" style="color: #ec6090; width: 20px;"></i>
                                            Your data is safe with us
                                        </p>
                                        <p style="color: #666; font-size: 14px;">
                                            <i class="fa fa-smile" style="color: #ec6090; width: 20px;"></i>
                                            We're here to help!
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Map -->
                    <div class="map-section">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.521260485283!2d106.828561!3d-6.208484!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f5397e2c8b2d%3A0x6f5c5b5c5b5c5b5c!2sJakarta!5e0!3m2!1sen!2sid!4v1700000000000" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Map Location">
                        </iframe>
                    </div>

                    <!-- ===== SOCIAL MEDIA ===== -->
                    <div class="social-section">
                        <h5><em>Connect</em> With Us</h5>
                        <p class="social-subtitle">Follow us on social media for updates and promotions.</p>
                        <div class="social-links">
                            <a href="#" class="facebook" title="Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="twitter" title="Twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="instagram" title="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="youtube" title="YouTube">
                                <i class="fab fa-youtube"></i>
                            </a>
                            <a href="#" class="whatsapp" title="WhatsApp">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                            <a href="#" class="tiktok" title="TikTok">
                                <i class="fab fa-tiktok"></i>
                            </a>
                        </div>
                    </div>
                    <!-- ***** Contact Section End ***** -->

            </div>
        </div>
    </div>
</div>
@endsection