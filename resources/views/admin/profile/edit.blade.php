@extends('layouts.admin')

@section('title', 'Edit Profile')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Edit Profile</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Edit Profile</li>
    </ol>

    <div class="profile-card">
        <div class="card">
            <div class="card-body">
                <!-- Avatar -->
                <div class="text-center">
                    <div class="profile-avatar">
                        {{ strtoupper(substr($admin->name, 0, 2)) }}
                    </div>
                    <h4 class="mb-1">{{ $admin->name }}</h4>
                    <p class="text-muted small">
                        <span class="badge bg-{{ $admin->role == 'super_admin' ? 'danger' : ($admin->role == 'admin' ? 'primary' : ($admin->role == 'manager' ? 'warning' : 'secondary')) }}">
                            {{ ucfirst(str_replace('_', ' ', $admin->role)) }}
                        </span>
                    </p>
                </div>

                <hr>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('admin.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Personal Information -->
                    <div class="form-section">
                        <h6><i class="fas fa-user me-2"></i>Personal Information</h6>
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name *</label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $admin->name) }}" 
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address *</label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $admin->email) }}" 
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- <div class="mb-3">
                            <label class="form-label">Role</label>
                            <input type="text" 
                                   class="form-control" 
                                   value="{{ ucfirst(str_replace('_', ' ', $admin->role)) }}" 
                                   disabled>
                            <small class="text-muted">Role cannot be changed here. Contact Super Admin.</small>
                        </div> --}}
                    </div>

                    <!-- Change Password -->
                    <div class="form-section">
                        <h6><i class="fas fa-key me-2"></i>Change Password</h6>
                        <p class="text-muted small">Leave password fields empty if you don't want to change it.</p>

                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" 
                                   class="form-control @error('current_password') is-invalid @enderror" 
                                   id="current_password" 
                                   name="current_password" 
                                   placeholder="Enter current password">
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="new_password" class="form-label">New Password</label>
                            <input type="password" 
                                   class="form-control @error('new_password') is-invalid @enderror" 
                                   id="new_password" 
                                   name="new_password" 
                                   placeholder="Enter new password (min 6 characters)">
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                            <input type="password" 
                                   class="form-control" 
                                   id="new_password_confirmation" 
                                   name="new_password_confirmation" 
                                   placeholder="Confirm new password">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection