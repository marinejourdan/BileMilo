<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("getAllUsers")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups("getAllUsers")]
    private ?string $name = null;

    #[ORM\OneToMany(targetEntity: 'User', fetch: 'EAGER', mappedBy: 'client')]
    private $users;

    public function getUsers()
    {
        return $this->users;
    }

    public function setUsers(?string $users): self
    {
        $this->users = $users;

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
}
