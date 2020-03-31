<?php
namespace Module\Auth\Domain\Events;


use Module\Auth\Domain\Model\UserModel;

class CancelAccount {

    /**
     * @var UserModel 用户模型
     */
    protected $user;

    /**
     * @var int 注销时间戳
     */
    protected $timestamp;

    public function __construct(UserModel $user, $timestamp) {
        $this->user = $user;
        $this->timestamp = $timestamp;
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