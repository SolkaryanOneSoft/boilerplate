<?php

namespace App\Enums;

enum FileExtensionsEnum
{
    public const ALLOWED_UPLOAD_IMAGE = [
        'jpeg', 'png', 'jpg','webp', 'svg', 'avif'     // image
    ];

    public const ALLOWED_UPLOAD_IMAGE_VIDEO = [
        'jpeg', 'png', 'jpg','webp', 'svg', 'avif',     // image
        'mp4', 'mov', 'avi', 'mkv', 'webm'              // video
    ];

    public const ALLOWED_UPLOAD_FILE = [
        'pdf', 'doc', 'docx', 'msg'
    ];
}
