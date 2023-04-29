<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $image = null;

    #[ORM\Column(nullable: true)]
    private ?int $parent = null;

    #[ORM\Column]
    private ?bool $en_avant = null;

    #[ORM\ManyToMany(targetEntity: ProduitSimple::class, mappedBy: 'categorie')]
    private Collection $produitSimples;

    public function __construct()
    {
        $this->produitSimples = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getParent(): ?int
    {
        return $this->parent;
    }

    public function setParent(?int $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function isEnAvant(): ?bool
    {
        return $this->en_avant;
    }

    public function setEnAvant(bool $en_avant): self
    {
        $this->en_avant = $en_avant;

        return $this;
    }

    /**
     * @return Collection<int, ProduitSimple>
     */
    public function getProduitSimples(): Collection
    {
        return $this->produitSimples;
    }

    public function addProduitSimple(ProduitSimple $produitSimple): self
    {
        if (!$this->produitSimples->contains($produitSimple)) {
            $this->produitSimples->add($produitSimple);
            $produitSimple->addCategorie($this);
        }

        return $this;
    }

    public function removeProduitSimple(ProduitSimple $produitSimple): self
    {
        if ($this->produitSimples->removeElement($produitSimple)) {
            $produitSimple->removeCategorie($this);
        }

        return $this;
    }
}
