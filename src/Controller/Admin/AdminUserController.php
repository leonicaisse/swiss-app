<?php

namespace App\Controller\Admin;

use App\Entity\PasswordReset;
use App\Entity\User;
use App\Entity\UserSearch;
use App\Form\UserSearchType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

/**
 * @Route("/admin/users")
 */
class AdminUserController extends AbstractController
{
    /**
     * @var UserRepository
     */
    private $repository;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * AdminCommandController constructor.
     * @param UserRepository $repository
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserRepository $repository, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {

        $this->repository = $repository;
        $this->em = $em;
        $this->encoder = $encoder;
    }

    /**
     * @Route("/", name="admin.user.index", methods={"GET"})>
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $search = new UserSearch();
        $form = $this->createForm(UserSearchType::class, $search);
        $form->handleRequest($request);

        $users = $paginator->paginate(
            $this->repository->findAllQuery($search),
            $request->query->getInt('page', 1),
            15
        );

        return $this->render('admin/user/index.html.twig', [
            'users' => $users,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/new", name="admin.user.new", methods={"GET","POST"})
     * @param Request $request
     * @param TokenGeneratorInterface $tokenGenerator
     * @return Response
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     * @throws \Exception
     */
    public function new(Request $request, TokenGeneratorInterface $tokenGenerator): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRoles($request->get('user')['roles']);
            $pw = sha1(random_bytes(10));
            $user->setPassword($this->encoder->encodePassword($user, $pw));
            $this->em->persist($user);
            $token = $tokenGenerator->generateToken();
            $password_reset = new PasswordReset();
            $password_reset->setEmail($user->getEmail());
            $password_reset->setToken(sha1($token));
            $this->em->persist($password_reset);
            $this->em->flush();
            $url = $this->generateUrl('password.reset', ['token' => $token, 'email' => $user->getEmail()], UrlGeneratorInterface::ABSOLUTE_URL);
            $this->addFlash('success', 'L\'utilisateur ' . $user->getUsername() . ' a été créé avec succès.');
            $message = (new TemplatedEmail())
                ->from(new Address('no-reply@swissapp.fr', 'Swissapp'))
                ->to($user->getEmail())
                ->subject('Thanks for signing up!')
                ->html($this->renderView('email/set_password.html.twig', [
                    'user' => $user,
                    'url' => $url
                ]));
            $user->notify($message);
            return $this->redirectToRoute('admin.user.index');
        }
        return $this->render('admin/user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin.user.edit", methods={"GET","POST"}, requirements={"id": "^[0-9]+$"})
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function edit(Request $request, User $user): Response
    {
        if (!$user) $this->handleNotFound();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'L\'utilisateur ' . $user->getUsername() . ' a été modifié avec succès.');
            return $this->redirectToRoute('admin.user.index');
        }
        return $this->render('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route(path="/{id}", name="admin.user.delete", methods="DELETE", requirements={"id": "^[0-9]+$"})
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function delete(Request $request, User $user): Response
    {
        if ($user) {
            if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
                $this->em->remove($user);
                $this->em->flush();
                $this->addFlash('success', 'L\'utilisateur ' . $user->getUsername() . ' a été supprimé avec succès.');
            }
        }
        return $this->redirectToRoute('admin.user.index');
    }


    /**
     * @Route(path="/{route}", name="admin.product.notfound")
     */
    public function handleNotFound()
    {
        throw $this->createNotFoundException("La page demandée n'existe pas.");
    }
}
