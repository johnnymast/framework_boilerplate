<?php

namespace App\Framework\Auth;

use App\Framework\Auth\Repository\UserRepository;
use App\Framework\Session\Facade\Session;
use Doctrine\ORM\EntityManager;
use App\Model\User;

final class Guard
{

    /**
     * Reference to the User Repository.
     *
     * @var \App\Framework\Auth\Repository\UserRepository
     */
    protected UserRepository $repository;

    /**
     * Guard constructor.
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __construct()
    {
        $settings = config('auth.user');
        $this->repository = app()->resolve(EntityManager::class)->getRepository($settings['entity']);
    }

    /**
     * Authenticate a usr.
     *
     * @param string $email    The user email address.
     * @param string $password The user password.
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     *
     * @return bool
     */
    public function authenticate(string $email, string $password): bool
    {
        $user = $this->repository->findOneByEmail($email);

        if ($user) {
            if (password_verify($password, $user->getPassword())) {
                $this->login($user);
                return true;
            }
        }

        $this->logout();

        return false;
    }

    /**
     * Add a user to the session.
     *
     * @param \App\Model\User $user
     *
     * @return void
     */
    public function login(User $user): void
    {
        Session::set('user', $user);
    }

    /**
     * Remove the user from as session.
     *
     * @return void
     */
    public function logout(): void
    {
        Session::delete('user');
    }
}
