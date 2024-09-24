<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\ManyToOne(targetEntity: Incident::class, inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Incident $incident = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null; // Changement ici

    // Getters et setters...

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;
        return $this;
    }

    public function getIncident(): ?Incident
    {
        return $this->incident;
    }

    public function setIncident(?Incident $incident): static
    {
        $this->incident = $incident;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user; // Getter pour l'utilisateur
    }

    public function setUser(User $user): static
    {
        $this->user = $user; // Setter pour l'utilisateur
        return $this;
    }
}
