<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'AzazeL Warehouse')</title>
    
    {{-- LOGO --}}
    <link rel="icon" type="image/png" href="{{ url('assets/logo/1.png') }}">
    <link rel="apple-touch-icon" href="{{ url('assets/logo/1.png') }}">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('template_web/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="{{ asset('template_web/assets/css/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('template_web/assets/css/templatemo-cyborg-gaming.css') }}">
    <link rel="stylesheet" href="{{ asset('template_web/assets/css/owl.css') }}">
    <link rel="stylesheet" href="{{ asset('template_web/assets/css/animate.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css">
    
    <!-- ===== HEADER FIX CSS ===== -->
    <link rel="stylesheet" href="{{ asset('template_web/assets/css/header-fix.css') }}">
</head>
<body>

<!-- ***** Preloader Start ***** -->
<div id="js-preloader" class="js-preloader">
    <div class="preloader-inner">
        <span class="dot"></span>
        <div class="dots">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
</div>
<!-- ***** Preloader End ***** -->

<!-- ***** Header Area Start ***** -->
<header class="header-area header-sticky">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="main-nav">
                    <!-- Logo -->
                    <a href="{{ route('home') }}" class="logo mt-2">
                        <h4><em>AZAZEL</em> WAREHOUSE</h4>
                    </a>
                    
                    <!-- Search -->
                    <div class="search-input">
                        <form id="search" action="{{ route('home') }}" method="GET">
                            <input type="text" placeholder="Search products..." name="search" value="{{ request('search') }}">
                            <i class="fa fa-search"></i>
                        </form>
                    </div>
                    
                    <!-- Menu -->
                    <ul class="nav">
                        <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
                        <li><a href="{{ route('shop.all') }}" class="{{ request()->routeIs('shop.all') ? 'active' : '' }}">Shop</a></li>
                        <li><a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">About</a></li>
                        <li><a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a></li>
                        <li>
                            <a href="{{ route('track.order') }}" class="{{ request()->routeIs('track.order') ? 'active' : '' }}">
                                <i class="fa fa-search-location me-1"></i> Track Order
                            </a>
                        </li>
                        <!-- ===== CART BUTTON (Always Active) ===== -->
                        <li class="cart-item">
                            <a href="{{ route('cart') }}" id="cartBtn" class="cart-active">
                                <i class="fa fa-shopping-cart"></i>
                                <span class="cart-text">Cart</span>
                                <span class="cart-badge" id="cartBadge">0</span>
                                <span class="cart-dot" id="cartDot"></span>
                            </a>
                        </li>
                    </ul>   
                    <a class='menu-trigger'>
                        <span>Menu</span>
                    </a>
                </nav>
            </div>
        </div>
    </div>
</header>
<!-- ***** Header Area End ***** -->

<!-- ===== TOAST CONTAINER ===== -->
<div class="toast-container" id="toastContainer"></div>

@yield('content')

<footer>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <p>Copyright &copy; {{ date('Y') }} <a href="{{ route('home') }}">AzazeL Warehouse</a>. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>

<!-- Scripts -->
<script src="{{ asset('template_web/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('template_web/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('template_web/assets/js/isotope.min.js') }}"></script>
<script src="{{ asset('template_web/assets/js/owl-carousel.js') }}"></script>
<script src="{{ asset('template_web/assets/js/tabs.js') }}"></script>
<script src="{{ asset('template_web/assets/js/popup.js') }}"></script>
<script src="{{ asset('template_web/assets/js/custom.js') }}"></script>

<!-- ===== HEADER STICKY SCRIPT ===== -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sticky Header on Scroll
        var header = document.querySelector('.header-area');
        if (header) {
            window.addEventListener('scroll', function() {
                if (window.scrollY > 10) {
                    header.classList.add('background-header');
                } else {
                    header.classList.remove('background-header');
                }
            });
        }
        
        
    });
</script>

