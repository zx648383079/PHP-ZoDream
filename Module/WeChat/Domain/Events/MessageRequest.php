<?php
declare(strict_types=1);
namespace Module\WeChat\Domain\Events;

final class MessageRequest {

    public function __construct(
        public int $wid,
        public string $from,
        public string $to,
        public int $type,
        public string $content,
        public int $timestamp = 0
    ) {
        if ($this->timestamp === 0) {
            $this->timestamp = time();
        }
    }
}