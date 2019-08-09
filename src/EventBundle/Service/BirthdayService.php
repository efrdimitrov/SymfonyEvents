<?php


namespace EventBundle\Service;


use Doctrine\ORM\EntityManagerInterface;
use EventBundle\Entity\Birthday;
use EventBundle\Repository\BirthdayRepository;

class BirthdayService implements BirthdayServiceInterface
{
    /**
     * @var BirthdayRepository
     */
    private $birthdayRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * BirthdayService constructor.
     * @param BirthdayRepository $birthdayRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(BirthdayRepository $birthdayRepository,
                                EntityManagerInterface $entityManager)
    {
        $this->birthdayRepository = $birthdayRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @return Birthday[]
     */
    public function getAll(): array
    {
        return $this->birthdayRepository->findAll();
    }

    public function save(Birthday $birthday)
    {
        $this->entityManager->persist($birthday);
        $this->entityManager->flush();
    }

    public function get(int $id): Birthday
    {
        return $this->birthdayRepository->find($id);
    }
}