<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Page;
use App\Form\ContactType;
use App\Repository\PageRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use ReCaptcha\ReCaptcha;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class DefaultController
 * @package App\Controller
 * @Route("/")
 */
class DefaultController extends ExtendedController
{
    private PageRepository $pageRepository;
    private EntityManagerInterface $entityManager;
    private MailerInterface $mailer;
    private TranslatorInterface $translator;

    public function __construct(
        PageRepository $pageRepository,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer,
        TranslatorInterface $translator
    )
    {
        $this->pageRepository = $pageRepository;
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
    public function page(Page $page): Response
    {
        if ($page->getMenu()) {
            if (!$page->getMenu()->isPublic() && !$this->isGranted('IS_AUTHENTICATED_FULLY')) {
                throw new AccessDeniedException();
            }
        }

        return $this->render('default/page.html.twig', [
            'page' => $page,
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

        if (!$page) {
            throw new NotFoundHttpException();
        }

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

        if ($form->isSubmitted() && $form->isValid()) {
            $recaptcha = new ReCaptcha('6LcfjbkbAAAAAEPoOQCCqBQjQR0sxD1wXnaPUB-T');

            $resp = $recaptcha->verify(
                $request->request->get('g-recaptcha-response'),
                $request->getClientIp()
            );

            if (!$resp->isSuccess()) {
                $this->addFlash('error', $this->translator->trans("contact.error"));
                return $this->reload();
            }


            $this->entityManager->persist($contact);
            $this->entityManager->flush();

            //$users = $this->userRepository->findByRole(User::ROLE_ADMIN);

            $email = new TemplatedEmail();
            $email->from('contact@planeur-bailleau.org');
            $email->addTo(new Address('coordination.cvve.bailleau@gmail.com'));

            /*
            foreach($users as $user) {
                $email->addTo(new Address($user->getEmail()));
            }*/

            $email->subject('[Planeur-Bailleau.org] Nouveau message');
            $email->htmlTemplate('email/contact.html.twig');
            $email->context([
                'contact' => $contact,
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
            'form' => $form->createView(),
        ]);
    }
}
