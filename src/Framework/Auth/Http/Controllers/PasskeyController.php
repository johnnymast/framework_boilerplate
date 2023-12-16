<?php

namespace App\Framework\Auth\Http\Controllers;

use App\Framework\Auth\Guard;
use App\Framework\Session\Facade\Session;
use App\Framework\Validation\Exceptions\ValidationDefinitionException;
use App\Framework\Validation\Validator;
use App\Http\Controller\Controller;
use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use ReflectionException;

class PasskeyController extends Controller
{


    /**
     * @var Guard
     */
    protected Guard $guard;

    /**
     * LoginController constructor.
     *
     * @param EntityManager $em Inject entity manager.
     */
    public function __construct(EntityManager $em)
    {
        $this->guard = new Guard();
        $settings = config('auth.login');

        parent::__construct($em);
    }

    /**
     * Show the login form.
     *
     * @param Response $response The response Object.
     *
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @return Response
     */
    public function showLoginForm(Response $response): Response
    {
        if ($this->isBlocked()) {
            return view('auth.blocked');
        }
        return view('auth.login');
    }
}
