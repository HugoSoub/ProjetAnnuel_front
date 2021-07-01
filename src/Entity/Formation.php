<?php

namespace App\Entity;

use JsonSerializable;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\FormationRepository;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 */
class Formation implements JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    public function jsonSerialize()
    {
        return array(
            'id'              => $this->id,
            'name'            => $this->name,
            'date'            => $this->date,
            'borderColor'     => '#4680ff',
            'backgroundColor' => '#4680ff',
            'textColor'       => '#fff'
        );
    }
}
