<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Password
 * @package App\Entity
 * @ORM\Entity()
 */
class Password
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private User $user;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private string $code;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private \DateTime $expires;

    /**
     * Password constructor.
     */
    public function __construct() {
        $this->expires = new \DateTime('+1 day');
        $this->code = md5(rand());
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
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * @return \DateTime
     */
    public function getExpires(): \DateTime
    {
        return $this->expires;
    }

    /**
     * @param \DateTime $expires
     */
    public function setExpires(\DateTime $expires): void
    {
        $this->expires = $expires;
    }
}