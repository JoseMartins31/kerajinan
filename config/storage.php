<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Storage Configuration
    |--------------------------------------------------------------------------
    |
    | Here you can configure storage settings for file uploads
    |
    */

    'upload_limits' => [
        'images' => [
            'max_size' => 2048, // KB
            'allowed_mimes' => ['jpeg', 'png', 'jpg', 'gif', 'webp'],
        ],
        'documents' => [
            'max_size' => 5120, // KB (5MB)
            'allowed_mimes' => ['pdf', 'doc', 'docx', 'txt', 'xlsx', 'xls'],
        ],
        'payment_proofs' => [
            'max_size' => 2048, // KB
            'allowed_mimes' => ['jpeg', 'png', 'jpg'],
        ],
    ],

    'directories' => [
        'payment_proofs' => 'payment-proofs',
        'product_images' => 'product-images',
        'user_uploads' => 'uploads',
        'documents' => 'documents',
        'images' => 'images',
    ],

];
