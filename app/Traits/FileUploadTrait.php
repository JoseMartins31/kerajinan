<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait FileUploadTrait
{
    /**
     * Upload file to storage
     *
     * @param UploadedFile $file
     * @param string $folder
     * @param string|null $filename
     * @return string|false
     */
    protected function uploadFile(UploadedFile $file, $folder = 'uploads', $filename = null)
    {
        if (!$filename) {
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        }

        return $file->storeAs($folder, $filename, 'public');
    }

    /**
     * Delete file from storage
     *
     * @param string $filePath
     * @return bool
     */
    protected function deleteFile($filePath)
    {
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            return Storage::disk('public')->delete($filePath);
        }

        return false;
    }

    /**
     * Get file URL
     *
     * @param string $filePath
     * @return string|null
     */
    protected function getFileUrl($filePath)
    {
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            return asset('storage/' . $filePath);
        }

        return null;
    }

    /**
     * Upload image with validation
     *
     * @param UploadedFile $file
     * @param string $folder
     * @param array $allowedMimes
     * @param int $maxSize (in KB)
     * @return string|false
     */
    protected function uploadImage(UploadedFile $file, $folder = 'images', $allowedMimes = null, $maxSize = null)
    {
        // Get config values if not provided
        if ($allowedMimes === null) {
            $allowedMimes = config('storage.upload_limits.images.allowed_mimes', ['jpeg', 'png', 'jpg', 'gif']);
        }

        if ($maxSize === null) {
            $maxSize = config('storage.upload_limits.images.max_size', 2048);
        }

        // Validate file type
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, $allowedMimes)) {
            return false;
        }

        // Validate file size (convert KB to bytes)
        if ($file->getSize() > ($maxSize * 1024)) {
            return false;
        }

        // Generate filename
        $filename = time() . '_' . Str::random(10) . '.' . $extension;

        return $file->storeAs($folder, $filename, 'public');
    }

    /**
     * Upload document with validation
     *
     * @param UploadedFile $file
     * @param string $folder
     * @param array $allowedMimes
     * @param int $maxSize (in KB)
     * @return string|false
     */
    protected function uploadDocument(UploadedFile $file, $folder = 'documents', $allowedMimes = null, $maxSize = null)
    {
        // Get config values if not provided
        if ($allowedMimes === null) {
            $allowedMimes = config('storage.upload_limits.documents.allowed_mimes', ['pdf', 'doc', 'docx', 'txt']);
        }

        if ($maxSize === null) {
            $maxSize = config('storage.upload_limits.documents.max_size', 5120);
        }

        // Validate file type
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, $allowedMimes)) {
            return false;
        }

        // Validate file size (convert KB to bytes)
        if ($file->getSize() > ($maxSize * 1024)) {
            return false;
        }

        // Generate filename
        $filename = time() . '_' . Str::random(10) . '.' . $extension;

        return $file->storeAs($folder, $filename, 'public');
    }

    /**
     * Upload payment proof with validation
     *
     * @param UploadedFile $file
     * @param string $orderId
     * @return string|false
     */
    protected function storePaymentProof(UploadedFile $file, $orderId)
    {
        $allowedMimes = config('storage.upload_limits.payment_proofs.allowed_mimes', ['jpeg', 'png', 'jpg']);
        $maxSize = config('storage.upload_limits.payment_proofs.max_size', 2048);
        $folder = config('storage.directories.payment_proofs', 'payment-proofs');

        // Validate file type
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, $allowedMimes)) {
            return false;
        }

        // Validate file size (convert KB to bytes)
        if ($file->getSize() > ($maxSize * 1024)) {
            return false;
        }

        // Generate filename with order ID
        $filename = 'payment_' . $orderId . '_' . time() . '.' . $extension;

        return $file->storeAs($folder, $filename, 'public');
    }
}
