<?php

namespace App\Entity;

use App\Repository\OeuvreRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: OeuvreRepository::class)]
class Oeuvre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    

    #[ORM\Column]
    private ?int $Prix = null;

    #[ORM\Column(length: 255)]
    private ?string $Stock = null;

    #[ORM\Column(length: 255)]
    private ?string $NomOeuvre = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrix(): ?int
    {
        return $this->Prix;
    }

    public function setPrix(int $Prix): self
    {
        $this->Prix = $Prix;

        return $this;
    }

    public function getStock(): ?string
    {
        return $this->Stock;
    }

    public function setStock(string $Stock): self
    {
        $this->Stock = $Stock;

        return $this;
    }

    public function getNomOeuvre(): ?string
    {
        return $this->NomOeuvre;
    }

    public function setNomOeuvre(string $NomOeuvre): self
    {
        $this->NomOeuvre = $NomOeuvre;

        return $this;
    }

    
    
    public function __toString()
    {
        return (string) $this->id;
    }
}
