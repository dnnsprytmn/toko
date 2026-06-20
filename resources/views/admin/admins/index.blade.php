@extends('layouts.admin')

@section('title', 'Admin Management')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Admin Management</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Admins</li>
    </ol>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
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
    
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-users me-1"></i>
                    Admins List
                    <span class="badge bg-primary ms-2">{{ $totalAdmins }} Total</span>
                </div>
                <!-- Hanya tampilkan tombol Add New Admin jika bukan staff -->
                @if(Auth::guard('admin')->user()->role != 'staff')
                <a href="{{ route('admin.admins.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i> Add New Admin
                </a>
                @endif
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th width="50">#</th>
                            <th width="60">Avatar</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Created At</th>
                            <th width="120">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($admins as $admin)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div class="admin-avatar {{ $admin->id == Auth::guard('admin')->id() ? 'self' : '' }}">
                                    {{ strtoupper(substr($admin->name, 0, 2)) }}
                                </div>
                            </td>
                            <td>
                                {{ $admin->name }}
                                @if($admin->id == Auth::guard('admin')->id())
                                    <span class="badge bg-success ms-1">You</span>
                                @endif
                            </td>
                            <td>{{ $admin->email }}</td>
                            <td>
                                <span class="role-badge role-{{ $admin->role }}">
                                    {{ ucfirst(str_replace('_', ' ', $admin->role)) }}
                                </span>
                            </td>
                            <td>{{ $admin->created_at->locale('id')->isoFormat('D MMM YYYY HH:mm') }}</td>
                            <td>
                                @php
                                    $currentUser = Auth::guard('admin')->user();
                                    $isStaff = $currentUser->role == 'staff';
                                    $isSelf = $admin->id == $currentUser->id;
                                @endphp
                                
                                <div class=" btn-sm">
                                    @if($isStaff)
                                        <!-- Staff hanya bisa melihat, tidak bisa edit/hapus -->
                                        <button class="btn btn-secondary" disabled title="You are staff, cannot edit">
                                            <i class="fas fa-lock"></i>
                                        </button>
                                    @elseif($isSelf)
                                        <!-- Tidak bisa edit diri sendiri -->
                                        <button class="btn btn-secondary" disabled title="Cannot edit yourself">
                                            <i class="fas fa-lock"></i>
                                        </button>
                                    @else
                                        <!-- Admin/Manager/Super Admin bisa edit dan hapus -->
                                        <a href="{{ route('admin.admins.edit', $admin) }}" 
                                           class="btn btn-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.admins.destroy', $admin) }}" 
                                              method="POST" 
                                              style="display: inline-block;"
                                              onsubmit="return confirm('Are you sure you want to delete this admin?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-users fa-3x text-muted mb-3 d-block"></i>
                                <h5>No admins found</h5>
                                <p class="text-muted">Start by adding your first admin</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($admins->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        Showing {{ $admins->firstItem() ?? 0 }} to {{ $admins->lastItem() ?? 0 }} 
                        of {{ $admins->total() }} results
                    </div>
                    <div>
                        {{ $admins->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection