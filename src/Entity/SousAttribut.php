<?php

namespace App\Entity;

use App\Repository\SousAttributRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SousAttributRepository::class)]
class SousAttribut
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    private ?string $valeur = null;

    #[ORM\ManyToOne(inversedBy: 'sousAttributs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Attribut $attribut = null;

    #[ORM\ManyToMany(targetEntity: ProduitSimple::class, mappedBy: 'terms')]
    private Collection $produitSimples;

    public function __construct()
    {
        $this->produitSimples = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getValeur(): ?string
    {
        return $this->valeur;
    }

    public function setValeur(string $valeur): self
    {
        $this->valeur = $valeur;

        return $this;
    }

    public function getAttribut(): ?Attribut
    {
        return $this->attribut;
    }

    public function setAttribut(?Attribut $attribut): self
    {
        $this->attribut = $attribut;

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
            $produitSimple->addTerm($this);
        }

        return $this;
    }

    public function removeProduitSimple(ProduitSimple $produitSimple): self
    {
        if ($this->produitSimples->removeElement($produitSimple)) {
            $produitSimple->removeTerm($this);
        }

        return $this;
    }
}
