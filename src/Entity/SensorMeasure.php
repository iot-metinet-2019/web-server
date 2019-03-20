<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SensorMeasureRepository")
 */
class SensorMeasure
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"get"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Sensor", inversedBy="sensorMeasures")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"get"})
     */
    private $sensor;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Measure", inversedBy="sensorMeasures")
     * @ORM\JoinColumn(nullable=false)
     */
    private $measure;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get"})
     */
    private $value;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSensor(): ?Sensor
    {
        return $this->sensor;
    }

    public function setSensor(?Sensor $sensor): self
    {
        $this->sensor = $sensor;

        return $this;
    }

    public function getMeasure(): ?Measure
    {
        return $this->measure;
    }

    public function setMeasure(?Measure $measure): self
    {
        $this->measure = $measure;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }
}
