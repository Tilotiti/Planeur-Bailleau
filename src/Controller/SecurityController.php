<?php

namespace App\Controller;

use App\Entity\Password;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class SecurityController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;
    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $passwordEncoder;
    /**
     * @var TokenStorageInterface
     */
    private TokenStorageInterface $tokenStorage;
    /**
     * @var SessionInterface
     */
    private SessionInterface $session;
    /**
     * @var EventDispatcherInterface
     */
    private EventDispatcherInterface $eventDispatcher;
    /**
     * @var MailerInterface
     */
    private MailerInterface $mailer;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        UserPasswordEncoderInterface $passwordEncoder,
        TokenStorageInterface $tokenStorage,
        SessionInterface $session,
        EventDispatcherInterface $eventDispatcher,
        MailerInterface $mailer
    ) {

        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
        $this->tokenStorage = $tokenStorage;
        $this->session = $session;
        $this->eventDispatcher = $eventDispatcher;
        $this->mailer = $mailer;
    }
    /**
     * @Route("/login", name="security_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('index');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/invitation/{code}", name="security_invitation")
     * @param string $code
     * @param Request $request
     * @return Response|RedirectResponse
     */
    public function invit(
        string $code,
        Request $request
    ): Response
    {
        if ($this->isGranted('ROLE_USER')) {

            $this->addFlash('danger', "Vous êtes déjà connecté.");
            return $this->redirectToRoute('index');
        }

        $user = $this->userRepository->findByInvitationCode($code);

        if (!$user) {
            $this->addFlash('danger', "Aucune compte ne correspond à cette invitation.");

            return $this->redirectToRoute('security_login');
        }

        if ($user->getPassword()) {
            $this->addFlash(
                'danger',
                "L'invitation a déjà été acceptée. Vous pouvez vous connecter avec les identifiants renseignés."
            );

            return $this->redirectToRoute('security_login');
        }

        if ($request->isMethod('POST')) {
            // Check password
            if ($request->request->get('password') != $request->request->get('confirmation')) {
                $this->addFlash('danger', "Votre confirmation de mot de passe ne correspond pas. Merci de réessayer.");

                return new RedirectResponse(
                    $request->getUri()
                );
            }

            if (strlen($request->request->get('password')) < 6) {
                $this->addFlash('danger', "Votre mot de passe est trop court. Merci de réessayer.");

                return new RedirectResponse(
                    $request->getUri()
                );
            }

            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    $request->request->get('password')
                )
            );

            $this->entityManager->flush();

            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $this->tokenStorage->setToken($token);

            $this->session->set('_security_main', serialize($token));

            $event = new InteractiveLoginEvent($request, $token);
            $this->eventDispatcher->dispatch($event);

            $this->addFlash('success', "Votre mot de passe a été enregistré. Vous êtes maintenant connecté.");

            return $this->redirectToRoute('index');
        }

        return $this->render('security/invitation.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/password", name="security_password")
     * @param Request $request
     * @return Response|RedirectResponse
     */
    public function password(
        Request $request
    ): Response
    {

        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');

            $user = $this->userRepository->findOneByEmail($email);

            if (!$user) {
                $this->addFlash('danger', 'Aucun compte ne correspond à cet e-mail.');

                return new RedirectResponse($request->getUri());
            }

            $password = new Password();
            $password->setUser($user);

            $this->entityManager->persist($password);
            $this->entityManager->flush();

            // Send Email
            $email = new TemplatedEmail();
            $email->from(
                new Address('contact@planeur-bailleau.org', 'CVVE - Planeur Bailleau')
            );
            $email->to($user->getEmail());
            $email->subject('[Planeur Bailleau] Demande de renouvellement de mot de passe.');
            $email->htmlTemplate('email/user/password.html.twig');
            $email->context(
                [
                    'user' => $user,
                    'password' => $password
                ]
            );

            $this->mailer->send($email);

            $this->addFlash('success', 'Une demande de renouvellement de mot de passe a été envoyée par e-mail. Celle-ci sera effective pendant 24h.');

            return $this->redirectToRoute('security_login');
        }

        return $this->render('security/password.html.twig');
    }

    /**
     * @Route("/request/{password}", name="security_request")
     * @ParamConverter("password", options={"mapping": {"password": "code"}})
     * @param Password $password
     * @param Request $request
     * @return Response|RedirectResponse
     */
    public function request(
        Password $password,
        Request $request
    )
    {
        if ($password->getExpires() <= new \DateTime()) {
            $this->addFlash('danger', "Cette demande a expiré. Merci de recommencer votre demande.");

            return $this->redirectToRoute('index');
        }

        if ($this->isGranted('ROLE_USER')) {
            $this->addFlash('danger', "Vous êtes déjà connecté.");

            return $this->redirectToRoute('index');
        }

        $user = $password->getUser();

        if ($request->isMethod('POST')) {
            // Check password
            if ($request->request->get('password') != $request->request->get('confirmation')) {
                $this->addFlash('danger', "Votre confirmation de mot de passe ne correspond pas. Merci de réessayer.");

                return new RedirectResponse(
                    $request->getUri()
                );
            }

            if (strlen($request->request->get('password')) < 6) {
                $this->addFlash('danger', "Votre mot de passe est trop court. Merci de réessayer.");

                return new RedirectResponse(
                    $request->getUri()
                );
            }

            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    $request->request->get('password')
                )
            );

            $this->entityManager->remove($password);
            $this->entityManager->flush();

            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $this->tokenStorage->setToken($token);

            $this->session->set('_security_main', serialize($token));

            $event = new InteractiveLoginEvent($request, $token);
            $this->eventDispatcher->dispatch($event);

            $this->addFlash('success', "Votre mot de passe a été enregistré. Vous êtes maintenant connecté.");

            return $this->redirectToRoute('index');
        }

        return $this->render('security/request.html.twig', [
            'user' => $user,
        ]);
    }
}
