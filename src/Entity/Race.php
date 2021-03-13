<?php

namespace App\Entity;

use App\Repository\RaceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @ORM\ManyToMany(targetEntity=Runner::class, inversedBy="races")
     */
    private $runners;

    public function __construct()
    {
        $this->runners = new ArrayCollection();
    }

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

    /**
     * @return Collection|Runner[]
     */
    public function getRunners(): Collection
    {
        return $this->runners;
    }

    public function addRunner(Runner $runner): self
    {
        if (!$this->runners->contains($runner)) {
            $this->runners[] = $runner;
        }

        return $this;
    }

    public function removeRunner(Runner $runner): self
    {
        $this->runners->removeElement($runner);

        return $this;
    }
}
