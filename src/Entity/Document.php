<?php


namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * Class Document
 * @package App\Entity
 * @ORM\Entity
 */
class Document
{
    /**
     * @var integer
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @var Aircraft|null
     * @ORM\ManyToOne(targetEntity="App\Entity\Aircraft", inversedBy="documents")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Aircraft $aircraft = null;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private string $title = '';

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

    /**
     * @var DocumentCategory
     * @ORM\ManyToOne(targetEntity="App\Entity\DocumentCategory", inversedBy="documents")
     */
    private DocumentCategory $documentCategory;

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
     * @return Aircraft|null
     */
    public function getAircraft(): ?Aircraft
    {
        return $this->aircraft;
    }

    /**
     * @param Aircraft|null $aircraft
     */
    public function setAircraft(?Aircraft $aircraft): void
    {
        $this->aircraft = $aircraft;
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
}