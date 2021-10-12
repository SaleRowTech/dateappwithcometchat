<?php

namespace App\Entity;

use App\Repository\WheelUserRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WheelUserRepository::class)
 */
class WheelUser
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $playerID;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $amount;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayerID(): ?string
    {
        return $this->playerID;
    }

    public function setPlayerID(string $playerID): self
    {
        $this->playerID = $playerID;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(?int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }
}
