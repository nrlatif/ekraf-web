# CARA UPLOAD DAN MENYIMPAN GAMBAR - Laravel + Filament

## 📋 OVERVIEW

Project Laravel + Filament ini sudah dikonfigurasi dengan lengkap untuk upload dan menyimpan gambar. Berikut adalah penjelasan lengkap cara kerjanya:

## 🗂️ STRUKTUR PENYIMPANAN

```
storage/
  app/
    public/              # Storage disk 'public'
      products/          # Gambar produk
      banners/           # Gambar banner  
      artikels/          # Gambar artikel
      authors/           # Foto author
      katalogs/          # Gambar katalog
      users/             # Profile picture users
      business-categories/ # Icon kategori bisnis
      sub-sectors/       # Gambar sub sektor
      article-categories/ # Icon kategori artikel
      test-uploads/      # Testing upload (REMOVED)

public/
  storage/               # Symbolic link ke storage/app/public
```

**URL Access Pattern**: `/storage/[folder]/[filename]`
- Contoh: `/storage/products/abc123.jpg`

## ⚙️ KONFIGURASI

### 1. Disk Storage (`config/filesystems.php`)
```php
'public' => [
    'driver' => 'local',
    'root' => storage_path('app/public'),
    'url' => env('APP_URL').'/storage',
    'visibility' => 'public',
],
```

### 2. Symbolic Link
```bash
php artisan storage:link
```
✅ **Status**: Sudah dibuat dan terhubung

## 🚀 CARA UPLOAD GAMBAR

### 1. Upload Melalui Filament Admin Panel

**Contoh di ProductResource:**
```php
Forms\Components\FileUpload::make('image')
    ->label('Product Image')
    ->image()                                    // Hanya terima file gambar
    ->directory('products')                      // Simpan di folder 'products'
    ->disk('public')                            // Gunakan disk 'public'
    ->visibility('public')                      // File bisa diakses public
    ->maxSize(2048)                            // Max 2MB
    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
    ->imageResizeMode('cover')                 // Auto resize
    ->imageCropAspectRatio('1:1')              // Crop ratio 1:1
    ->imageResizeTargetWidth('500')            // Resize ke 500px
    ->imageResizeTargetHeight('500')
    ->required()
```

### 2. Upload Manual Menggunakan Helper

**ImageUploadHelper** (`app/Helpers/ImageUploadHelper.php`):

```php
use App\Helpers\ImageUploadHelper;

// Upload dengan nama file random
$path = ImageUploadHelper::upload($file, 'products', 'public');

// Upload dengan nama file custom
$path = ImageUploadHelper::uploadWithName($file, 'product_123', 'products', 'public');

// Validasi gambar
$errors = ImageUploadHelper::validateImage($file, 2048); // Max 2MB

// Hapus gambar
ImageUploadHelper::delete($path, 'public');

// Get URL gambar
$url = ImageUploadHelper::getUrl($path, 'public');

// Get info gambar
$info = ImageUploadHelper::getImageInfo($file);
```

### 3. Upload Melalui Custom Controller

**Contoh di Controller:**
```php
public function upload(Request $request)
{
    $request->validate([
        'image' => 'required|image|mimes:jpeg,png,webp,gif|max:2048'
    ]);

    $file = $request->file('image');
    $path = ImageUploadHelper::upload($file, 'uploads', 'public');
    
    return back()->with('success', 'Upload berhasil!');
}
```

## 🖼️ CARA MENAMPILKAN GAMBAR

### 1. Di Blade Template
```php
<!-- Method 1: asset() helper -->
<img src="{{ asset('storage/' . $product->image) }}" alt="Product">

<!-- Method 2: Storage::url() -->
<img src="{{ Storage::disk('public')->url($product->image) }}" alt="Product">
```

### 2. Di Filament Table
```php
Tables\Columns\ImageColumn::make('image')
    ->disk('public')
    ->height(50)
    ->width(50)
```

### 3. Di Filament View
```php
Forms\Components\ViewField::make('image')
    ->view('filament.forms.components.image-preview')
```

## 📤 PROSES UPLOAD STEP-BY-STEP

