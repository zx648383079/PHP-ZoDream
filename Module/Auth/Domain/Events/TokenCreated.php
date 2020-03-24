<?php
namespace Module\Auth\Domain\Events;


use Module\Auth\Domain\Model\UserModel;

class TokenCreated {

    /**
     * @var UserModel 用户模型
     */
    protected $user;

    protected $token;

    public function __construct($token, UserModel $user) {
        $this->token = $token;
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getToken() {
        return $this->token;
    }

    /**
     * @return UserModel
     */
    public function getUser(): UserModel {
        return $this->user;
    }
}