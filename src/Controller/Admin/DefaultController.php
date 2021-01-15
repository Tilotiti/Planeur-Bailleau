<?php


namespace App\Controller\Admin;


use App\Controller\ExtendedController;
use App\Repository\AircraftRepository;
use App\Repository\DocumentRepository;
use App\Repository\MenuRepository;
use App\Repository\PageRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController
 * @package App\Controller\Admin
 * @Route("/admin")
 */
class DefaultController extends ExtendedController
{
    /**
     * @var AircraftRepository
     */
    private AircraftRepository $aircraftRepository;
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;
    /**
     * @var DocumentRepository
     */
    private DocumentRepository $documentRepository;
    /**
     * @var MenuRepository
     */
    private MenuRepository $menuRepository;
    /**
     * @var PostRepository
     */
    private PostRepository $postRepository;
    /**
     * @var PageRepository
     */
    private PageRepository $pageRepository;

    public function __construct(
        AircraftRepository $aircraftRepository,
        UserRepository $userRepository,
        DocumentRepository $documentRepository,
        MenuRepository $menuRepository,
        PostRepository $postRepository,
        PageRepository $pageRepository
    ) {
        $this->aircraftRepository = $aircraftRepository;
        $this->userRepository = $userRepository;
        $this->documentRepository = $documentRepository;
        $this->menuRepository = $menuRepository;
        $this->postRepository = $postRepository;
        $this->pageRepository = $pageRepository;
    }

    /**
     * @Route("", name="admin")
     * @return ResponseAlias
     */
    public function index() {
        $stats = [
            'aircrafts' => $this->aircraftRepository->count([]),
            'users' => $this->userRepository->count([]),
            'documents' => $this->documentRepository->count([]),
            'pages' => $this->pageRepository->count([]),
            'posts' => $this->postRepository->count([]),
            'menus' => $this->menuRepository->count([]),
        ];

        return $this->render('admin/index.html.twig', [
            'stats' => $stats
        ]);
    }
}