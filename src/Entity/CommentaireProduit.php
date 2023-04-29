<?php

namespace App\Entity;

use App\Repository\CommentaireProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentaireProduitRepository::class)]
class CommentaireProduit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'commentaireProduits')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ProduitSimple $produit = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $contenu = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $date = null;

    #[ORM\OneToMany(mappedBy: 'commentaire', targetEntity: ReponseCommentaire::class)]
    private Collection $reponseCommentaires;

    #[ORM\ManyToOne(inversedBy: 'commentaireProduits')]
    private ?Client $client = null;

    #[ORM\ManyToOne(inversedBy: 'commentaireProduits')]
    private ?Vendeur $vendeur = null;

    #[ORM\Column]
    private ?int $type = null;

    public function __construct()
    {
        $this->reponseCommentaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(string $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection<int, ReponseCommentaire>
     */
    public function getReponseCommentaires(): Collection
    {
        return $this->reponseCommentaires;
    }

    public function addReponseCommentaire(ReponseCommentaire $reponseCommentaire): self
    {
        if (!$this->reponseCommentaires->contains($reponseCommentaire)) {
            $this->reponseCommentaires->add($reponseCommentaire);
            $reponseCommentaire->setCommentaire($this);
        }

        return $this;
    }

    public function removeReponseCommentaire(ReponseCommentaire $reponseCommentaire): self
    {
        if ($this->reponseCommentaires->removeElement($reponseCommentaire)) {
            // set the owning side to null (unless already changed)
            if ($reponseCommentaire->getCommentaire() === $this) {
                $reponseCommentaire->setCommentaire(null);
            }
        }

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

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

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }
}
