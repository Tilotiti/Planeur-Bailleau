<?php


namespace App\Controller\Admin;


use App\Controller\ExtendedController;
use App\Entity\Menu;
use App\Form\MenuType;
use App\Repository\MenuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MenuController
 * @package App\Controller\Admin
 * @Route("/admin/menu")
 * @IsGranted("ROLE_ADMIN")
 */
class MenuController extends ExtendedController
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var MenuRepository
     */
    private MenuRepository $menuRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        MenuRepository $menuRepository
    ) {
        $this->entityManager = $entityManager;
        $this->menuRepository = $menuRepository;
    }

    /**
     * @Route("", name="admin_menu")
     */
    public function index(): Response {
        $menus = $this->menuRepository->findBy([], [
            'order' => 'ASC'
        ]);

        return $this->render('admin/menu/index.html.twig', [
            'menus' => $menus
        ]);
    }

    /**
     * @Route("/add", name="admin_menu_add")
     * @Route("/edit/{menu}", name="admin_menu_edit")
     * @param Request $request
     * @param Menu|null $menu
     * @return Response
     */
    public function form(Request $request, ?Menu $menu = null): Response {
        if(!$menu) {
            $menu = new Menu();
        }

        $form = $this->createForm(MenuType::class, $menu);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($menu);
            $this->entityManager->flush();

            $this->addFlash('success', "Le menu a été enregistré.");
            return $this->redirectToRoute('admin_menu');
        }

        return $this->render('admin/menu/form.html.twig', [
            'menu' => $menu,
            'form' => $form->createView()
        ]);
    }
}