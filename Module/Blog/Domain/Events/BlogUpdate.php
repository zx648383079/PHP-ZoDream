<?php
namespace Module\Blog\Domain\Events;

use Module\Blog\Domain\Model\BlogModel;

class BlogUpdate {
    /**
     * @var BlogModel 博客
     */
    protected $blog;

    /**
     * @var int 登录时间戳
     */
    protected $timestamp;

    /**
     * Create a new event instance.
     * @param $blog
     * @param $timestamp
     */
    public function __construct(BlogModel $blog, $timestamp) {
        $this->blog = $blog;
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
}