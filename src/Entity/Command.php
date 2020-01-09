<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommandRepository")
 * @UniqueEntity("reference")
 */
class Command
{
    const STATE = [
        "0" => "En attente de fichier",
        "1" => "En attente de B.A.T.",
        "2" => "En cours de production",
        "3" => "En cours de livraison",
        "4" => "Livrée",
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\Regex(pattern="/[0-9]{6}[\.][0-9]{4}$/", htmlPattern=false, message="La référence doit être de la forme 000000.0000")
     * @ORM\Column(type="string", length=255)
     */
    public $reference;

    /**
     * @ORM\Column(type="integer", options={"default":0})
     */
    private $state = 0;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * One command has many items. This is the inverse side.
     * @OneToMany(targetEntity="Item", mappedBy="command", cascade={"persist"})
     */
    private $items;

    /**
     * Command constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->created_at = new \DateTime();
        $this->updated_at = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getState(): ?int
    {
        return $this->state;
    }

    public function setState(int $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getStateType(): string
    {
        return self::STATE[$this->getState()];
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection|Item[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Item $item): self
    {
        if (!$this->items->contains($item)) {
            $item->addCommand($this);
            $this->items[] = $item;
        }

        return $this;
    }

    public function removeItem(Item $item): self
    {
        if ($this->items->contains($item)) {
            $item->removeCommand($this);
            $this->items->removeElement($item);
        }

        return $this;
    }
}
