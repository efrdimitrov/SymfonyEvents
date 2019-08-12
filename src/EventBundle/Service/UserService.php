<?php


namespace EventBundle\Service;


use EventBundle\Entity\User;

class UserService implements UserServiceInterface
{
    /**
     * @param User $user
     * @return bool
     */
    public function register(User $user): bool
    {
        return true;
    }
}