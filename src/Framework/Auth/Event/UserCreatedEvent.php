<?php

namespace App\Framework\Auth\Event;

use App\Model\User;

class UserCreatedEvent
{
    public function __construct(public User $user)
    {
    }
}
