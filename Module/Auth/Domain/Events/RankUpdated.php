<?php
declare(strict_types=1);
namespace Module\Auth\Domain\Events;

final readonly class RankUpdated {
    protected int $timestamp;
    public function __construct(
        protected int $user,
        protected int $exp,
        protected string $remark,
        protected int $type = 0,
        protected int|string $id = 0
    ) {
        $this->timestamp = time();
    }

    public function getUser(): int {
        return $this->user;
    }

    public function getValue(): int {
        return $this->exp;
    }
}