<?php

namespace App\Traits;

use App\Services\CloudinaryService;
use Illuminate\Http\UploadedFile;

trait HandlesCloudinaryUploads
{
    /**
     * Upload file to Cloudinary and return updated data
     *
     * @param array $data
     * @param string $fileField
     * @param string $cloudinaryIdField
     * @param string $metaField
     * @param string $folder
     * @param int|null $width
     * @param int|null $height
     * @param string|null $oldCloudinaryId
     * @return array
     */
    protected function handleCloudinaryUpload(
        array $data,
        string $fileField,
        string $cloudinaryIdField,
        string $metaField,
        string $folder,
        ?int $width = null,
        ?int $height = null,
        ?string $oldCloudinaryId = null
    ): array {
        if (!empty($data[$fileField])) {
            $filePath = $data[$fileField];
            $fullPath = storage_path('app/public/' . $filePath);
            
            if (file_exists($fullPath)) {
                $cloudinaryService = app(CloudinaryService::class);
                $uploadedFile = new UploadedFile(
                    $fullPath,
                    basename($fullPath),
                    mime_content_type($fullPath),
                    null,
                    true
                );
                
                $result = $width && $height 
                    ? $cloudinaryService->uploadImage($uploadedFile, $folder, $width, $height)
                    : $cloudinaryService->uploadFile($uploadedFile, $folder);
                
                if ($result) {
                    // Delete old image from Cloudinary if exists
                    if (!empty($oldCloudinaryId)) {
                        $cloudinaryService->deleteFile($oldCloudinaryId);
                    }
                    
                    $data[$cloudinaryIdField] = $result['public_id'];
                    $data[$metaField] = $result;
                    
                    // Delete local file after successful upload
                    if (file_exists($fullPath)) {
                        unlink($fullPath);
                    }
                    
                    // Clear file path since we're using Cloudinary
                    $data[$fileField] = null;
                }
            }
        }

        return $data;
    }

    /**
     * Handle avatar upload specifically
     */
    protected function handleAvatarUpload(array $data, string $userId = null, ?string $oldCloudinaryId = null): array
    {
        return $this->handleCloudinaryUpload(
            $data,
            'avatar',
            'cloudinary_id',
            'cloudinary_meta',
            'ekraf/avatars',
            200,
            200,
            $oldCloudinaryId
        );
    }

    /**
     * Handle thumbnail upload specifically
     */
    protected function handleThumbnailUpload(array $data, ?string $oldCloudinaryId = null): array
    {
        return $this->handleCloudinaryUpload(
            $data,
            'thumbnail',
            'thumbnail_cloudinary_id',
            'thumbnail_meta',
            'ekraf/articles',
            800,
            450,
            $oldCloudinaryId
        );
    }

    /**
     * Handle banner image upload specifically
     */
    protected function handleBannerUpload(array $data, ?string $oldCloudinaryId = null): array
    {
        return $this->handleCloudinaryUpload(
            $data,
            'image',
            'image_cloudinary_id',
            'image_meta',
            'ekraf/banners',
            1200,
            675,
            $oldCloudinaryId
        );
    }

    /**
     * Handle product image upload specifically
     */
    protected function handleProductImageUpload(array $data, ?string $oldCloudinaryId = null): array
    {
        return $this->handleCloudinaryUpload(
            $data,
            'image',
            'image_cloudinary_id',
            'image_meta',
            'ekraf/products',
            500,
            500,
            $oldCloudinaryId
        );
    }

    /**
     * Handle catalog image upload specifically
     */
    protected function handleCatalogImageUpload(array $data, ?string $oldCloudinaryId = null): array
    {
        return $this->handleCloudinaryUpload(
            $data,
            'image',
            'image_cloudinary_id',
            'image_meta',
            'ekraf/catalogs',
            800,
            600,
            $oldCloudinaryId
        );
    }
}
