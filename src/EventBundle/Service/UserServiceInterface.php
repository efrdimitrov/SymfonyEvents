<?php


namespace EventBundle\Service;

use EventBundle\Entity\User;


interface UserServiceInterface
{
    /**
     * @return User[]
     */
    public function getAll(): array;
    /**
     * @param int $id
     * @return User
     */
    public function get(int $id): ? User;
    /**
     * @param User $user
     * @return mixed
     */
    public function save(User $user);

}