<?php


namespace EventBundle\Service;


use EventBundle\Entity\Birthday;

interface BirthdayServiceInterface
{

    public function getAll():array;

    public function get(int $id): Birthday;

    public function getLast() : Birthday;

    public function save(Birthday $birthday);

}