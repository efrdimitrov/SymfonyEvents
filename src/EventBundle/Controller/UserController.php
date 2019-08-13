<?php

namespace EventBundle\Controller;

use EventBundle\Entity\User;
use EventBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class UserController extends Controller
{

    /**
     * @Route("register", name="user_register")
     * @param Request $request
     * @return Response
     */
    public function register(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $this->passwordHash($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute("security_login");
        }

        return $this->render('users/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/profile", name="user_profile")
     */
    public function profile()
    {

        $userRepository = $this->getDoctrine()
            ->getRepository(User::class);
        $currentUser = $userRepository->find($this->getUser());

        return $this->render("users/profile.html.twig",
            ['user' => $currentUser]);
    }

    /**
     * @Route("/edit_profile/{id}", name="edit_profile")
     *
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function edit(Request $request, User $user)
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            $this->passwordHash($user);

            $em = $this->getDoctrine()->getManager();
            $em->merge($user);
            $em->flush();

            return $this->redirectToRoute("user_profile");
        }

        return $this->render("users/edit_profile.html.twig",
            [
                'form' => $form->createView(),
                'user' => $user
            ]);
    }

    /**
     * @Route("/logout", name="security_logout")
     * @throws \Exception
     */
    public function logout()
    {
        throw new \Exception("Logout failed!");
    }

    /**
     * @param User $user
     * @return bool
     */
    public function passwordHash(User $user): bool
    {
        $passwordHash =
            $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPassword());
        $user->setPassword($passwordHash);
    }
}
