<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class ProductSearch
{
    const STOCK = [
        "high" => "Stock élevé",
        "low" => "Stock bas",
        "empty" => "Stock absent",
    ];

    /**
     * @var string|null
     * @Assert\Type("string")
     */
    private $searchInput;

    /**
     * @var array
     * @Assert\Type("array")
     */
    private $stock = array();

    /**
     * @return string|null
     */
    public function getSearchInput(): ?string
    {
        return $this->searchInput;
    }

    /**
     * @param string|null $searchInput
     * @return ProductSearch
     */
    public function setSearchInput(?string $searchInput): ProductSearch
    {
        $this->searchInput = $searchInput;
        return $this;
    }

    /**
     * @return array
     */
    public function getStock(): array
    {
        return $this->stock;
    }

    /**
     * @param array $stock
     * @return ProductSearch
     */
    public function setStock(array $stock): ProductSearch
    {
        $this->stock = $stock;
        return $this;
    }

}