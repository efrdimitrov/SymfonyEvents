<?php


namespace EventBundle\Service\Event;


use EventBundle\Entity\Event;

interface EventServiceInterface
{
    /**
     * @return Event[]
     */
    public function getAll(): array;

    public function get(int $id): Event;

    public function getLast() : Event;

    public function save(Event $event);
}