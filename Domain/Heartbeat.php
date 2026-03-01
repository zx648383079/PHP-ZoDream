<?php
declare(strict_types=1);
namespace Domain;


interface Heartbeat {
    /**
     * 保持连接的心跳
     */
    public function pulse(int $delta): array;
}