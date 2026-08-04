<?php

declare(strict_types=1);

return [
    'name' => 'VIP Karvan',
    'url' => 'http://localhost/vipqr/public',
    'timezone' => 'Asia/Baku',
    'locale' => 'az',
    'session_name' => 'vipqr_session',
    'upload_max_bytes' => 2 * 1024 * 1024,
    'upload_allowed_mimes' => [
        'image/jpeg',
        'image/png',
        'image/webp',
        'image/gif',
    ],
    'upload_allowed_ext' => ['jpg', 'jpeg', 'png', 'webp', 'gif'],
    'video_max_bytes' => 12 * 1024 * 1024,
    'video_allowed_mimes' => [
        'video/mp4',
        'video/webm',
        'video/quicktime',
    ],
    'video_allowed_ext' => ['mp4', 'webm', 'mov'],
];
