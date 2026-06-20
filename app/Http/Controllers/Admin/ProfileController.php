<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the admin profile.
     */
    public function edit()
    {
        $admin = Auth::guard('admin')->user();
        return view('admin.profile.edit', compact('admin'));
    }

    /**
     * Update the admin profile.
     */
    public function update(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $admin->id,
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:6|confirmed',
            'new_password_confirmation' => 'nullable|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update name and email
        $admin->name = $request->name;
        $admin->email = $request->email;

        // Update password if provided
        if ($request->filled('new_password')) {
            // Verify current password
            if (!Hash::check($request->current_password, $admin->password)) {
                return redirect()->back()
                    ->withErrors(['current_password' => 'Current password is incorrect'])
                    ->withInput();
            }

            $admin->password = Hash::make($request->new_password);
        }

        $admin->save();

        return redirect()->route('admin.profile.edit')
            ->with('success', 'Profile updated successfully!');
    }
}