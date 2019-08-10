<?php


namespace EventBundle\Service;


use EventBundle\Entity\User;

interface UserServiceInterface
{
    public function register(User $user) : bool;
}