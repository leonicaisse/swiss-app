<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use http\Url;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/products")
 */
class AdminProductController extends AbstractController
{
    /**
     * @var ProductRepository
     */
    private $repository;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * AdminCommandController constructor.
     * @param ProductRepository $repository
     * @param EntityManagerInterface $em
     */
    public function __construct(ProductRepository $repository, EntityManagerInterface $em)
    {

        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/", name="admin.product.index", methods={"GET"})
     * @param ProductRepository $repository
     * @return Response
     */
    public function index(ProductRepository $repository): Response
    {
        return $this->render('admin/product/index.html.twig', [
            'products' => $repository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin.product.new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function new(Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($product);
            $this->em->flush();
            $this->addFlash('success', 'Le produit ' . $product->getReference() . ' a été créé avec succès.');
            return $this->redirectToRoute('admin.product.index');
        }
        return $this->render('admin/product/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{reference}", name="admin.product.edit", methods={"GET","POST"}, requirements={"reference": "MOD[0-9]{4}$"})
     * @param string $reference
     * @param Request $request
     * @return Response
     */
    public function edit(string $reference, Request $request): Response
    {
        $product = $this->repository->findOneBy(['reference' => $reference]);
        if(!$product) $this->handleNotFound();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'Le produit ' . $product->getReference() . ' a été modifié avec succès.');
            return $this->redirectToRoute('admin.product.index');
        }
        return $this->render('admin/product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route(path="/{id}", name="admin.product.delete", methods="DELETE", requirements={"id": "[0-9]+$"})
     * @param Request $request
     * @param Product $product
     * @return Response
     */
    public function delete(Request $request, Product $product): Response
    {
        if ($product) {
            if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->request->get('_token'))) {
                $this->em->remove($product);
                $this->em->flush();
                $this->addFlash('success', 'Le produit ' . $product->getReference() . ' a été supprimé avec succès.');
            }
        }
        return $this->redirectToRoute('admin.product.index');
    }


    /**
     * @Route(path="/{route}", name="admin.product.notfound")
     */
    public function handleNotFound()
    {
        throw $this->createNotFoundException("La page demandée n'existe pas.");
    }
}
