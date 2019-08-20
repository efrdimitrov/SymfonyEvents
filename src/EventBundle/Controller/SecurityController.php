<?php

namespace EventBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\User\User;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="security_login")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login()
    {
        var_dump($this->login());

//
//        $em = $this->getDoctrine()->getManager();
//        $allUsers = $em->getRepository(User::class)->findAll(array('username' => 'ASC'));
//        if (in_array($user->getUsername(), $allUsers)) {
//            $this->addFlash('exists_user', 'Username already exists!');
//            return $this->redirectToRoute("user_register");
//        }






        return $this->render('security/login.html.twig');
    }
}
