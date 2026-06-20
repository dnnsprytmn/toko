@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="page-content">
                <div class="heading-section text-center">
                    <h4><em>KERANJANG</em> KAMU</h4>
                </div>

                <!-- ***** Cart Section Start ***** -->
                <div class="cart-section">
                    

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="background: #3a1a1a; border-color: #dc3545; color: #dc3545; border-radius: 12px; padding: 15px 20px;">
                            <i class="fa fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" style="filter: invert(1);"></button>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert" style="background: #1a3a2a; border-color: #28a745; color: #28a745; border-radius: 12px; padding: 15px 20px;">
                            <i class="fa fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" style="filter: invert(1);"></button>
                        </div>
                    @endif

                    @if(session()->get('cart') && count(session()->get('cart')) > 0)
                        @php $total = 0 @endphp
                        @foreach(session()->get('cart') as $id => $details)
                            @php 
                                $subtotal = $details['price'] * $details['quantity'];
                                $total += $subtotal;
                            @endphp
                            <div class="cart-item" data-id="{{ $id }}" data-name="{{ $details['name'] }}">
                                <img src="{{ $details['image'] }}" alt="{{ $details['name'] }}" class="product-image">
                                
                                <div class="product-info">
                                    <div class="product-name">{{ $details['name'] }}</div>
                                    <div class="product-price">Rp. {{ number_format($details['price'], 0, ',', '.') }}</div>
                                </div>
                                
                                <div class="quantity-control">
                                    {{-- <button class="qty-btn qty-minus" data-id="{{ $id }}">
                                        <i class="fa fa-minus"></i>
                                    </button> --}}
                                    <input type="number" class="qty-input" value="{{ $details['quantity'] }}" 
                                           min="1" max="100" data-id="{{ $id }}" id="qty-{{ $id }}">
                                    {{-- <button class="qty-btn qty-plus" data-id="{{ $id }}">
                                        <i class="fa fa-plus"></i>
                                    </button> --}}
                                </div>
                                
                                <div class="item-subtotal" id="subtotal-{{ $id }}">
                                    Rp. {{ number_format($subtotal, 0, ',', '.') }}
                                </div>
                                
                                <!-- ===== REMOVE BUTTON WITH MODAL ===== -->
                                <button class="remove-btn" data-id="{{ $id }}" data-name="{{ $details['name'] }}" 
                                        onclick="showRemoveModal({{ $id }}, '{{ $details['name'] }}')" 
                                        title="Remove item">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        @endforeach

                        <!-- Cart Summary -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="cart-summary">
                                    <h4><em>Order</em> Summary</h4>
                                    
                                    <div class="summary-row">
                                        <span class="text-white">Subtotal</span>
                                        <span class="value" id="subtotal-total">Rp. {{ number_format($total, 0, ',', '.') }}</span>
                                    </div>
                                    {{-- <div class="summary-row">
                                        <span>Shipping</span>
                                        <span class="value" id="shipping-total">Rp. 10.000</span>
                                    </div> --}}
                                    <div class="summary-row total">
                                        <h4>TOTAL</h4>
                                        <span class="value" id="grand-total">Rp. {{ number_format($total, 0, ',', '.') }}</span>
                                    </div>
                                    
                                    <a href="{{ route('checkout') }}" class="btn-checkout">
                                        <i class="fa fa-credit-card me-2"></i> Proceed to Checkout
                                    </a>
                                    <a href="{{ route('shop.all') }}" class="btn-continue">
                                        <i class="fa fa-arrow-left me-2"></i> Continue Shopping
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Empty Cart -->
                        <div class="empty-cart">
                            <div class="empty-icon">
                                <i class="fa fa-shopping-cart"></i>
                            </div>
                            <h3>Your cart is empty</h3>
                            <p>Looks like you haven't added any items to your cart yet.</p>
                            <a href="{{ route('shop.all') }}" class="btn-shop">
                                <i class="fa fa-arrow-left me-2"></i> Start Shopping
                            </a>
                        </div>
                    @endif
                </div>
                <!-- ***** Cart Section End ***** -->

            </div>
        </div>
    </div>
</div>

