<?php

namespace App\Framework\Auth\Http\Middleware;

use App\Framework\Session\Facade\Session;
use App\Model\User;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthRequiredMiddleware implements MiddlewareInterface
{
    protected string $redirectTo = 'loggedout';

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
        $flash = Session::getFlash();

        if (Session::has('user')) {
            $user = Session::get('user');
            $settings = config('auth.user');

            if ($settings['require_confirmation'] && !$user->isActivated()) {
                /**
                 * Reload the user account from the database because
                 * the user could have activated their account since creating
                 * their account.
                 */
                $em = app()->resolve(EntityManager::class);
                $user = $em->getRepository(User::class)->find($user->getId());

                $response = app()
                    ->getResponseFactory()
                    ->createResponse();

                Session::set('user', $user);

                $response = $response
                    ->withStatus(302)
                    ->withBody($request->getBody())
                    ->withHeader('Location', app()->getRouteCollector()->getRouteParser()->urlFor('auth.activate'));

            } else {
                $response = $handler->handle($request);
            }


        } else {

            $response = app()
                ->getResponseFactory()
                ->createResponse();

            $response = $response
                ->withStatus(302)
                ->withBody($request->getBody())
                ->withHeader('Location', app()->getRouteCollector()->getRouteParser()->urlFor($this->redirectTo));
        }

        return $response;
    }
}
