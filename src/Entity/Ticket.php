<?php

namespace App\Entity;

use App\Repository\TicketRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;

#[ORM\Entity(repositoryClass: TicketRepository::class)]
#[ApiResource]
class Ticket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'tickets')]
    private ?User $owner = null;

    #[ORM\ManyToOne]
    private ?User $assignee = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $first_assigned_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $last_assigned_at = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(type: 'text')]
    private ?string $description = null;

    #[ORM\Column(length: 10)]
    private ?string $priority = null;

    #[ORM\Column(length: 15)]
    private ?string $state = null;

    public function __construct(string $title, string $description, string $priority)
    {
        $this->title = $title;
        $this->description = $description;
        $this->priority = $priority;
        $this->state = 'PENDING';
        $this->created_at = new \DateTimeImmutable(); // si nécessaire
    }
    public function start(): void
    {
        if ($this->state === 'PENDING') {
            $this->state = 'IN_PROGRESS';
            $this->first_assigned_at = new \DateTimeImmutable();
            $this->last_assigned_at = new \DateTimeImmutable();
        }
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    public function getAssignee(): ?User
    {
        return $this->assignee;
    }

    public function setAssignee(?User $assignee): static
    {
        $this->assignee = $assignee;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getFirstAssignedAt(): ?\DateTimeImmutable
    {
        return $this->first_assigned_at;
    }

    public function setFirstAssignedAt(\DateTimeImmutable $first_assigned_at): static
    {
        $this->first_assigned_at = $first_assigned_at;

        return $this;
    }

    public function getLastAssignedAt(): ?\DateTimeImmutable
    {
        return $this->last_assigned_at;
    }

    public function setLastAssignedAt(\DateTimeImmutable $last_assigned_at): static
    {
        $this->last_assigned_at = $last_assigned_at;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPriority(): ?string
    {
        return $this->priority;
    }

    public function setPriority(string $priority): static
    {
        $this->priority = $priority;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): static
    {
        $this->state = $state;

        return $this;
    }
}
