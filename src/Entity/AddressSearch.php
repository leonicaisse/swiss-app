<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class AddressSearch
{
    const SEARCH_BY = [
        'name' => 'Nom de la structure',
    ];

    const ORDER_BY = [
        'name' => 'Nom de la structure',
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
    private $searchBy = self::SEARCH_BY['name'];

    /**
     * @var string;
     * @Assert\Type("string")
     */
    private $orderBy = 'name';

    /**
     * @var string
     * @Assert\Type("string")
     */
    private $orderDirection = 'DESC';

    /**
     * @return string|null
     */
    public function getSearchInput(): ?string
    {
        return $this->searchInput;
    }

    /**
     * @param string|null $searchInput
     * @return AddressSearch
     */
    public function setSearchInput(string $searchInput): AddressSearch
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
     * @return AddressSearch
     */
    public function setSearchBy(string $searchBy): AddressSearch
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
     * @return AddressSearch
     */
    public function setOrderBy(string $orderBy): AddressSearch
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
     * @return AddressSearch
     */
    public function setOrderDirection(string $orderDirection): AddressSearch
    {
        $this->orderDirection = $orderDirection;
        return $this;
    }
}