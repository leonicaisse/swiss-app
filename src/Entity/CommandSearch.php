<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class CommandSearch
{
    const SEARCH_BY = [
        'reference' => 'Numéro de commande',
        'product' => 'Référence de produit'
    ];

    const STOCK = [
        "high" => "Stock élevé",
        "low" => "Stock bas",
        "empty" => "Stock absent",
    ];

    const ORDER_BY = [
        'reference' => 'Numéro de commande',
        'date' => 'Date de la commande'
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
    private $searchBy = self::SEARCH_BY['reference'];

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
     * @var array
     * @Assert\Type("array")
     */
    private $state = array();

    /**
     * @return string|null
     */
    public function getSearchInput(): ?string
    {
        return $this->searchInput;
    }

    /**
     * @param string|null $searchInput
     * @return CommandSearch
     */
    public function setSearchInput(string $searchInput): CommandSearch
    {
        $this->searchInput = $searchInput;
        return $this;
    }

    /**
     * @return string
     */
    public function getSearchBy(): string
    {
        return $this->searchBy;
    }

    /**
     * @param string $searchBy
     * @return CommandSearch
     */
    public function setSearchBy(string $searchBy): CommandSearch
    {
        $this->searchBy = $searchBy;
        return $this;
    }

    /**
     * @return string
     */
    public function getSearchByType(): string
    {
        return self::SEARCH_BY[$this->searchBy];
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
     * @return CommandSearch
     */
    public function setOrderBy(string $orderBy): CommandSearch
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
     * @return CommandSearch
     */
    public function setOrderDirection(string $orderDirection): CommandSearch
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
     * @return CommandSearch
     */
    public function setStock(array $stock): CommandSearch
    {
        $this->stock = $stock;
        return $this;
    }


    /**
     * @return array
     */
    public function getState(): array
    {
        return $this->state;
    }

    /**
     * @param array $state
     * @return CommandSearch
     */
    public function setState(array $state): CommandSearch
    {
        $this->state = $state;
        return $this;
    }
}