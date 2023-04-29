<?php

namespace App\Entity;

use App\Repository\CommandesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandesRepository::class)]
class Commandes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Prix = null;

    #[ORM\Column(length: 255)]
    private ?string $DateCommande = null;

    #[ORM\Column(length: 255)]
    private ?string $DateLivraison = null;

    #[ORM\Column(length: 255)]
    private ?string $AdresseLivraison = null;

    #[ORM\Column(length: 255)]
    private ?string $PrixLivraison = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    private ?StatutPaiement $StatutPaiement = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    private ?Client $Client = null;

    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: DetailsCommande::class)]
    private Collection $detailsCommandes;

    public function __construct()
    {
        $this->detailsCommandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrix(): ?string
    {
        return $this->Prix;
    }

    public function setPrix(string $Prix): self
    {
        $this->Prix = $Prix;

        return $this;
    }

    public function getDateCommande(): ?string
    {
        return $this->DateCommande;
    }

    public function setDateCommande(string $DateCommande): self
    {
        $this->DateCommande = $DateCommande;

        return $this;
    }

    public function getDateLivraison(): ?string
    {
        return $this->DateLivraison;
    }

    public function setDateLivraison(string $DateLivraison): self
    {
        $this->DateLivraison = $DateLivraison;

        return $this;
    }

    public function getAdresseLivraison(): ?string
    {
        return $this->AdresseLivraison;
    }

    public function setAdresseLivraison(string $AdresseLivraison): self
    {
        $this->AdresseLivraison = $AdresseLivraison;

        return $this;
    }

    public function getPrixLivraison(): ?string
    {
        return $this->PrixLivraison;
    }

    public function setPrixLivraison(string $PrixLivraison): self
    {
        $this->PrixLivraison = $PrixLivraison;

        return $this;
    }

    public function getStatutPaiement(): ?StatutPaiement
    {
        return $this->StatutPaiement;
    }

    public function setStatutPaiement(?StatutPaiement $StatutPaiement): self
    {
        $this->StatutPaiement = $StatutPaiement;

        return $this;
    }


    public function getClient(): ?Client
    {
        return $this->Client;
    }

    public function setClient(?Client $Client): self
    {
        $this->Client = $Client;

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
            $detailsCommande->setCommande($this);
        }

        return $this;
    }

    public function removeDetailsCommande(DetailsCommande $detailsCommande): self
    {
        if ($this->detailsCommandes->removeElement($detailsCommande)) {
            // set the owning side to null (unless already changed)
            if ($detailsCommande->getCommande() === $this) {
                $detailsCommande->setCommande(null);
            }
        }

        return $this;
    }
}