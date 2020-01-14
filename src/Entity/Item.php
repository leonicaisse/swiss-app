<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ItemRepository")
 * @Vich\Uploadable
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

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string|null
     */
    private $filename;

    /**
     * @Vich\UploadableField(mapping="item_files", fileNameProperty="filename")
     * @var File|null
     */
    public $file;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $estimatedDelivery;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $realDelivery;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $deliverTo;

    /**
     * Item constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->command = new ArrayCollection();
        $this->product = new ArrayCollection();
        $this->updated_at = new \DateTime();
    }

    /**
     * @return integer
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return ArrayCollection
     */
    public function getCommand()
    {
        return $this->command;
    }

    public function addCommand(Command $command): self
    {
        $this->command = $command;
        return $this;
    }

    public function setCommand($command): self
    {
        $this->command = $command;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getProduct()
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

    /**
     * @return string|null
     */
    public function getFilename(): ?string
    {
        return $this->filename;
    }

    /**
     * @param string|null $filename
     * @return Item
     */
    public function setFilename(?string $filename): Item
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * @return File|null
     */
    public function getFile(): ?File
    {
        return $this->file;
    }

    /**
     * @param File $file
     * @return Item
     * @throws \Exception
     */
    public function setFile(File $file): Item
    {
        $this->file = $file;
        if ($this->file instanceof UploadedFile) {
            $this->command->updated_at = new \DateTime('now');
            $this->updated_at = new \DateTime('now');
        }
        return $this;
    }

    public function getEstimatedDelivery(): ?\DateTimeInterface
    {
        return $this->estimatedDelivery;
    }

    public function setEstimatedDelivery(?\DateTimeInterface $estimatedDelivery): self
    {
        $this->estimatedDelivery = $estimatedDelivery;

        return $this;
    }

    public function getRealDelivery(): ?\DateTimeInterface
    {
        return $this->realDelivery;
    }

    public function setRealDelivery(?\DateTimeInterface $realDelivery): self
    {
        $this->realDelivery = $realDelivery;

        return $this;
    }

    public function getDeliverTo(): ?string
    {
        return $this->deliverTo;
    }

    public function setDeliverTo(?string $deliverTo): self
    {
        $this->deliverTo = $deliverTo;

        return $this;
    }
}
