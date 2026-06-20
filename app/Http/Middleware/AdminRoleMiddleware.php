<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        // Cek apakah admin sudah login
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login')->with('error', 'Please login to access admin panel');
        }

        $user = Auth::guard('admin')->user();
        
        // Jika role adalah staff, batasi akses
        if ($user->role == 'staff' && $role != 'staff') {
            return redirect()->route('admin.dashboard')
                ->with('error', 'You do not have permission to access this page');
        }

        // Jika role staff mencoba akses halaman admin management
        if ($user->role == 'staff' && $request->routeIs('admin.admins.*')) {
            // Staff hanya bisa melihat daftar admin, tidak bisa edit/hapus/tambah
            if ($request->routeIs('admin.admins.edit') || 
                $request->routeIs('admin.admins.update') || 
                $request->routeIs('admin.admins.destroy') || 
                $request->routeIs('admin.admins.create') || 
                $request->routeIs('admin.admins.store')) {
                return redirect()->route('admin.admins.index')
                    ->with('error', 'You do not have permission to manage admins');
            }
        }

        return $next($request);
    }
}