<?php
/**
 * Created by PhpStorm.
 * User: mantas
 * Date: 18.12.6
 * Time: 13.48
 */

namespace AppBundle\Controller;


use AppBundle\Entity\User;
use AppBundle\Form\RegistrationType;
use AppBundle\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{
    /**
     * @Route("/user/list", name="user.list")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listUsers()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $users = $entityManager->getRepository(User::class)->findAll();

        return $this->render('user/list.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/user/add", name="user.add")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addUser(Request $request)
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $roles = $form->get('roles')->getData();
            $user->setRoles(array($roles));
            $user->setEnabled(true);
            $user->setPlainPassword($form->get('password')->getData());
            $userManager = $this->get('fos_user.user_manager');
            $userManager->updateUser($user);

            $this->addFlash(
                'form_user',
                'Vartotojas buvo sėkmingai sukurtas'
            );

            return $this->redirectToRoute('user.list');
        }

        return $this->render('user/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user/edit/{id}", name="user.edit")
     * @param Request $request
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editUser(Request $request, User $user)
    {

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $roles = $form->get('roles')->getData();
            $user->setRoles(array($roles));
            $userManager = $this->get('fos_user.user_manager');
            $userManager->updateUser($user);

            $this->addFlash(
                'form_user',
                'Vartotojas buvo sėkmingai pakeistas'
            );

            return $this->redirectToRoute('user.list');
        }

        return $this->render('user/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user/remove/{id}", name="user.remove")
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function removeUser(User $user)
    {
        $userManager = $this->get('fos_user.user_manager');
        $userManager->deleteUser($user);

        $this->addFlash(
            'form_user',
            'Vartotojas buvo sėkmingai pašalintas'
        );

        return $this->redirectToRoute('user.list');
    }
}