<?php


namespace EventBundle\Service;


use EventBundle\Entity\User;

class UserService implements UserServiceInterface
{

    public function register(User $user): bool
    {
        return true;
    }
}