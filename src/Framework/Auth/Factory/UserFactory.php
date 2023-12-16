<?php

namespace App\Framework\Auth\Factory;

use App\Framework\Auth\Mail\EmailVerification;
use App\Framework\Facade\Email;
use App\Model\User;
use Doctrine\ORM\EntityManager;

final class UserFactory
{
    /**
     * Create a new user.
     *
     * @param string $name     The Name of the user.
     * @param string $email    The Email for the user.
     * @param string $password The password for the user.
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @return \App\Model\User
     */
    public static function create(string $name = '', string $email = '', string $password = ''): User
    {
        $settings = config('auth.user');

        $em = app()->resolve(EntityManager::class);

        /**
         * @var \App\Model\User
         */
        $user = new $settings['entity'];
        $user->setName($name);
        $user->setEmail($email);
        $user->setPassword($password);

        if ($settings['require_confirmation']) {
            $user->setActivated(false);
            $user->setVerificationToken(self::createVerificationToken());

            $cornfirmationRequest = new EmailVerification($user);

            Email::to($user->getEmail())
                ->send($cornfirmationRequest);
        } else {
            $user->setActivated(true);
        }
        $em->persist($user);
        $em->flush();

        return $user;
    }

    /**
     * Create an activation token.
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public static function createVerificationToken(): string
    {
        $settings = config('auth.user');
        $repository = app()->resolve(EntityManager::class)->getRepository($settings['entity']);

        $tokenGeneric = $settings['secret_key'];

        $data = time() ;
        $token = hash('sha256', $tokenGeneric . $data);

        if ($repository->findOneBy(['verification_token' => $token])) {
            return self::createVerificationToken();
        }

        return $token;
    }

    /**
     * Create an password reset token.
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public static function createPasswordToken(): string
    {
        $settings = config('auth.user');
        $repository = app()->resolve(EntityManager::class)->getRepository($settings['entity']);

        $tokenGeneric = $settings['secret_key'];

        $data = time() ;
        $token = hash('sha256', $tokenGeneric . $data);

        if ($repository->findOneBy(['password_token' => $token])) {
            return self::createPasswordToken();
        }

        return $token;
    }
}
