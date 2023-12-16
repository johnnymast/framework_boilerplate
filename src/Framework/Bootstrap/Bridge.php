<?php

namespace App\Framework\Bootstrap;

use App\Framework\Application;
use DI\Bridge\Slim\Bridge as SlimBridge;
use DI\Bridge\Slim\CallableResolver;
use DI\Bridge\Slim\ControllerInvoker;
use DI\Container;
use Invoker\CallableResolver as InvokerCallableResolver;
use Invoker\Invoker;
use Invoker\ParameterResolver\AssociativeArrayResolver;
use Invoker\ParameterResolver\Container\TypeHintContainerResolver;
use Invoker\ParameterResolver\DefaultValueResolver;
use Invoker\ParameterResolver\ResolverChain;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\Factory\AppFactory;
use Slim\Interfaces\CallableResolverInterface;
use Slim\Interfaces\MiddlewareDispatcherInterface;
use Slim\Interfaces\RouteCollectorInterface;
use Slim\Interfaces\RouteResolverInterface;


class Bridge extends SlimBridge
{

    public static function createAppFromContainer(ContainerInterface $container): Application
    {
        $responseFactory = $container->has(ResponseFactoryInterface::class)
        && (
        $responseFactoryFromContainer = $container->get(ResponseFactoryInterface::class)
        ) instanceof ResponseFactoryInterface
            ? $responseFactoryFromContainer
            : AppFactory::determineResponseFactory();

        $callableResolver = $container->has(CallableResolverInterface::class)
        && (
        $callableResolverFromContainer = $container->get(CallableResolverInterface::class)
        ) instanceof CallableResolverInterface
            ? $callableResolverFromContainer
            : null;

        $routeCollector = $container->has(RouteCollectorInterface::class)
        && (
        $routeCollectorFromContainer = $container->get(RouteCollectorInterface::class)
        ) instanceof RouteCollectorInterface
            ? $routeCollectorFromContainer
            : null;

        $routeResolver = $container->has(RouteResolverInterface::class)
        && (
        $routeResolverFromContainer = $container->get(RouteResolverInterface::class)
        ) instanceof RouteResolverInterface
            ? $routeResolverFromContainer
            : null;

        $middlewareDispatcher = $container->has(MiddlewareDispatcherInterface::class)
        && (
        $middlewareDispatcherFromContainer = $container->get(MiddlewareDispatcherInterface::class)
        ) instanceof MiddlewareDispatcherInterface
            ? $middlewareDispatcherFromContainer
            : null;

        return new Application(
            $responseFactory,
            $container,
            $callableResolver,
            $routeCollector,
            $routeResolver,
            $middlewareDispatcher
        );
    }

    public static function createFromContainer(ContainerInterface $container = null): Application
    {
        /**
         * @var \DI\Container $container
         */
        $container = $container ?: new Container;

        $callableResolver = new InvokerCallableResolver($container);


        $container->set(CallableResolverInterface::class, new CallableResolver($callableResolver));
        $app = static::createAppFromContainer($container);

        $container->set(Application::class, $app);

        $controllerInvoker = static::createControllerInvoker($container);
        $app->getRouteCollector()->setDefaultInvocationStrategy($controllerInvoker);

        return $app;
    }

    public static function createControllerInvoker(ContainerInterface $container): ControllerInvoker
    {
        $resolvers = [
            // Inject parameters by name first
            new AssociativeArrayResolver(),
            // Then inject services by type-hints for those that weren't resolved
            new TypeHintContainerResolver($container),
            // Then fall back on parameters default values for optional route parameters
            new DefaultValueResolver(),
        ];

        $invoker = new Invoker(new ResolverChain($resolvers), $container);

        return new ControllerInvoker($invoker);
    }
}
