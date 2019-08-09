<?php

namespace EventBundle\Controller;

use EventBundle\Entity\Category;
use EventBundle\Entity\Event;
use EventBundle\Form\EventType;
use EventBundle\Service\EventServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

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
     * @Route("/create_event", name="create_event")
     *
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @return Response
     */
    public function create()
    {

        $categoryRepository = $this
            ->getDoctrine()
            ->getRepository(Category::class);

        $categories = $categoryRepository->findAll();

        return $this->render("events/create_event.html.twig",
                ['categories' => $categories]);
    }

    /**
     * @Route("/added_event", name="added_event")
     *
     * @param Request $request
     * @return Response
     */
    public function createProcess(Request $request)
    {
        $event = new Event();
        $event->setAuthor($this->getUser());
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);
        $this->eventService->save($event);

        return $this->render("events/added_event.html.twig",
            ['event' => $event]);
    }

    /**
     * @Route("/my_events", name="my_events")
     *
     * @return Response
     */
    public function myEvent()
    {
        $events = $this
            ->getDoctrine()
            ->getRepository(Event::class)
            ->findAll();

        return $this->render("events/my_events.html.twig",
            ['events' => $events]);
    }

    /**
     * @Route("/edit_event/{id}", name="edit_event")
     *
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @param Event $event
     * @return Response
     */
    public function edit(Request $request, Event $event)
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            $em = $this->getDoctrine()->getManager();
            $em->merge($event);
            $em->flush();

            return $this->redirectToRoute("all_events");
        }

        $categoryRepository = $this
            ->getDoctrine()
            ->getRepository(Category::class);

        $categories = $categoryRepository->findAll();

        return $this->render('events/edit_event.html.twig',
            [
                'form' => $form->createView(),
                'event' => $event,
                'categories' => $categories
            ]);
    }

    /**
     * @Route("/delete/{id}", name="delete_event")
     *
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @param Event $event
     * @return Response
     */
    public function delete(Request $request, Event $event)
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        $categoryRepository = $this
            ->getDoctrine()
            ->getRepository(Category::class);

        $categories = $categoryRepository->findAll();

        return $this->render('events/edit_event.html.twig',
            [
                'form' => $form->createView(),
                'event' => $event,
                'categories' => $categories
            ]);
    }

}
