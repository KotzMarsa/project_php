<?php
/**
 * UserData controller.
 */

namespace App\Controller;

use App\Entity\UserData;
use App\Form\UserDataType;
use App\Repository\UserDataRepository;
use DateTime;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserDataController.
 *
 * @Route("/user_data")
 *
 * @IsGranted("ROLE_USER")
 */
class UserDataController extends AbstractController
{
    /**
     * Index action.
     *
     * @param Request            $request    HTTP request
     * @param UserDataRepository $repository Repository
     * @param PaginatorInterface $paginator  Paginator
     *
     * @return Response HTTP response
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
     * @param Request            $request    HTTP request
     * @param UserDataRepository $repository UserData repository
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
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
        $userData->setDate(new DateTime());

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($userData);

            $this->addFlash('success', 'message.created_successfully');

            return $this->redirectToRoute('user_data_index');
        }

        return $this->render(
            'user_data/new.html.twig',
            ['form' => $form->createView(),
                'user' => $userData, ]
        );
    }
}
