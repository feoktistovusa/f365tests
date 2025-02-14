<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Attribute\SerializedName;

#[ORM\Entity]
#[ORM\Table(name: "patient")]
class Patient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "bigint", options: ["unsigned" => true])]
    #[OA\Property(description: "Unique identifier for the patient")]
    #[Groups(['patient:read'])]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 20)]
    #[OA\Property(description: "Title of the patient (Mr, Mrs, Dr)")]
    #[Groups(['patient:read', 'patient:write'])]
    private ?string $title;

    #[ORM\Column(type: "string", length: 100)]
    #[OA\Property(description: "First name of the patient")]
    #[Groups(['patient:read', 'patient:write'])]
    private ?string $firstName;

    #[ORM\Column(type: "string", length: 100)]
    #[OA\Property(description: "Last name of the patient")]
    #[Groups(['patient:read', 'patient:write'])]
    private ?string $lastName;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[OA\Property(description: "Date of birth of the patient")]
    #[Groups(['patient:write'])]
    private ?\DateTimeInterface $dob;

    #[ORM\Column(type: "datetime", options: ["default" => "CURRENT_TIMESTAMP"])]
    #[OA\Property(description: "Record creation timestamp")]
    #[Groups(['patient:read'])]
    private ?\DateTimeInterface $createdAt;

    #[ORM\Column(type: "datetime", options: ["default" => "CURRENT_TIMESTAMP", "on update" => "CURRENT_TIMESTAMP"])]
    #[OA\Property(description: "Record last update timestamp")]
    #[Groups(['patient:read'])]
    private ?\DateTimeInterface $updatedAt;

    #[ORM\Column(type: "boolean", options: ["default" => false])]
    #[OA\Property(description: "Indicates if the patient is inactive (on hold)")]
    private ?bool $onHold = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getDob(): ?\DateTimeInterface
    {
        return $this->dob;
    }

    public function setDob(?\DateTimeInterface $dob): self
    {
        $this->dob = $dob;
        return $this;
    }

    #[Groups(['patient:read'])]
    #[SerializedName('dob')]
    public function getDobFormatted(): ?string
    {
        return $this->dob?->format('Y-m-d');
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function isOnHold(): ?bool
    {
        return $this->onHold;
    }

    public function setOnHold(?bool $onHold): self
    {
        $this->onHold = $onHold;
        return $this;
    }
}
