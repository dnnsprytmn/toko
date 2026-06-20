@extends('layouts.admin')

@section('title', 'Edit Admin')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Edit Admin</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.admins.index') }}">Admins</a></li>
        <li class="breadcrumb-item active">Edit: {{ $admin->name }}</li>
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
            <i class="fas fa-user-edit me-1"></i>
            Edit Admin: {{ $admin->name }}
        </div>
        <div class="card-body">
            <form action="{{ route('admin.admins.update', $admin) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name *</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="{{ old('name', $admin->name) }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address *</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="{{ old('email', $admin->email) }}" required>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="password" class="form-label">New Password (Optional)</label>
                            <input type="password" class="form-control" id="password" name="password">
                            <small class="text-muted">Leave empty to keep current password</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="password_confirmation" 
                                   name="password_confirmation">
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="role" class="form-label">Role *</label>
                    <select class="form-select" id="role" name="role" required>
                        <option value="super_admin" {{ old('role', $admin->role) == 'super_admin' ? 'selected' : '' }}>
                            Super Admin
                        </option>
                        <option value="admin" {{ old('role', $admin->role) == 'admin' ? 'selected' : '' }}>
                            Admin
                        </option>
                        <option value="manager" {{ old('role', $admin->role) == 'manager' ? 'selected' : '' }}>
                            Manager
                        </option>
                        <option value="staff" {{ old('role', $admin->role) == 'staff' ? 'selected' : '' }}>
                            Staff
                        </option>
                    </select>
                </div>
                
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Update Admin
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