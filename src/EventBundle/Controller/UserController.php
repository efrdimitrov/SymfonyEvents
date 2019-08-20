<?php

namespace EventBundle\Controller;

use EventBundle\Entity\User;
use EventBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class UserController extends Controller
{
    /**
     * @Route("/users/register", name="user_register", methods={"GET"})
     *
     * @return Response
     */
    public function register()
    {
        return $this->render('users/register.html.twig', ['form' => $this->createForm(UserType::class)->createView()]);
    }

    /**
     * @Route("/users/register/process", name="user_register_process", methods={"POST"})
     *
     * @param Request $request
     * @return Response
     */
    public function registerProcess(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        $allUsers = $em->getRepository(User::class)->findAll(array('username' => 'ASC'));
        if (in_array($user->getUsername(), $allUsers)) {
            $this->addFlash('exists_user', 'Username already exists!');
            return $this->redirectToRoute("user_register");
        }

        $allEmails = $em->getRepository(User::class)->findAll(array('email' => 'ASC'));
        if (in_array($user->getEmail(), $allEmails)) {
            $this->addFlash('exists_email', 'This email already exists!');
            return $this->redirectToRoute("user_register");
        }

        $this->passwordHash($user);

        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash('success', 'Account successfully created!');

        return $this->render("security/login.html.twig",
            [
                'user' => $user,
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

        if ($form->isSubmitted()) {
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
     * @Route("/delete_profile/{id}", name="delete_profile")
     *
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function delete(Request $request, User $user)
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();

            return $this->redirectToRoute("security_logout");
        }

        return $this->render("users/delete_user.html.twig",
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
        $this->addFlash('info', 'See you soon');
        throw new \Exception("Logout failed!");
    }

    /**
     * @param User $user
     */
    public function passwordHash(User $user)
    {
        $passwordHash = $this
            ->get('security.password_encoder')
            ->encodePassword($user, $user->getPassword());
        $user->setPassword($passwordHash);
    }

}
