<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MeasureRepository")
 */
class Measure
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"get"})
     */
    private $time;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SensorMeasure", mappedBy="measure", orphanRemoval=true)
     * @Groups({"get"})
     */
    private $sensorMeasures;

    public function __construct()
    {
        $this->sensorMeasures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(\DateTimeInterface $time): self
    {
        $this->time = $time;

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
            $sensorMeasure->setMeasure($this);
        }

        return $this;
    }

    public function removeSensorMeasure(SensorMeasure $sensorMeasure): self
    {
        if ($this->sensorMeasures->contains($sensorMeasure)) {
            $this->sensorMeasures->removeElement($sensorMeasure);
            // set the owning side to null (unless already changed)
            if ($sensorMeasure->getMeasure() === $this) {
                $sensorMeasure->setMeasure(null);
            }
        }

        return $this;
    }
}
