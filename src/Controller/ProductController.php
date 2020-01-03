<?php

namespace App\Controller;

//use App\Entity\Command;
use App\Entity\ProductSearch;
use App\Form\ProductSearchType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProductController
 * @package App\Controller
 * @Route(path="/products")
 */
class ProductController extends AbstractController
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
     * CommandController constructor.
     * @param ProductRepository $repository
     * @param EntityManagerInterface $em
     */
    public function __construct(ProductRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/", name="product.index")
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $search = new ProductSearch();
        $form = $this->createForm(ProductSearchType::class, $search);
        $form->handleRequest($request);

        $products = $paginator->paginate(
            $this->repository->findAllQuery($search),
            $request->query->getInt('page', 1),
            12
        );
        return $this->render('product/index.html.twig', [
            'products' => $products,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{reference}", name="product.show", methods={"GET","POST"}, requirements={"reference": "MOD[0-9]{4}$"})
     * @param string $reference
     * @return Response
     */
    public function show(string $reference): Response
    {
        $product = $this->repository->findOneBy(['reference' => $reference]);
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route(path="/{route}", name="admin.product.notfound")
     */
    public function handleNotFound()
    {
        throw $this->createNotFoundException("La page demandée n'existe pas.");
    }
}
