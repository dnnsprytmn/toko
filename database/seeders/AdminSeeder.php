<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan tabel admins ada
        if (!Schema::hasTable('admins')) {
            $this->command->error('Table admins does not exist!');
            return;
        }

        // Hapus data yang sudah ada terlebih dahulu
        Admin::where('email', 'admin@example.com')->delete();
        Admin::where('email', 'staff@example.com')->delete();
        Admin::where('email', 'manager@example.com')->delete();
        
        // Buat admin super
        Admin::create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'super_admin',
        ]);

        // Buat admin staff
        Admin::create([
            'name' => 'Staff Admin',
            'email' => 'staff@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Buat admin manager
        Admin::create([
            'name' => 'Manager Admin',
            'email' => 'manager@example.com',
            'password' => Hash::make('password123'),
            'role' => 'manager',
        ]);
        
        $this->command->info('Admin users created successfully!');
        $this->command->info('Email: admin@example.com');
        $this->command->info('Password: password123');
        $this->command->info('Total Admins: ' . Admin::count());
    }
}

