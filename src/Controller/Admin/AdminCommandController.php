<?php

namespace App\Controller\Admin;

use App\Entity\Command;
use App\Entity\CommandSearch;
use App\Form\CommandSearchType;
use App\Form\CommandType;
use App\Repository\CommandRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * @Route(path="/admin/commands")
 */
class AdminCommandController extends AbstractController
{
    /**
     * @var CommandRepository
     */
    private $repository;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * AdminCommandController constructor.
     * @param CommandRepository $repository
     * @param EntityManagerInterface $em
     */
    public function __construct(CommandRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route(path="/", name="admin.command.index")
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function index(PaginatorInterface $paginator, Request $request)
    {
        $search = new CommandSearch();
        $form = $this->createForm(CommandSearchType::class, $search);
        $form->handleRequest($request);

        $commands = $paginator->paginate(
            $this->repository->findAllQuery($search),
            $request->query->getInt('page', 1),
            15
        );
        return $this->render('admin/command/index.html.twig', [
            'commands' => $commands,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route(path="/new", name="admin.command.new")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function new(Request $request)
    {
        $command = new Command();
        $form = $this->createForm(CommandType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($command);
            $this->em->flush();
            $this->addFlash('success', 'La commande ' . $command->getReference() . ' a été créée avec succès.');
            return $this->redirectToRoute('admin.command.index');
        }
        return $this->render('admin/command/new.html.twig', [
            'command' => $command,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route(path="/{reference}", name="admin.command.edit", requirements={"reference": "[0-9]{6}[\.][0-9]{4}$"}, methods="GET|POST")
     * @param string $reference
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(string $reference, Request $request)
    {
        $command = $this->repository->findOneBy(['reference' => $reference]);
        if (!$command) $this->handleNotFound();
        $form = $this->createForm(CommandType::class, $command);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'La commande ' . $command->getReference() . ' a été modifiée avec succès.');
            return $this->redirectToRoute('admin.command');
        }
        return $this->render('admin/command/edit.html.twig', [
            'command' => $command,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route(path="/{id}", name="admin.command.delete", methods="DELETE", requirements={"id": "[0-9]+$"})
     * @param Command $command
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Command $command, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $command->getId(), $request->get('_token'))) {
            $this->em->remove($command);
            $this->em->flush();
            $this->addFlash('success', 'La commande ' . $command->getReference() . ' a été supprimée avec succès.');
        }
        return $this->redirectToRoute('admin.command.index');
    }


    /**
     * @Route(path="/{route}", name="admin.command.notfound")
     */
    public function handleNotFound()
    {
        throw $this->createNotFoundException("La page demandée n'existe pas.");
    }
}