<?php

namespace App\Entity;

use App\Repository\MarqueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MarqueRepository::class)]
class Marque
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $logo = null;

    #[ORM\OneToMany(mappedBy: 'marque', targetEntity: ProduitSimple::class)]
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

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

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
            $produitSimple->setMarque($this);
        }

        return $this;
    }

    public function removeProduitSimple(ProduitSimple $produitSimple): self
    {
        if ($this->produitSimples->removeElement($produitSimple)) {
            // set the owning side to null (unless already changed)
            if ($produitSimple->getMarque() === $this) {
                $produitSimple->setMarque(null);
            }
        }

        return $this;
    }
}
