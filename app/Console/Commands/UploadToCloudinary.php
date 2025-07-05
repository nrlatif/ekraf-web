<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CloudinaryService;
use App\Models\Author;
use App\Models\User;
use App\Models\Artikel;
use App\Models\Banner;
use App\Models\Katalog;
use App\Models\Product;
use Illuminate\Http\UploadedFile;

class UploadToCloudinary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cloudinary:upload {--model=all : Which model to upload (all, authors, users, articles, banners, catalogs, products)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upload existing local files to Cloudinary';

    protected CloudinaryService $cloudinaryService;

    public function __construct(CloudinaryService $cloudinaryService)
    {
        parent::__construct();
        $this->cloudinaryService = $cloudinaryService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $model = $this->option('model');

        $this->info('Starting Cloudinary upload process...');

        switch ($model) {
            case 'authors':
                $this->uploadAuthors();
                break;
            case 'users':
                $this->uploadUsers();
                break;
            case 'articles':
                $this->uploadArticles();
                break;
            case 'banners':
                $this->uploadBanners();
                break;
            case 'catalogs':
                $this->uploadCatalogs();
                break;
            case 'products':
                $this->uploadProducts();
                break;
            case 'all':
            default:
                $this->uploadAuthors();
                $this->uploadUsers();
                $this->uploadArticles();
                $this->uploadBanners();
                $this->uploadCatalogs();
                $this->uploadProducts();
                break;
        }

        $this->info('Cloudinary upload process completed!');
    }

    private function uploadAuthors()
    {
        $this->info('Uploading author avatars...');
        $authors = Author::whereNull('cloudinary_id')
                        ->whereNotNull('avatar')
                        ->get();

        $bar = $this->output->createProgressBar($authors->count());
        $bar->start();

        foreach ($authors as $author) {
            $filePath = storage_path('app/public/' . $author->avatar);
            
            if (file_exists($filePath)) {
                $uploadedFile = new UploadedFile($filePath, basename($filePath), null, null, true);
                $result = $this->cloudinaryService->uploadAvatar($uploadedFile, 'author_' . $author->id);
                
                if ($result) {
                    $author->update([
                        'cloudinary_id' => $result['public_id'],
                        'cloudinary_meta' => $result
                    ]);
                    $this->newLine();
                    $this->info("Uploaded avatar for author: {$author->name}");
                }
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
    }

    private function uploadUsers()
    {
        $this->info('Uploading user profile images...');
        $users = User::whereNull('cloudinary_id')
                    ->whereNotNull('image')
                    ->get();

        $bar = $this->output->createProgressBar($users->count());
        $bar->start();

        foreach ($users as $user) {
            $filePath = storage_path('app/public/' . $user->image);
            
            if (file_exists($filePath)) {
                $uploadedFile = new UploadedFile($filePath, basename($filePath), null, null, true);
                $result = $this->cloudinaryService->uploadAvatar($uploadedFile, 'user_' . $user->id);
                
                if ($result) {
                    $user->update([
                        'cloudinary_id' => $result['public_id'],
                        'cloudinary_meta' => $result
                    ]);
                    $this->newLine();
                    $this->info("Uploaded image for user: {$user->name}");
                }
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
    }

    private function uploadArticles()
    {
        $this->info('Uploading article thumbnails...');
        $articles = Artikel::whereNull('thumbnail_cloudinary_id')
                          ->whereNotNull('thumbnail')
                          ->get();

        $bar = $this->output->createProgressBar($articles->count());
        $bar->start();

        foreach ($articles as $article) {
            $filePath = storage_path('app/public/' . $article->thumbnail);
            
            if (file_exists($filePath)) {
                $uploadedFile = new UploadedFile($filePath, basename($filePath), null, null, true);
                $result = $this->cloudinaryService->uploadArticleThumbnail($uploadedFile);
                
                if ($result) {
                    $article->update([
                        'thumbnail_cloudinary_id' => $result['public_id'],
                        'thumbnail_meta' => $result
                    ]);
                    $this->newLine();
                    $this->info("Uploaded thumbnail for article: {$article->title}");
                }
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
    }

    private function uploadBanners()
    {
        $this->info('Uploading banner images...');
        $banners = Banner::whereNull('image_cloudinary_id')
                        ->whereNotNull('image')
                        ->get();

        $bar = $this->output->createProgressBar($banners->count());
        $bar->start();

        foreach ($banners as $banner) {
            $filePath = storage_path('app/public/' . $banner->image);
            
            if (file_exists($filePath)) {
                $uploadedFile = new UploadedFile($filePath, basename($filePath), null, null, true);
                $result = $this->cloudinaryService->uploadBanner($uploadedFile);
                
                if ($result) {
                    $banner->update([
                        'image_cloudinary_id' => $result['public_id'],
                        'image_meta' => $result
                    ]);
                    $this->newLine();
                    $this->info("Uploaded image for banner: {$banner->title}");
                }
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
    }

    private function uploadCatalogs()
    {
        $this->info('Uploading catalog images...');
        $catalogs = Katalog::whereNull('image_cloudinary_id')
                          ->whereNotNull('image')
                          ->get();

        $bar = $this->output->createProgressBar($catalogs->count());
        $bar->start();

        foreach ($catalogs as $catalog) {
            $filePath = storage_path('app/public/' . $catalog->image);
            
            if (file_exists($filePath)) {
                $uploadedFile = new UploadedFile($filePath, basename($filePath), null, null, true);
                $result = $this->cloudinaryService->uploadCatalogImage($uploadedFile);
                
                if ($result) {
                    $catalog->update([
                        'image_cloudinary_id' => $result['public_id'],
                        'image_meta' => $result
                    ]);
                    $this->newLine();
                    $this->info("Uploaded image for catalog: {$catalog->title}");
                }
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
    }

    private function uploadProducts()
    {
        $this->info('Uploading product images...');
        $products = Product::whereNull('image_cloudinary_id')
                          ->whereNotNull('image')
                          ->get();

        $bar = $this->output->createProgressBar($products->count());
        $bar->start();

        foreach ($products as $product) {
            $filePath = storage_path('app/public/' . $product->image);
            
            if (file_exists($filePath)) {
                $uploadedFile = new UploadedFile($filePath, basename($filePath), null, null, true);
                $result = $this->cloudinaryService->uploadProductImage($uploadedFile);
                
                if ($result) {
                    $product->update([
                        'image_cloudinary_id' => $result['public_id'],
                        'image_meta' => $result
                    ]);
                    $this->newLine();
                    $this->info("Uploaded image for product: {$product->name}");
                }
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
    }
}
