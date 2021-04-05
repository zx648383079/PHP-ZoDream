<?php
declare(strict_types=1);
namespace Module\Auth\Domain\Events;

use Module\Auth\Domain\Model\UserModel;

class Register {
    /**
     * Register constructor.
     * @param UserModel $user 用户模型
     * @param string $ip IP地址
     * @param int $timestamp 登
     */
    public function __construct(
        protected UserModel $user,
        protected string $ip,
        protected int $timestamp) {
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