<?php
/**
 * DiaryEntry controller.
 */

namespace App\Controller;

use App\Entity\DiaryEntry;
use App\Form\DiaryEntryType;
use App\Repository\DiaryEntryRepository;
use DateTime;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DiaryEntryController.
 *
 * @Route("/diary_entry")
 *
 * @IsGranted("ROLE_USER")
 */
class DiaryEntryController extends AbstractController
{
    /**
     * Index action.
     *
     * @param Request              $request    HTTP request
     * @param DiaryEntryRepository $repository DiaryEntry repository
     * @param PaginatorInterface   $paginator  Paginator
     *
     * @return Response
     *
     * @Route(
     *     "/",
     *     name="diary_entry_index",
     * )
     * @IsGranted("ROLE_USER",)
     */
    public function index(Request $request, DiaryEntryRepository $repository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $repository->queryByUser($this->getUser()),
            $request->query->getInt('page', 1),
            DiaryEntry::NUMBER_OF_ITEMS
        );

        return $this->render(
            'diary_entry/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * View action.
     *
     * @param Request              $request    HTTP request
     * @param DiaryEntryRepository $repository DiaryEntry repository
     * @param PaginatorInterface   $paginator  Paginator
     *
     * @param int                  $sub
     *
     * @return Response
     *
     * @Route(
     *     "/{sub}/view",
     *     methods={"GET"},
     *     name="diary_entry_view",
     *     requirements={"sub": "[0-9]\d*"},
     * )
     * @IsGranted("ROLE_USER",)
     */
    public function view(Request $request, DiaryEntryRepository $repository, PaginatorInterface $paginator, int $sub): Response
    {
        $pagination = $paginator->paginate(
            $repository->queryByPastDate($this->getUser(), $sub),
            $request->query->getInt('page', 1),
            DiaryEntry::NUMBER_OF_ITEMS
        );

//        $form = $this->createForm(SearchDateType::class, $diaryEntry, $sub, ['method' => 'PUT']);
//        $form->handleRequest($request);
//
//        return $this->render(
//            'diary_entry/edit.html.twig',
//            [
//                'form' => $form->createView(),
//            ]
//        );

        return $this->render(
            'diary_entry/view.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * New action.
     *
     * @param Request              $request    HTTP request
     * @param DiaryEntryRepository $repository DiaryEntry repository
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/new",
     *     methods={"GET", "POST"},
     *     name="diary_entry_new",
     * )
     * @IsGranted("ROLE_USER",)
     */
    public function new(Request $request, DiaryEntryRepository $repository): Response
    {
        $entry = new DiaryEntry();
        $form = $this->createForm(DiaryEntryType::class, $entry);
        $form->handleRequest($request);
        $entry->setDate(new DateTime());
        $entry->setUser($this->getUser());

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($entry);

            $this->addFlash('success', 'message.created_successfully');

            return $this->redirectToRoute('diary_entry_index');
        }

        return $this->render(
            'diary_entry/new.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param Request              $request    HTTP request
     * @param DiaryEntry           $diaryEntry DiaryEntry entity
     * @param DiaryEntryRepository $repository DiaryEntry repository
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="diary_entry_edit",
     * )
     * @IsGranted("ROLE_USER",)
     */
    public function edit(Request $request, DiaryEntry $diaryEntry, DiaryEntryRepository $repository): Response
    {
        if ($diaryEntry->getUser() !== $this->getUser()) {
            $this->addFlash('warning', 'message.item_not_found');

            return $this->redirectToRoute('diary_entry_index');
        }

        $form = $this->createForm(DiaryEntryType::class, $diaryEntry, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($diaryEntry);

            $this->addFlash('success', 'message.updated_successfully');

            return $this->redirectToRoute('diary_entry_index');
        }

        return $this->render(
            'diary_entry/edit.html.twig',
            [
                'form' => $form->createView(),
                'diary_entry' => $diaryEntry,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param Request              $request    HTTP request
     * @param DiaryEntry           $diaryEntry DiaryEntry entity
     * @param DiaryEntryRepository $repository DiaryEntry repository
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/{id}/delete",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="diary_entry_delete",
     * )
     * @IsGranted("ROLE_USER",)
     */
    public function delete(Request $request, DiaryEntry $diaryEntry, DiaryEntryRepository $repository): Response
    {
        $form = $this->createForm(FormType::class, $diaryEntry, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->delete($diaryEntry);
            $this->addFlash('success', 'message.deleted_successfully');

            return $this->redirectToRoute('diary_entry_index');
        }

        return $this->render(
            'diary_entry/delete.html.twig',
            [
                'form' => $form->createView(),
                'diary_entry' => $diaryEntry,
            ]
        );
    }
}
