@extends('layouts.app')

@section('title', 'Checkout - AzazeL Warehouse')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="page-content">
                <div class="heading-section text-center">
                    <h4><em>CHECKOUT</em> BELANJAAN MU</h4>
                </div>

                <!-- ***** Checkout Section Start ***** -->
                <div class="checkout-section">
                    <!-- Alert Messages - Hanya tampil jika ada error validasi -->
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

                    <div class="row">
                        <!-- Checkout Form -->
                        <div class="col-lg-7">
                            <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
                                @csrf
                                
                                <!-- Customer Information -->
                                <div style="background: #27292a; padding: 20px; border-radius: 12px; margin-bottom: 25px; border: 1px solid #3a3c3d;">
                                    <h5 style="color: #ec6090; margin-bottom: 15px;">
                                        <i class="fa fa-user me-2"></i> Customer Information
                                    </h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="customer_name">Full Name <span class="required">*</span></label>
                                                <input type="text" 
                                                       class="form-control @error('customer_name') is-invalid @enderror" 
                                                       id="customer_name" 
                                                       name="customer_name" 
                                                       value="{{ old('customer_name') }}"
                                                       placeholder="Enter your full name"
                                                       required>
                                                @error('customer_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="customer_email">Email Address <span class="required">*</span></label>
                                                <input type="email" 
                                                       class="form-control @error('customer_email') is-invalid @enderror" 
                                                       id="customer_email" 
                                                       name="customer_email" 
                                                       value="{{ old('customer_email') }}"
                                                       placeholder="Enter your email"
                                                       required>
                                                @error('customer_email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="customer_phone">Phone Number <span class="required">*</span></label>
                                        <input type="text" 
                                               class="form-control @error('customer_phone') is-invalid @enderror" 
                                               id="customer_phone" 
                                               name="customer_phone" 
                                               value="{{ old('customer_phone') }}"
                                               placeholder="Enter your phone number"
                                               required>
                                        @error('customer_phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Shipping Address -->
                                <div style="background: #27292a; padding: 20px; border-radius: 12px; margin-bottom: 25px; border: 1px solid #3a3c3d;">
                                    <h5 style="color: #ec6090; margin-bottom: 15px;">
                                        <i class="fa fa-truck me-2"></i> Shipping Address
                                    </h5>
                                    <div class="form-group">
                                        <label for="shipping_address">Address <span class="required">*</span></label>
                                        <textarea class="form-control @error('shipping_address') is-invalid @enderror" 
                                                  id="shipping_address" 
                                                  name="shipping_address" 
                                                  rows="2" 
                                                  placeholder="Enter your shipping address"
                                                  required>{{ old('shipping_address') }}</textarea>
                                        @error('shipping_address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="shipping_city">City <span class="required">*</span></label>
                                                <input type="text" 
                                                       class="form-control @error('shipping_city') is-invalid @enderror" 
                                                       id="shipping_city" 
                                                       name="shipping_city" 
                                                       value="{{ old('shipping_city') }}"
                                                       placeholder="Enter city"
                                                       required>
                                                @error('shipping_city')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="shipping_postal_code">Postal Code <span class="required">*</span></label>
                                                <input type="text" 
                                                       class="form-control @error('shipping_postal_code') is-invalid @enderror" 
                                                       id="shipping_postal_code" 
                                                       name="shipping_postal_code" 
                                                       value="{{ old('shipping_postal_code') }}"
                                                       placeholder="Enter postal code"
                                                       required>
                                                @error('shipping_postal_code')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Payment Method -->
                                <div style="background: #27292a; padding: 20px; border-radius: 12px; margin-bottom: 25px; border: 1px solid #3a3c3d;">
                                    <h5 style="color: #ec6090; margin-bottom: 15px;">
                                        <i class="fa fa-credit-card me-2"></i> Payment Method
                                    </h5>
                                    <div class="payment-methods">
                                        <div class="payment-method active">
                                            <input type="radio" name="payment_method" id="bank_transfer" value="bank_transfer" checked>
                                            <label class="payment-label" for="bank_transfer">
                                                <i class="fa fa-university"></i>
                                                <span>Bank Transfer</span>
                                            </label>
                                        </div>
                                        <div class="payment-method">
                                            <input type="radio" name="payment_method" id="credit_card" value="credit_card">
                                            <label class="payment-label" for="credit_card">
                                                <i class="fa fa-credit-card"></i>
                                                <span>Credit Card</span>
                                            </label>
                                        </div>
                                        <div class="payment-method">
                                            <input type="radio" name="payment_method" id="e_wallet" value="e_wallet">
                                            <label class="payment-label" for="e_wallet">
                                                <i class="fa fa-wallet"></i>
                                                <span>E-Wallet</span>
                                            </label>
                                        </div>
                                    </div>
                                    @error('payment_method')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Notes -->
                                <div style="background: #27292a; padding: 20px; border-radius: 12px; margin-bottom: 25px; border: 1px solid #3a3c3d;">
                                    <h5 style="color: #ec6090; margin-bottom: 15px;">
                                        <i class="fa fa-sticky-note me-2"></i> Order Notes (Optional)
                                    </h5>
                                    <div class="form-group">
                                        <textarea class="form-control" 
                                                  id="notes" 
                                                  name="notes" 
                                                  rows="3" 
                                                  placeholder="Any special notes for your order?">{{ old('notes') }}</textarea>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Order Summary -->
                        <div class="col-lg-5">
                            <div class="order-summary">
                                <h4><em>Order</em> Summary</h4>
                                
                                @php
                                    $cart = session()->get('cart', []);
                                    $subtotal = 0;
                                    foreach($cart as $item) {
                                        $subtotal += $item['price'] * $item['quantity'];
                                    }
                                    $shipping = 10000;
                                    $total = $subtotal;
                                @endphp

                                @foreach($cart as $item)
                                <div class="order-item">
                                    <span class="item-name">{{ $item['name'] }} × {{ $item['quantity'] }}</span>
                                    <span class="item-price">Rp. {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                                </div>
                                @endforeach

                                <div class="summary-row">
                                    <span class="text-white">Subtotal</span>
                                    <span class="value">Rp. {{ number_format($subtotal, 0, ',', '.') }}</span>
                                </div>
                                <div class="summary-row total">
                                    <h4 class="text-white">TOTAL</h4>
                                    <span class="value">Rp. {{ number_format($total, 0, ',', '.') }}</span>
                                </div>

                                <button type="submit" form="checkoutForm" class="btn-place-order" id="submitBtn">
                                    <i class="fa fa-check-circle"></i>
                                    Place Order
                                </button>

                                <a href="{{ route('cart') }}" class="btn-continue" style="display: block; width: 100%; padding: 10px; background: transparent; color: #666; border: 1px solid #3a3c3d; border-radius: 25px; font-size: 14px; text-align: center; transition: all 0.3s ease; margin-top: 10px; text-decoration: none;">
                                    <i class="fa fa-arrow-left me-2"></i> Back to Cart
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ***** Checkout Section End ***** -->

            </div>
        </div>
    </div>
</div>

<!-- ===== SCRIPT ===== -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ===== PAYMENT METHOD SELECTION =====
        const paymentMethods = document.querySelectorAll('.payment-method');
        
        paymentMethods.forEach(method => {
            method.addEventListener('click', function() {
                paymentMethods.forEach(m => m.classList.remove('active'));
                this.classList.add('active');
                const radio = this.querySelector('input[type="radio"]');
                if (radio) radio.checked = true;
            });
        });

        // ===== PREVENT DOUBLE SUBMIT & SHOW TOAST =====
        const form = document.getElementById('checkoutForm');
        const submitBtn = document.getElementById('submitBtn');
        
        form.addEventListener('submit', function(e) {
            // Validasi form
            const name = document.getElementById('customer_name').value.trim();
            const email = document.getElementById('customer_email').value.trim();
            const phone = document.getElementById('customer_phone').value.trim();
            const address = document.getElementById('shipping_address').value.trim();
            const city = document.getElementById('shipping_city').value.trim();
            const postal = document.getElementById('shipping_postal_code').value.trim();

            if (!name || !email || !phone || !address || !city || !postal) {
                e.preventDefault();
                Toast.error('⚠️ Error', 'Mohon isi semua field yang wajib diisi.');
                return false;
            }

            // Disable button dan show loading
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin me-2"></i> Processing...';
            
            // Toast akan muncul dari session setelah redirect
            // Tidak ada alert tambahan
        });

        // ===== CEK SESSION ERROR DARI CONTROLLER =====
        @if(session('error'))
            Toast.error('❌ Error', '{{ session('error') }}');
        @endif
    });
</script>
@endsection