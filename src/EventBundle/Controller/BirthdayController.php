<?php

namespace EventBundle\Controller;

use EventBundle\Entity\Birthday;
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
        $form = $this->createForm(BirthdayType::class);

        return $this->render('birthdays/create_birthday.html.twig',
            ['form' => $form->createView()]);
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

        return $this->render( "birthdays/added_birthday.html.twig",
            [
                'birthday' => $this->birthdayService->getLast()
            ]);

    }

    /**
     * @Route("/my_birthdays", name="my_birthdays")
     *
     * @return Response
     */
    public function myBirthdays()
    {
        $birthdays = $this
            ->getDoctrine()
            ->getRepository(Birthday::class)
            ->findBy(['author' => $this->getUser()]);

        return $this->render("birthdays/my_birthdays.html.twig",
            ['birthdays' => $birthdays]);
    }

    /**
     * @Route("/edit_birthday/{id}", name="edit_birthday")
     *
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @param Birthday $birthday
     * @return Response
     */
    public function edit(Request $request, Birthday $birthday)
    {
        $form = $this->createForm(BirthdayType::class, $birthday);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            $em = $this->getDoctrine()->getManager();
            $em->merge($birthday);
            $em->flush();

            return $this->redirectToRoute("my_birthdays");
        }

        return $this->render("birthdays/edit_birthday.html.twig",
            [
                'form' => $form->createView(),
                'birthday' => $birthday
            ]);
    }

    /**
     * @Route("/delete_birthday/{id}", name="delete_birthday")
     *
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @param Birthday $birthday
     * @return Response
     */
    public function delete(Request $request, Birthday $birthday)
    {
        $form = $this->createForm(BirthdayType::class, $birthday);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            $em = $this->getDoctrine()->getManager();
            $em->remove($birthday);
            $em->flush();

            return $this->redirectToRoute("my_birthdays");
        }

        return $this->render("birthdays/delete_birthday.html.twig",
            [
                'form' => $form->createView(),
                'birthday' => $birthday
            ]);
    }
}
