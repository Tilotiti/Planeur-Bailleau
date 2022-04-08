<?php


namespace App\Controller\Admin;


use App\Controller\ExtendedController;
use App\Entity\Document;
use App\Form\DocumentType;
use App\Form\Filter\DocumentFilter;
use App\Form\Filter\PageFilter;
use App\Repository\DocumentRepository;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use League\Flysystem\FilesystemInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DocumentController
 * @package App\Controller\Admin
 * @Route("/admin/document")
 * @IsGranted("ROLE_ADMIN")
 */
class DocumentController extends ExtendedController
{

    private EntityManagerInterface $entityManager;
    private DocumentRepository $documentRepository;
    private FilesystemInterface $s3Filesystem;

    public function __construct(
        EntityManagerInterface $entityManager,
        DocumentRepository $documentRepository,
        FilesystemInterface $s3Filesystem
    )
    {
        $this->entityManager = $entityManager;
        $this->documentRepository = $documentRepository;
        $this->s3Filesystem = $s3Filesystem;
    }

    /**
     * @Route("", name="admin_document")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $filter = $this->createFilter(DocumentFilter::class);
        $filter->handleRequest($request);

        $documents = $this->documentRepository->pagination(
            $filter->getData(),
            $request->query->getInt('page', 1)
        );

        return $this->render('admin/document/index.html.twig', [
            'documents' => $documents,
            'filter' => $filter->createView()
        ]);
    }

    /**
     * @Route("/add", name="admin_document_add")
     * @Route("/edit/{document}", name="admin_document_edit")
     * @param Request $request
     * @param Document|null $document
     * @return Response
     */
    public function form(Request $request, ?Document $document = null): Response
    {
        $new = false;

        if (!$document) {
            $document = new Document();
            $new = true;
        }

        $form = $this->createForm(DocumentType::class, $document, [
            'new' => $new
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $document->setUpdatedAt(new \DateTime());

            $this->entityManager->persist($document);
            $this->entityManager->flush();

            if($form->get('file')->getData()) {
                $file = $form->get('file')->getData();

                $stream = fopen($file->getRealPath(), 'r+');

                $slug = (new Slugify())->slugify($document->getTitle());

                $filename = 'documents/'.$document->getId().'.'.$slug.'.'.$file->getClientOriginalExtension();

                $this->s3Filesystem->writeStream($filename, $stream);

                fclose($stream);

                $document->setUrl('https://bailleau.s3.eu-west-3.amazonaws.com/'.$filename);

                $this->entityManager->flush();
            }

            $this->addFlash('success', "Le document a été enregistré.");
            return $this->redirectToRoute('admin_document');
        }

        return $this->render('admin/document/form.html.twig', [
            'document' => $document,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{document}", name="admin_document_delete")
     * @param Document $document
     */
    public function delete(Document $document) {
        $file = str_replace('https://bailleau.s3.eu-west-3.amazonaws.com/', '', $document->getUrl());

        $this->s3Filesystem->delete($file);

        $this->entityManager->remove($document);
        $this->entityManager->flush();

        $this->addFlash('success', "Le fichier a été supprimé.");
        return $this->redirectToRoute('admin_document');
    }
}