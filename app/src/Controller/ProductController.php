<?php
/**
 * Product controller.
 */

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProductController.
 *
 * @Route("/product")
 */
class ProductController extends AbstractController
{
    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\ProductRepository         $repository Repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator  Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     name="product_index",
     * )
     */
    public function index(Request $request, ProductRepository $repository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $repository->queryAll(),
            $request->query->getInt('page', 1),
            Product::NUMBER_OF_ITEMS
        );

        return $this->render(
            'product/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * View action.
     *
     * @param \App\Entity\Product $Product Product entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     name="product_view",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function view(Product $product): Response
    {
        return $this->render(
            'product/view.html.twig',
            ['product' => $product]
        );
    }

    /**
     * New action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\ProductRepository         $repository Product repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
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
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\Product                       $product    Product entity
     * @param \App\Repository\ProductRepository         $repository Product repository
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
     *     name="product_delete",
     * )
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
}
