<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StorageController extends Controller
{
    /**
     * Display storage information (for debugging)
     */
    public function info()
    {
        if (!app()->environment(['local', 'testing'])) {
            abort(404);
        }

        $publicDisk = Storage::disk('public');

        $info = [
            'storage_path' => storage_path('app/public'),
            'public_path' => public_path('storage'),
            'storage_url' => asset('storage'),
            'is_linked' => is_link(public_path('storage')),
            'directories' => $publicDisk->directories(),
            'files' => $publicDisk->files(),
            'disk_config' => config('filesystems.disks.public'),
        ];

        return response()->json($info, 200, JSON_PRETTY_PRINT);
    }

    /**
     * Test file upload (for debugging)
     */
    public function testUpload(Request $request)
    {
        if (!app()->environment(['local', 'testing'])) {
            abort(404);
        }

        if ($request->hasFile('test_file')) {
            $file = $request->file('test_file');
            $path = $file->store('test-uploads', 'public');

            return response()->json([
                'success' => true,
                'path' => $path,
                'url' => asset('storage/' . $path),
                'size' => $file->getSize(),
                'mime' => $file->getMimeType(),
                'original_name' => $file->getClientOriginalName()
            ]);
        }

        return response()->json(['error' => 'No file uploaded'], 400);
    }

    /**
     * Clean up test files (for debugging)
     */
    public function cleanup()
    {
        if (!app()->environment(['local', 'testing'])) {
            abort(404);
        }

        $publicDisk = Storage::disk('public');

        // Remove test files
        if ($publicDisk->exists('test.txt')) {
            $publicDisk->delete('test.txt');
        }

        // Remove test upload directory
        if ($publicDisk->exists('test-uploads')) {
            $publicDisk->deleteDirectory('test-uploads');
        }

        return response()->json(['message' => 'Test files cleaned up']);
    }
}
