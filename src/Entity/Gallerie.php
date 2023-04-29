<?php

namespace App\Entity;

use App\Repository\GallerieRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GallerieRepository::class)]
class Gallerie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'galleries')]
    #[ORM\JoinColumn(referencedColumnName: "id", onDelete: "CASCADE")]
    private ?ProduitSimple $produit_simple = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $image = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduitSimple(): ?ProduitSimple
    {
        return $this->produit_simple;
    }

    public function setProduitSimple(?ProduitSimple $produit_simple): self
    {
        $this->produit_simple = $produit_simple;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }
}
