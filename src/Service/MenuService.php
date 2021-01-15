<?php


namespace App\Service;


use App\Entity\Menu;
use App\Repository\MenuRepository;
use Symfony\Component\Security\Core\Security;

class MenuService
{
    /**
     * @var MenuRepository
     */
    private MenuRepository $menuRepository;
    /**
     * @var Security
     */
    private Security $security;

    public function __construct(
        MenuRepository $menuRepository,
        Security $security
    )
    {
        $this->menuRepository = $menuRepository;
        $this->security = $security;
    }

    /**
     * @return Menu[]
     */
    public function getMenus(): array {
        $filters = [];

        if(!$this->security->isGranted('IS_AUTHENTICATED_FULLY')) {
            $filters['public'] = true;
        }

        return $this->menuRepository->findBy($filters, [
            'order' => 'ASC'
        ]);
    }
}