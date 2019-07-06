<?php
/**
 * User controller.
 */

namespace App\Controller;

use App\Entity\Product;
use App\Entity\User;
use App\Form\UserNameType;
use App\Form\UserPasswordType;
use App\Repository\UserRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\FormType;

/**
 * Class TaskController.
 *
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param Request                                   $request    HTTP request
     * @param User                                      $user
     * @param UserRepository                            $repository User repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     name="user_index",
     * )
     */
    public function index(): Response
    {
        $user = $this->getUser();

        return $this->render(
            'user/index.html.twig',
            ['user' => $user]
        );
    }

    /**
     * ChangePassword action.
     *
     * @param Request                      $request         HTTP request
     * @param User                         $user
     * @param UserRepository               $repository      User repository
     * @param UserPasswordEncoderInterface $passwordEncoder
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/{id}/password",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="user_password",
     * )
     *
     * @IsGranted(
     *     "MANAGE",
     *     subject="user",
     * )
     */
    public function changePassword(Request $request, User $user, UserRepository $repository, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $form = $this->createForm(UserPasswordType::class, $user, ['method' => 'PUT']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $repository->saveUser($user);
            $this->addFlash('success', 'message.updated_successfully');

            return $this->redirectToRoute('user_index');
        }

        return $this->render(
            'user/password.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }

    /**
     * ChangeName action.
     *
     * @param Request        $request    HTTP request
     * @param User           $user
     * @param UserRepository $repository User repository
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/{id}/name",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="user_name",
     * )
     *
     * @IsGranted(
     *     "MANAGE",
     *     subject="user",
     * )
     */
    public function changeName(Request $request, User $user, UserRepository $repository, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $form = $this->createForm(UserNameType::class, $user, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->saveUser($user);

            $this->addFlash('success', 'message.updated_successfully');

            return $this->redirectToRoute('user_index');
        }

        return $this->render(
            'user/name.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }

    /**
     * List action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\ProductRepository         $repository Repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator  Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/list",
     *     name="user_list",
     * )
     *
     * @IsGranted(
     *     "ROLE_ADMIN"
     * )
     */
    public function list(Request $request, UserRepository $repository, PaginatorInterface $paginator): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $pagination = $paginator->paginate(
                $repository->queryAll(),
                $request->query->getInt('page', 1),
                Product::NUMBER_OF_ITEMS
            );
        } else {
            $pagination = $paginator->paginate(
                $repository->queryByUser($this->getUser()),
                $request->query->getInt('page', 1),
                Product::NUMBER_OF_ITEMS
            );
        }

        return $this->render(
            'user/list.html.twig',
            ['pagination' => $pagination]
        );
    }
    /**
     * Grant admin action.
     *
     * @param Request        $request    HTTP request
     * @param User           $user       User entity
     * @param UserRepository $repository User repository
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/{id}/promote",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="user_promote",
     * )
     *
     * @IsGranted(
     *     "ROLE_ADMIN"
     * )
     */
    public function promote(Request $request, User $user, UserRepository $repository): Response
    {
        $form = $this->createForm(FormType::class, $user, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $roles = $user->getRoles();
            if (isset($roles[1])) {
                $user->setRoles(['ROLE_USER']);
            } else {
                $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
            }
            $repository->saveUser($user);

            $this->addFlash('success', 'message.updated_successfully');

            return $this->redirectToRoute('user_list');
        }

        return $this->render(
            'user/promote.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }
}
