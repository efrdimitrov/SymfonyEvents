<?php


namespace EventBundle\Service\Birthday;


use EventBundle\Entity\Birthday;

interface BirthdayServiceInterface
{

    public function getAll():array;

    public function get(int $id): Birthday;

    public function getLast() : Birthday;

    public function save(Birthday $birthday);

}