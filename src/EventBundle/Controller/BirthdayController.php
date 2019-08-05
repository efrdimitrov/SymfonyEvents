<?php

namespace EventBundle\Controller;

use EventBundle\Entity\Birthday;
use EventBundle\Form\BirthdayType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class BirthdayController extends Controller
{
    /**
     * @Route("/createBirthday", name="create_birthday")
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

        if($form->isSubmitted()){
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
     * @Route("/allBirthdays", name="all_birthdays")
     * @return Response
     * @param Request $request
     */
    public function getAll(Request $request)
    {
        $birthdays = $this
            ->getDoctrine()
            ->getRepository(Birthday::class)
            ->findAll($request);

        return $this->render('birthdays/all_birthdays.html.twig',
        ['birthdays' => $birthdays]);
    }
}
