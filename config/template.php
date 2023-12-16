<?php

use App\Framework\Renderer\Renderers\BladeRenderer;

return [
    'renderer' => BladeRenderer::class,
    'view' => [
        'path' => [view_path()],
        'cache' => cache_path('views'),
    ],
];