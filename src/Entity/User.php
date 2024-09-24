<?php
namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;



#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $role = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;
        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;
        return $this;
    }

    /**
     * Méthode pour retourner les rôles de l'utilisateur.
     */
    public function getRoles(): array
    {
        // Symfony s'attend à un tableau de rôles
        return [$this->role];
    }

    /**
     * Nécessaire pour UserInterface, mais on peut retourner null
     * si on n'utilise pas un "salt".
     */
    public function getSalt(): ?string
    {
        return null; // Pas de "salt" car on utilise bcrypt ou autre algo moderne
    }

    /**
     * Méthode pour retourner l'identifiant unique de l'utilisateur (email dans ce cas).
     */
    public function getUserIdentifier(): string
    {
        return $this->email; // Utilise l'email comme identifiant
    }

    /**
     * Si tu stockes des données sensibles dans l'entité,
     * cette méthode est utilisée pour les effacer.
     */
    public function eraseCredentials(): void
    {
        // Si tu as des données sensibles dans l'entité, efface-les ici
    }
}
