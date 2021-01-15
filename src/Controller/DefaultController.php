<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Page;
use App\Entity\User;
use App\Form\ContactType;
use App\Repository\PageRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class DefaultController
 * @package App\Controller
 * @Route("/")
 */
class DefaultController extends ExtendedController
{
    /**
     * @var PageRepository
     */
    private PageRepository $pageRepository;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;
    /**
     * @var MailerInterface
     */
    private MailerInterface $mailer;
    /**
     * @var TranslatorInterface
     */
    private TranslatorInterface $translator;

    public function __construct(
        PageRepository $pageRepository,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer,
        TranslatorInterface $translator
    ) {
        $this->pageRepository = $pageRepository;
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
        $this->translator = $translator;
    }
    /**
     * @Route("", name="index")
     */
    public function index(): Response
    {
        $page = $this->pageRepository->findOneByCode('home');

        return $this->render('default/index.html.twig', [
            'page' => $page,
        ]);
    }

    /**
     * @Route("/{menu}/{page}/{url}", name="page")
     * @param Page $page
     * @return Response
     */
    public function page(Page $page): Response {
        return $this->render('default/page.html.twig', [
            'page' => $page
        ]);
    }

    /**
     * @Route({
     *     "fr": "/boutique",
     *     "en": "/shop"
     * } , name="shop")
     */
    public function shop(): Response
    {
        $page = $this->pageRepository->findOneByCode('shop');

        return $this->render('default/page.html.twig', [
            'page' => $page,
        ]);
    }

    /**
     * @Route({
     *     "fr": "/contactez-nous",
     *     "en": "/contact-us"
     * } , name="contact")
     * @param Request $request
     * @return Response
     */
    public function contact(Request $request): Response
    {
        $page = $this->pageRepository->findOneByCode('contact');

        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($contact);
            $this->entityManager->flush();

            $users = $this->userRepository->findByRole(User::ROLE_ADMIN);

            $email = new TemplatedEmail();
            $email->from('contact@planeur-bailleau.org');

            foreach($users as $user) {
                $email->addTo(new Address($user->getEmail()));
            }

            $email->subject('[Planeur-Bailleau.org] Nouveau message');
            $email->htmlTemplate('include/email/contact.html.twig');
            $email->context([
                'contact' => $contact
            ]);

            try {
                $this->mailer->send($email);
            } catch (TransportExceptionInterface $e) {
                $this->addFlash('danger', $this->translator->trans("contact.error"));
                return $this->reload();
            }

            $this->addFlash('success', $this->translator->trans("contact.success"));
            return $this->reload();
        }

        return $this->render('default/contact.html.twig', [
            'page' => $page,
            'form' => $form->createView()
        ]);
    }
}
