<?php

namespace App\Framework;

use Psr\Http\Message\RequestInterface;
use Slim\App;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Psr7\Request;

/**
 * @method mixed getContainer()->set(string $id)
 */
class Application extends App
{
    /**
     * Reference to the request.
     *
     * @var \Psr\Http\Message\RequestInterface
     */
    protected RequestInterface $request;

    /**
     * Set the request.
     *
     * @param \Psr\Http\Message\RequestInterface $request
     *
     * @return void
     */
    public function setRequest(RequestInterface $request): void
    {
        $this->request = $request;
    }

    /**
     * Return the request.
     *
     * @return \Psr\Http\Message\RequestInterface
     */
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    /**
     * Register a new dependency to the Container.
     *
     * @param mixed ...$args The argument for the set function.
     *
     * @return mixed
     */
    public function bind(mixed ...$args): mixed
    {
        return $this->getContainer()->set(...$args);
    }

    /**
     * Resolve a dependency from the container.
     *
     * @param mixed ...$args Arguments for the get function.
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     *
     * @return mixed
     */
    public function resolve(mixed ...$args): mixed
    {
        return $this->getContainer()->get(...$args);
    }

    /**
     * Reference to the request.
     *
     * @return \Slim\Psr7\Request
     */
    public function request(): Request
    {
        return ServerRequestFactory::createFromGlobals();
    }

    /**
     * Check if the application is being run from the console.
     *
     * @return bool
     */
    public function runningInConsole(): bool
    {
        return (php_sapi_name() == "cli");
    }
}
