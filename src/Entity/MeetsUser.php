<?php

namespace App\Entity;

use App\Repository\MeetsUserRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MeetsUserRepository::class)
 */
class MeetsUser
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
    private $deviceID;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nickname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gender;

    /**
     * @ORM\Column(type="integer")
     */
    private $age;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $zodiac;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $meal_preferences;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $human_preferences;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDeviceID(): ?string
    {
        return $this->deviceID;
    }

    public function setDeviceID(string $deviceID): self
    {
        $this->deviceID = $deviceID;

        return $this;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): self
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getZodiac(): ?string
    {
        return $this->zodiac;
    }

    public function setZodiac(string $zodiac): self
    {
        $this->zodiac = $zodiac;

        return $this;
    }

    public function getMealPreferences(): ?string
    {
        return $this->meal_preferences;
    }

    public function setMealPreferences(string $meal_preferences): self
    {
        $this->meal_preferences = $meal_preferences;

        return $this;
    }

    public function getHumanPreferences(): ?string
    {
        return $this->human_preferences;
    }

    public function setHumanPreferences(string $human_preferences): self
    {
        $this->human_preferences = $human_preferences;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }
}
