<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanUnusedImages extends Command
{
    protected $signature = 'images:clean';
    protected $description = 'Delete unused product images from storage';

    public function handle()
    {
        $this->info('🔍 Scanning for unused images...');

        // ===== GET ALL PRODUCT IMAGES FROM DATABASE =====
        $productImages = Product::whereNotNull('image_url')
            ->where('image_url', 'not like', '%dummyimage.com%')
            ->where('image_url', 'not like', '%http%')
            ->pluck('image_url')
            ->toArray();

        $this->info('📊 Found ' . count($productImages) . ' images in database');

        // ===== GET ALL FILES IN PRODUCTS DIRECTORY =====
        $files = Storage::disk('public')->files('products');
        
        $this->info('📁 Found ' . count($files) . ' files in storage/products');

        $deleted = 0;
        $totalSize = 0;

        foreach ($files as $file) {
            // Jika file tidak ada di database, hapus
            if (!in_array($file, $productImages)) {
                // Dapatkan ukuran file
                $size = Storage::disk('public')->size($file);
                $totalSize += $size;
                
                // Hapus file
                Storage::disk('public')->delete($file);
                $deleted++;
                $this->line('🗑️  Deleted: ' . $file . ' (' . $this->formatSize($size) . ')');
            }
        }

        $this->info('✅ Cleaning completed!');
        $this->info('🗑️  ' . $deleted . ' files deleted');
        $this->info('💾 ' . $this->formatSize($totalSize) . ' space freed');
    }

    private function formatSize($bytes)
    {
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }
}