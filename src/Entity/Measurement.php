<?php

namespace App\Entity;

use App\Repository\MeasurementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MeasurementRepository::class)]
class Measurement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Location::class, inversedBy: 'measurements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Location $location = null;


    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $date = null;

    /**
     * @var Collection<int, Value>
     */
    #[ORM\OneToMany(targetEntity: Value::class, mappedBy: 'measurement')]
    private Collection $value;

    public function __construct()
    {
        $this->value = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): static
    {
        $this->date = $date;

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
            $value->setMeasurement($this);
        }

        return $this;
    }

    public function removeValue(Value $value): static
    {
        if ($this->value->removeElement($value)) {
            // set the owning side to null (unless already changed)
            if ($value->getMeasurement() === $this) {
                $value->setMeasurement(null);
            }
        }

        return $this;
    }
}