<!-- ===== CART BADGE & ACTIVE STATE ===== -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cartBtn = document.getElementById('cartBtn');
        const cartBadge = document.getElementById('cartBadge');
        const cartDot = document.getElementById('cartDot');

        // ===== CHECK IF MOBILE =====
        function isMobile() {
            return window.innerWidth <= 992;
        }

        // ===== UPDATE CART STATE =====
        function updateCartState(count) {
            // Update badge text
            cartBadge.textContent = count;

            // Check if mobile
            const mobile = isMobile();

            if (count > 0) {
                // Desktop: show dot
                if (!mobile) {
                    cartDot.classList.add('show');
                    cartBtn.classList.add('cart-has-items');
                    cartBadge.classList.remove('has-items');
                } else {
                    // Mobile: show badge with red color
                    cartDot.classList.remove('show');
                    cartBtn.classList.add('cart-has-items');
                    cartBadge.classList.add('has-items');
                }
                
                // Bounce animation
                cartBadge.classList.remove('bounce');
                void cartBadge.offsetWidth;
                cartBadge.classList.add('bounce');
            } else {
                // Cart empty
                cartDot.classList.remove('show');
                cartBtn.classList.remove('cart-has-items');
                cartBadge.classList.remove('has-items');
                cartBadge.textContent = '0';
            }
            
            // Update badge text again (ensure consistency)
            cartBadge.textContent = count;
        }

        // ===== GET CART COUNT =====
        function getCartCount() {
            fetch('{{ route('cart.count') }}')
                .then(response => response.json())
                .then(data => {
                    const count = data.count || 0;
                    updateCartState(count);
                })
                .catch(() => {
                    // Fallback: use current badge value
                    const currentCount = parseInt(cartBadge.textContent) || 0;
                    updateCartState(currentCount);
                });
        }

        // ===== INITIAL LOAD =====
        getCartCount();

        // ===== UPDATE ON WINDOW RESIZE =====
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                const count = parseInt(cartBadge.textContent) || 0;
                updateCartState(count);
            }, 250);
        });

        // ===== UPDATE ON CART CHANGE =====
        const originalFetch = window.fetch;
        window.fetch = function(...args) {
            return originalFetch.apply(this, args).then(function(response) {
                const url = args[0];
                if (typeof url === 'string' && (url.includes('add-to-cart') || url.includes('update-cart') || url.includes('remove-from-cart'))) {
                    setTimeout(getCartCount, 300);
                }
                return response;
            });
        };

        // ===== CUSTOM EVENT =====
        document.addEventListener('cartUpdated', function() {
            getCartCount();
        });

        // ===== UPDATE ON PAGE VISIBILITY =====
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                setTimeout(getCartCount, 500);
            }
        });

        // ===== UPDATE ON POPSTATE =====
        window.addEventListener('popstate', function() {
            setTimeout(getCartCount, 500);
        });

        console.log('Cart monitoring active!');
    });
</script>

<!-- ===== TOAST NOTIFICATION SYSTEM ===== -->
<script>
    class Toast {
        static show(options) {
            const {
                title = '',
                message = '',
                type = 'success',
                duration = 4000,
                emoji = null
            } = options;

            const container = document.getElementById('toastContainer');
            if (!container) return null;

            const toast = document.createElement('div');
            toast.className = `toast-item toast-${type}`;

            const emojiMap = {
                success: '🎉',
                error: '❌',
                info: 'ℹ️',
                warning: '⚠️'
            };
            const emojiChar = emoji || emojiMap[type] || '📢';

            toast.innerHTML = `
                <div class="toast-emoji">${emojiChar}</div>
                <div class="toast-content">
                    <div class="toast-title">${title}</div>
                    <div class="toast-message">${message}</div>
                </div>
                <button class="toast-close" onclick="this.closest('.toast-item').remove()">
                    <i class="fas fa-times"></i>
                </button>
                <div class="toast-progress"></div>
            `;

            container.appendChild(toast);

            requestAnimationFrame(() => {
                toast.classList.add('show');
            });

            setTimeout(() => {
                Toast.remove(toast);
            }, duration);

            return toast;
        }

        static remove(toast) {
            toast.classList.remove('show');
            toast.classList.add('hide');
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.remove();
                }
            }, 500);
        }

        static success(title, message, duration = 4000) {
            return this.show({ title, message, type: 'success', duration });
        }

        static error(title, message, duration = 4000) {
            return this.show({ title, message, type: 'error', duration });
        }

        static info(title, message, duration = 4000) {
            return this.show({ title, message, type: 'info', duration });
        }

        static warning(title, message, duration = 4000) {
            return this.show({ title, message, type: 'warning', duration });
        }

        static cartAdd(productName, quantity = 1, duration = 3500) {
            const title = '🛒 Added to Cart!';
            const message = `<span class="toast-product">${productName}</span> × ${quantity} added to your cart`;
            return this.show({ title, message, type: 'success', duration, emoji: '🛒' });
        }

        static cartRemove(productName, duration = 3500) {
            const title = '🗑️ Removed from Cart';
            const message = `<span class="toast-product">${productName}</span> removed from your cart`;
            return this.show({ title, message, type: 'info', duration, emoji: '🗑️' });
        }

        static cartUpdate(productName, quantity, duration = 3000) {
            const title = '🔄 Cart Updated';
            const message = `<span class="toast-product">${productName}</span> quantity updated to ${quantity}`;
            return this.show({ title, message, type: 'info', duration, emoji: '🔄' });
        }

        static cartError(message, duration = 4000) {
            return this.show({ title: '⚠️ Error', message, type: 'error', duration, emoji: '⚠️' });
        }
    }

    // ===== SESSION FLASH MESSAGES TO TOAST =====
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('toast'))
            @php
                $toast = session('toast');
            @endphp
            @if($toast['type'] == 'cart_add')
                Toast.cartAdd('{{ $toast['product'] }}', {{ $toast['quantity'] ?? 1 }});
            @elseif($toast['type'] == 'cart_remove')
                Toast.cartRemove('{{ $toast['product'] }}');
            @elseif($toast['type'] == 'cart_update')
                Toast.cartUpdate('{{ $toast['product'] }}', {{ $toast['quantity'] ?? 1 }});
            @endif
        @endif

        @if(session('success'))
            Toast.success('✅ Success!', '{{ session('success') }}');
        @endif

        @if(session('error'))
            Toast.error('❌ Error!', '{{ session('error') }}');
        @endif

        @if(session('info'))
            Toast.info('ℹ️ Info', '{{ session('info') }}');
        @endif

        @if(session('warning'))
            Toast.warning('⚠️ Warning', '{{ session('warning') }}');
        @endif
    });
</script>

@stack('scripts')

</body>
</html>