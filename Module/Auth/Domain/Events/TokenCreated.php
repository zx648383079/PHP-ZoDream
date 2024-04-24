<?php
declare(strict_types=1);
namespace Module\Auth\Domain\Events;

use Zodream\Infrastructure\Contracts\UserObject;

readonly class TokenCreated {

    protected int $expiredAt;
    public function __construct(
        protected string $token,
        protected UserObject $user,
        int $tokenExpired = 20160) {
        $this->expiredAt = $tokenExpired + time();
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return int
     */
    public function getExpiredAt(): int
    {
        return $this->expiredAt;
    }

    /**
     * @return UserObject
     */
    public function getUser(): UserObject {
        return $this->user;
    }
}