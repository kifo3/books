<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\DeleteAction;
use App\Entity\Interfaces\CreatedAtSettableInterface;
use App\Entity\Interfaces\IsDeletedSettableInterface;
use App\Entity\Interfaces\UpdatedAtSettableInterface;
use App\Entity\Interfaces\UserSettableInterface;
use App\Repository\BookRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: BookRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => [
            //'security' => "is_granted('ROLE_ADMIN')",

        ],
        'post'
    ],
    itemOperations: [
        'get' => [
            'security' => "object.getUser() == user || is_granted('ROLE_ADMIN')",
        ],
        'put' => [
            'security' => "object.getUser() == user || is_granted('ROLE_ADMIN')",
        ],
        'delete' => [
            'controller' => DeleteAction::class,
            'security' => "object.getUser() == user || is_granted('ROLE_ADMIN')",
        ]
    ],
    denormalizationContext: ['groups' => ['book:write']],
    normalizationContext: ['groups' => ['book:read']],
)]
class Book implements
    UserSettableInterface,
    CreatedAtSettableInterface,
    UpdatedAtSettableInterface,
    IsDeletedSettableInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['book:read'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['book:read', 'book:write'])]
    private $name;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'books')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['book:read'])]
    private $user;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['book:read'])]
    private $createdAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Groups(['book:read'])]
    private $updatedAt;

    #[ORM\Column(type: 'boolean')]
    #[Groups(['book:read'])]
    private $isDeleted = false;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['book:read', 'book:write'])]
    private $description;

    #[ORM\Column(type: 'text')]
    #[Groups(['book:read', 'book:write'])]
    private $text;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'books')]
    #[Groups(['book:read', 'book:write'])]
    private $category;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?UserInterface $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function isIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }
}
