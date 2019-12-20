<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            //эмулируем залогиненного пользователя
            if($user->getTarget()){

                $userTarget = $this->getDoctrine()
                    ->getRepository(User::class)
                    ->findByTarget();
                if($userTarget){
                    $userTarget->setTarget(false);
                    $entityManager->persist($userTarget);
                }
            }

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //эмулируем залогиненного пользователя
            if($user->getTarget()){

                $userTarget = $this->getDoctrine()
                    ->getRepository(User::class)
                    ->findByTarget();
                if($userTarget){
                    $userTarget->setTarget(false);
                    $this->getDoctrine()->getManager()->persist($userTarget);
                }
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }

    /**
     * Функцинал для добавления пользователя в избранные срабатывает только для user.target = 1
     * @Route("/faveUser/{id}", name="user_fave_user", methods={"GET"})
     */
    public function faveUser(User $user)
    {
        $userTarget = $this->getDoctrine()
            ->getRepository(User::class)
            ->findByTarget();

        if($userTarget->getFavoriteUsers()->filter(function(User $u) use ($user){
            return $u->getId() == $user->getId();
        })->isEmpty()){
            $userTarget->addFavoriteUser($user);
        } else {
            $userTarget->removeFavoriteUser($user);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($userTarget);
        $entityManager->flush();
        return $this->redirectToRoute('user_show', ['id' => $userTarget->getId()]);
    }

    /**
     *  Функцинал для добавления продукта в избранные срабатывает только для user.target = 1
     * @Route("/faveProduct/{id}", name="user_fave_product", methods={"GET"})
     */
    public function faveProduct(Product $product)
    {
        $userTarget = $this->getDoctrine()
            ->getRepository(User::class)
            ->findByTarget();

        if($userTarget->getFavoriteProducts()->filter(function(Product $p) use ($product){
            return $p->getId() == $product->getId();
        })->isEmpty()){
            $userTarget->addFavoriteProduct($product);
        } else {
            $userTarget->removeFavoriteProduct($product);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($userTarget);
        $entityManager->flush();
        return $this->redirectToRoute('user_show', ['id' => $userTarget->getId()]);
    }
}
