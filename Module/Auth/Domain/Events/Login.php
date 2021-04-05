<?php
declare(strict_types=1);
namespace Module\Auth\Domain\Events;


use Module\Auth\Domain\Model\UserModel;

class Login {

    /**
     * Create a new event instance.
     * @param UserModel $user 用户模型
     * @param string $agent Agent对象
     * @param string $ip IP地址
     * @param int $timestamp 登录时间戳
     */
    public function __construct(
        protected UserModel $user,
        protected string $agent,
        protected string $ip,
        protected int $timestamp) {
    }

    /**
     * @return UserModel
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getAgent() {
        return $this->agent;
    }

    public function getIp() {
        return $this->ip;
    }

    public function getTimestamp() {
        return $this->timestamp;
    }
}