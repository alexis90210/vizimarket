<?php

namespace App\Entity;

use App\Repository\CreateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: CreateurRepository::class)]
class Createur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $dateInscription = null;

    #[ORM\Column(length: 255)]
    private ?string $Logo = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $ConditionGeneralVente = "";

    #[ORM\Column(type: Types::TEXT)]
    private ?string $MentionLegale = "";

    #[ORM\Column(type: Types::TEXT)]
    private ?string $ConditionGeneralUtilisation = "";

    #[ORM\Column]
    private ?float $CommissionVendeur = 0.0;

    #[ORM\Column(length: 255)]
    private ?string $SitePoliceLink = "";

    #[ORM\Column(length: 255)]
    private ?string $SitePoliceNom = "";

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $typeValidationVendeur = 0;

    #[ORM\OneToMany(mappedBy: 'createur', targetEntity: Vendeur::class)]
    private Collection $Vendeur;

    #[ORM\Column(length: 255)]
    private ?string $access = null;

    #[ORM\OneToOne(mappedBy: 'Createur', cascade: ['persist', 'remove'])]
    private ?ConfigurationCreateur $configurationCreateur = null;

    public function __construct()
    {
        $this->roles[] = 'ROLE_CREATEUR';
        $this->access = 'createur';
        $this->dateInscription = date("d/m/Y H:i:s");
        $this->Vendeur = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getDateInscription(): ?string
    {
        return $this->dateInscription;
    }

    public function setDateInscription(string $dateInscription): self
    {
        $this->dateInscription = $dateInscription;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->Logo;
    }

    public function setLogo(string $Logo): self
    {
        $this->Logo = $Logo;

        return $this;
    }

    public function getConditionGeneralVente(): ?string
    {
        return $this->ConditionGeneralVente;
    }

    public function setConditionGeneralVente(string $ConditionGeneralVente): self
    {
        $this->ConditionGeneralVente = $ConditionGeneralVente;

        return $this;
    }

    public function getMentionLegale(): ?string
    {
        return $this->MentionLegale;
    }

    public function setMentionLegale(string $MentionLegale): self
    {
        $this->MentionLegale = $MentionLegale;

        return $this;
    }

    public function getConditionGeneralUtilisation(): ?string
    {
        return $this->ConditionGeneralUtilisation;
    }

    public function setConditionGeneralUtilisation(string $ConditionGeneralUtilisation): self
    {
        $this->ConditionGeneralUtilisation = $ConditionGeneralUtilisation;

        return $this;
    }

    public function getCommissionVendeur(): ?float
    {
        return $this->CommissionVendeur;
    }

    public function setCommissionVendeur(float $CommissionVendeur): self
    {
        $this->CommissionVendeur = $CommissionVendeur;

        return $this;
    }

    public function getTypeValidationVendeur(): ?int
    {
        return $this->typeValidationVendeur;
    }

    public function setTypeValidationVendeur(int $typeValidationVendeur): self
    {
        $this->typeValidationVendeur = $typeValidationVendeur;

        return $this;
    }

    public function getSitePoliceLink(): ?string
    {
        return $this->SitePoliceLink;
    }

    public function setSitePoliceLink(string $SitePoliceLink): self
    {
        $this->SitePoliceLink = $SitePoliceLink;

        return $this;
    }

    public function getSitePoliceNom(): ?string
    {
        return $this->SitePoliceNom;
    }

    public function setSitePoliceNom(string $SitePoliceNom): self
    {
        $this->SitePoliceNom = $SitePoliceNom;

        return $this;
    }

    /**
     * @return Collection<int, Vendeur>
     */
    public function getVendeur(): Collection
    {
        return $this->Vendeur;
    }

    public function addVendeur(Vendeur $vendeur): self
    {
        if (!$this->Vendeur->contains($vendeur)) {
            $this->Vendeur->add($vendeur);
            $vendeur->setCreateur($this);
        }

        return $this;
    }

    public function removeVendeur(Vendeur $vendeur): self
    {
        if ($this->Vendeur->removeElement($vendeur)) {
            // set the owning side to null (unless already changed)
            if ($vendeur->getCreateur() === $this) {
                $vendeur->setCreateur(null);
            }
        }

        return $this;
    }

    public function getAccess(): ?string
    {
        return $this->access;
    }

    public function setAccess(string $access): self
    {
        $this->access = $access;

        return $this;
    }

    public function getConfigurationCreateur(): ?ConfigurationCreateur
    {
        return $this->configurationCreateur;
    }

    public function setConfigurationCreateur(?ConfigurationCreateur $configurationCreateur): self
    {
        // unset the owning side of the relation if necessary
        if ($configurationCreateur === null && $this->configurationCreateur !== null) {
            $this->configurationCreateur->setCreateur(null);
        }

        // set the owning side of the relation if necessary
        if ($configurationCreateur !== null && $configurationCreateur->getCreateur() !== $this) {
            $configurationCreateur->setCreateur($this);
        }

        $this->configurationCreateur = $configurationCreateur;

        return $this;
    }
}
