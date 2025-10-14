<?php

namespace App\Entity;

use App\Repository\AttributesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AttributesRepository::class)]
class Attributes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $unit = null;

    /**
     * @var Collection<int, Value>
     */
    #[ORM\OneToMany(targetEntity: Value::class, mappedBy: 'attribute')]
    private Collection $value;

    public function __construct()
    {
        $this->value = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(?string $unit): static
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * @return Collection<int, Value>
     */
    public function getValue(): Collection
    {
        return $this->value;
    }

    public function addValue(Value $value): static
    {
        if (!$this->value->contains($value)) {
            $this->value->add($value);
            $value->setAttribute($this);
        }

        return $this;
    }

    public function removeValue(Value $value): static
    {
        if ($this->value->removeElement($value)) {
            // set the owning side to null (unless already changed)
            if ($value->getAttribute() === $this) {
                $value->setAttribute(null);
            }
        }

        return $this;
    }
}
