<?php

namespace App\Entity;

use App\Repository\ConfigurationCreateurRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConfigurationCreateurRepository::class)]
class ConfigurationCreateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $CouleurFond = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $CouleurHeader = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $CouleurFooter = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $FontFamily = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $FontSize = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ModeValidationVendeur = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $TauxCommission = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $ConditionGeneraleVente = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $MentionsLegales = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $PolitiqueConfidentialite = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $ConditionGeneraleUtilisation = null;

    #[ORM\OneToOne(inversedBy: 'configurationCreateur', cascade: ['persist', 'remove'])]
    private ?Createur $Createur = null;

    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCouleurFond(): ?string
    {
        return $this->CouleurFond;
    }

    public function setCouleurFond(?string $CouleurFond): self
    {
        $this->CouleurFond = $CouleurFond;

        return $this;
    }

    public function getCouleurHeader(): ?string
    {
        return $this->CouleurHeader;
    }

    public function setCouleurHeader(?string $CouleurHeader): self
    {
        $this->CouleurHeader = $CouleurHeader;

        return $this;
    }

    public function getCouleurFooter(): ?string
    {
        return $this->CouleurFooter;
    }

    public function setCouleurFooter(?string $CouleurFooter): self
    {
        $this->CouleurFooter = $CouleurFooter;

        return $this;
    }

    public function getFontFamily(): ?string
    {
        return $this->FontFamily;
    }

    public function setFontFamily(?string $FontFamily): self
    {
        $this->FontFamily = $FontFamily;

        return $this;
    }

    public function getFontSize(): ?string
    {
        return $this->FontSize;
    }

    public function setFontSize(?string $FontSize): self
    {
        $this->FontSize = $FontSize;

        return $this;
    }

    public function getModeValidationVendeur(): ?string
    {
        return $this->ModeValidationVendeur;
    }

    public function setModeValidationVendeur(string $ModeValidationVendeur): self
    {
        $this->ModeValidationVendeur = $ModeValidationVendeur;

        return $this;
    }

    public function getTauxCommission(): ?string
    {
        return $this->TauxCommission;
    }

    public function setTauxCommission(string $TauxCommission): self
    {
        $this->TauxCommission = $TauxCommission;

        return $this;
    }

    public function getConditionGeneraleVente(): ?string
    {
        return $this->ConditionGeneraleVente;
    }

    public function setConditionGeneraleVente(?string $ConditionGeneraleVente): self
    {
        $this->ConditionGeneraleVente = $ConditionGeneraleVente;

        return $this;
    }

    public function getMentionsLegales(): ?string
    {
        return $this->MentionsLegales;
    }

    public function setMentionsLegales(?string $MentionsLegales): self
    {
        $this->MentionsLegales = $MentionsLegales;

        return $this;
    }

    public function getPolitiqueConfidentialite(): ?string
    {
        return $this->PolitiqueConfidentialite;
    }

    public function setPolitiqueConfidentialite(string $PolitiqueConfidentialite): self
    {
        $this->PolitiqueConfidentialite = $PolitiqueConfidentialite;

        return $this;
    }

    public function getConditionGeneraleUtilisation(): ?string
    {
        return $this->ConditionGeneraleUtilisation;
    }

    public function setConditionGeneraleUtilisation(string $ConditionGeneraleUtilisation): self
    {
        $this->ConditionGeneraleUtilisation = $ConditionGeneraleUtilisation;

        return $this;
    }

    public function getCreateur(): ?Createur
    {
        return $this->Createur;
    }

    public function setCreateur(?Createur $Createur): self
    {
        $this->Createur = $Createur;

        return $this;
    }
}
