<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\DeleteAction;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => [
            'security' => "is_granted('ROLE_ADMIN')",

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
            'security' => "object == user.grtUser() || is_granted('ROLE_ADMIN')",
        ]
    ],
    denormalizationContext: ['groups' => ['category:write']],
    normalizationContext: ['groups' => ['category:read']],
)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['category:read'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['category:read', 'category:write'])]
    private $name;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Book::class)]
    #[Groups(['category:read'])]
    private $books;

    public function __construct()
    {
        $this->books = new ArrayCollection();
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

    /**
     * @return Collection<int, Book>
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function addBook(Book $book): self
    {
        if (!$this->books->contains($book)) {
            $this->books[] = $book;
            $book->setCategory($this);
        }

        return $this;
    }

    public function removeBook(Book $book): self
    {
        if ($this->books->removeElement($book)) {
            // set the owning side to null (unless already changed)
            if ($book->getCategory() === $this) {
                $book->setCategory(null);
            }
        }

        return $this;
    }
}
