<?php
namespace Module\Auth\Domain\Events;


use Module\Auth\Domain\Model\UserModel;

class Login {
    /**
     * @var UserModel 用户模型
     */
    protected $user;

    /**
     * @var string Agent对象
     */
    protected $agent;

    /**
     * @var string IP地址
     */
    protected $ip;

    /**
     * @var int 登录时间戳
     */
    protected $timestamp;

    /**
     * Create a new event instance.
     * @param $user
     * @param $agent
     * @param $ip
     * @param $timestamp
     */
    public function __construct(UserModel $user, $agent, $ip, $timestamp) {
        $this->user = $user;
        $this->agent = $agent;
        $this->ip = $ip;
        $this->timestamp = $timestamp;
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