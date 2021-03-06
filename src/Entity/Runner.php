<?php

namespace App\Entity;

use App\Repository\RunnerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=RunnerRepository::class)
 */
class Runner
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     * @Assert\NotBlank(message = "O campo 'name' precisa ser preenchido.")
     * @Assert\Length(
     *    min = 3,
     *    max = 150,
     *    minMessage = "O nome deve ter no mínimo {{ limit }} caracteres",
     *    maxMessage = "O nome não deve ser maior que {{ limit }} caracteres"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=11)
     * @Assert\NotBlank(message = "O campo 'cpf' precisa ser preenchido.")
     * @Assert\Length(
     *    min = 11,
     *    max = 14,
     *    minMessage = "O CPF deve ter no mínimo {{ limit }} caracteres.",
     *    maxMessage = "O CPF não deve ser maior que {{ limit }} caracteres."
     * )
     */
    private $cpf;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank(message = "O campo 'birthdate' precisa ser preenchido.")
     */
    private $birthdate;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCpf(): ?string
    {
        return $this->cpf;
    }

    public function setCpf(string $cpf): self
    {
        $this->cpf = $cpf;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;
        return $this;
    }
}
