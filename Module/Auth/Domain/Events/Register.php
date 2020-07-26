<?php
namespace Module\Auth\Domain\Events;

use Module\Auth\Domain\Model\UserModel;

class Register {
    /**
     * @var UserModel 用户模型
     */
    protected $user;

    /**
     * @var string IP地址
     */
    protected $ip;

    /**
     * @var int 登录时间戳
     */
    protected $timestamp;

    public function __construct(UserModel $user, $ip, $timestamp) {
        $this->user = $user;
        $this->ip = $ip;
        $this->timestamp = $timestamp;
    }

    /**
     * @return UserModel
     */
    public function getUser() {
        return $this->user;
    }

    public function getIp() {
        return $this->ip;
    }

    public function getTimestamp() {
        return $this->timestamp;
    }

    public function getUserId() {
        return $this->user->id;
    }
}