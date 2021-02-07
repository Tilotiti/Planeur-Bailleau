<?php


namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Category
 * @package App\Entity
 * @ORM\Entity
 */
class DocumentCategory
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
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
    private string $description = '';

    /**
     * @var Collection|ArrayCollection|Document[]
     * @ORM\OneToMany(targetEntity="App\Entity\Document", mappedBy="documentCategory")
     */
    private Collection $documents;

    public function __construct() {
        $this->documents = new ArrayCollection();
    }

    public function __toString(): string
    {
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

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return Document[]|ArrayCollection|Collection
     */
    public function getDocuments()
    {
        return $this->documents;
    }

    /**
     * @param Document[]|ArrayCollection|Collection $documents
     */
    public function setDocuments($documents): void
    {
        $this->documents = $documents;
    }
}