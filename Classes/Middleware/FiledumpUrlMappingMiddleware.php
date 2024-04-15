<?php

declare(strict_types=1);


namespace WEBcoast\FiledumpPathUrls\Middleware;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class FiledumpUrlMappingMiddleware implements MiddlewareInterface
{

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (str_starts_with($request->getUri()->getPath(), '/dump-file/')) {
            $urlParts = explode('/', trim($request->getUri()->getPath(), '/'));
            $query = [
                'eID' => 'dumpFile',
                't' => $urlParts[1],
                $urlParts[1] => $urlParts[2],
                'token' => $urlParts[3]
            ];

            $request = $request->withQueryParams($query);
        }

        return $handler->handle($request);
    }
}
