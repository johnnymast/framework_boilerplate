<?php
declare(strict_types=1);

namespace App\Framework\Events\Providers;

use App\Framework\Interfaces\Events\ListenerProviderInterface;

/**
 * The Listener Provider provide listeners to the EventDispatcher.
 */
class Provider implements ListenerProviderInterface
{
    /**
     * @var ?ListenerCollection
     */
    protected ?ListenerCollection $listeners = null;

    /**
     * @param \App\Framework\Events\Providers\ListenerCollection $listeners
     */
    public function __construct(ListenerCollection $listeners)
    {
        $this->listeners = $listeners;
    }

    /**
     * Return the listeners for the event passed.
     *
     * @param object $event The Event.
     *
     * @return callable
     */
    public function getListenersForEvent(object $event): iterable
    {
        return $this->listeners->getForEvent(get_class($event));
    }
}