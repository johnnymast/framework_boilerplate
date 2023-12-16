<?php

namespace App\Framework\Auth\Http\Middleware;

use App\Framework\Session\Facade\Session;
use App\Model\User;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthMiddleware implements MiddlewareInterface
{

    /**
     * Handle the middleware.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Server\RequestHandlerInterface $handler
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @return \Slim\Psr7\Response
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (Session::has('user')) {
            $repository = app()->resolve(EntityManager::class)->getRepository(User::class);
            $sessionUser = Session::get('user');

            $user = $repository->find($sessionUser->getId());
            $request = $request->withAttribute('user', $user);
        }

        return $handler->handle($request);
    }
}

