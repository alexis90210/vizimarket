<?php

namespace App\Entity;

use App\Repository\MessagePriveRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MessagePriveRepository::class)]
class MessagePrive
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getConversations"])]
    private ?int $id = null;
    #[Groups(["getConversations"])]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $contenu = null;


    #[ORM\Column(length: 255)]
    #[Groups(["getConversations"])]
    private ?string $date = null;


    #[ORM\Column]
    #[Groups(["getConversations"])]
    private ?int $sens = null;

    #[ORM\ManyToOne(inversedBy: 'messagePrives')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Conversation $conversation = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSens(): ?int
    {
        return $this->sens;
    }

    public function setSens(int $sens): self
    {
        $this->sens = $sens;

        return $this;
    }

    public function getConversation(): ?Conversation
    {
        return $this->conversation;
    }

    public function setConversation(?Conversation $conversation): self
    {
        $this->conversation = $conversation;

        return $this;
    }
}
