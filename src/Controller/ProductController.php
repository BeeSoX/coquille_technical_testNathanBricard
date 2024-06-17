<?php
namespace App\Controller;

use App\Repository\ProductRepository;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/products', name: 'product_list', methods: ['GET'])]
    public function list(): Response
    {
        return $this->render('product/list.html.twig');
    }

    #Delete a product (fonctionne)
    #[Route('/api/product/delete/{id}', name: 'product_delete', methods: ['DELETE'])]
    public function delete(ProductRepository $products, int $id): Response
    {
        $products->remove($id);
        return $this->render('product/list.html.twig');
    }

    #Update a product (pas eu le temps)
    #[Route('/api/product/update', name: 'product_update', methods: ['PUT'])]
    public function update(ProductRepository $products, int $id): Response
    {
        $products->remove($id);
    }
    #Create a product (fonctionne)
    #[Route('/api/product/add', name: 'product_create', methods: ['GET', 'POST'])]
    public function create(Request $request, ProductRepository $products): Response
    {
        $data = json_decode($request->getContent(), true);
        $product = new Product();
        $product->setName($data['name']);
        $product->setDescription($data['description']);
        $product->setPrice($data['price']);
        $products->save($product);
        return $this->render('product/list.html.twig');
    }

    #[Route('/api/product/listproducts', name: 'api_product_list', defaults: ['page' => '1'], methods: ['GET'])]
    #[Route('/api/product/listproducts/{page}', name: 'api_product_list_paginated', methods: ['GET'])]
    public function apiList(int $page, ProductRepository $products): JsonResponse
    {
        $paginator = $products->getAllPaginated($page);
        return $this->json($paginator);
    }
    
}
