<?php

namespace App\DTO;

class CreateTokenData
{
    /**
     * @var int
     */
    private int $userId;

    /**
     * @var string
     */
    private string $email;

    /**
     * @var string
     */
    private string $userRole;

    /**
     * @param int    $userId
     * @param string $email
     * @param string $userRole
     */
    public function __construct(
        int $userId,
        string $email,
        string $userRole
    ) {
        $this->userId = $userId;
        $this->email = $email;
        $this->userRole = $userRole;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getUserRole(): string
    {
        return $this->userRole;
    }
}
