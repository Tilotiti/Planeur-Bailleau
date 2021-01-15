<?php


namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslatableTrait;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Class Page
 * @package App\Entity
 * @ORM\Entity
 * @method string getTitle()
 * @method string getContent()
 */
class Page implements TranslatableInterface
{
    use TranslatableTrait;

    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @var Menu|null
     * @ORM\ManyToOne(targetEntity="App\Entity\Menu", inversedBy="pages")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Menu $menu = null;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $code = null;

    /**
     * @var int
     * @ORM\Column(type="integer", name="`order`")
     */
    private int $order = 0;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetimetz")
     */
    private \DateTime $createdAt;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetimetz")
     */
    private \DateTime $updatedAt;

    public function __construct() {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
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
     * @return Menu|null
     */
    public function getMenu(): ?Menu
    {
        return $this->menu;
    }

    /**
     * @param Menu|null $menu
     */
    public function setMenu(?Menu $menu): void
    {
        $this->menu = $menu;
    }

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string|null $code
     */
    public function setCode(?string $code): void
    {
        $this->code = $code;
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
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
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