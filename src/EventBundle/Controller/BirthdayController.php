<?php

namespace EventBundle\Controller;

use EventBundle\Entity\Birthday;
use EventBundle\Entity\Event;
use EventBundle\Entity\User;
use EventBundle\Form\BirthdayType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use EventBundle\Service\BirthdayServiceInterface;

class BirthdayController extends Controller
{
    /**
     * @var BirthdayServiceInterface
     */
    private $birthdayService;

    /**
     * BirthdayController constructor.
     * @param BirthdayServiceInterface $birthdayService
     */
    public function __construct(BirthdayServiceInterface $birthdayService)
    {
        $this->birthdayService = $birthdayService;
    }

    /**
     * @Route("/create_birthday", name="create_birthday", methods={"GET"})
     *
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @return Response
     */
    public function crete()
    {
        $birthdays = $this->birthdaysAuthor();

        $events = $this->eventsAuthor();

        $form = $this->createForm(BirthdayType::class);

        return $this->render('birthdays/create_birthday.html.twig',
            [
                'form' => $form->createView(),
                'birthdays' => $birthdays,
                'events' => $events,
            ]);
    }

    /**
     * @Route("/added_birthday", name="added_birthday", methods={"POST"})
     *
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @return Response
     */
    public function getLastAdded(Request $request)
    {
        $birthday = new Birthday();
        $birthday->setAuthor($this->getUser());
        $form = $this->createForm(BirthdayType::class, $birthday);
        $form->handleRequest($request);
        $this->birthdayService->save($birthday);

        $events = $this->eventsAuthor();
        $birthdays = $this->birthdaysAuthor();

        return $this->render("birthdays/added_birthday.html.twig",
            [
                'birthday' => $this->birthdayService->getLast(),
                'events' => $events,
                'birthdays' => $birthdays,
            ]);

    }

    /**
     * @Route("/my_birthdays", name="my_birthdays")
     *
     * @return Response
     */
    public function myBirthdays()
    {
        $birthdays = $this->birthdaysAuthor();

        $events = $this->eventsAuthor();

        return $this->render("birthdays/my_birthdays.html.twig",
            [
                'birthdays' => $birthdays,
                'events' => $events,
            ]);
    }

    /**
     * @Route("/edit_birthday/{id}", name="edit_birthday")
     *
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function edit(Request $request, int $id)
    {
        $birthday = $this->getBirthdayValid($id);

        $events = $this->eventsAuthor();

        /** @var User $currentUser */
        $currentUser = $this->getUser();

        if (null === $birthday || !$currentUser->isAuthorBirthday($birthday)) {
            return $this->redirectToRoute("my_birthdays");
        }

        $form = $this->createForm(BirthdayType::class, $birthday);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->merge($birthday);
            $em->flush();

            return $this->redirectToRoute("my_birthdays");
        }

        return $this->render("birthdays/edit_birthday.html.twig",
            [
                'form' => $form->createView(),
                'birthday' => $birthday,
                'events' => $events,
            ]);
    }

    /**
     * @Route("/delete_birthday/{id}", name="delete_birthday")
     *
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function delete(Request $request, int $id)
    {
        $birthday = $this->getBirthdayValid($id);

        $events = $this->eventsAuthor();

        /** @var User $currentUser */
        $currentUser = $this->getUser();

        if (null === $birthday || !$currentUser->isAuthorBirthday($birthday)) {
            return $this->redirectToRoute("my_birthdays");
        }

        $form = $this->createForm(BirthdayType::class, $birthday);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($birthday);
            $em->flush();

            return $this->redirectToRoute("my_birthdays");
        }

        return $this->render("birthdays/delete_birthday.html.twig",
            [
                'form' => $form->createView(),
                'birthday' => $birthday,
                'events' => $events,
            ]);
    }

    /**
     * @param int $id
     * @return object|null
     */
    public function getBirthdayValid(int $id)
    {
        $birthday = $this
            ->getDoctrine()
            ->getRepository(Birthday::class)
            ->find($id);
        return $birthday;
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
}
