<?php
declare(strict_types=1);
namespace Module\Auth\Domain\Events;

use Module\Auth\Domain\Model\UserModel;

class TokenCreated {

    protected int $expiredAt;
    public function __construct(
        protected string $token,
        protected UserModel $user,
        int $tokenExpired = 20160) {
        $this->expiredAt = $tokenExpired + time();
    }

    /**
     * @return string
     */
    public function getToken() {
        return $this->token;
    }

    /**
     * @return int
     */
    public function getExpiredAt() {
        return $this->expiredAt;
    }

    /**
     * @return UserModel
     */
    public function getUser(): UserModel {
        return $this->user;
    }
}