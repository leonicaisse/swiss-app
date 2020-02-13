<?php

namespace App\Controller;


use App\Entity\Address;
use App\Entity\AddressSearch;
use App\Form\AddressSearchType;
use App\Repository\AddressRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/address")
 */
class AddressController extends AbstractController
{
    /**
     * @var AddressRepository
     */
    private $addressRepository;

    /**
     * AddressController constructor.
     * @param AddressRepository $addressRepository
     */
    public function __construct(AddressRepository $addressRepository)
    {
        $this->addressRepository = $addressRepository;
    }

    /**
     * @Route("/", name="address.index", methods={"GET"})
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $search = new AddressSearch();
        $form = $this->createForm(AddressSearchType::class, $search);
        $form->handleRequest($request);

        $addresses = $paginator->paginate(
            $this->addressRepository->findAllQuery($search),
            $request->query->getInt('page', 1),
            9
        );

        return $this->render('address/index.html.twig', [
            'addresses' => $addresses,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}", name="address.show", methods={"GET"})
     * @param Address $address
     * @return Response
     */
    public function show(Address $address): Response
    {
        return $this->render('address/show.html.twig', [
            'address' => $address,
        ]);
    }
}
