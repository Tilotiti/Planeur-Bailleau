<?php

namespace App\Controller;

use App\Entity\Page;
use App\Repository\PageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController
 * @package App\Controller
 * @Route("/")
 */
class DefaultController extends AbstractController
{
    /**
     * @var PageRepository
     */
    private PageRepository $pageRepository;

    public function __construct(PageRepository $pageRepository) {
        $this->pageRepository = $pageRepository;
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
    public function page(Page $page): Response {
        return $this->render('default/page.html.twig', [
            'page' => $page
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

        return $this->render('default/page.html.twig', [
            'page' => $page,
        ]);
    }

    /**
     * @Route({
     *     "fr": "/contactez-nous",
     *     "en": "/contact-us"
     * } , name="contact")
     */
    public function contact(): Response
    {
        $page = $this->pageRepository->findOneByCode('contact');

        return $this->render('default/contact.html.twig', [
            'page' => $page,
        ]);
    }
}
