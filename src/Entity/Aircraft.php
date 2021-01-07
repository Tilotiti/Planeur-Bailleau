<?php


namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Aircraft
 * @package App\Entity
 * @ORM\Entity
 */
class Aircraft
{
    /**
     * @var string
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private string $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private string $license = '';

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private string $competitionNumber = '';

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private string $model = '';

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private string $type;

    const TYPE_MONO = 'mono';
    const TYPE_BI = 'bi';
    const TYPE_TOWING = 'towing';

    /**
     * @var Collection|Document[]
     * @ORM\OneToMany(targetEntity="App\Entity\Document", mappedBy="aircraft")
     */
    private Collection $documents;

    public function __construct() {
        $this->type = self::TYPE_MONO;
        $this->documents = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getLicense(): string
    {
        return $this->license;
    }

    /**
     * @param string $license
     */
    public function setLicense(string $license): void
    {
        $this->license = $license;
    }

    /**
     * @return string
     */
    public function getCompetitionNumber(): string
    {
        return $this->competitionNumber;
    }

    /**
     * @param string $competitionNumber
     */
    public function setCompetitionNumber(string $competitionNumber): void
    {
        $this->competitionNumber = $competitionNumber;
    }

    /**
     * @return string
     */
    public function getModel(): string
    {
        return $this->model;
    }

    /**
     * @param string $model
     */
    public function setModel(string $model): void
    {
        $this->model = $model;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return Document[]|Collection
     */
    public function getDocuments()
    {
        return $this->documents;
    }

    /**
     * @param Document[]|Collection $documents
     */
    public function setDocuments($documents): void
    {
        $this->documents = $documents;
    }
}