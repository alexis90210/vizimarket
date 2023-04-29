<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client implements UserInterface, PasswordAuthenticatedUserInterface
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
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getConversations"])]
    private ?string $prenom = null;

    #[ORM\Column(length: 15)]
    private ?string $tel = null;

    #[ORM\Column]
    private ?string $createdAt = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(["getConversations"])]
    private ?string $photo = null;

    // #[ORM\OneToMany(mappedBy: 'expediteur', targetEntity: CommentaireProduit::class)]
    // private Collection $commentaireProduits;

    // #[ORM\OneToMany(mappedBy: 'Expediteur', targetEntity: ReponseCommentaire::class)]
    // private Collection $reponseCommentaires;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Conversation::class)]
    private Collection $conversations;

    #[ORM\OneToMany(mappedBy: 'Client', targetEntity: Commandes::class)]
    private Collection $commandes;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: CommentaireProduit::class)]
    private Collection $commentaireProduits;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: ReponseCommentaire::class)]
    private Collection $reponseCommentaires;


    public function __construct()
    {
        $this->roles[] = 'ROLE_CLIENT';
        $this->createdAt = date('d/m/Y H:i:s');
        // $this->commentaireProduits = new ArrayCollection();
        // $this->reponseCommentaires = new ArrayCollection();
        $this->conversations = new ArrayCollection();
        $this->commandes = new ArrayCollection();
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

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(string $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;

        return $this;
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

    // /**
    //  * @return Collection<int, CommentaireProduit>
    //  */
    // public function getCommentaireProduits(): Collection
    // {
    //     return $this->commentaireProduits;
    // }

    // public function addCommentaireProduit(CommentaireProduit $commentaireProduit): self
    // {
    //     if (!$this->commentaireProduits->contains($commentaireProduit)) {
    //         $this->commentaireProduits->add($commentaireProduit);
    //         $commentaireProduit->setExpediteur($this);
    //     }

    //     return $this;
    // }

    // public function removeCommentaireProduit(CommentaireProduit $commentaireProduit): self
    // {
    //     if ($this->commentaireProduits->removeElement($commentaireProduit)) {
    //         // set the owning side to null (unless already changed)
    //         if ($commentaireProduit->getExpediteur() === $this) {
    //             $commentaireProduit->setExpediteur(null);
    //         }
    //     }

    //     return $this;
    // }

    // /**
    //  * @return Collection<int, ReponseCommentaire>
    //  */
    // public function getReponseCommentaires(): Collection
    // {
    //     return $this->reponseCommentaires;
    // }

    // public function addReponseCommentaire(ReponseCommentaire $reponseCommentaire): self
    // {
    //     if (!$this->reponseCommentaires->contains($reponseCommentaire)) {
    //         $this->reponseCommentaires->add($reponseCommentaire);
    //         $reponseCommentaire->setExpediteur($this);
    //     }

    //     return $this;
    // }

    // public function removeReponseCommentaire(ReponseCommentaire $reponseCommentaire): self
    // {
    //     if ($this->reponseCommentaires->removeElement($reponseCommentaire)) {
    //         // set the owning side to null (unless already changed)
    //         if ($reponseCommentaire->getExpediteur() === $this) {
    //             $reponseCommentaire->setExpediteur(null);
    //         }
    //     }

    //     return $this;
    // }

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
            $conversation->setClient($this);
        }

        return $this;
    }

    public function removeConversation(Conversation $conversation): self
    {
        if ($this->conversations->removeElement($conversation)) {
            // set the owning side to null (unless already changed)
            if ($conversation->getClient() === $this) {
                $conversation->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Commandes>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commandes $commande): self
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes->add($commande);
            $commande->setClient($this);
        }

        return $this;
    }

    public function removeCommande(Commandes $commande): self
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getClient() === $this) {
                $commande->setClient(null);
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
            $commentaireProduit->setClient($this);
        }

        return $this;
    }

    public function removeCommentaireProduit(CommentaireProduit $commentaireProduit): self
    {
        if ($this->commentaireProduits->removeElement($commentaireProduit)) {
            // set the owning side to null (unless already changed)
            if ($commentaireProduit->getClient() === $this) {
                $commentaireProduit->setClient(null);
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
            $reponseCommentaire->setClient($this);
        }

        return $this;
    }

    public function removeReponseCommentaire(ReponseCommentaire $reponseCommentaire): self
    {
        if ($this->reponseCommentaires->removeElement($reponseCommentaire)) {
            // set the owning side to null (unless already changed)
            if ($reponseCommentaire->getClient() === $this) {
                $reponseCommentaire->setClient(null);
            }
        }

        return $this;
    }
}
