<?php

namespace EventBundle\Controller;

use EventBundle\Entity\Birthday;
use EventBundle\Entity\Category;
use EventBundle\Entity\Event;
use EventBundle\Entity\User;
use EventBundle\Form\EventType;
use EventBundle\Service\Event\EventServiceInterface;
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
        $events = $this->eventsAuthor();
        $birthdays = $this->birthdaysAuthor();

        $categoryRepository = $this
            ->getDoctrine()
            ->getRepository(Category::class);
        $categories = $categoryRepository->findAll();
        return $this->render("events/create_event.html.twig",
            [
                'categories' => $categories,
                'events' => $events,
                'birthdays' => $birthdays,
            ]);
    }

    /**
     * @Route("/added_event", name="added_event")
     *
     * @param Request $request
     * @return Response
     */

    public function getLastAdded(Request $request)
    {
        $event = new Event();
        $event->setAuthor($this->getUser());
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);
        $this->eventService->save($event);

        $events = $this->eventsAuthor();
        $birthdays = $this->birthdaysAuthor();

        return $this->render('events/added_event.html.twig',
            [
                'event' => $this->eventService->getLast(),
                'events' => $events,
                'birthdays' => $birthdays,
            ]);
    }

    /**
     * @Route("/my_events", name="my_events")
     *
     * @return Response
     */
    public function myEvents()
    {
        $events = $this->eventsAuthor();
        $birthdays = $this->birthdaysAuthor();

        if (count($events) == 0) {
            return $this->redirectToRoute('create_event');
        }

        return $this->render("events/my_events.html.twig",
            [
                'events' => $events,
                'birthdays' => $birthdays,
            ]);
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
        $events = $this->eventsAuthor();
        $birthdays = $this->birthdaysAuthor();

        /** @var User $currentUser */
        $currentUser = $this->getUser();

        if (null === $event || !$currentUser->isAuthorEvent($event)) {
            return $this->redirectToRoute("my_events");
        }

        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->merge($event);
            $em->flush();
            return $this->redirectToRoute("my_events");
        }
        $categoryRepository = $this
            ->getDoctrine()
            ->getRepository(Category::class);
        $categories = $categoryRepository->findAll();
        return $this->render('events/edit_event.html.twig',
            [
                'form' => $form->createView(),
                'event' => $event,
                'events' => $events,
                'birthdays' => $birthdays,
                'categories' => $categories
            ]);
    }

    /**
     * @Route("/delete_event/{id}", name="delete_event")
     *
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param $id
     * @return Response
     */
    public function delete(int $id)
    {
        $event = $this->getEventValid($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($event);
        $em->flush();

        return $this->redirectToRoute("my_events");
    }

    /**
     * @param int $id
     * @return object|null
     */
    public function getEventValid(int $id)
    {
        $event = $this
            ->getDoctrine()
            ->getRepository(Event::class)
            ->find($id);
        return $event;
    }

    /**
     * @return array
     */
    public function eventsAuthor(): array
    {
        $events = $this
            ->getDoctrine()
            ->getRepository(Event::class)
            ->findBy(['author' => $this->getUser()]);

        return $events;
    }

    /**
     * @return array
     */
    public function birthdaysAuthor(): array
    {
        $birthdays = $this
            ->getDoctrine()
            ->getRepository(Birthday::class)
            ->findBy(['author' => $this->getUser()]);
        return $birthdays;
    }
}