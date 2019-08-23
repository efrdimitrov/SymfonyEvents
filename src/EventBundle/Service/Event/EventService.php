<?php


namespace EventBundle\Service\Event;


use Doctrine\ORM\EntityManagerInterface;
use EventBundle\Entity\Event;
use EventBundle\Repository\EventRepository;

class EventService implements EventServiceInterface
{
    /**
     * @var EventRepository
     */
    private $eventRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * EventService constructor.
     * @param EventRepository $eventRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EventRepository $eventRepository,
                                EntityManagerInterface $entityManager)
    {
        $this->eventRepository = $eventRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @return Event[]
     */
    public function getAll(): array
    {
        return $this->eventRepository->findAll();
    }

    public function get(int $id): Event
    {
        // TODO: Implement get() method.
    }

    public function getLast(): Event
    {
        return $this->eventRepository->findBy([], ['id' => 'DESC'])[0];
    }

    public function save(Event $event)
    {
        $this->entityManager->persist($event);
        $this->entityManager->flush();
    }

}