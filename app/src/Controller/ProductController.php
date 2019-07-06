<?php
/**
 * Product controller.
 */

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
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
 * Class ProductController.
 *
 * @Route("/product")
 *
 * @IsGranted("ROLE_USER")
 */
class ProductController extends AbstractController
{
    /**
     * Index action.
     *
     * @param Request            $request    HTTP request
     * @param ProductRepository  $repository Repository
     * @param PaginatorInterface $paginator  Paginator
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/",
     *     name="product_index",
     * )
     */
    public function index(Request $request, ProductRepository $repository, PaginatorInterface $paginator): Response
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
            'product/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * View action.
     *
     * @param Request            $request
     * @param ProductRepository  $repository
     * @param PaginatorInterface $paginator
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     name="product_category",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function view(Request $request, ProductRepository $repository, PaginatorInterface $paginator): Response
    {
        $categoryId = $request->get('id');
        if ($this->isGranted('ROLE_ADMIN')) {
            $pagination = $paginator->paginate(
                $repository->queryByCategory($categoryId),
                $request->query->getInt('page', 1),
                Product::NUMBER_OF_ITEMS
            );
        } else {
            $pagination = $paginator->paginate(
                $repository->queryByCategoryAndUser($categoryId, $this->getUser()),
                $request->query->getInt('page', 1),
                Product::NUMBER_OF_ITEMS
            );
        }

        return $this->render(
            'product/view.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * New action.
     *
     * @param Request           $request    HTTP request
     * @param ProductRepository $repository Product repository
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/new",
     *     methods={"GET", "POST"},
     *     name="product_new",
     * )
     */
    public function new(Request $request, ProductRepository $repository): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        $product->setIsAccepted(0);
        $product->setUser($this->getUser());
        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($product);

            $this->addFlash('success', 'message.created_successfully');

            return $this->redirectToRoute('product_index');
        }

        return $this->render(
            'product/new.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Delete action.
     *
     * @param Request           $request    HTTP request
     * @param Product           $product    Product entity
     * @param ProductRepository $repository Product repository
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
     *     name="product_delete",
     * )
     *
     * @IsGranted("ROLE_ADMIN",)
     */
    public function delete(Request $request, Product $product, ProductRepository $repository): Response
    {
        $form = $this->createForm(FormType::class, $product, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->delete($product);
            $this->addFlash('success', 'message.deleted_successfully');

            return $this->redirectToRoute('product_index');
        }

        return $this->render(
            'product/delete.html.twig',
            [
                'form' => $form->createView(),
                'product' => $product,
            ]
        );
    }

    /**
     * Accept action.
     *
     * @param Request           $request    HTTP request
     * @param Product           $product    Product entity
     * @param ProductRepository $repository Product repository
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
     *     name="product_accept",
     * )
     * @IsGranted("ROLE_ADMIN",)
     */
    public function accept(Request $request, Product $product, ProductRepository $repository): Response
    {
        $form = $this->createForm(ProductType::class, $product, ['method' => 'PUT']);
        $form->handleRequest($request);
        $product->setIsAccepted(1);
        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($product);

            $this->addFlash('success', 'message.updated_successfully');

            return $this->redirectToRoute('product_index');
        }

        return $this->render(
            'product/accept.html.twig',
            [
                'form' => $form->createView(),
                'product' => $product,
            ]
        );
    }
}
