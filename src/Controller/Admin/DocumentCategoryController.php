<?php


namespace App\Controller\Admin;


use App\Controller\ExtendedController;
use App\Entity\DocumentCategory;
use App\Form\DocumentCategoryType;
use App\Repository\DocumentCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DocumentController
 * @package App\Controller\Admin
 * @Route("/admin/document/category")
 * @IsGranted("ROLE_ADMIN")
 */
class DocumentCategoryController extends ExtendedController
{
    private EntityManagerInterface $entityManager;
    private DocumentCategoryRepository $categoryRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        DocumentCategoryRepository $categoryRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @Route("", name="admin_document_category")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $documentCategories = $this->categoryRepository->pagination(
            [],
            $request->query->getInt('page', 1)
        );

        return $this->render('admin/document/category/index.html.twig', [
            'documentCategories' => $documentCategories
        ]);
    }

    /**
     * @Route("/add", name="admin_document_category_add")
     * @Route("/edit/{documentCategory}", name="admin_document_category_edit")
     * @param Request $request
     * @param DocumentCategory|null $documentCategory
     * @return Response
     */
    public function form(Request $request, ?DocumentCategory $documentCategory = null): Response
    {

        if (!$documentCategory) {
            $documentCategory = new DocumentCategory();
        }

        $form = $this->createForm(DocumentCategoryType::class, $documentCategory);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($documentCategory);
            $this->entityManager->flush();

            $this->addFlash('success', "La catégorie a été enregistrée.");
            return $this->redirectToRoute('admin_document_category');
        }

        return $this->render('admin/document/category/form.html.twig', [
            'documentCategory' => $documentCategory,
            'form' => $form->createView()
        ]);
    }
}