<?php

namespace App\Framework\Auth\Http\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use App\Framework\Session\Facade\Session;
use App\Http\Controller\Controller;
use App\Framework\Auth\Guard;

class VerificationController extends Controller
{
    /**
     * Show the page informing the user they need to verify there email.
     *
     * @param \Psr\Http\Message\ResponseInterface $response The response object.
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function showActivationPage(Response $response): Response
    {
        return view('auth.verify');
    }

    /**
     * Confirm the account token.
     *
     * @param \Psr\Http\Message\ResponseInterface $response The response object.
     * @param array<string>                       $args     The route arguments.
     *
     * @throws \Doctrine\ORM\Exception\NotSupported
     * @throws \Doctrine\ORM\Exception\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function confirmToken(Response $response, array $args = []): Response
    {
        $settings = config('auth.user');

        /**
         * @var \App\Framework\Auth\Repository\UserRepository $repository
         */
        $repository = $this->em->getRepository($settings['entity']);
        $user = $repository->findOneBy(['verification_token' => $args['token'] ?? '']);

        $flash = Session::getFlash();

        if ($user) {
            $guard = new Guard();
            $user->setActivated(true);
            $user->setEmailVerifiedAt(new \DateTime());

            $this->em->persist($user);
            $this->em->flush();

            $guard->login($user);

            $response = $response
                ->withStatus(302)
                ->withHeader(
                    'Location',
                    app()->getRouteCollector()->getRouteParser()->urlFor('home')
                );
            $flash->add('success', 'Your account is now activated.');
        } else {
            $flash->add('error', 'Your account could not be found.');

            $response = $response
                ->withStatus(302)
                ->withHeader(
                    'Location',
                    app()->getRouteCollector()->getRouteParser()->urlFor('auth.verify_email')
                );
        }

        return $response;
    }
}
