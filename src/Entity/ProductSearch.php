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

    const ORDER_BY = [
        'reference' => 'Référence de produit',
        'quantity' => 'Quantité en stock'
    ];

    /**
     * @var string|null
     * @Assert\Type("string")
     */
    private $searchInput;

    /**
     * @var string;
     * @Assert\Type("string")
     */
    private $orderBy = 'reference';

    /**
     * @var string
     * @Assert\Type("string")
     */
    private $orderDirection = 'DESC';

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
     * @return string
     */
    public function getOrderBy(): string
    {
        return $this->orderBy;
    }

    /**
     * @param string $orderBy
     * @return ProductSearch
     */
    public function setOrderBy(string $orderBy): ProductSearch
    {
        $this->orderBy = $orderBy;
        return $this;
    }

    /**
     * @return string
     */
    public function getOrderByType(): string
    {
        return self::ORDER_BY[$this->orderBy];
    }

    /**
     * @return string
     */
    public function getOrderDirection(): string
    {
        return $this->orderDirection;
    }

    /**
     * @param string $orderDirection
     * @return ProductSearch
     */
    public function setOrderDirection(string $orderDirection): ProductSearch
    {
        $this->orderDirection = $orderDirection;
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