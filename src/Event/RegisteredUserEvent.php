<?php

namespace App\Event;

use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class RegisteredUserEvent extends Event
{
    public const NAME = 'user.register';

    private User $registeredUser;

    public function __construct(User $registeredUser)
    {
        $this->registeredUser = $registeredUser;
    }

    public function getRegisterdUser(): User
    {
        return $this->registeredUser;
    }
}