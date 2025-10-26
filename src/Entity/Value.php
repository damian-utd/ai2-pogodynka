<?php

namespace App\Entity;

use App\Repository\ValueRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ValueRepository::class)]
class Value
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Measurement::class, inversedBy: 'value')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Measurement $measurement = null;

    #[ORM\ManyToOne(targetEntity: Attributes::class, inversedBy: 'value')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Attributes $attribute = null;

    #[ORM\Column(length: 255)]
    private ?string $value = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMeasurement(): ?Measurement
    {
        return $this->measurement;
    }

    public function setMeasurement(?Measurement $measurement): static
    {
        $this->measurement = $measurement;

        return $this;
    }

    public function getAttribute(): ?Attributes
    {
        return $this->attribute;
    }

    public function setAttribute(?Attributes $attribute): static
    {
        $this->attribute = $attribute;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): static
    {
        $this->value = $value;

        return $this;
    }
}
