<?php

namespace App\Entity;

use App\Repository\AttributRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AttributRepository::class)]
class Attribut
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\OneToMany(mappedBy: 'attribut', targetEntity: SousAttribut::class)]
    private Collection $sousAttributs;


    public function __construct()
    {
        $this->sousAttributs = new ArrayCollection();
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, SousAttribut>
     */
    public function getSousAttributs(): Collection
    {
        return $this->sousAttributs;
    }

    public function addSousAttribut(SousAttribut $sousAttribut): self
    {
        if (!$this->sousAttributs->contains($sousAttribut)) {
            $this->sousAttributs->add($sousAttribut);
            $sousAttribut->setAttribut($this);
        }

        return $this;
    }

    public function removeSousAttribut(SousAttribut $sousAttribut): self
    {
        if ($this->sousAttributs->removeElement($sousAttribut)) {
            // set the owning side to null (unless already changed)
            if ($sousAttribut->getAttribut() === $this) {
                $sousAttribut->setAttribut(null);
            }
        }

        return $this;
    }
}
