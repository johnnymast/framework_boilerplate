<?php declare(strict_types=1);

namespace App\Framework\Events;

use App\Framework\Interfaces\Events\EventDispatcherInterface;
use App\Framework\Interfaces\Events\StoppableEventInterface;
use App\Framework\Interfaces\Events\ListenerProviderInterface;

/**
 * A Dispatcher is a service object implementing EventDispatcherInterface.
 * It is responsible for retrieving Listeners from a Listener Provider for the Event dispatched, and invoking
 * each Listener with that Event.
 */
class Dispatcher implements EventDispatcherInterface
{
    /**
     * Reference to the Provider.
     *
     * @var ?ListenerProviderInterface
     */
    protected ?ListenerProviderInterface $provider = null;

    /**
     * EventDispatcher constructor.
     *
     * @param \App\Framework\Interfaces\Events\ListenerProviderInterface $provider
     */
    public function __construct(ListenerProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    /**
     * Dispatch a new event.
     *
     * @param object $event The event to dispatch.
     *
     * @return object
     */
    public function dispatch(object $event): object
    {
        if ($this->provider) {
            foreach ($this->provider->getListenersForEvent($event) as $listener) {
                if ($event instanceof StoppableEventInterface && $event->isPropagationStopped()) {
                    return $event;
                }

                $listener($event);
            }
        }
        return $event;
    }
}
