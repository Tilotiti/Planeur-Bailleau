<?php


namespace App\Controller\Admin;


use App\Controller\ExtendedController;
use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PostController
 * @package App\Controller\Admin
 * @Route("/admin/post")
 * @IsGranted("ROLE_ADMIN")
 */
class PostController extends ExtendedController
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var PostRepository
     */
    private PostRepository $postRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        PostRepository $postRepository
    ) {
        $this->entityManager = $entityManager;
        $this->postRepository = $postRepository;
    }

    /**
     * @Route("", name="admin_post")
     */
    public function index(): Response {
        $posts = $this->postRepository->pagination([]);

        return $this->render('admin/post/index.html.twig', [
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/add", name="admin_post_add")
     * @Route("/edit/{post}", name="admin_post_edit")
     * @param Request $request
     * @param Post|null $post
     * @return Response
     */
    public function form(Request $request, ?Post $post = null): Response {
        if(!$post) {
            $post = new Post();
        }

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $post->setUser($this->getUser());
            $post->setUpdatedAt(new \DateTime());
            $this->entityManager->persist($post);
            $this->entityManager->flush();

            $this->addFlash('success', "Le post a été enregistré.");
            return $this->redirectToRoute('admin_post');
        }

        return $this->render('admin/post/form.html.twig', [
            'post' => $post,
            'form' => $form->createView()
        ]);
    }
}