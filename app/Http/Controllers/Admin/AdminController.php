<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // Menampilkan daftar admin (semua role bisa melihat)
    public function index()
    {
        $admins = Admin::latest()->paginate(10);
        $totalAdmins = Admin::count();
        
        return view('admin.admins.index', compact('admins', 'totalAdmins'));
    }

    // Menampilkan form tambah admin - Hanya untuk admin/super_admin/manager
    public function create()
    {
        // Cek jika staff mencoba akses
        if (Auth::guard('admin')->user()->role == 'staff') {
            return redirect()->route('admin.admins.index')
                ->with('error', 'You do not have permission to create admin accounts');
        }
        
        return view('admin.admins.create');
    }

    // Menyimpan admin baru - Hanya untuk admin/super_admin/manager
    public function store(Request $request)
    {
        // Cek jika staff mencoba akses
        if (Auth::guard('admin')->user()->role == 'staff') {
            return redirect()->route('admin.admins.index')
                ->with('error', 'You do not have permission to create admin accounts');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:super_admin,admin,manager,staff',
        ]);

        Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.admins.index')
            ->with('success', 'Admin created successfully!');
    }

    // Menampilkan form edit admin - Hanya untuk admin/super_admin/manager
    public function edit(Admin $admin)
    {
        $user = Auth::guard('admin')->user();
        
        // Staff tidak boleh edit admin
        if ($user->role == 'staff') {
            return redirect()->route('admin.admins.index')
                ->with('error', 'You do not have permission to edit admin accounts');
        }
        
        // Cek apakah admin yang login mencoba edit dirinya sendiri
        if ($admin->id == $user->id) {
            return redirect()->route('admin.admins.index')
                ->with('error', 'You cannot edit your own account here. Use profile settings.');
        }

        return view('admin.admins.edit', compact('admin'));
    }

    // Update admin - Hanya untuk admin/super_admin/manager
    public function update(Request $request, Admin $admin)
    {
        $user = Auth::guard('admin')->user();
        
        // Staff tidak boleh update admin
        if ($user->role == 'staff') {
            return redirect()->route('admin.admins.index')
                ->with('error', 'You do not have permission to update admin accounts');
        }
        
        // Cek apakah admin yang login mencoba update dirinya sendiri
        if ($admin->id == $user->id) {
            return redirect()->route('admin.admins.index')
                ->with('error', 'You cannot edit your own account here. Use profile settings.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $admin->id,
            'role' => 'required|in:super_admin,admin,manager,staff',
        ]);

        $data = $request->only(['name', 'email', 'role']);

        // Update password jika diisi
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'min:6|confirmed',
            ]);
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);

        return redirect()->route('admin.admins.index')
            ->with('success', 'Admin updated successfully!');
    }

    // Hapus admin - Hanya untuk admin/super_admin/manager
    public function destroy(Admin $admin)
    {
        $user = Auth::guard('admin')->user();
        
        // Staff tidak boleh hapus admin
        if ($user->role == 'staff') {
            return redirect()->route('admin.admins.index')
                ->with('error', 'You do not have permission to delete admin accounts');
        }
        
        // Cek apakah admin yang login mencoba menghapus dirinya sendiri
        if ($admin->id == $user->id) {
            return redirect()->route('admin.admins.index')
                ->with('error', 'You cannot delete your own account!');
        }

        // Cek apakah admin terakhir
        if (Admin::count() <= 1) {
            return redirect()->route('admin.admins.index')
                ->with('error', 'Cannot delete the last admin account!');
        }

        $admin->delete();

        return redirect()->route('admin.admins.index')
            ->with('success', 'Admin deleted successfully!');
    }
}