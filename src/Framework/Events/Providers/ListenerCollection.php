<?php declare(strict_types=1);
/*
 * This file is part of Redbox-Events.
 *
 * (c) Johnny Mast <mastjohnny@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Framework\Events\Providers;

use Closure;
use InvalidArgumentException;
use ReflectionFunction;
use ReflectionNamedType;

/**
 * The ListenerCollection holds a collection of listeners
 * for specific events.
 */
class ListenerCollection
{
    /**
     * @var array<string, callable>
     */
    protected array $listeners = [];

    /**
     * @param string $event
     *
     * @return mixed
     */
    public function getForEvent(string $event): mixed
    {
        return $this->listeners[$event] ?? [];
    }

    /**
     * Add a new listener for a given event.
     *
     * @param callable $listener
     *
     * @return $this
     * @throws \ReflectionException
     */
    public function add(callable $listener): ListenerCollection
    {
        $info = $this->getParameterType($listener);

        foreach ($info as $evt) {
            $this->listeners[$evt][] = $listener;
        }

        return $this;
    }

    /**
     * Determine the parameter type for the callable function
     * used in the add function.
     *
     * @param callable $func
     *
     * @see \App\Framework\Events\Providers\ListenerCollection::add()
     * @return array<string>
     *
     * @throws \ReflectionException
     */
    private function getParameterType(callable $func): array
    {
        $closure = new ReflectionFunction(Closure::fromCallable($func));
        $params = $closure->getParameters();

        if (isset($params[0])) {
            $reflectedType = $params[0]->getType();
        } else {
            throw new InvalidArgumentException('Listeners must accept an event object.');
        }

        if ($reflectedType instanceof ReflectionNamedType) {
            return [$reflectedType->getName()];
        }

        throw new InvalidArgumentException('Listeners must declare an object type they can accept.');
    }
}