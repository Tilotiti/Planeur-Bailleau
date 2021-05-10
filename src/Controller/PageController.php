<?php

namespace App\Controller;

use App\Entity\Aircraft;
use App\Entity\Page;
use App\Repository\AircraftRepository;
use App\Repository\DocumentCategoryRepository;
use App\Repository\DocumentRepository;
use App\Repository\PageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    private AircraftRepository $aircraftRepository;
    private PageRepository $pageRepository;
    private DocumentCategoryRepository $documentCategoryRepository;

    public function __construct(
        AircraftRepository $aircraftRepository,
        PageRepository $pageRepository,
        DocumentCategoryRepository $documentCategoryRepository
    ) {
        $this->aircraftRepository = $aircraftRepository;
        $this->pageRepository = $pageRepository;
        $this->documentCategoryRepository = $documentCategoryRepository;
    }

    /**
     * @Route({
     *     "fr": "/nos-machines",
     *     "en": "/our-aircrafts"
     * } , name="aircrafts")
     * @param Page $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function aircrafts() {
        $page = $this->pageRepository->findOneByCode('aircrafts');

        $aircrafts = $this->aircraftRepository->findBy([], [
            'license' => 'ASC'
        ]);

        return $this->render('default/aircrafts.html.twig', [
            'page' => $page,
            'aircrafts' => $aircrafts
        ]);
    }

    /**
     * @Route("/documents" , name="documents")
     * @param Page $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function documents() {
        $page = $this->pageRepository->findOneByCode('documents');

        $categories = $this->documentCategoryRepository->findBy([], [
            'title' => 'ASC'
        ]);

        return $this->render('default/documents.html.twig', [
            'page' => $page,
            'categories' => $categories
        ]);
    }
}