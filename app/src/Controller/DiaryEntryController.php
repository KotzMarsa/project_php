<?php
/**
 * DiaryEntry controller.
 */

namespace App\Controller;

use App\Entity\DiaryEntry;
use App\Repository\DiaryEntryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @param \App\Repository\DiaryEntryRepository        $repository DiaryEntry repository
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
     * @param \App\Entity\DiaryEntry $diary_entry DiaryEntry entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     name="diary_entry_view",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function view(DiaryEntry $diary_entry): Response
    {
        return $this->render(
            'diary_entry/view.html.twig',
            ['diary_entry' => $diary_entry]
        );
    }
}