<?php

namespace App\Controller;

use App\Entity\PasswordReset;
use App\Form\ForgotPasswordType;
use App\Form\ResetPasswordType;
use App\Repository\PasswordResetRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use function MongoDB\BSON\toJSON;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;
    /**
     * @var PasswordResetRepository
     */
    private $resetRepository;

    public function __construct(UserRepository $userRepository, PasswordResetRepository $resetRepository, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
        $this->userRepository = $userRepository;
        $this->resetRepository = $resetRepository;
        $this->em = $em;
        $this->encoder = $encoder;
    }

    /**
     * @Route(path="/login", name="login")
     * @param AuthenticationUtils $authenticationUtils
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        $lastUsername = $authenticationUtils->getLastUsername();
        $error = $authenticationUtils->getLastAuthenticationError();
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
     * @Route(path="/forgot-password", name="password.forgotten", methods={"GET","POST"})
     * @param Request $request
     * @param TokenGeneratorInterface $tokenGenerator
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function forgottenPassword(Request $request, TokenGeneratorInterface $tokenGenerator)
    {
        $form = $this->createForm(ForgotPasswordType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $email = $request->request->get('forgot_password')['email'];
            $user = $this->userRepository->findOneBy(['email' => $email]);
            if ($user) {
                $token = $tokenGenerator->generateToken();
                $password_reset = new PasswordReset();
                $password_reset->setEmail($user->getEmail());
                $password_reset->setToken($token);
                $this->em->persist($password_reset);
                $this->em->flush();
                $url = $this->generateUrl('password.reset', ['token' => $token, 'email' => $user->getEmail()], UrlGeneratorInterface::ABSOLUTE_URL);
                $message = (new TemplatedEmail())
                    ->from(new Address('no-reply@swissapp.fr', 'Swissapp'))
                    ->to($user->getEmail())
                    ->subject('Password reset request')
                    ->html($this->renderView('email/reset_password.html.twig', [
                        'user' => $user,
                        'url' => $url
                    ]));
                $user->notify($message);
            }
        }
        return $this->render('security/password_forgotten.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route(path="/reset-password/{token}", name="password.reset")
     * @param string $token
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function resetPassword(string $token, Request $request)
    {
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);
        $email = $request->get('email');
        $passwordReset = $this->resetRepository->findOneBy(['token' => $token]);
        if ($passwordReset && $email) {
            if ($passwordReset->isValidFor($email)) {
                if ($form->isSubmitted() && $form->isValid()) {
                    $user = $this->userRepository->findOneBy(['email' => $email]);
                    $pw = $request->get('reset_password')['password']['first'];
                    $user->setPassword($this->encoder->encodePassword($user, $pw));
                    $this->em->remove($passwordReset);
                    $this->em->flush();
                    $url = $this->generateUrl('login', [], UrlGeneratorInterface::ABSOLUTE_URL);
                    $message = (new TemplatedEmail())
                        ->from(new Address('no-reply@swissapp.fr', 'Swissapp'))
                        ->to($user->getEmail())
                        ->subject('Password updated')
                        ->html($this->renderView('email/password_has_been_reset.html.twig', [
                            'user' => $user,
                            'url' => $url
                        ]));
                    $user->notify($message);
                } else {
                    return $this->render('security/password_reset.html.twig', [
                        'form' => $form->createView()
                    ]);
                }
            }
        }
        return $this->render('security/invalid.html.twig', [
            "message" => 'Le lien est invalide ou expiré.'
        ]);
    }
}