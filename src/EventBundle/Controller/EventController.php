<?php

namespace EventBundle\Controller;

use EventBundle\Entity\Event;
use EventBundle\Form\EventType;
use EventBundle\Service\EventServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class EventController extends Controller
{
    /**
     * @var EventServiceInterface
     */
    private $eventService;

    /**
     * EventController constructor.
     * @param EventServiceInterface $eventService
     */
    public function __construct(EventServiceInterface $eventService)
    {
        $this->eventService = $eventService;
    }

    /**
     * @Route("/all_events", name="all_events")
     */
    public function homeEvent()
    {
        /**
         * @Route("/all_events", name="all_events"
         */
        return $this->render("events/all_events.html.twig",
            ['events' => $this->eventService->getAll()]);

    }


//    /**
//     * @Route("/profile", name="user_profile")
//     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
//     * @return Response
//     */
//    public function create()
//    { echo 'eventCon';
//        return $this->render('events/create_event.html.twig');
//    }

    /**
     * @Route("/profile", name="user_profile")
     */
    public function getEvent()
    { echo 'eventCon';
        $eventRepository = $this->getDoctrine()
            ->getRepository(Event::class);

        $events = $eventRepository->find($this->getUser());

        return $this->render("users/profile.html.twig",
            ['events' => $events]);
    }

    /**
     * @Route("/all_events", name="all_events")
     * @return Response
     */
    public function getAll()
    {
        return $this->render('events/all_events.html.twig');
    }

//    /**
//     * @Route("", name="create_event")
//     *
//     * @return Response
//     */
//    public function create()
//    {
//        return $this->render('events/create_event.html.twig',
//            ['form' => $this->createForm(EventType::class)->createView()]);
//
//    }

    /**
     * @Route("/all_events", name="all_events_process")
     * @Method({"POST"})
     *
     * @param Request $request
     * @return Response
     */
    public function createProcess(Request $request)
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);
        $this->eventService->save($event);

        return $this->redirectToRoute('all_events');
    }
}
