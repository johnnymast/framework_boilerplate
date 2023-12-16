<?php

namespace App\Framework\Csrf\Http\Middleware;

use App\Framework\Exceptions\Csrf\ExpiredCSRFToken;
use App\Framework\Exceptions\Csrf\InvalidCSRFToken;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;

class CSRFMiddleware implements MiddlewareInterface
{

    /**
     * Handle the middleware.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request The request.
     * @param \Psr\Http\Server\RequestHandlerInterface $handler The handler.
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Exception
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /**
         * @var \App\Framework\Csrf\CsrfProtection $csrf
         */
        $csrf = app()->resolve('csrf');
        $params = $request->getParsedBody();

        if ($request->getMethod() == 'GET') {
            if (!$csrf->hasToken()) {
                $csrf->generateToken();
            }
        } else {
            try {

                if (isset($params['_token'])) {
                    $csrf->validateToken($params['_token']);
                } else {
                    log_debug("1) ".$params['_token']."does not match".$csrf->getTokenValue());
                    throw new InvalidCSRFToken();
                }
            } catch (InvalidCSRFToken|ExpiredCSRFToken $e) {
                log_debug("2) ".$params['_token']."does not match".$csrf->getTokenValue());

                return view('web.errors.csrf', [
                    'message' => $e->getMessage()
                ])->withStatus(403);
            }
        }

        return $handler
            ->handle($request);
    }
}

