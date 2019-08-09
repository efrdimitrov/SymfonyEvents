<?php


namespace EventBundle\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use EventBundle\Entity\Category;
use Doctrine\ORM\Mapping\ClassMetadata;

class CategoryRepository extends EntityRepository
{
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, new ClassMetadata(Category::class));
    }
}