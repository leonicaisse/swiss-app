<?php

namespace App\Controller;

//use App\Entity\Command;
use App\Entity\CommandSearch;
use App\Form\CommandSearchType;
use App\Repository\CommandRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandController extends AbstractController
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
     * CommandController constructor.
     * @param CommandRepository $repository
     * @param EntityManagerInterface $em
     */
    public function __construct(CommandRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/commands", name="command.index")
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $search = new CommandSearch();
        $form = $this->createForm(CommandSearchType::class, $search);
        $form->handleRequest($request);
        $commands = $paginator->paginate(
            $this->repository->findAllQuery($search),
            $request->query->getInt('page', 1),
            9
        );
        return $this->render('command/index.html.twig', [
            'commands' => $commands,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/commands/{reference}", name="command.show", requirements={"reference": "[0-9]{6}[\.][0-9]{4}$"})
     * @param string $reference
     * @return Response
     */
    public function show(string $reference): Response
    {
        $command = $this->repository->findOneBy(['reference' => $reference]);
        return $this->render('command/show.html.twig', [
            'command' => $command,
        ]);
    }
}