<!-- ===== CUSTOM CONFIRMATION MODAL ===== -->
<div class="custom-modal" id="removeModal">
    <div class="modal-box">
        <button class="modal-close" onclick="closeRemoveModal()">
            <i class="fa fa-times"></i>
        </button>
        
        <span class="modal-icon">🗑️</span>
        <h5 class="modal-title">Hapus <em>Item</em>?</h5>
        <p class="modal-subtitle">
            Apakah Anda yakin ingin menghapus <br>
            <span class="product-name" id="modalProductName">item ini</span> dari keranjang?
        </p>
        
        <div class="modal-actions">
            <button class="btn-modal btn-cancel" onclick="closeRemoveModal()">
                <i class="fa fa-times me-1"></i> Batal
            </button>
            <button class="btn-modal btn-danger" id="confirmRemoveBtn">
                <i class="fa fa-trash me-1"></i> Hapus
            </button>
        </div>
    </div>
</div>

<script>
    // ===== CUSTOM REMOVE MODAL =====
    let removeItemId = null;

    function showRemoveModal(id, name) {
        removeItemId = id;
        document.getElementById('modalProductName').textContent = name;
        document.getElementById('removeModal').classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeRemoveModal() {
        document.getElementById('removeModal').classList.remove('show');
        document.body.style.overflow = '';
        removeItemId = null;
    }

    // Confirm remove
    document.getElementById('confirmRemoveBtn').addEventListener('click', function() {
        if (removeItemId) {
            window.location.href = `{{ url('remove-from-cart') }}/${removeItemId}`;
        }
    });

    // Close modal on backdrop click
    document.getElementById('removeModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeRemoveModal();
        }
    });

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeRemoveModal();
        }
    });

    // ===== QUANTITY CONTROL =====
    document.addEventListener('DOMContentLoaded', function() {
        const qtyInputs = document.querySelectorAll('.qty-input');
        const qtyMinus = document.querySelectorAll('.qty-minus');
        const qtyPlus = document.querySelectorAll('.qty-plus');
        
        function updateQuantity(id, quantity) {
            const formData = new FormData();
            formData.append('quantity', quantity);
            formData.append('_token', '{{ csrf_token() }}');
            
            const cartItem = document.querySelector(`.cart-item[data-id="${id}"]`);
            if (cartItem) cartItem.style.opacity = '0.6';
            
            fetch(`{{ url('update-cart') }}/${id}`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update subtotal
                    const subtotalElement = document.getElementById(`subtotal-${id}`);
                    if (subtotalElement) {
                        subtotalElement.textContent = `Rp. ${data.subtotal.toLocaleString('id-ID')}`;
                    }
                    
                    // Update totals
                    const total = data.total;
                    // const shipping = 10000;
                    const grandTotal = total;
                    
                    document.getElementById('subtotal-total').textContent = `Rp. ${total.toLocaleString('id-ID')}`;
                    document.getElementById('grand-total').textContent = `Rp. ${grandTotal.toLocaleString('id-ID')}`;
                    
                    // Update input
                    const input = document.querySelector(`.qty-input[data-id="${id}"]`);
                    if (input) input.value = data.quantity;
                } else {
                    // Use Toast if available, fallback to alert
                    if (typeof Toast !== 'undefined') {
                        Toast.error('Error', data.message || 'Failed to update cart');
                    } else {
                        alert(data.message || 'Failed to update cart');
                    }
                }
                if (cartItem) cartItem.style.opacity = '1';
            })
            .catch(error => {
                console.error('Error:', error);
                if (typeof Toast !== 'undefined') {
                    Toast.error('Error', 'Failed to update cart');
                } else {
                    alert('Failed to update cart');
                }
                if (cartItem) cartItem.style.opacity = '1';
            });
        }
        
        qtyMinus.forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                const input = document.querySelector(`.qty-input[data-id="${id}"]`);
                let val = parseInt(input.value);
                if (val > 1) {
                    val--;
                    input.value = val;
                    updateQuantity(id, val);
                }
            });
        });
        
        qtyPlus.forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                const input = document.querySelector(`.qty-input[data-id="${id}"]`);
                let val = parseInt(input.value);
                if (val < 100) {
                    val++;
                    input.value = val;
                    updateQuantity(id, val);
                }
            });
        });
        
        qtyInputs.forEach(input => {
            input.addEventListener('change', function() {
                const id = this.dataset.id;
                let val = parseInt(this.value);
                if (isNaN(val) || val < 1) val = 1;
                if (val > 100) val = 100;
                this.value = val;
                updateQuantity(id, val);
            });
        });
    });
</script>
@endsection