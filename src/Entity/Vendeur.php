<?php

namespace App\Entity;

use App\Repository\VendeurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: VendeurRepository::class)]
class Vendeur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getConversations"])]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups(["getConversations"])]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getConversations"])]
    private ?string $Nom = null;

    #[ORM\Column(length: 255)]
    private ?string $Mobile = null;

    #[ORM\Column(length: 255)]
    private ?string $Description = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(["getConversations"])]
    private ?string $ImageProfile = null;

    #[ORM\Column(length: 255)]
    private ?string $PhotoCouverture = null;

    #[ORM\Column(length: 255)]
    private ?string $Adresse = null;

    #[ORM\Column(length: 255)]
    private ?string $Pays = null;

    #[ORM\ManyToOne(inversedBy: 'Vendeur')]
    private ?Createur $createur = null;

    #[ORM\Column(length: 255)]
    private ?string $dateInscription = null;

    #[ORM\Column(length: 255)]
    private ?string $access = null;

    #[ORM\OneToMany(mappedBy: 'vendeur', targetEntity: ProduitSimple::class)]
    private Collection $ProduitSimple;


    #[ORM\OneToMany(mappedBy: 'vendeur', targetEntity: Conversation::class)]
    private Collection $conversations;

    #[ORM\OneToMany(mappedBy: 'vendeur', targetEntity: CommentaireProduit::class)]
    private Collection $commentaireProduits;

    #[ORM\OneToMany(mappedBy: 'vendeur', targetEntity: ReponseCommentaire::class)]
    private Collection $reponseCommentaires;

    public function __construct()
    {
        $this->roles[] = "ROLE_VENDEUR";
        $this->access = 'vendeur';
        $this->dateInscription = date('d/m/Y H:i:s');
        $this->ProduitSimple = new ArrayCollection();
        $this->conversations = new ArrayCollection();
        $this->commentaireProduits = new ArrayCollection();
        $this->reponseCommentaires = new ArrayCollection();
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
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getMobile(): ?string
    {
        return $this->Mobile;
    }

    public function setMobile(string $Mobile): self
    {
        $this->Mobile = $Mobile;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function getImageProfile(): ?string
    {
        return $this->ImageProfile;
    }

    public function setImageProfile(string $ImageProfile): self
    {
        $this->ImageProfile = $ImageProfile;

        return $this;
    }

    public function getPhotoCouverture(): ?string
    {
        return $this->PhotoCouverture;
    }

    public function setPhotoCouverture(string $PhotoCouverture): self
    {
        $this->PhotoCouverture = $PhotoCouverture;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->Adresse;
    }

    public function setAdresse(string $Adresse): self
    {
        $this->Adresse = $Adresse;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->Pays;
    }

    public function setPays(string $Pays): self
    {
        $this->Pays = $Pays;

        return $this;
    }

    public function getCreateur(): ?Createur
    {
        return $this->createur;
    }

    public function setCreateur(?Createur $createur): self
    {
        $this->createur = $createur;

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

    public function getAccess(): ?string
    {
        return $this->access;
    }

    public function setAccess(string $access): self
    {
        $this->access = $access;

        return $this;
    }

    /**
     * @return Collection<int, ProduitSimple>
     */
    public function getProduitSimple(): Collection
    {
        return $this->ProduitSimple;
    }

    public function addProduitSimple(ProduitSimple $produitSimple): self
    {
        if (!$this->ProduitSimple->contains($produitSimple)) {
            $this->ProduitSimple->add($produitSimple);
            $produitSimple->setVendeur($this);
        }

        return $this;
    }

    public function removeProduitSimple(ProduitSimple $produitSimple): self
    {
        if ($this->ProduitSimple->removeElement($produitSimple)) {
            // set the owning side to null (unless already changed)
            if ($produitSimple->getVendeur() === $this) {
                $produitSimple->setVendeur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Conversation>
     */
    public function getConversations(): Collection
    {
        return $this->conversations;
    }

    public function addConversation(Conversation $conversation): self
    {
        if (!$this->conversations->contains($conversation)) {
            $this->conversations->add($conversation);
            $conversation->setVendeur($this);
        }

        return $this;
    }

    public function removeConversation(Conversation $conversation): self
    {
        if ($this->conversations->removeElement($conversation)) {
            // set the owning side to null (unless already changed)
            if ($conversation->getVendeur() === $this) {
                $conversation->setVendeur(null);
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
            $commentaireProduit->setVendeur($this);
        }

        return $this;
    }

    public function removeCommentaireProduit(CommentaireProduit $commentaireProduit): self
    {
        if ($this->commentaireProduits->removeElement($commentaireProduit)) {
            // set the owning side to null (unless already changed)
            if ($commentaireProduit->getVendeur() === $this) {
                $commentaireProduit->setVendeur(null);
            }
        }

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
            $reponseCommentaire->setVendeur($this);
        }

        return $this;
    }

    public function removeReponseCommentaire(ReponseCommentaire $reponseCommentaire): self
    {
        if ($this->reponseCommentaires->removeElement($reponseCommentaire)) {
            // set the owning side to null (unless already changed)
            if ($reponseCommentaire->getVendeur() === $this) {
                $reponseCommentaire->setVendeur(null);
            }
        }

        return $this;
    }
}