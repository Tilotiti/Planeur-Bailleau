<?php


namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Contact
 * @package App\Entity
 * @ORM\Entity
 */
class Contact
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @var string|null
     * @ORM\Column(type="string")
     * @Assert\Email()
     */
    private ?string $email = null;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $phone = null;

    /**
     * @var string|null
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private ?string $name = null;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetimetz")
     */
    private \DateTime $datetime;

    /**
     * @var string|null
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private ?string $content = null;

    public function __construct() {
        $this->datetime = new \DateTime();
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
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string|null $phone
     */
    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return \DateTime
     */
    public function getDatetime(): \DateTime
    {
        return $this->datetime;
    }

    /**
     * @param \DateTime $datetime
     */
    public function setDatetime(\DateTime $datetime): void
    {
        $this->datetime = $datetime;
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string|null $content
     */
    public function setContent(?string $content): void
    {
        $this->content = $content;
    }
}