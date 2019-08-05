<?php


namespace EventBundle\Service;


use EventBundle\Entity\Event;

interface EventServiceInterface
{
    /**
     * @return Event[]
     */
    public function getAll(): array;

    public function get(int $id): Event;

    public function save(Event $event);
}