<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ImageUploadHelper;
use Illuminate\Support\Facades\Storage;

class TestUploadController extends Controller
{
    /**
     * Show upload form
     */
    public function index()
    {
        return view('test-upload');
    }

    /**
     * Handle upload
     */
    public function upload(Request $request)
    {
        // Validasi input
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,webp,gif|max:2048', // 2MB max
            'folder' => 'required|string'
        ]);

        try {
            $file = $request->file('image');
            $folder = $request->input('folder', 'test-uploads');

            // Validasi tambahan menggunakan helper
            $validationErrors = ImageUploadHelper::validateImage($file, 2048);
            if (!empty($validationErrors)) {
                return back()->withErrors($validationErrors);
            }

            // Upload file
            $filePath = ImageUploadHelper::upload($file, $folder, 'public');

            // Get file info
            $fileInfo = ImageUploadHelper::getImageInfo($file);

            return back()->with([
                'success' => 'Image uploaded successfully!',
                'file_path' => $filePath,
                'file_info' => $fileInfo
            ]);

        } catch (\Exception $e) {
            return back()->withErrors(['upload' => 'Upload failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Delete uploaded file
     */
    public function delete(Request $request)
    {
        $filePath = $request->input('file_path');
        
        if ($filePath && ImageUploadHelper::delete($filePath, 'public')) {
            return response()->json(['success' => true, 'message' => 'File deleted successfully']);
        }
        
        return response()->json(['success' => false, 'message' => 'File not found or delete failed']);
    }

    /**
     * Get file URL
     */
    public function getUrl($filePath)
    {
        return response()->json([
            'url' => ImageUploadHelper::getUrl($filePath, 'public'),
            'exists' => Storage::disk('public')->exists($filePath)
        ]);
    }
}
