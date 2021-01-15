<?php


namespace App\Controller\Admin;


use App\Controller\ExtendedController;
use App\Entity\Page;
use App\Form\Filter\PageFilter;
use App\Form\PageType;
use App\Repository\PageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PageController
 * @package App\Controller\Admin
 * @Route("/admin/page")
 * @IsGranted("ROLE_ADMIN")
 */
class PageController extends ExtendedController
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var PageRepository
     */
    private PageRepository $pageRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        PageRepository $pageRepository
    ) {
        $this->entityManager = $entityManager;
        $this->pageRepository = $pageRepository;
    }

    /**
     * @Route("", name="admin_page")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response {
        $filter = $this->createFilter(PageFilter::class);
        $filter->handleRequest($request);

        $pages = $this->pageRepository->pagination($filter->getData(), $request->query->getInt('page', 1));

        return $this->render('admin/page/index.html.twig', [
            'pages' => $pages,
            'filter' => $filter->createView()
        ]);
    }

    /**
     * @Route("/add", name="admin_page_add")
     * @Route("/edit/{page}", name="admin_page_edit")
     * @param Request $request
     * @param Page|null $page
     * @return Response
     */
    public function form(Request $request, ?Page $page = null): Response {
        if(!$page) {
            $page = new Page();
        }

        $form = $this->createForm(PageType::class, $page);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $page->setUpdatedAt(new \DateTime());

            $this->entityManager->persist($page);
            $this->entityManager->flush();

            $this->addFlash('success', "Le page a été enregistrée.");
            return $this->redirectToRoute('admin_page');
        }

        return $this->render('admin/page/form.html.twig', [
            'page' => $page,
            'form' => $form->createView()
        ]);
    }
}