<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SensorRepository")
 */
class Sensor
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"get", "sensors"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"sensors"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=32)
     * @Groups({"sensors"})
     */
    private $mac;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SensorMeasure", mappedBy="sensor", orphanRemoval=true)
     */
    private $sensorMeasures;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"sensors"})
     */
    private $type;

    public function __construct()
    {
        $this->sensorMeasures = new ArrayCollection();
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

    public function getMac(): ?string
    {
        return $this->mac;
    }

    public function setMac(string $mac): self
    {
        $this->mac = $mac;

        return $this;
    }

    /**
     * @return Collection|SensorMeasure[]
     */
    public function getSensorMeasures(): Collection
    {
        return $this->sensorMeasures;
    }

    public function addSensorMeasure(SensorMeasure $sensorMeasure): self
    {
        if (!$this->sensorMeasures->contains($sensorMeasure)) {
            $this->sensorMeasures[] = $sensorMeasure;
            $sensorMeasure->setSensor($this);
        }

        return $this;
    }

    public function removeSensorMeasure(SensorMeasure $sensorMeasure): self
    {
        if ($this->sensorMeasures->contains($sensorMeasure)) {
            $this->sensorMeasures->removeElement($sensorMeasure);
            // set the owning side to null (unless already changed)
            if ($sensorMeasure->getSensor() === $this) {
                $sensorMeasure->setSensor(null);
            }
        }

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}
