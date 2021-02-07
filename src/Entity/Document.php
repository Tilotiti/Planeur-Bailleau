<?php


namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @var DocumentCategory|null
     * @ORM\ManyToOne(targetEntity="App\Entity\DocumentCategory", inversedBy="documents")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?DocumentCategory $documentCategory;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private string $url = '';

    /**
     * @var UploadedFile|null
     * @Assert\File(maxSize="100M", mimeTypes={"application/pdf"})
     */
    private ?UploadedFile $file = null;

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

    public function getDocumentCategory(): ?DocumentCategory
    {
        return $this->documentCategory;
    }

    public function setDocumentCategory(?DocumentCategory $documentCategory): void
    {
        $this->documentCategory = $documentCategory;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function getFile(): ?UploadedFile
    {
        return $this->file;
    }

    public function setFile(?UploadedFile $file): void
    {
        $this->file = $file;
    }
}