<?php

namespace App\Traits;

use App\Services\CloudinaryService;
use App\Services\ExternalImageUploadService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

trait HandlesCloudinaryUploads
{
    /**
     * Upload file using External Service (Android-compatible) with Cloudinary fallback
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
                // First, try to upload to external service (Android-compatible)
                $externalService = app(ExternalImageUploadService::class);
                
                try {
                    $uploadedFile = new UploadedFile(
                        $fullPath,
                        basename($fullPath),
                        mime_content_type($fullPath),
                        null,
                        true
                    );
                    
                    $externalUrl = $externalService->uploadImage($uploadedFile);
                    
                    if ($externalUrl) {
                        // Success with external service - use this URL
                        $data[$fileField] = $externalUrl;
                        
                        // Clean up cloudinary fields since we're using external service
                        $data[$cloudinaryIdField] = null;
                        $data[$metaField] = [
                            'service' => 'external',
                            'url' => $externalUrl,
                            'uploaded_at' => now()->toISOString()
                        ];
                        
                        // Delete old Cloudinary image if exists
                        if (!empty($oldCloudinaryId)) {
                            $cloudinaryService = app(CloudinaryService::class);
                            $cloudinaryService->deleteFile($oldCloudinaryId);
                        }
                        
                        // Delete local file after successful upload
                        if (file_exists($fullPath)) {
                            unlink($fullPath);
                        }
                        
                        return $data;
                    }
                } catch (\Exception $e) {
                    // Log error but continue to Cloudinary fallback
                    Log::warning('External image upload failed, falling back to Cloudinary', [
                        'error' => $e->getMessage(),
                        'file' => $filePath
                    ]);
                }
                
                // Fallback to Cloudinary if external service fails
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
                    
                    // Store Cloudinary URL in the original field for hybrid compatibility
                    $data[$fileField] = $result['secure_url'];
                    
                    // Delete local file after successful upload
                    if (file_exists($fullPath)) {
                        unlink($fullPath);
                    }
                }
            }
        }

        return $data;
    }

    /**
     * Handle avatar upload using external service (Android-compatible)
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
     * Handle product image upload using external service (Android-compatible)
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
}
