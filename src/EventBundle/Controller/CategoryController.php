<?php

namespace EventBundle\Controller;

use EventBundle\Entity\Category;
use EventBundle\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends Controller
{

//    /**
//     * @Route("/allEvents", name="all_events")
//     *
//     * @param Request $request
//     * @return Response
//     */
//    public function getAll(Request $request)
//    {
//        $categories = $this
//            ->getDoctrine()
//            ->getRepository(Category::class)
//            ->findAll($request);
//
//        return $this->render("events/all_events.html.twig",
//            ['categories' => $categories]);
//    }

}
