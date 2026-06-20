<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login - Toko Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px 30px;
            transition: transform 0.3s;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .btn-danger {
            padding: 12px 30px;
            transition: transform 0.3s;
        }
        .btn-danger:hover {
            transform: translateY(-2px);
        }
        .alert {
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header">
                                    <h3 class="text-center font-weight-light my-4">
                                        <i class="fas fa-lock me-2"></i>Admin Login
                                    </h3>
                                </div>
                                <div class="card-body">
                                    @if(session('error'))
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            {{ session('error') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                        </div>
                                    @endif
                                    
                                    @if(session('success'))
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            <i class="fas fa-check-circle me-2"></i>
                                            {{ session('success') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                        </div>
                                    @endif

                                    <form method="POST" action="{{ route('admin.login.post') }}">
                                        @csrf
                                        <div class="form-floating mb-3">
                                            <input class="form-control @error('email') is-invalid @enderror" 
                                                   id="inputEmail" 
                                                   type="email" 
                                                   name="email"
                                                   value="{{ old('email') }}"
                                                   placeholder="name@example.com" 
                                                   required autofocus />
                                            <label for="inputEmail">
                                                <i class="fas fa-envelope me-2"></i>Email address
                                            </label>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="form-floating mb-3">
                                            <input class="form-control @error('password') is-invalid @enderror" 
                                                   id="inputPassword" 
                                                   type="password" 
                                                   name="password"
                                                   placeholder="Password" 
                                                   required />
                                            <label for="inputPassword">
                                                <i class="fas fa-key me-2"></i>Password
                                            </label>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" 
                                                   id="inputRememberPassword" 
                                                   type="checkbox" 
                                                   name="remember"
                                                   value="1" />
                                            <label class="form-check-label" for="inputRememberPassword">
                                                Remember Password
                                            </label>
                                        </div>
                                        
                                        <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                            <a class="small" href="">Forgot Password?</a>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-sign-in-alt me-2"></i>Login
                                            </button>
                                        </div>
                                        
                                        <div class="mt-3 text-center">
                                            <a class="btn btn-danger" href="{{ route('home') }}">
                                                <i class="fas fa-arrow-left me-2"></i>Back to Website
                                            </a>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center py-3">
                                    <div class="small">
                                        <a href="{{ route('admin.register') }}">
                                            <i class="fas fa-user-plus me-2"></i>Need an account? Sign up!
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    {{-- <div id="layoutAuthentication_footer">
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
    </div> --}}
</body>
</html>