1. **File Selection**: User pilih file di form
2. **Client Validation**: JavaScript validasi di frontend (optional)
3. **Server Validation**: Laravel validasi tipe file, ukuran, dll
4. **Image Processing**: Filament resize/crop gambar (jika ada)
5. **File Storage**: File disimpan ke `storage/app/public/[directory]/`
6. **Database Save**: Path file disimpan ke database (misal: `products/abc123.jpg`)
7. **Public Access**: File bisa diakses via URL `/storage/products/abc123.jpg`

## 🔒 VALIDASI & KEAMANAN

### Validasi File
```php
// Laravel Validation Rules
'image' => 'required|image|mimes:jpeg,png,webp,gif|max:2048'

// Custom Validation dengan Helper
$errors = ImageUploadHelper::validateImage($file, 2048);
if (!empty($errors)) {
    return back()->withErrors($errors);
}
```

### Security Best Practices
- ✅ Validasi MIME type
- ✅ Batasi ukuran file (max 2MB)
- ✅ Generate nama file random
- ✅ Hanya simpan file gambar
- ✅ Gunakan disk 'public' untuk file aman
- ✅ CSRF protection pada form

## 🧪 TESTING UPLOAD

### 1. Test Page Manual
**URL**: `http://127.0.0.1:8000/test-upload`

Features:
- Upload form dengan CSRF protection
- Preview gambar sebelum upload
- Pilihan folder penyimpanan
- Validasi client-side & server-side
- Success/error messaging

### 2. Test Melalui Filament Admin
**URL**: `http://127.0.0.1:8000/admin`

Fitur upload tersedia di:
- Products Management (image)
- Articles Management (thumbnail)
- Authors Management (avatar)
- Banners Management (image)
- Katalog Management (image)
- Business Categories Management (image/icon)
- Sub Sectors Management (image)
- Article Categories Management (icon)
- Users Management (profile picture)

## 📁 FILES YANG TERLIBAT

### Models
- `app/Models/Product.php` - Model produk dengan field image
- `app/Models/Artikel.php` - Model artikel dengan field image
- `app/Models/Author.php` - Model author dengan field photo
- `app/Models/Banner.php` - Model banner dengan field image
- `app/Models/Katalog.php` - Model katalog dengan field image

### Resources (Filament)
- `app/Filament/Resources/ProductResource.php`
- `app/Filament/Resources/ArtikelResource.php`
- `app/Filament/Resources/AuthorResource.php`
- `app/Filament/Resources/BannerResource.php`
- `app/Filament/Resources/KatalogResource.php`

### Helpers
- `app/Helpers/ImageUploadHelper.php` - Helper untuk upload gambar

### Controllers (Testing)
- `app/Http/Controllers/TestUploadController.php` - Controller untuk test upload

### Views (Testing)
- `resources/views/test-upload.blade.php` - View test upload

### Routes
- `routes/web.php` - Routes untuk test upload

## 🛠️ TROUBLESHOOTING

### Problem: File tidak muncul di browser
**Solution**: 
```bash
php artisan storage:link
```

### Problem: Permission denied
**Solution**: 
```bash
chmod -R 755 storage/
chmod -R 755 public/storage
```

### Problem: CSRF token mismatch  
**Solution**: Pastikan `@csrf` ada di form
```php
<form method="POST" enctype="multipart/form-data">
    @csrf
    <!-- form fields -->
</form>
```

### Problem: File terlalu besar
**Solution**: Check `php.ini`:
```ini
upload_max_filesize = 10M
post_max_size = 10M
max_execution_time = 300
```

### Problem: URL gambar salah
**Solution**: Check `.env`:
```env
APP_URL=http://127.0.0.1:8000
```

## ✅ STATUS IMPLEMENTASI

- ✅ **Storage Configuration**: Configured
- ✅ **Symbolic Link**: Created  
- ✅ **Helper Class**: Implemented
- ✅ **Filament FileUpload**: Configured in all resources
- ✅ **Image Processing**: Enabled (intervention/image)
- ✅ **Validation**: Implemented
- ✅ **Security**: Secured
- ✅ **Testing Interface**: Available
- ✅ **Error Handling**: Implemented
- ✅ **Documentation**: Complete

## 🎯 READY TO USE!

Upload gambar sudah siap digunakan. Akses:
- **Test Upload**: http://127.0.0.1:8000/test-upload
- **Filament Admin**: http://127.0.0.1:8000/admin

**Developer**: Sistem upload gambar sudah terintegrasi dengan baik ke seluruh model dan resource Filament. Siap untuk pengembangan lebih lanjut!
