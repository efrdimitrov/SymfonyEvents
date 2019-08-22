<?php

namespace EventBundle\Controller;

use EventBundle\Entity\Birthday;
use EventBundle\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends Controller
{

    /**
     * @Route("/", name="event_index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {

        $events = $this
            ->getDoctrine()
            ->getRepository(Event::class)
            ->findBy(['author' => $this->getUser()]);

        $birthdays = $this
            ->getDoctrine()
            ->getRepository(Birthday::class)
            ->findBy(['author' => $this->getUser()]);

        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'events' => $events,
            'birthdays' => $birthdays,
        ]);
    }



}
