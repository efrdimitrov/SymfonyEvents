<?php

namespace EventBundle\Controller;

use EventBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends Controller
{
    /**
     * @Route("/create_event", name="create_event")
     */
    public function getAll(Category $category)
    { echo 'CategoryController';

        return $this->render("events/create_event.html.twig",
            ['categories' => $category]);
    }
}
