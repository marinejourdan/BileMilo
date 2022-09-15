<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\Since;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Hateoas\Relation(
 *      "self",
 *      href = @Hateoas\Route(
 *          "detailClient",
 *          parameters = { "id" = "expr(object.getId())" }
 *      ),
 *      exclusion = @Hateoas\Exclusion(groups="getAllClients")
 * )
 * @Hateoas\Relation(
 *      "delete",
 *      href = @Hateoas\Route(
 *          "deleteClient",
 *          parameters = { "id" = "expr(object.getId())" }
 *      ),
 *      exclusion = @Hateoas\Exclusion(groups="getAllClients")
 * )
 * @Hateoas\Relation(
 *      "update",
 *      href = @Hateoas\Route(
 *          "updateClient",
 *          parameters = { "id" = "expr(object.getId())" }
 *      ),
 *      exclusion = @Hateoas\Exclusion(groups="getAllClients")
 * )
 */
#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['getAllClients'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['getAllClients'])]
    #[Assert\NotBlank(message: 'le nom du client est obligatoire')]
    #[Assert\length(min: 1, max: 255, minMessage: 'le titre doit daire au moins 1 caractère', maxMessage: ' le titre ne doit pas dépasser 255 caracteres')]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['getAllClients'])]
    #[Assert\NotBlank(message: 'le mail du client est obligatoire')]
    #[Assert\length(min: 1, max: 255, minMessage: 'le titre doit faire au moins 1 caractère', maxMessage: ' le titre ne doit pas dépasser 255 caracteres')]
    private ?string $surname = null;

    #[Groups(['getAllClients'])]
    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['getAllClients'])]
    private ?int $numberStreet = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['getAllClients'])]
    private ?string $typeStreet = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['getAllClients'])]
    private ?string $nameStreet = null;

    #[ORM\Column]
    #[Groups(['getAllClients'])]
    private ?int $postal_code = null;

    #[ORM\Column(length: 255)]
    #[Groups(['getAllClients'])]
    #[Assert\NotBlank(message: 'la ville du user est obligatoire')]
    private ?string $Town = null;

    #[ORM\ManyToOne(targetEntity: 'User', fetch: 'EAGER', inversedBy: 'clients')]
    private $user;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['getAllClients'])]
    #[Since('2.0')]
    private ?string $comment = null;

    public function getUser()
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(Client $client): self
    {
        $this->client = $client;

        return $this;
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

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }
}
