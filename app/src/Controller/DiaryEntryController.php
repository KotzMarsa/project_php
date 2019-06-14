<?php
/**
 * DiaryEntry controller.
 */

namespace App\Controller;

use App\Entity\Category;
use App\Entity\DiaryEntry;
use App\Entity\Product;
use App\Entity\Task;
use App\Form\DiaryEntryType;
use App\Form\ProductType;
use App\Form\TaskType;
use App\Repository\DiaryEntryRepository;
use App\Repository\ProductRepository;
use App\Repository\TaskRepository;
use Doctrine\DBAL\Types\TextType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DiaryEntryController.
 *
 * @Route("/diary_entry")
 */
class DiaryEntryController extends AbstractController
{
    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\DiaryEntryRepository      $repository DiaryEntry repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator  Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route(
     *     "/",
     *     name="diary_entry_index",
     * )
     */
    public function index(Request $request, DiaryEntryRepository $repository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $repository->queryAll(),
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
     * @param DiaryEntry $diaryEntry
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     name="diary_entry_view",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function view(DiaryEntry $diaryEntry): Response
    {
        return $this->render(
            'diary_entry/view.html.twig',
            ['diary_entry' => $diaryEntry]
        );
    }

    /**
     * New action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\DiaryEntryRepository      $repository DiaryEntry repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/new",
     *     methods={"GET", "POST"},
     *     name="diary_entry_new",
     * )
     */
    public function new(Request $request, DiaryEntryRepository $repository): Response
    {
        $entry = new DiaryEntry();
        $form = $this->createForm(DiaryEntryType::class, $entry);
        $form->handleRequest($request);
        $entry->setDate(new \DateTime());

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
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\DiaryEntry                    $diaryEntry DiaryEntry entity
     * @param \App\Repository\DiaryEntryRepository      $repository DiaryEntry repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="diary_entry_edit",
     * )
     */
    public function edit(Request $request, DiaryEntry $diaryEntry, DiaryEntryRepository $repository): Response
    {
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
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\DiaryEntry                    $diaryEntry DiaryEntry entity
     * @param \App\Repository\DiaryEntryRepository      $repository DiaryEntry repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/delete",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="diary_entry_delete",
     * )
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
