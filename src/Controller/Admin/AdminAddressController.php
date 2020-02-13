<?php

namespace App\Controller\Admin;

use App\Entity\Address;
use App\Form\AddressType;
use App\Repository\AddressRepository;
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
     * @Route("/", name="admin.address.index", methods={"GET"})
     * @param AddressRepository $addressRepository
     * @return Response
     */
    public function index(AddressRepository $addressRepository): Response
    {
        return $this->render('admin/address/index.html.twig', [
            'addresses' => $addressRepository->findAll(),
        ]);
    }


    /**
     * @Route("/{id}", name="admin.address.show", methods={"GET"})
     */
    public function show(Address $address): Response
    {
        return $this->render('admin/address/show.html.twig', [
            'address' => $address,
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
     */
    public function delete(Request $request, Address $address): Response
    {
        if ($this->isCsrfTokenValid('delete' . $address->getId(), $request->request->get('_token'))) {
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
