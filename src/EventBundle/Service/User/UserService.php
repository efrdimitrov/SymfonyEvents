<?php


namespace EventBundle\Service\User;

use EventBundle\Entity\User;
use EventBundle\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserService implements UserServiceInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }
    /**
     * @return User[]
     */
    public function getAll(): array
    {
        return $this->userRepository->findAll();
    }
    /**
     * @param int $id
     * @return User|object|null
     */
    public function get(int $id): ?User
    {
        return $this->userRepository->find($id);
    }
    /**
     * @param array $array
     * @return User|object
     */
    public function getBy(array $array)
    {
        return $this->userRepository->findOneBy($array);
    }
    /**
     * @param User $user
     * @return UserService
     */
    public function save(User $user)
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $this;
    }
}