<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use http\Message;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups("getAllUsers")]
    #[Assert\NotBlank(message:"le nom du user est obligatoire")]
    #[Assert\length(min:1, max:255, minMessage:"le titre doit daire au moins 1 caractère", maxMessage:" le titre ne doit pas dépasser 255 caracteres")]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups("getAllUsers")]
    #[Assert\NotBlank(message:"le mail du user est obligatoire")]
    #[Assert\length(min:1, max:255, minMessage:"le titre doit daire au moins 1 caractère", maxMessage:" le titre ne doit pas dépasser 255 caracteres")]
    private ?string $surname = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(nullable: true)]
    #[Groups("getAllUsers")]
    private ?int $numberStreet = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups("getAllUsers")]
    private ?string $typeStreet = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups("getAllUsers")]
    private ?string $nameStreet = null;

    #[ORM\Column]
    #[Groups("getAllUsers")]
    private ?int $postal_code = null;

    #[ORM\Column(length: 255)]
    #[Groups("getAllUsers")]
    #[Assert\NotBlank(message:"la ville du user est obligatoire")]
    private ?string $Town = null;

    #[ORM\ManyToOne(targetEntity: 'Client', cascade:['persist'],fetch: 'EAGER', inversedBy: 'users')]
    #[Groups("getAllUsers")]
    #[Assert\NotBlank(message:"le client du user est obligatoire")]
    private $client;

    public function getClient(): ?Client
    {
        return $this->client;
    }
    public function setClient($client): self
    {
        $this->client = $client;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }
    public function getNumberStreet(): ?int
    {
        return $this->numberStreet;
    }

    public function setNumberStreet(?int $numberStreet): self
    {
        $this->numberStreet = $numberStreet;

        return $this;
    }

    public function getTypeStreet(): ?string
    {
        return $this->typeStreet;
    }

    public function setTypeStreet(?string $typeStreet): self
    {
        $this->typeStreet = $typeStreet;

        return $this;
    }

    public function getNameStreet(): ?string
    {
        return $this->nameStreet;
    }

    public function setNameStreet(?string $nameStreet): self
    {
        $this->nameStreet = $nameStreet;

        return $this;
    }

    public function getPostalCode(): ?int
    {
        return $this->postal_code;
    }

    public function setPostalCode(int $postal_code): self
    {
        $this->postal_code = $postal_code;

        return $this;
    }

    public function getTown(): ?string
    {
        return $this->Town;
    }

    public function setTown(string $Town): self
    {
        $this->Town = $Town;

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

    public function getUsername(): string {
        return $this->getUserIdentifier();
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
}