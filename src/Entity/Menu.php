<?php


namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslatableTrait;
use Symfony\Component\PropertyAccess\PropertyAccess;


/**
 * Class Menu
 * @package App\Entity
 * @ORM\Entity
 * @method getTitle()
 * @method getUrl()
 */
class Menu implements TranslatableInterface
{
    use TranslatableTrait;

    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private int $id;

    /**
     * @var int
     * @ORM\Column(type="integer", name="`order`")
     */
    private int $order = 0;

    /**
     * @var bool Does the menu should be shown to the public or restricted to connected users ?
     * @ORM\Column(type="boolean")
     */
    private bool $public = false;

    /**
     * @var Collection|ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\Page", mappedBy="menu")
     * @ORM\OrderBy({"order": "ASC"})
     */
    private Collection $pages;

    public function __construct() {
        $this->pages = new ArrayCollection();
    }

    public function __toString() {
        return $this->getTitle();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return $this->order;
    }

    /**
     * @param int $order
     */
    public function setOrder(int $order): void
    {
        $this->order = $order;
    }

    /**
     * @return bool
     */
    public function isPublic(): bool
    {
        return $this->public;
    }

    /**
     * @param bool $public
     */
    public function setPublic(bool $public): void
    {
        $this->public = $public;
    }

    /**
     * @return ArrayCollection|Collection
     */
    public function getPages()
    {
        return $this->pages;
    }

    /**
     * @param ArrayCollection|Collection $pages
     */
    public function setPages($pages): void
    {
        $this->pages = $pages;
    }

    /**
     * @param string $method
     * @param array $arguments
     * @return mixed
     */
    public function __call(string $method, array $arguments)
    {
        $translate = $this->translate();

        if (substr($method, 0, 3) == 'set') {
            PropertyAccess::createPropertyAccessor()->setValue($translate, $method, $arguments[0]);

            return $this;
        } else {
            return PropertyAccess::createPropertyAccessor()->getValue($translate, $method);
        }
    }
}