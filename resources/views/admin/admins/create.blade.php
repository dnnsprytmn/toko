@extends('layouts.admin')

@section('title', 'Add New Admin')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Add New Admin</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.admins.index') }}">Admins</a></li>
        <li class="breadcrumb-item active">Add New</li>
    </ol>
    
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-user-plus me-1"></i>
            Admin Information
        </div>
        <div class="card-body">
            <form action="{{ route('admin.admins.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name *</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="{{ old('name') }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address *</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="{{ old('email') }}" required>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="password" class="form-label">Password *</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <small class="text-muted">Minimum 6 characters</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password *</label>
                            <input type="password" class="form-control" id="password_confirmation" 
                                   name="password_confirmation" required>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="role" class="form-label">Role *</label>
                    <select class="form-select" id="role" name="role" required>
                        <option value="">Select Role</option>
                        <option value="super_admin" {{ old('role') == 'super_admin' ? 'selected' : '' }}>
                            Super Admin
                        </option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>
                            Admin
                        </option>
                        <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>
                            Manager
                        </option>
                        <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>
                            Staff
                        </option>
                    </select>
                    <small class="text-muted">
                        <strong>Super Admin:</strong> Full access<br>
                        <strong>Admin:</strong> Standard admin access<br>
                        <strong>Manager:</strong> Management access<br>
                        <strong>Staff:</strong> Limited access
                    </small>
                </div>
                
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save Admin
                    </button>
                    <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection