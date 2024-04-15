<?php

declare(strict_types=1);

return [
    'frontend' => [
        'filedump-path-urls' => [
            'target' => \WEBcoast\FiledumpPathUrls\Middleware\FiledumpUrlMappingMiddleware::class,
            'before' => [
                'typo3/cms-frontend/eid',
                'beechit/eid-frontend/authentication'
            ]
        ]
    ]
];
