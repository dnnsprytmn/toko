<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    // Show login form
    public function showLoginForm()
    {
        // Jika sudah login, redirect ke dashboard
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    // Process login
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Cek kredensial
        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember') ? true : false;
        
        // Attempt login dengan guard admin
        if (Auth::guard('admin')->attempt($credentials, $remember)) {
            // Regenerate session untuk keamanan
            $request->session()->regenerate();
            
            // Redirect ke dashboard
            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Welcome back, ' . Auth::guard('admin')->user()->name . '!');
        }

        // Jika login gagal
        return back()
            ->withInput($request->only('email', 'remember'))
            ->with('error', 'Invalid email or password!');
    }

    // Logout
    public function logout(Request $request)
    {
        // Logout dari guard admin
        Auth::guard('admin')->logout();
        
        // Invalidate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('admin.login')
            ->with('success', 'You have been logged out successfully!');
    }

    // Show register form
    public function showRegisterForm()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.register');
    }

    // Process register
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
        ]);

        Auth::guard('admin')->login($admin);
        $request->session()->regenerate();

        return redirect()->route('admin.dashboard')
            ->with('success', 'Account created successfully!');
    }
}