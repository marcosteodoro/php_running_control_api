<?php

namespace App\Entity;

use App\Repository\RaceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=RaceRepository::class)
 */
class Race
{
    const VALID_TYPES = [3, 5, 10, 21, 42];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message = "O campo 'type' precisa ser preenchido.")
     * @Assert\Choice(choices = Race::VALID_TYPES, message="Tipo de prova inválido.")
     */
    private $type;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank(message = "O campo 'type' precisa ser preenchido.")
     */
    private $date;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}
