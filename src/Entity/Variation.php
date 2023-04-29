<?php

namespace App\Entity;

use App\Repository\VariationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VariationRepository::class)]
class Variation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $prix = null;

    #[ORM\Column]
    private array $combinaison = [];

    #[ORM\ManyToOne(inversedBy: 'variations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ProduitSimple $produit = null;

    #[ORM\OneToMany(mappedBy: 'variation', targetEntity: GallerieVariable::class)]
    private Collection $gallerieVariables;

    #[ORM\Column(length: 255)]
    private ?string $image_couverture = null;

    #[ORM\Column(nullable: true)]
    private ?int $prixPromo = null;

    #[ORM\Column(nullable: true)]
    private ?int $poids = null;

    #[ORM\Column(nullable: true)]
    private ?int $Longueur = null;

    #[ORM\Column(nullable: true)]
    private ?int $Largeur = null;

    #[ORM\Column(nullable: true)]
    private ?int $Hauteur = null;

    #[ORM\Column(nullable: true)]
    private ?int $PrixLivraison = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $information_a_savoir = null;

    public function __construct()
    {
        $this->gallerieVariables = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getCombinaison(): array
    {
        return $this->combinaison;
    }

    public function setCombinaison(array $combinaison): self
    {
        $this->combinaison = $combinaison;

        return $this;
    }

    public function getProduit(): ?ProduitSimple
    {
        return $this->produit;
    }

    public function setProduit(?ProduitSimple $produit): self
    {
        $this->produit = $produit;

        return $this;
    }

    /**
     * @return Collection<int, GallerieVariable>
     */
    public function getGallerieVariables(): Collection
    {
        return $this->gallerieVariables;
    }

    public function addGallerieVariable(GallerieVariable $gallerieVariable): self
    {
        if (!$this->gallerieVariables->contains($gallerieVariable)) {
            $this->gallerieVariables->add($gallerieVariable);
            $gallerieVariable->setVariation($this);
        }

        return $this;
    }

    public function removeGallerieVariable(GallerieVariable $gallerieVariable): self
    {
        if ($this->gallerieVariables->removeElement($gallerieVariable)) {
            // set the owning side to null (unless already changed)
            if ($gallerieVariable->getVariation() === $this) {
                $gallerieVariable->setVariation(null);
            }
        }

        return $this;
    }

    public function getImageCouverture(): ?string
    {
        return $this->image_couverture;
    }

    public function setImageCouverture(string $image_couverture): self
    {
        $this->image_couverture = $image_couverture;

        return $this;
    }

    public function getPrixPromo(): ?int
    {
        return $this->prixPromo;
    }

    public function setPrixPromo(?int $prixPromo): self
    {
        $this->prixPromo = $prixPromo;

        return $this;
    }

    public function getPoids(): ?int
    {
        return $this->poids;
    }

    public function setPoids(int $poids): self
    {
        $this->poids = $poids;

        return $this;
    }

    public function getLongueur(): ?int
    {
        return $this->Longueur;
    }

    public function setLongueur(int $Longueur): self
    {
        $this->Longueur = $Longueur;

        return $this;
    }

    public function getLargeur(): ?int
    {
        return $this->Largeur;
    }

    public function setLargeur(?int $Largeur): self
    {
        $this->Largeur = $Largeur;

        return $this;
    }

    public function getHauteur(): ?int
    {
        return $this->Hauteur;
    }

    public function setHauteur(?int $Hauteur): self
    {
        $this->Hauteur = $Hauteur;

        return $this;
    }

    public function getPrixLivraison(): ?int
    {
        return $this->PrixLivraison;
    }

    public function setPrixLivraison(?int $PrixLivraison): self
    {
        $this->PrixLivraison = $PrixLivraison;

        return $this;
    }

    public function getInformationASavoir(): ?string
    {
        return $this->information_a_savoir;
    }

    public function setInformationASavoir(?string $information_a_savoir): self
    {
        $this->information_a_savoir = $information_a_savoir;

        return $this;
    }
}