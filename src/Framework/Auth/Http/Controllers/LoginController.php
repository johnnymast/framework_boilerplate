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

class LoginController extends Controller
{
    /**
     * @var string
     */
    protected string $redirectTo = 'home';

    /**
     * The number of login attempts before
     * timing out the user.
     *
     * @var int
     */
    protected int $maxLoginAttempts = 3;

    /**
     * The number of minutes to time out
     * the user for if the maximum login
     * failed login attempts have been reached.
     *
     * @var int
     */
    protected int $loginTimeoutMinutes = 5;

    /**
     * Enable blocking users if they fail to
     * authenticate a number of times.
     *
     * @var bool
     */
    protected bool $enableLoginBlocking = false;

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

        if (isset($settings['redirect_to'])) {
            $this->redirectTo = $settings['redirect_to'];
        }

        $login_blocking = config('auth.login_blocking');

        if ($login_blocking['enabled']) {
            $this->enableLoginBlocking = true;
            $this->maxLoginAttempts = $login_blocking['max_attempts'];
            $this->loginTimeoutMinutes = $login_blocking['timeout_in_minutes'];
        }

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

    /**
     * Check if the user is blocked.
     *
     * @return bool
     */
    private function isBlocked(): bool
    {
        if ($this->enableLoginBlocking) {
            if (Session::has('auth_login_block_expires_at')) {
                return true;
            }
        }
        return false;
    }

    /**
     * Handle the Login.
     *
     * @param Request  $request  The request object.
     * @param Response $response The response object.
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     * @throws ValidationDefinitionException
     * @return Response
     */
    public function login(Request $request, Response $response): Response
    {
        $validator = new Validator($request->getParsedBody());

        $data = $validator->validate([
            'email' => 'string',
            'password' => 'string',
        ]);

        $flash = Session::getFlash();


        if ($this->enableLoginBlocking) {
            if ($this->isBlocked()) {
                $expires = Session::get('auth_login_block_expires_at');

                if (time() > $expires) {
                    Session::set('auth_login_attempts', 0);
                    Session::delete('auth_login_block_expires_at');
                } else {
                    return view('auth.blocked');
                }
            }
        }

        if ($validator->passes()) {
            $guard = new Guard();

            if ($guard->authenticate($data['email'], $data['password'])) {
                $flash->add('success', 'User Logged in');

                return $response
                    ->withStatus(302)
                    ->withHeader('Location', app()->getRouteCollector()->getRouteParser()->urlFor($this->redirectTo));
            } else {
                if ($this->enableLoginBlocking) {
                    $attempts = Session::get('auth_login_attempts', 0);
                    $attempts += 1;

                    Session::set('auth_login_attempts', $attempts);

                    if ($attempts > $this->maxLoginAttempts) {
                        Session::set(
                            'auth_login_block_expires_at',
                            time() + (60 * $this->loginTimeoutMinutes)
                        );
                    }
                }

                $flash->add('error', 'Could not login user');
            }
        } else {
            $flash->set('error', $validator->errors());
        }

        return view('auth.login');
    }

    /**
     * Handle the logout.
     *
     * @param \Psr\Http\Message\ResponseInterface $response The response object.
     *
     * @return Response
     */
    public function logout(Response $response): Response
    {
        $this->guard->logout();

        return $response
            ->withStatus(302)
            ->withHeader('Location', app()->getRouteCollector()->getRouteParser()->urlFor('auth.login'));
    }
}
