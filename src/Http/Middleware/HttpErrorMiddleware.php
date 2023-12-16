<?php

namespace App\Http\Middleware;

use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;

class HttpErrorMiddleware implements MiddlewareInterface
{

    /**
     * Handle the middleware.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Server\RequestHandlerInterface $handler
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (HttpNotFoundException $httpException) {
            return view('web.errors.404')->withStatus(404);
        }
    }
}

