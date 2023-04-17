<?php


namespace App\Controller;


use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PostController
 * @package App\Controller
 * @Route({
 *     "fr": "actualites",
 *     "en": "news"
 * })
 */
class PostController extends ExtendedController
{
    /**
     * @var PostRepository
     */
    private PostRepository $postRepository;

    public function __construct(
        PostRepository $postRepository
    ) {
        $this->postRepository = $postRepository;
    }

    /**
     * @Route("", name="post")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response {
        $posts = $this->postRepository->pagination([
            'locale' => $request->getLocale(),
            'published' => true
        ], $request->query->getInt('page', 1));

        return $this->render('post/index.html.twig', [
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/{post}/{code}", name="post_view", priority="1")
     * @param Post $post
     * @return Response
     */
    public function view(Post $post): Response {
        return $this->render('post/view.html.twig', [
            'post' => $post
        ]);
    }
}