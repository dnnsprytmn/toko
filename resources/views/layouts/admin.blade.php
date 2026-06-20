<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
        {{-- LOGO --}}
    <link rel="icon" type="image/png" href="{{ url('assets/logo/1.png') }}">
    <link rel="apple-touch-icon" href="{{ url('assets/logo/1.png') }}">
    <title>@yield('title', 'Admin Dashboard')</title>
    <link href="{{ url('template_admin/css/styles.css') }}" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
    <!-- Top Navigation -->
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand ps-3" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-store me-2"></i>Admin Panel
        </a>
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!">
            <i class="fas fa-bars"></i>
        </button>
        
        <!-- Mobile Search Toggle Button -->
        <button class="mobile-search-toggle" onclick="toggleMobileSearch()" title="Search">
            <i class="fas fa-search"></i>
        </button>
        
        <!-- Navbar Search (Desktop) -->
        <div class="d-none d-md-inline-block ms-auto me-0 me-md-3 my-2 my-md-0 search-container">
            <i class="fas fa-search search-icon"></i>
            <input class="form-control" 
                   type="text" 
                   id="navbarSearch" 
                   placeholder="Search products..." 
                   autocomplete="off"
                   oninput="handleSearch(this.value)">
            <button class="clear-btn" id="clearSearchBtn" onclick="clearSearch()">
                <i class="fas fa-times-circle"></i>
            </button>
            
            <!-- Search Results Dropdown -->
            <div class="search-results" id="searchResults">
                <div class="search-loading" id="searchLoading">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                    <p class="mt-2">Searching...</p>
                </div>
                <div id="searchResultContent">
                    <!-- Results will be inserted here -->
                </div>
            </div>
        </div>
        
        <!-- Navbar User Dropdown -->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle btn btn-success" href="#" role="button" data-bs-toggle="dropdown">
                    <i class="fas fa-user fa-fw"></i>
                    {{ Auth::guard('admin')->user()->name ?? 'Admin' }}
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('admin.profile.edit') }}">
                        <i class="fas fa-user-circle me-2"></i>Profile
                    </a></li>
                    <li><hr class="dropdown-divider" /></li>
                    <li>
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>

    <!-- Mobile Search Modal -->
    <div class="mobile-search-modal" id="mobileSearchModal">
        <div class="search-header">
            <button class="close-btn" onclick="toggleMobileSearch()">
                <i class="fas fa-arrow-left"></i>
            </button>
            <div class="search-input-wrapper">
                <input type="text" 
                       id="mobileSearchInput" 
                       placeholder="Search products..." 
                       autocomplete="off"
                       oninput="handleMobileSearch(this.value)">
                <button class="clear-mobile-btn" id="clearMobileBtn" onclick="clearMobileSearch()">
                    <i class="fas fa-times-circle"></i>
                </button>
            </div>
        </div>
        
        <div class="mobile-results" id="mobileResults">
            <div class="search-loading" id="mobileSearchLoading">
                <i class="fas fa-spinner fa-spin fa-2x"></i>
                <p class="mt-2">Searching...</p>
            </div>
            <div id="mobileResultContent">
                <!-- Mobile results will be inserted here -->
                <div class="no-result">
                    <i class="fas fa-search fa-3x mb-3 d-block"></i>
                    <p>Type at least 2 characters to start searching</p>
                </div>
            </div>
        </div>
    </div>

    <div id="layoutSidenav">
        <!-- Sidebar -->
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Core</div>
                        
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                           href="{{ route('admin.dashboard') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                        
                        <div class="sb-sidenav-menu-heading">Management</div>
                        <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" 
                           href="{{ route('admin.products.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-box"></i></div>
                            Products
                        </a>
                        <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" 
                           href="{{ route('admin.orders.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-shopping-cart"></i></div>
                            Orders
                        </a>

                        <!-- HANYA TAMPILKAN MENU ADMINS JIKA BUKAN STAFF -->
                        @if(Auth::guard('admin')->user()->role != 'staff')
                        <a class="nav-link {{ request()->routeIs('admin.admins.*') ? 'active' : '' }}" href="{{ route('admin.admins.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                                Admins
                        </a>
                        @endif

                        <a class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}" href="{{ route('admin.customers.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                                Customers
                        </a>
                        
                        <!-- ===== REPORTS MENU ===== -->
                        <div class="sb-sidenav-menu-heading">Reports</div>
                        <a class="nav-link collapsed {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" 
                        href="#" 
                        data-bs-toggle="collapse" 
                        data-bs-target="#collapseReports" 
                        aria-expanded="{{ request()->routeIs('admin.reports.*') ? 'true' : 'false' }}" 
                        aria-controls="collapseReports">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-line"></i></div>
                            Reports
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse {{ request()->routeIs('admin.reports.*') ? 'show' : '' }}" 
                            id="collapseReports" 
                            aria-labelledby="headingReports" 
                            data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link {{ request()->routeIs('admin.reports.sales') ? 'active' : '' }}" 
                                href="{{ route('admin.reports.sales') }}">
                                    <i class="fas fa-chart-bar me-2"></i>Sales Report
                                </a>
                                <a class="nav-link {{ request()->routeIs('admin.reports.products') ? 'active' : '' }}" 
                                href="{{ route('admin.reports.products') }}">
                                    <i class="fas fa-box me-2"></i>Product Report
                                </a>
                                <a class="nav-link {{ request()->routeIs('admin.reports.customers') ? 'active' : '' }}" 
                                href="{{ route('admin.reports.customers') }}">
                                    <i class="fas fa-users me-2"></i>Customer Report
                                </a>
                            </nav>
                        </div>
                        
                        <div class="sb-sidenav-menu-heading">Profile</div>
                        <a class="nav-link" href="{{ route('admin.profile.edit') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
                            Profile
                        </a>
                        <a class="nav-link" href="{{ route('home') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-globe"></i></div>
                            View Website
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    {{ Auth::guard('admin')->user()->name ?? 'Admin User' }}
                </div>
            </nav>
        </div>
        
        <!-- Main Content -->
        <div id="layoutSidenav_content">
            <main>
                @yield('content')
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Admin Panel {{ date('Y') }}</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>
    
    <script>
        // Sidebar toggle
        const sidebarToggle = document.getElementById('sidebarToggle');
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function() {
                document.body.classList.toggle('sb-sidenav-toggled');
            });
        }
        
        // Initialize DataTable if exists
        if (document.getElementById('datatablesSimple')) {
            new simpleDatatables.DataTable('#datatablesSimple');
        }
        
        // ============================================
        // === SEARCH FUNCTIONALITY (DESKTOP + MOBILE) ===
        // ============================================
        
        let searchTimeout = null;
        
        // ===== DESKTOP SEARCH =====
        const searchInput = document.getElementById('navbarSearch');
        const searchResults = document.getElementById('searchResults');
        const searchLoading = document.getElementById('searchLoading');
        const searchResultContent = document.getElementById('searchResultContent');
        const clearBtn = document.getElementById('clearSearchBtn');
        
        // ===== MOBILE SEARCH =====
        const mobileSearchInput = document.getElementById('mobileSearchInput');
        const mobileResults = document.getElementById('mobileResults');
        const mobileSearchLoading = document.getElementById('mobileSearchLoading');
        const mobileResultContent = document.getElementById('mobileResultContent');
        const clearMobileBtn = document.getElementById('clearMobileBtn');
        
        // === MOBILE SEARCH FUNCTIONS ===
        function toggleMobileSearch() {
            const modal = document.getElementById('mobileSearchModal');
            modal.classList.toggle('show');
            if (modal.classList.contains('show')) {
                setTimeout(() => {
                    mobileSearchInput.focus();
                }, 300);
            } else {
                mobileSearchInput.value = '';
                clearMobileBtn.classList.remove('show');
                mobileResultContent.innerHTML = `
                    <div class="no-result">
                        <i class="fas fa-search fa-3x mb-3 d-block"></i>
                        <p>Type at least 2 characters to start searching</p>
                    </div>
                `;
                mobileResults.classList.remove('show');
            }
        }
        
        function handleMobileSearch(query) {
            // Show/hide clear button
            if (query.length > 0) {
                clearMobileBtn.classList.add('show');
            } else {
                clearMobileBtn.classList.remove('show');
                mobileResultContent.innerHTML = `
                    <div class="no-result">
                        <i class="fas fa-search fa-3x mb-3 d-block"></i>
                        <p>Type at least 2 characters to start searching</p>
                    </div>
                `;
                return;
            }
            
            // Only search if query has at least 2 characters
            if (query.length < 2) {
                mobileResultContent.innerHTML = `
                    <div class="no-result">
                        <i class="fas fa-info-circle fa-2x mb-3 d-block"></i>
                        <p>Type at least 2 characters to search</p>
                    </div>
                `;
                return;
            }
            
            // Show loading
            mobileSearchLoading.classList.add('show');
            mobileResultContent.innerHTML = '';
            mobileResults.classList.add('show');
            
            // Clear previous timeout
            if (searchTimeout) {
                clearTimeout(searchTimeout);
            }
            
            // Debounce search
            searchTimeout = setTimeout(() => {
                performMobileSearch(query);
            }, 300);
        }
        
        function performMobileSearch(query) {
            fetch(`{{ route('admin.search.products') }}?query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    mobileSearchLoading.classList.remove('show');
                    
                    if (data.length === 0) {
                        mobileResultContent.innerHTML = `
                            <div class="no-result">
                                <i class="fas fa-search fa-2x mb-2 d-block"></i>
                                No products found for "<strong>${query}</strong>"
                            </div>
                        `;
                    } else {
                        let html = '';
                        data.forEach(product => {
                            html += `
                                <a href="{{ route('admin.products.index') }}?search=${encodeURIComponent(product.name)}" 
                                   class="result-item"
                                   onclick="toggleMobileSearch()">
                                    <img src="${product.image_url}" alt="${product.name}">
                                    <div class="info">
                                        <div class="name">${highlightText(product.name, query)}</div>
                                        <div class="price">$${parseFloat(product.price).toFixed(2)}</div>
                                    </div>
                                    <span class="view-link">View →</span>
                                </a>
                            `;
                        });
                        
                        html += `
                            <div class="search-more">
                                <a href="{{ route('admin.products.index') }}?search=${encodeURIComponent(query)}" onclick="toggleMobileSearch()">
                                    <i class="fas fa-arrow-right me-1"></i> View all results for "${query}"
                                </a>
                            </div>
                        `;
                        
                        mobileResultContent.innerHTML = html;
                    }
                })
                .catch(error => {
                    console.error('Search error:', error);
                    mobileSearchLoading.classList.remove('show');
                    mobileResultContent.innerHTML = `
                        <div class="no-result text-danger">
                            <i class="fas fa-exclamation-triangle fa-2x mb-2 d-block"></i>
                            Error searching products
                        </div>
                    `;
                });
        }
        
        function clearMobileSearch() {
            mobileSearchInput.value = '';
            clearMobileBtn.classList.remove('show');
            mobileResultContent.innerHTML = `
                <div class="no-result">
                    <i class="fas fa-search fa-3x mb-3 d-block"></i>
                    <p>Type at least 2 characters to start searching</p>
                </div>
            `;
            mobileSearchInput.focus();
        }
        
        // === DESKTOP SEARCH FUNCTIONS ===
        function handleSearch(query) {
            // Show/hide clear button
            if (query.length > 0) {
                clearBtn.classList.add('show');
            } else {
                clearBtn.classList.remove('show');
                searchResults.classList.remove('show');
                return;
            }
            
            // Only search if query has at least 2 characters
            if (query.length < 2) {
                searchResults.classList.remove('show');
                return;
            }
            
            // Show loading
            searchLoading.classList.add('show');
            searchResultContent.innerHTML = '';
            searchResults.classList.add('show');
            
            // Clear previous timeout
            if (searchTimeout) {
                clearTimeout(searchTimeout);
            }
            
            // Debounce search
            searchTimeout = setTimeout(() => {
                performSearch(query);
            }, 300);
        }
        
        function performSearch(query) {
            fetch(`{{ route('admin.search.products') }}?query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    searchLoading.classList.remove('show');
                    
                    if (data.length === 0) {
                        searchResultContent.innerHTML = `
                            <div class="no-result">
                                <i class="fas fa-search fa-2x mb-2 d-block"></i>
                                No products found for "<strong>${query}</strong>"
                            </div>
                        `;
                    } else {
                        let html = '';
                        data.forEach(product => {
                            html += `
                                <a href="{{ route('admin.products.index') }}?search=${encodeURIComponent(product.name)}" 
                                   class="result-item">
                                    <img src="${product.image_url}" alt="${product.name}">
                                    <div class="info">
                                        <div class="name">${highlightText(product.name, query)}</div>
                                        <div class="price">Rp. ${parseFloat(product.price).toFixed(2)}</div>
                                    </div>
                                    <span class="view-link">View →</span>
                                </a>
                            `;
                        });
                        
                        html += `
                            <div class="search-more">
                                <a href="{{ route('admin.products.index') }}?search=${encodeURIComponent(query)}">
                                    <i class="fas fa-arrow-right me-1"></i> View all results for "${query}"
                                </a>
                            </div>
                        `;
                        
                        searchResultContent.innerHTML = html;
                    }
                })
                .catch(error => {
                    console.error('Search error:', error);
                    searchLoading.classList.remove('show');
                    searchResultContent.innerHTML = `
                        <div class="no-result text-danger">
                            <i class="fas fa-exclamation-triangle fa-2x mb-2 d-block"></i>
                            Error searching products
                        </div>
                    `;
                });
        }
        
        function highlightText(text, query) {
            const words = query.split(' ');
            let result = text;
            words.forEach(word => {
                if (word.length > 2) {
                    const regex = new RegExp(word.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), 'gi');
                    result = result.replace(regex, match => `<span class="highlight">${match}</span>`);
                }
            });
            return result;
        }
        
        function clearSearch() {
            searchInput.value = '';
            clearBtn.classList.remove('show');
            searchResults.classList.remove('show');
            searchResultContent.innerHTML = '';
            searchInput.focus();
        }
        
        // Close search results when clicking outside (Desktop)
        document.addEventListener('click', function(e) {
            const container = document.querySelector('.search-container');
            if (container && !container.contains(e.target)) {
                searchResults.classList.remove('show');
            }
        });
        
        // Handle keyboard navigation (Desktop)
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                clearSearch();
            }
            if (e.key === 'Enter') {
                const query = this.value.trim();
                if (query.length > 0) {
                    window.location.href = `{{ route('admin.products.index') }}?search=${encodeURIComponent(query)}`;
                }
            }
        });
        
        // Handle keyboard navigation (Mobile)
        mobileSearchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                clearMobileSearch();
            }
            if (e.key === 'Enter') {
                const query = this.value.trim();
                if (query.length > 0) {
                    window.location.href = `{{ route('admin.products.index') }}?search=${encodeURIComponent(query)}`;
                    toggleMobileSearch();
                }
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>