<?php

namespace App\Framework\Auth\Http\Controllers;

use App\Framework\Auth\Factory\UserFactory;
use App\Framework\Auth\Mail\PasswordReset;
use App\Framework\Facade\Email;
use App\Framework\Session\Facade\Session;
use App\Framework\Validation\Exceptions\ValidationDefinitionException;
use App\Framework\Validation\Validator;
use App\Http\Controller\Controller;
use Doctrine\ORM\EntityManager;
use PHPMailer\PHPMailer\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use ReflectionException;

class ResetPasswordController extends Controller
{
    /**
     * Show the password request form.
     *
     * @param Response $response The response Object.
     *
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @return Response
     */
    public function showLinkRequestForm(Response $response): Response
    {
        return view('auth.passwords.request');
    }

    /**
     * Show the password request form.
     *
     * @param Request  $request  The request object
     * @param Response $response The response Object.
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     * @throws ValidationDefinitionException
     * @throws \PHPMailer\PHPMailer\Exception
     * @return Response
     */
    public function request(Request $request, Response $response): Response
    {
        $validator = new Validator($request->getParsedBody());

        $data = $validator->validate([
            'email' => 'string',
        ]);

        $flash = Session::getFlash();

        if ($validator->passes()) {
            $settings = config('auth.user');
            $entityManager = app()->resolve(EntityManager::class);
            $repository = $entityManager->getRepository($settings['entity']);
            $user = $repository->findOneBy(['email' => $data['email']]);

            if ($user) {
                $user->setPasswordToken(UserFactory::createPasswordToken());

                $entityManager->persist($user);
                $entityManager->flush();

                try {
                    Email::to($user->getEmail())
                        ->send(new PasswordReset($user));
                } catch (Exception $e) {
                    $flash->add('error', 'Error sending password reset email');
                    return view('auth.passwords.request');
                }

                return $response
                    ->withStatus(302)
                    ->withBody($request->getBody())
                    ->withHeader(
                        'Location',
                        app()->getRouteCollector()->getRouteParser()->urlFor('auth.password.mailsent')
                    );
            } else {
                $flash->add('error', 'Error sending password reset email');
            }
        } else {
            $flash->add('error', $validator->errors());
        }

        return view('auth.passwords.request');
    }

    /**
     * Confirm that the password reset link has been sent..
     *
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @return Response
     */
    public function showLinkSentPage(): Response
    {
        return view('auth.passwords.mail-sent');
    }


    /**
     * Show the password reset form.
     *
     * @param Request       $request  The request object.
     * @param Response      $response The response object.
     * @param array<string> $args     The arguments for the request.
     *
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @return Response
     */
    public function showResetForm(Request $request, Response $response, array $args = []): Response
    {
        $password_token = $args['password_token'];
        $settings = config('auth.user');

        $entityManager = app()->resolve(EntityManager::class);
        $repository = $entityManager->getRepository($settings['entity']);
        $user = $repository->findOneBy(['password_token' => $password_token]);

        $flash = Session::getFlash();

        if ($user) {
            return view('auth.passwords.reset', [
                'token' => $password_token,
            ]);
        } else {
            $flash->add('error', 'Unknown password reset token');

            $response = $response->withStatus(302)->withBody(
                $request->getBody(),
            )->withHeader(
                'Location',
                app()->getRouteCollector()->getRouteParser()->urlFor('auth.password.request')
            );
        }

        return $response;
    }

    /**
     * Update the user's password.
     *
     * @param Request       $request  The request object.
     * @param Response      $response The response object.
     * @param array<string> $args     The arguments for the request.
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     * @throws ValidationDefinitionException
     * @return Response
     */
    public function reset(Request $request, Response $response, array $args = []): Response
    {
        $validator = new Validator($request->getParsedBody());

        $data = $validator->validate([
            'password' => 'string',
            'password_again' => 'string',
        ]);

        $password_token = $args['password_token'];
        $settings = config('auth.user');

        $entityManager = app()->resolve(EntityManager::class);
        $repository = $entityManager->getRepository($settings['entity']);
        $user = $repository->findOneBy(['password_token' => $password_token]);
        $flash = Session::getFlash();

        if ($user) {
            if ($data['password'] !== $data['password_again']) {
                $flash->add('error', 'Password fields dont match');

                $response = $response->withStatus(302)->withBody(
                    $request->getBody(),
                )->withHeader(
                    'Location',
                    app()->getRouteCollector()->getRouteParser()->urlFor('auth.password.request')
                );
            } else {
                $user->setPassword(password_hash($data['password'], PASSWORD_DEFAULT));

                $entityManager->persist($user);
                $entityManager->flush();

                $flash->add('success', 'Please login with your new password');

                $response = $response->withStatus(302)->withBody(
                    $request->getBody(),
                )->withHeader(
                    'Location',
                    app()->getRouteCollector()->getRouteParser()->urlFor('auth.login')
                );
            }
        } else {
            $flash->add('error', 'Unknown password reset token');

            $response = $response->withStatus(302)->withBody(
                $request->getBody(),
            )->withHeader(
                'Location',
                app()->getRouteCollector()->getRouteParser()->urlFor('auth.password.request')
            );
        }

        return $response;
    }
}
