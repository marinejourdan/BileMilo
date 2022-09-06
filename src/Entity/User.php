<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use http\Message;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
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

    #[ORM\Column(length: 255)]
    #[Groups("getAllUsers")]
    #[Assert\NotBlank(message:"le prenom du user est obligatoire")]
    #[Assert\length(min:3, max:255, minMessage:"le titre doit daire au moins 3 caractères", maxMessage:" le titre ne doit pas dépasser 255 caracteres")]
    private ?string $mail = null;

    #[ORM\Column(length: 255)]
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

    public function setClient(Client $client): self
    {
        $this->client = $client;

        return $this;
    }
    public function getId(): ?int
    {
        return $this->id;
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

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

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
}
