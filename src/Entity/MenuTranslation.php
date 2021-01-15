<?php


namespace App\Entity;


use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslationInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslationTrait;

/**
 * Class MenuTranslation
 * @package App\Entity
 * @ORM\Entity
 */
class MenuTranslation implements TranslationInterface
{
    use TranslationTrait;

    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private int $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private string $title = '';

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private string $url = '';

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
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url): void
    {
        $url = (new Slugify())->slugify($url);
        $this->url = $url;
    }
}