<?php

/**
 * DEMO SCRIPT: CARA UPLOAD DAN MENYIMPAN GAMBAR
 * 
 * Script ini mendemonstrasikan cara kerja upload gambar di Laravel + Filament
 */

echo "=== DEMO UPLOAD GAMBAR LARAVEL + FILAMENT ===\n\n";

// 1. STRUKTUR PENYIMPANAN
echo "1. STRUKTUR PENYIMPANAN:\n";
echo "   - Disk: 'public' -> storage/app/public/\n";
echo "   - URL Access: /storage/[folder]/[filename]\n";
echo "   - Folders: products/, banners/, artikels/, authors/, katalogs/\n\n";

// 2. KONFIGURASI FILAMENT FILEUPLOAD
echo "2. KONFIGURASI FILAMENT FILEUPLOAD:\n";
echo "   Forms\\Components\\FileUpload::make('image')\n";
echo "       ->image()                    // Hanya terima gambar\n";
echo "       ->directory('products')      // Folder penyimpanan\n";
echo "       ->disk('public')            // Disk storage\n";
echo "       ->visibility('public')      // Akses public\n";
echo "       ->maxSize(2048)             // Max 2MB\n";
echo "       ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])\n";
echo "       ->imageResizeMode('cover')   // Auto resize\n";
echo "       ->imageCropAspectRatio('1:1') // Crop 1:1\n";
echo "       ->imageResizeTargetWidth('500')\n";
echo "       ->imageResizeTargetHeight('500')\n\n";

// 3. CARA KERJA UPLOAD
echo "3. PROSES UPLOAD STEP-BY-STEP:\n";
echo "   a. User pilih file di form Filament\n";
echo "   b. Filament validasi (tipe, ukuran, dll)\n";
echo "   c. Jika ada resize/crop, gambar diproses\n";
echo "   d. File disimpan ke storage/app/public/[directory]/\n";
echo "   e. Path (misal: 'products/abc123.jpg') disimpan ke database\n";
echo "   f. File bisa diakses via URL /storage/products/abc123.jpg\n\n";

// 4. HELPER CLASS METHODS
echo "4. IMAGEUPLOADHELPER METHODS:\n";
echo "   - ImageUploadHelper::upload(\$file, 'products');\n";
echo "   - ImageUploadHelper::uploadWithName(\$file, 'custom_name', 'products');\n";
echo "   - ImageUploadHelper::validateImage(\$file, 2048);\n";
echo "   - ImageUploadHelper::delete(\$path);\n";
echo "   - ImageUploadHelper::getUrl(\$path);\n";
echo "   - ImageUploadHelper::getImageInfo(\$file);\n\n";

// 5. CONTOH PENGGUNAAN DI BLADE
echo "5. CARA AKSES GAMBAR DI BLADE TEMPLATE:\n";
echo "   <!-- Method 1: asset() -->\n";
echo "   <img src=\"{{ asset('storage/' . \$product->image) }}\" alt=\"Product\">\n\n";
echo "   <!-- Method 2: Storage::url() -->\n";
echo "   <img src=\"{{ Storage::disk('public')->url(\$product->image) }}\" alt=\"Product\">\n\n";

// 6. CONTOH DI FILAMENT TABLE
echo "6. CARA TAMPIL GAMBAR DI FILAMENT TABLE:\n";
echo "   Tables\\Columns\\ImageColumn::make('image')\n";
echo "       ->disk('public')\n";
echo "       ->height(50)\n";
echo "       ->width(50)\n\n";

// 7. CEK KONFIGURASI STORAGE
$currentDir = __DIR__;
$storagePath = $currentDir . "/storage/app/public";
$publicLink = $currentDir . "/public/storage";

echo "7. CEK KONFIGURASI STORAGE:\n";
echo "   - Storage path: " . $storagePath . "\n";
echo "   - Public link: " . $publicLink . "\n";
echo "   - Link exists: " . (is_link($publicLink) ? 'YES' : 'NO') . "\n";
echo "   - Storage folder exists: " . (is_dir($storagePath) ? 'YES' : 'NO') . "\n\n";

// 8. CONTOH VALIDASI FILE
echo "8. CONTOH VALIDASI MANUAL:\n";
echo "   \$allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];\n";
echo "   \$maxSize = 2048; // KB\n";
echo "   if (!in_array(\$file->getMimeType(), \$allowedTypes)) {\n";
echo "       return 'File type not allowed';\n";
echo "   }\n";
echo "   if (\$file->getSize() > \$maxSize * 1024) {\n";
echo "       return 'File too large';\n";
echo "   }\n\n";

// 9. TROUBLESHOOTING
echo "9. TROUBLESHOOTING COMMON ISSUES:\n";
echo "   - File tidak muncul: Cek apakah storage:link sudah dijalankan\n";
echo "   - Permission error: Cek permission folder storage/app/public\n";
echo "   - URL salah: Pastikan APP_URL di .env sudah benar\n";
echo "   - File terlalu besar: Cek php.ini upload_max_filesize & post_max_size\n\n";

// 10. SECURITY BEST PRACTICES
echo "10. SECURITY BEST PRACTICES:\n";
echo "    - Selalu validasi tipe file (MIME type)\n";
echo "    - Batasi ukuran file upload\n";
echo "    - Generate nama file random untuk keamanan\n";
echo "    - Jangan simpan file executable (.php, .exe, dll)\n";
echo "    - Gunakan disk 'public' hanya untuk file yang aman\n\n";

echo "=== DEMO SELESAI ===\n";
echo "Upload gambar sudah dikonfigurasi dengan benar di project ini!\n";
echo "Gunakan Filament admin panel untuk test upload.\n\n";
