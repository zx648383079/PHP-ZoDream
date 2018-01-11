<?php
namespace Domain;

use Zodream\Infrastructure\Database\Engine\Redis;

class SecKill {
    protected function redis() {
        return new Redis();
    }
}