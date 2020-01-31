<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class UserSearch
{
    const ORDER_BY = [
        'username' => 'Nom d\'utilisateur',
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
    private $orderBy = 'username';

    /**
     * @var string
     * @Assert\Type("string")
     */
    private $orderDirection = 'DESC';

    /**
     * @var array
     * @Assert\Type("array")
     */
    private $users = array();

    /**
     * @return string|null
     */
    public function getSearchInput(): ?string
    {
        return $this->searchInput;
    }

    /**
     * @param string|null $searchInput
     * @return UserSearch
     */
    public function setSearchInput(?string $searchInput): UserSearch
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
     * @return UserSearch
     */
    public function setOrderBy(string $orderBy): UserSearch
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
     * @return UserSearch
     */
    public function setOrderDirection(string $orderDirection): UserSearch
    {
        $this->orderDirection = $orderDirection;
        return $this;
    }

    /**
     * @return array
     */
    public function getUsers(): array
    {
        return $this->users;
    }

    /**
     * @param array $users
     */
    public function setUsers(array $users): void
    {
        $this->users = $users;
    }
}