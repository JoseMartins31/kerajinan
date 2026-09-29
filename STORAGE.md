# Storage System Documentation

## Overview

This Laravel application is configured with a complete file storage system for handling uploads and file management.

## Configuration

### Storage Setup

- **Storage Link**: Symbolic link created between `storage/app/public` and `public/storage`
- **Default Disk**: `public` disk for web-accessible files
- **Base URL**: Files accessible via `/storage/` URLs

### Directory Structure

```
storage/app/public/
├── documents/         # Document uploads (PDF, DOC, etc.)
├── images/           # General image uploads
├── payment-proofs/   # Payment proof images
├── product-images/   # Product images
└── uploads/          # General file uploads
```

## File Upload Limits (Configurable in config/storage.php)

### Images

- **Max Size**: 2MB (2048 KB)
- **Allowed Types**: JPEG, PNG, JPG, GIF, WebP

### Documents

- **Max Size**: 5MB (5120 KB)
- **Allowed Types**: PDF, DOC, DOCX, TXT, XLSX, XLS

### Payment Proofs

- **Max Size**: 2MB (2048 KB)
- **Allowed Types**: JPEG, PNG, JPG

## Usage

### Using FileUploadTrait

Include the trait in your controller:

```php
use App\Traits\FileUploadTrait;

class YourController extends Controller
{
    use FileUploadTrait;

    public function upload(Request $request)
    {
        if ($request->hasFile('image')) {
            $path = $this->uploadImage($request->file('image'), 'folder-name');
            // Save $path to database
        }
    }
}
```

### Available Methods

- `uploadFile($file, $folder, $filename)` - Basic file upload
- `uploadImage($file, $folder, $mimes, $maxSize)` - Image upload with validation
- `uploadDocument($file, $folder, $mimes, $maxSize)` - Document upload with validation
- `uploadPaymentProof($file, $orderId)` - Specialized payment proof upload
- `deleteFile($filePath)` - Delete file from storage
- `getFileUrl($filePath)` - Get public URL for file

## Testing (Development Only)

Access these routes in development environment:

- `/storage/info` - Display storage configuration
- POST `/storage/test-upload` - Test file upload
- DELETE `/storage/cleanup` - Clean up test files

## Security Notes

- Files are validated by type and size
- Payment proofs are restricted to images only
- Storage directories have proper permissions
- Files are served through Laravel's storage system

## Maintenance

- Monitor storage usage regularly
- Clean up old/unused files periodically
- Backup important files regularly
- Check disk space availability
