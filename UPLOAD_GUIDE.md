# 📸 CARA UPLOAD DAN MENYIMPAN GAMBAR DI LARAVEL + FILAMENT

## 🏗️ Struktur Penyimpanan

### Storage Configuration
```php
// config/filesystems.php
'public' => [
    'driver' => 'local',
    'root' => storage_path('app/public'),
    'url' => env('APP_URL').'/storage',
    'visibility' => 'public',
]
```

### Directory Structure
```
storage/
  app/
    public/
      products/          # Gambar produk
      banners/           # Gambar banner  
      artikels/          # Gambar artikel
      authors/           # Foto author
      katalogs/          # Gambar katalog
      uploads/           # Folder untuk testing
        custom/          # Upload dengan nama custom
        multiple/        # Upload multiple files
```

### Symbolic Link
```bash
php artisan storage:link
```
File di `storage/app/public/` dapat diakses melalui `/storage/`

---

## 🛠️ Implementasi Upload di Filament Resource

### Basic FileUpload Configuration
```php
Forms\Components\FileUpload::make('image')
    ->label('Product Image')
    ->image()                                    // Hanya terima file gambar
    ->directory('products')                      // Folder penyimpanan
    ->disk('public')                            // Disk storage
    ->visibility('public')                      // File bisa diakses public
    ->maxSize(2048)                            // Max 2MB
    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
    ->imageResizeMode('cover')                 // Auto resize
    ->imageCropAspectRatio('1:1')              // Crop ratio 1:1
    ->imageResizeTargetWidth('500')            // Resize ke 500px
    ->imageResizeTargetHeight('500')
    ->required()
```

### Advanced Configuration
```php
Forms\Components\FileUpload::make('gallery')
    ->label('Gallery Images')
    ->image()
    ->multiple()                               // Multiple files
    ->directory('gallery')
    ->disk('public')
    ->visibility('public')
    ->maxFiles(5)                             // Max 5 files
    ->maxSize(2048)                          // Max 2MB per file
    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
    ->imagePreviewHeight('250')
    ->loadingIndicatorPosition('center')
    ->panelAspectRatio('2:1')
    ->panelLayout('integrated')
    ->removeUploadedFileButtonPosition('right')
    ->uploadButtonPosition('left')
    ->uploadProgressIndicatorPosition('left')
    ->imageResizeMode('cover')
    ->imageCropAspectRatio('16:9')
    ->imageResizeTargetWidth('1920')
    ->imageResizeTargetHeight('1080')
```

---

## 🚀 Helper Class untuk Upload Manual

### ImageUploadHelper Methods

#### 1. Upload Basic
```php
use App\Helpers\ImageUploadHelper;

// Upload dengan nama file random
$path = ImageUploadHelper::upload($file, 'products', 'public');
// Result: 'products/abc123random.jpg'
```

#### 2. Upload dengan Custom Name
```php
// Upload dengan nama file custom
$path = ImageUploadHelper::uploadWithName($file, 'product_123', 'products', 'public');
// Result: 'products/product_123.jpg'
```

#### 3. Validasi Gambar
```php
// Validasi file gambar (max 2MB)
$errors = ImageUploadHelper::validateImage($file, 2048);
if (!empty($errors)) {
    // Handle validation errors
    foreach ($errors as $error) {
        echo $error . "\n";
    }
}
```

#### 4. Hapus Gambar
```php
// Hapus gambar dari storage
$deleted = ImageUploadHelper::delete($path, 'public');
if ($deleted) {
    echo "Image deleted successfully";
}
```

#### 5. Get URL Gambar
```php
// Get URL untuk akses gambar
$url = ImageUploadHelper::getUrl($path, 'public');
// Result: '/storage/products/abc123.jpg'
```

#### 6. Get File Information
```php
// Get informasi detail file
$info = ImageUploadHelper::getImageInfo($file);
/*
Result:
[
    'width' => 1920,
    'height' => 1080,
    'mime_type' => 'image/jpeg',
    'size_bytes' => 245760,
    'size_kb' => 240.00,
    'extension' => 'jpg',
    'original_name' => 'photo.jpg'
]
*/
```

---

## 🎯 Implementasi di Controller

### Basic Upload
```php
public function uploadImage(Request $request)
{
    $request->validate([
        'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048'
    ]);

    try {
        $file = $request->file('image');
        
        // Method 1: Menggunakan Helper
        $path = ImageUploadHelper::upload($file, 'uploads', 'public');
        
        // Method 2: Laravel Storage langsung
        // $path = $file->store('uploads', 'public');
        
        return response()->json([
            'success' => true,
            'path' => $path,
            'url' => asset('storage/' . $path)
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Upload failed: ' . $e->getMessage()
        ], 500);
    }
}
```

