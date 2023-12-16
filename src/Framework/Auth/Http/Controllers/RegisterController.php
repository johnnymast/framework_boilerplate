<?php

namespace App\Framework\Auth\Http\Controllers;

use App\Framework\Auth\Event\UserCreatedEvent;
use App\Framework\Auth\Exceptions\AuthRegisterException;
use App\Framework\Auth\Factory\UserFactory;
use App\Framework\Auth\Interfaces\RegisterUserInterface;
use App\Framework\Events\Dispatcher;
use App\Framework\Events\Providers\Provider;
use App\Framework\Session\Facade\Session;
use App\Framework\Validation\Exceptions\ValidationDefinitionException;
use App\Framework\Validation\Validator;
use App\Http\Controller\Controller;
use App\Model\User;
use Doctrine\ORM\EntityManager;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use ReflectionException;

class RegisterController extends Controller
{

    /**
     * Redirect to this route after
     * registration is complete.
     *
     * @var string
     */
    protected string $redirectTo = 'auth.verification';

    /**
     * RegisterController constructor.
     *
     * @param EntityManager $em Inject entity manager.
     *
     */
    public function __construct(EntityManager $em)
    {
        $settings = config('auth.register');

        if (isset($settings['redirect_to'])) {
            $this->redirectTo = $settings['redirect_to'];
        }

        parent::__construct($em);
    }

    /**
     * Show the registration form.
     *
     * @param Response $response The Response Object.
     *
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @return Response
     */
    public function showRegistrationForm(Response $response): Response
    {
        return view('auth.register');
    }

    /**
     * Handle the registration.
     *
     * @param Request  $request  The Request Object.
     * @param Response $response The Response Object.
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     * @throws ValidationDefinitionException
     * @return Response
     */
    public function register(Request $request, Response $response): Response
    {
        $validator = new Validator($request->getParsedBody());

        $data = $validator->validate([
            'email' => 'string',
            'name' => 'string',
            'password' => 'string',
            'password_confirmation' => 'string',
        ]);

        $flash = Session::getFlash();

        /**
         * Validator::passes() is also
         * available.
         */
        if ($validator->passes()) {
            if ($data['password'] == $data['password_confirmation']) {
                try {
                    $repository = App()->resolve(EntityManager::class)->getRepository(User::class);
                    if ($repository->findOneBy(['email' => $data['email']])) {
                        throw new AuthRegisterException("Account already exists.");
                    }

                    $user = UserFactory::create(
                        name: $data['name'],
                        email: $data['email'],
                        password: password_hash($data['password'], PASSWORD_DEFAULT)
                    );

                    $collection = app()->resolve(RegisterUserInterface::class);
                    $provider = new Provider($collection);
                    $dispatcher = new Dispatcher($provider);

                    $dispatcher->dispatch(new UserCreatedEvent($user));

                    if (!$user->getId()) {
                        $flash->add('error', 'Error creating user account');
                    } else {
                        $flash->add('success', 'User created');

                        return $response
                            ->withStatus(302)
                            ->withBody($request->getBody())
                            ->withHeader(
                                'Location',
                                app()->getRouteCollector()->getRouteParser()->urlFor($this->redirectTo)
                            );
                    }
                } catch (AuthRegisterException $e) {
                    $flash->add('error', $e->getMessage());
                } catch (Exception $e) {
                    $flash->add('error', 'Error creating user account ' . $e->getMessage());
                }
            } else {
                $flash->add('error', 'Passwords dont match');
            }
        } else {
            /**
             * Handle errors use $validator->errors()
             * to get the errors.
             */
            $flash->add('error', $validator->errors());
        }

        return view('auth.register');
    }
}
