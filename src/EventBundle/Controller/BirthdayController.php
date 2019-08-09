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
     * @Route("/create_birthday", name="create_birthday")
     *
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @return Response
     */
    public function crete(Request $request)
    {
        $birthday = new Birthday();
        $form = $this->createForm(BirthdayType::class, $birthday);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $birthday->setAuthor($this->getUser());
            $em = $this
                ->getDoctrine()
                ->getManager();
            $em->persist($birthday);
            $em->flush();

            return $this->redirectToRoute("all_birthdays");
        }

        return $this->render('birthdays/create_birthday.html.twig',
            ['form' => $form->createView()]);
    }

    /**
     * @Route("/added_birthday", name="added_birthday")
     *
     * @return Response
     * @param Request $request
     */
    public function createProcess(Request $request)
    {

        $birthday = new Birthday();
        $birthday->setAuthor($this->getUser());
        $form = $this->createForm(BirthdayType::class, $birthday);
        $form->handleRequest($request);
        $this->birthdayService->save($birthday);

        $birthdays = $this->getDoctrine()->getRepository(Birthday::class)->findAll();

        return $this->render( "birthdays/added_birthday.html.twig",
            ['birthdays' => $birthdays]);

    }

    /**
     * @Route("/my_birthdays", name="my_birthdays")
     *
     * @return Response
     */
    public function getAll()
    {
        $birthdays = $this
            ->getDoctrine()
            ->getRepository(Birthday::class)
            ->findAll();

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

            return $this->redirectToRoute("all_birthdays");
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

            return $this->redirectToRoute("all_birthdays");
        }

        return $this->render("birthdays/delete_birthday.html.twig",
            [
                'form' => $form->createView(),
                'birthday' => $birthday
            ]);
    }
}