### Multiple Upload
```php
public function uploadMultiple(Request $request)
{
    $request->validate([
        'images' => 'required|array|max:5',
        'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048'
    ]);

    $uploadedFiles = [];
    
    foreach ($request->file('images') as $file) {
        $path = ImageUploadHelper::upload($file, 'uploads/multiple', 'public');
        
        $uploadedFiles[] = [
            'path' => $path,
            'url' => asset('storage/' . $path),
            'size' => $file->getSize(),
            'original_name' => $file->getClientOriginalName()
        ];
    }
    
    return response()->json([
        'success' => true,
        'files' => $uploadedFiles
    ]);
}
```

---

## 🖼️ Cara Menampilkan Gambar

### Di Blade Template
```php
<!-- Method 1: Menggunakan asset() -->
<img src="{{ asset('storage/' . $product->image) }}" alt="Product Image">

<!-- Method 2: Menggunakan Storage::url() -->
<img src="{{ Storage::disk('public')->url($product->image) }}" alt="Product Image">

<!-- Method 3: Dengan conditional check -->
@if($product->image)
    <img src="{{ asset('storage/' . $product->image) }}" alt="Product Image" class="w-full h-48 object-cover">
@else
    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
        <span class="text-gray-500">No Image</span>
    </div>
@endif
```

### Di Filament Table
```php
Tables\Columns\ImageColumn::make('image')
    ->label('Image')
    ->disk('public')
    ->height(50)
    ->width(50)
    ->rounded()
```

### Di Filament InfoList
```php
Infolists\Components\ImageEntry::make('image')
    ->label('Product Image')
    ->disk('public')
    ->height(200)
    ->width(200)
```

---

## 🔒 Security Best Practices

### 1. Validasi File Type
```php
// Selalu validasi MIME type
$allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
if (!in_array($file->getMimeType(), $allowedTypes)) {
    throw new \Exception('File type not allowed');
}
```

### 2. Batasi Ukuran File
```php
// Max 2MB
$maxSize = 2048 * 1024; // bytes
if ($file->getSize() > $maxSize) {
    throw new \Exception('File too large');
}
```

### 3. Generate Nama File Random
```php
// Jangan gunakan nama file original untuk keamanan
$filename = Str::random(20) . '.' . $file->getClientOriginalExtension();
```

### 4. Validasi Dimensi Gambar
```php
$imageInfo = getimagesize($file->getPathname());
if ($imageInfo[0] > 4000 || $imageInfo[1] > 4000) {
    throw new \Exception('Image dimensions too large');
}
```

---

## 🧪 Testing Upload

### Test Page
Akses: `http://localhost:8000/upload-test`

### Features
- ✅ Single image upload
- ✅ Upload with custom filename
- ✅ Multiple image upload (max 5)
- ✅ Image list with delete function
- ✅ File information analyzer
- ✅ Real-time preview
- ✅ Error handling

---

## 🛠️ Troubleshooting

### 1. File tidak muncul
**Problem:** Gambar berhasil diupload tapi tidak bisa diakses
**Solution:** 
```bash
php artisan storage:link
```

### 2. Permission Error
**Problem:** Permission denied saat upload
**Solution:**
```bash
chmod -R 755 storage/app/public
```

### 3. File terlalu besar
**Problem:** File upload gagal karena terlalu besar
**Solution:** Edit `php.ini`:
```ini
upload_max_filesize = 10M
post_max_size = 10M
max_execution_time = 300
```

### 4. URL tidak benar
**Problem:** URL gambar mengarah ke path yang salah
**Solution:** Cek `APP_URL` di `.env`:
```env
APP_URL=http://localhost:8000
```

---

## 📊 Summary

✅ **Storage configured**: Disk `public` → `storage/app/public/`
✅ **Symbolic link created**: `/storage/` → `storage/app/public/`  
✅ **Helper class ready**: `ImageUploadHelper` dengan 6 methods
✅ **Filament integration**: FileUpload component dengan validasi
✅ **Controller examples**: Single, multiple, custom name upload
✅ **Security implemented**: File validation, size limits, MIME check
✅ **Testing page**: Functional upload test interface
✅ **Documentation**: Complete guide dan troubleshooting

**Upload gambar sudah siap digunakan!** 🎉
