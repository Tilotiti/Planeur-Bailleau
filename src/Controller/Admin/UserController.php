<?php


namespace App\Controller\Admin;


use App\Controller\ExtendedController;
use App\Entity\User;
use App\Form\Filter\UserFilter;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 * @package App\Controller\Admin
 * @Route("/admin/user")
 * @IsGranted("ROLE_ADMIN")
 */
class UserController extends ExtendedController
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
     * @var MailerInterface
     */
    private MailerInterface $mailer;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        MailerInterface $mailer
    ) {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->mailer = $mailer;
    }

    /**
     * @Route("", name="admin_user")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response {
        $filter = $this->createFilter(UserFilter::class);
        $filter->handleRequest($request);

        $users = $this->userRepository->pagination($filter->getData(), $request->query->getInt('user', 1));

        return $this->render('admin/user/index.html.twig', [
            'users' => $users,
            'filter' => $filter->createView()
        ]);
    }

    /**
     * @Route("/add", name="admin_user_add")
     * @Route("/edit/{user}", name="admin_user_edit")
     * @param Request $request
     * @param User|null $user
     * @return Response
     */
    public function form(Request $request, ?User $user = null): Response {
        if(!$user) {
            $user = new User();
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $user->setUpdatedAt(new \DateTime());

            if(!$user->getPassword()) {
                $email = new TemplatedEmail();
                $email->from(
                    new Address('contact@planeur-bailleau.org', 'CVVE - Planeur Bailleau')
                );
                $email->to($user->getEmail());
                $email->subject('[Planeur Bailleau] Votre compte');
                $email->htmlTemplate('email/user/add.html.twig');
                $email->context(
                    [
                        'user' => $user,
                    ]
                );

                $this->mailer->send($email);
            }

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->addFlash('success', "L'utilisateur a été enregistré.");
            return $this->redirectToRoute('admin_user');
        }

        return $this->render('admin/user/form.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }
}