<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Register - Toko Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
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
        }
        .btn-danger {
            padding: 12px 30px;
            transition: transform 0.3s;
        }
        .btn-danger:hover {
            transform: translateY(-2px);
        }
        .form-floating {
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card shadow-lg border-0 rounded-lg mt-5">
                    <div class="card-header">
                        <h3 class="text-center font-weight-light my-4">
                            <i class="fas fa-user-plus me-2"></i>Create Admin Account
                        </h3>
                    </div>
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Error!</strong> Please check your input.
                                <ul class="mb-0 mt-2">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
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

                        <form method="POST" action="{{ route('admin.register.post') }}">
                            @csrf
                            <div class="form-floating mb-3">
                                <input class="form-control @error('name') is-invalid @enderror" 
                                       id="inputName" 
                                       type="text" 
                                       name="name" 
                                       placeholder="John Doe" 
                                       required 
                                       value="{{ old('name') }}" />
                                <label for="inputName">
                                    <i class="fas fa-user me-2"></i>Full Name
                                </label>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-floating mb-3">
                                <input class="form-control @error('email') is-invalid @enderror" 
                                       id="inputEmail" 
                                       type="email" 
                                       name="email" 
                                       placeholder="name@example.com" 
                                       required 
                                       value="{{ old('email') }}" />
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
                            
                            <div class="form-floating mb-3">
                                <input class="form-control" 
                                       id="inputPasswordConfirm" 
                                       type="password" 
                                       name="password_confirmation" 
                                       placeholder="Confirm Password" 
                                       required />
                                <label for="inputPasswordConfirm">
                                    <i class="fas fa-check-circle me-2"></i>Confirm Password
                                </label>
                            </div>
                            
                            <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                <a class="btn btn-danger" href="{{ route('admin.login') }}">
                                    <i class="fas fa-arrow-left me-2"></i>Back to Login
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-user-plus me-2"></i>Register
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center py-3">
                        <div class="small">
                            <a href="{{ route('admin.login') }}">
                                Already have an account? Sign in!
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>