<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ItemRepository")
 */
class Item
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $reference = NULL;

    /**
     * Many items have one command. This is the owning side.
     * @ManyToOne(targetEntity="Command", inversedBy="items", cascade={"persist"})
     * @JoinColumn(name="command_id", referencedColumnName="id")
     */
    private $command;

    /**
     * Many items have one product. This is the owning side.
     * @ManyToOne(targetEntity="Product", inversedBy="items", cascade={"persist"})
     * @JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;


    public function __construct()
    {
        $this->command = new Command();
        $this->product = new Product();
    }

    /**
     * @return integer
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference)
    {
        $this->reference = "test";
        return $this;
    }

    /**
     * @return Command
     */
    public function getCommand(): Command
    {
        return $this->command;
    }

    public function addCommand(Command $command): self
    {
        $this->command = $command;
        return $this;
    }

    public function removeCommand(Command $command): self
    {
        if ($this->command->contains($command)) {
            $this->command->removeElement($command);
        }

        return $this;
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct($product)
    {
        $this->product = $product;
        return $this;
    }

    public function getOrderedQuantity()
    {
        return $this->quantity;
    }

    public function setOrderedQuantity($quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function addProduct(Product $product)
    {
        if (!$this->product->contains($product)) {
            $this->product->removeElement($product);
        }
        return $this;
    }

    public function removeProduct(Product $product)
    {
        if ($this->product->contains($product)) {
            $this->product->removeElement($product);
        }
        return $this;
    }
}
