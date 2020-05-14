<?php
namespace Module\Blog\Domain\Events;

use Module\Blog\Domain\Model\BlogModel;

class BlogUpdate {
    /**
     * @var BlogModel 博客
     */
    protected $blog;

    /**
     * 是否是新增
     * @var bool
     */
    protected $isNew = false;

    /**
     * @var int 登录时间戳
     */
    protected $timestamp;

    /**
     * Create a new event instance.
     * @param BlogModel $blog
     * @param bool $isNew
     * @param int $timestamp
     */
    public function __construct(BlogModel $blog, bool $isNew, int $timestamp) {
        $this->blog = $blog;
        $this->isNew = $isNew;
        $this->timestamp = $timestamp;
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
            return ;
        }
        url()->useCustomScript();
        $url = $this->blog->url;
        url()->useCustomScript(false);
        return $url;
    }
}