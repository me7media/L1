<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Services\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/", name="products")
     */
    public function index(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Product::class);
        $products = $repository->findAll();

        return $this->render('product/show.html.twig', [
            'products' => $products,
        ]);
    }

    /**
     * @Route("/product/{id}", name="product_show")
     *
     * @param Product $product
     *
     * @return Response
     */
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', ['products' => [$product]]);
    }

    /**
     * @Route("/product", name="create_product", methods={"GET","HEAD"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create()
    {
        $form = $this->createForm(ProductType::class)
            ->remove('id')
            ->createView();

        return $this->render('product/create.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/product", name="store_product", methods={"POST", "PUT"})
     *
     * @param Request        $request
     * @param ProductService $ps
     *
     * @return Response
     */
    public function store(Request $request, ProductService $ps)
    {
        $product = $ps->createProduct($request);

        return $this->showProduct($product);
    }

    /**
     * @Route("/product/{id}/edit", name="edit_product")
     *
     * @param Request        $request
     * @param Product        $product
     * @param ProductService $ps
     *
     * @return mixed
     */
    public function edit(Request $request, Product $product, ProductService $ps)
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->remove('id');
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            $ps->updateProduct($product);

            return $this->show($product);
        }

        return $this->render('product/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
