<?php
declare(strict_types=1);
namespace Module\Blog\Domain\Events;

use Module\Blog\Domain\Model\BlogModel;

class BlogUpdate {
    /**
     * Create a new event instance.
     * @param BlogModel $blog 博客
     * @param bool $isNew 是否是新增
     * @param int $timestamp 登录时间戳
     */
    public function __construct(
        protected  BlogModel $blog,
        protected bool $isNew,
        protected int $timestamp) {
    }

    /**
     * @return BlogModel
     */
    public function getBlog() {
        return $this->blog;
    }

    public function getTimestamp() {
        return $this->timestamp;
    }

    public function getIsNew() {
        return $this->isNew;
    }

    public function getUrl() {
        if (!$this->isNew) {
            return '';
        }
        url()->useCustomScript();
        $url = $this->blog->url;
        url()->useCustomScript(false);
        return $url;
    }
}