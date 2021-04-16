<?php
declare(strict_types=1);
namespace Module\Blog\Domain\Events;

use JetBrains\PhpStorm\Pure;

class BlogUpdate {
    protected string $url;

    /**
     * Create a new event instance.
     * @param int $id 博客
     * @param int $type 事件类型，0 新增 1 更新 2 删除
     * @param int $timestamp 登录时间戳
     */
    public function __construct(
        protected int $id,
        protected int $type,
        protected int $timestamp) {
        $creator = url();
        $creator->useCustomScript();
        $this->url = $creator->to('./', ['id' => $this->id]);
        $creator->useCustomScript(false);
    }

    /**
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    public function getTimestamp(): int {
        return $this->timestamp;
    }

    public function isNew(): bool {
        return $this->type < 1;
    }

    public function isUpdate(): bool {
        return $this->type === 1;
    }

    public function isDelete(): bool {
        return $this->type > 1;
    }

    #[Pure]
    public function getUrl(): string {
        return $this->isNew() ?  $this->url : '';
    }
}