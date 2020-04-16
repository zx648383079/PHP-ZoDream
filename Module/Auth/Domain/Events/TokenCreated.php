<?php
namespace Module\Auth\Domain\Events;


use Module\Auth\Domain\Model\UserModel;

class TokenCreated {

    /**
     * @var UserModel 用户模型
     */
    protected $user;

    /**
     * @var string
     */
    protected $token;
    /**
     * @var int
     */
    protected $expiredAt;

    public function __construct($token, UserModel $user, $tokenExpired = 20160) {
        $this->token = $token;
        $this->user = $user;
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