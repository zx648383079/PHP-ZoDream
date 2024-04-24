<?php
declare(strict_types=1);
namespace Module\Auth\Domain\Events;


use Module\Auth\Domain\Model\UserModel;

readonly class CancelAccount {

    /**
     * CancelAccount constructor.
     * @param UserModel $user 用户模型
     * @param int $timestamp 注销时间戳
     */
    public function __construct(
        protected UserModel $user,
        protected int $timestamp) {
    }


    /**
     * @return int
     */
    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    /**
     * @return UserModel
     */
    public function getUser(): UserModel
    {
        return $this->user;
    }

    public function getUserId(): int {
        return $this->user->id;
    }
}