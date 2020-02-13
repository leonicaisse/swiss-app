<?php

namespace App\Controller\Admin;

use App\Entity\Address;
use App\Entity\AddressSearch;
use App\Form\AddressSearchType;
use App\Form\AddressType;
use App\Repository\AddressRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/admin/address")
 */
class AdminAddressController extends AbstractController
{
    /**
     * @var AddressRepository
     */
    private $addressRepository;

    /**
     * AdminAddressController constructor.
     * @param AddressRepository $addressRepository
     */
    public function __construct(AddressRepository $addressRepository)
    {
        $this->addressRepository = $addressRepository;
    }

    /**
     * @Route("/", name="admin.address.index", methods={"GET"})
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(PaginatorInterface $paginator, Request $request)
    {
        $search = new AddressSearch();
        $form = $this->createForm(AddressSearchType::class, $search);
        $form->handleRequest($request);

        $addresses = $paginator->paginate(
            $this->addressRepository->findAllQuery($search),
            $request->query->getInt('page', 1),
            15
        );
        return $this->render('admin/address/index.html.twig', [
            'addresses' => $addresses,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/new", name="admin.address.new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($address);
            $entityManager->flush();

            return $this->redirectToRoute('admin.address.index');
        }

        return $this->render('admin/address/new.html.twig', [
            'address' => $address,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin.address.edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Address $address): Response
    {
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin.address.index');
        }

        return $this->render('admin/address/edit.html.twig', [
            'address' => $address,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin.address.delete", methods={"DELETE"})
     * @param Request $request
     * @param Address $address
     * @return Response
     */
    public function delete(Request $request, Address $address): Response
    {
        if ($this->isCsrfTokenValid('delete' . $address->getId(), $request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($address);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin.address.index');
    }

    /**
     * @Route(path="/{route}", name="admin.address.notfound")
     */
    public function handleNotFound()
    {
        throw $this->createNotFoundException("La page demandée n'existe pas.");
    }
}
