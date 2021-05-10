<?php

namespace App\Controller;

use App\Entity\Aircraft;
use App\Entity\Page;
use App\Repository\AircraftRepository;
use App\Repository\PageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    private AircraftRepository $aircraftRepository;
    private PageRepository $pageRepository;

    public function __construct(
        AircraftRepository $aircraftRepository,
        PageRepository $pageRepository
    ) {
        $this->aircraftRepository = $aircraftRepository;
        $this->pageRepository = $pageRepository;
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
}