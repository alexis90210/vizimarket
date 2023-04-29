<?php

namespace App\Entity;

use App\Repository\ProduitSimpleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitSimpleRepository::class)]
class ProduitSimple
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(nullable: true)]
    private ?int $tarif_regulier = null;

    #[ORM\Column(nullable: true)]
    private ?int $tarif_promo = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $image = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?int $longueur = null;

    #[ORM\Column(nullable: true)]
    private ?int $largeur = null;

    #[ORM\Column(nullable: true)]
    private ?int $hauteur = null;

    #[ORM\Column(nullable: true)]
    private ?int $poids = null;

    #[ORM\ManyToMany(targetEntity: Categorie::class, inversedBy: 'produitSimples')]
    private Collection $categorie;

    #[ORM\OneToMany(mappedBy: 'produit_simple', targetEntity: Gallerie::class)]
    private Collection $galleries;

    #[ORM\Column]
    private ?int $quantite = null;

    #[ORM\Column(nullable: true)]
    private ?int $prix_livraison = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $infos_a_savoir = null;

    #[ORM\ManyToOne(inversedBy: 'produitSimples')]
    private ?Marque $marque = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $caracteristique = null;

    #[ORM\Column]
    private ?int $produit_type = null;

    #[ORM\ManyToOne(inversedBy: 'ProduitSimple')]
    private ?Vendeur $vendeur = null;

    #[ORM\Column(length: 255)]
    private ?string $DateCreation = null;

    #[ORM\OneToMany(mappedBy: 'produit', targetEntity: Variation::class)]
    private Collection $variations;


    #[ORM\OneToMany(mappedBy: 'produit', targetEntity: CommentaireProduit::class)]
    private Collection $commentaireProduits;

    #[ORM\ManyToMany(targetEntity: SousAttribut::class, inversedBy: 'produitSimples')]
    private Collection $terms;

    #[ORM\OneToMany(mappedBy: 'produit', targetEntity: DetailsCommande::class)]
    private Collection $detailsCommandes;

    public function __construct()
    {
        $this->DateCreation = date('d/m/Y H:i:s');
        $this->categorie = new ArrayCollection();
        $this->galleries = new ArrayCollection();
        $this->variations = new ArrayCollection();
        $this->commentaireProduits = new ArrayCollection();
        $this->terms = new ArrayCollection();
        $this->detailsCommandes = new ArrayCollection();
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

    public function getTarifRegulier(): ?int
    {
        return $this->tarif_regulier;
    }

    public function setTarifRegulier(int $tarif_regulier): self
    {
        $this->tarif_regulier = $tarif_regulier;

        return $this;
    }

    public function getTarifPromo(): ?int
    {
        return $this->tarif_promo;
    }

    public function setTarifPromo(int $tarif_promo): self
    {
        $this->tarif_promo = $tarif_promo;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLongueur(): ?int
    {
        return $this->longueur;
    }

    public function setLongueur(?int $longueur): self
    {
        $this->longueur = $longueur;

        return $this;
    }

    public function getLargeur(): ?int
    {
        return $this->largeur;
    }

    public function setLargeur(?int $largeur): self
    {
        $this->largeur = $largeur;

        return $this;
    }

    public function getHauteur(): ?int
    {
        return $this->hauteur;
    }

    public function setHauteur(?int $hauteur): self
    {
        $this->hauteur = $hauteur;

        return $this;
    }

    public function getPoids(): ?int
    {
        return $this->poids;
    }

    public function setPoids(?int $poids): self
    {
        $this->poids = $poids;

        return $this;
    }

    /**
     * @return Collection<int, Categorie>
     */
    public function getCategorie(): Collection
    {
        return $this->categorie;
    }

    public function addCategorie(Categorie $categorie): self
    {
        if (!$this->categorie->contains($categorie)) {
            $this->categorie->add($categorie);
        }

        return $this;
    }

    public function removeCategorie(Categorie $categorie): self
    {
        $this->categorie->removeElement($categorie);

        return $this;
    }


    /**
     * @return Collection<int, Gallerie>
     */
    public function getGalleries(): Collection
    {
        return $this->galleries;
    }

    public function addGallery(Gallerie $gallery): self
    {
        if (!$this->galleries->contains($gallery)) {
            $this->galleries->add($gallery);
            $gallery->setProduitSimple($this);
        }

        return $this;
    }

    public function removeGallery(Gallerie $gallery): self
    {
        if ($this->galleries->removeElement($gallery)) {
            // set the owning side to null (unless already changed)
            if ($gallery->getProduitSimple() === $this) {
                $gallery->setProduitSimple(null);
            }
        }

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getPrixLivraison(): ?int
    {
        return $this->prix_livraison;
    }

    public function setPrixLivraison(?int $prix_livraison): self
    {
        $this->prix_livraison = $prix_livraison;

        return $this;
    }

    public function getInfosASavoir(): ?string
    {
        return $this->infos_a_savoir;
    }

    public function setInfosASavoir(?string $infos_a_savoir): self
    {
        $this->infos_a_savoir = $infos_a_savoir;

        return $this;
    }

    public function getMarque(): ?Marque
    {
        return $this->marque;
    }

    public function setMarque(?Marque $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function getCaracteristique(): ?string
    {
        return $this->caracteristique;
    }

    public function setCaracteristique(?string $caracteristique): self
    {
        $this->caracteristique = $caracteristique;

        return $this;
    }

    public function getProduitType(): ?int
    {
        return $this->produit_type;
    }

    public function setProduitType(int $produit_type): self
    {
        $this->produit_type = $produit_type;

        return $this;
    }

    public function getVendeur(): ?Vendeur
    {
        return $this->vendeur;
    }

    public function setVendeur(?Vendeur $vendeur): self
    {
        $this->vendeur = $vendeur;

        return $this;
    }

    public function getDateCreation(): ?string
    {
        return $this->DateCreation;
    }

    public function setDateCreation(string $DateCreation): self
    {
        $this->DateCreation = $DateCreation;

        return $this;
    }


    /**
     * @return Collection<int, Variation>
     */
    public function getVariations(): Collection
    {
        return $this->variations;
    }

    public function addVariation(Variation $variation): self
    {
        if (!$this->variations->contains($variation)) {
            $this->variations->add($variation);
            $variation->setProduit($this);
        }

        return $this;
    }

    public function removeVariation(Variation $variation): self
    {
        if ($this->variations->removeElement($variation)) {
            // set the owning side to null (unless already changed)
            if ($variation->getProduit() === $this) {
                $variation->setProduit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CommentaireProduit>
     */
    public function getCommentaireProduits(): Collection
    {
        return $this->commentaireProduits;
    }

    public function addCommentaireProduit(CommentaireProduit $commentaireProduit): self
    {
        if (!$this->commentaireProduits->contains($commentaireProduit)) {
            $this->commentaireProduits->add($commentaireProduit);
            $commentaireProduit->setProduit($this);
        }

        return $this;
    }

    public function removeCommentaireProduit(CommentaireProduit $commentaireProduit): self
    {
        if ($this->commentaireProduits->removeElement($commentaireProduit)) {
            // set the owning side to null (unless already changed)
            if ($commentaireProduit->getProduit() === $this) {
                $commentaireProduit->setProduit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SousAttribut>
     */
    public function getTerms(): Collection
    {
        return $this->terms;
    }

    public function addTerm(SousAttribut $term): self
    {
        if (!$this->terms->contains($term)) {
            $this->terms->add($term);
        }

        return $this;
    }

    public function removeTerm(SousAttribut $term): self
    {
        $this->terms->removeElement($term);

        return $this;
    }

    /**
     * @return Collection<int, DetailsCommande>
     */
    public function getDetailsCommandes(): Collection
    {
        return $this->detailsCommandes;
    }

    public function addDetailsCommande(DetailsCommande $detailsCommande): self
    {
        if (!$this->detailsCommandes->contains($detailsCommande)) {
            $this->detailsCommandes->add($detailsCommande);
            $detailsCommande->setProduit($this);
        }

        return $this;
    }

    public function removeDetailsCommande(DetailsCommande $detailsCommande): self
    {
        if ($this->detailsCommandes->removeElement($detailsCommande)) {
            // set the owning side to null (unless already changed)
            if ($detailsCommande->getProduit() === $this) {
                $detailsCommande->setProduit(null);
            }
        }

        return $this;
    }
}