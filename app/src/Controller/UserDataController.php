<?php

namespace App\Controller;

use App\Entity\DiaryEntry;
use App\Entity\UserData;
use App\Form\DiaryEntryType;
use App\Form\UserDataType;
use App\Repository\DiaryEntryRepository;
use App\Repository\UserDataRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TaskController.
 *
 * @Route("/user_data")
 */
class UserDataController extends AbstractController
{
    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\UserDataRepository        $repository Repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator  Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     name="user_data_index",
     * )
     */
    public function index(Request $request, UserDataRepository $repository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $repository->queryByUser($this->getUser()),
            $request->query->getInt('page', 1),
            UserData::NUMBER_OF_ITEMS
        );

        return $this->render(
            'user_data/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * New action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\UserDataRepository        $repository UserData repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/new",
     *     methods={"GET", "POST"},
     *     name="user_data_new",
     * )
     */
    public function new(Request $request, UserDataRepository $repository): Response
    {
        $userData = new UserData();
        $form = $this->createForm(UserDataType::class, $userData);
        $form->handleRequest($request);

        $userData->setUser($this->getUser());
        $userData->setDate(new \DateTime());

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($userData);

            $this->addFlash('success', 'message.created_successfully');

            return $this->redirectToRoute('user_data_index');
        }

        return $this->render(
            'user_data/new.html.twig',
            ['form' => $form->createView()]
        );
    }

}
