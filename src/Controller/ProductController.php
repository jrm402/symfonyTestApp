<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
	/**
     * @Route("/products", name="products_view")
     */
    public function viewProducts(): Response
    {
		$products = $this->getDoctrine()
			->getRepository(Product::class)
			->getAllProducts();

		return $this->render('products/products.html.twig', [
            'products' => $products,
        ]);
    }

	/**
     * @Route("/products/new", name="products_new")
     */
    public function newProduct(Request $request): Response
    {
		$product = new Product();
        $form = $this->createForm(ProductFormType::class, $product);

		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid()) {
			$product = [
				'name' => $product->getName(),
				'description' => $product->getDescription(),
			];
			$this->getDoctrine()
				->getRepository(Product::class)
				->saveProduct($product);

			$this->addFlash('success', "Product <b>{$product['name']}</b> created");
			return $this->redirectToRoute('products_view');
		}

		return $this->render('products/new/new.html.twig', [
            'productForm' => $form->createView(),
        ]);
    }

	/**
	 * @Route("/products/edit/{id}", name="products_edit")
	 */
	public function editProduct(int $id, Request $request): Response
	{
		$product = $this->getDoctrine()
			->getRepository(Product::class)
			->getProduct($id);

		if(!$product) {
			$this->addFlash('danger', 'There was an error editing that product');
			return $this->redirectToRoute('products_view');
		}

		$form = $this->createForm(ProductFormType::class, $product, [ 'edit' => true ]);
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid()) {
			// Update
			if($form->get('save')->isClicked()) {
				$product = $form->getData();
				$this->getDoctrine()
					->getRepository(Product::class)
					->saveProduct($product);

				$this->addFlash('success', "Product <b>{$product['name']}</b> updated");
			}
			// Delete
			else if($form->get('delete')->isClicked()) {
				$product = $form->getData();
				$this->getDoctrine()
					->getRepository(Product::class)
					->deleteProduct($product['id']);

				$this->addFlash('success', "Product <b>{$product['name']}</b> deleted");
			}

			return $this->redirectToRoute('products_view');
		}


		return $this->render('products/edit/edit.html.twig', [
			'product' => $product,
            'productForm' => $form->createView(),
        ]);
	}
}
