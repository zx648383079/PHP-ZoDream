<?php
declare(strict_types=1);
namespace Module\Blog\Domain\Listeners;

use Module\Blog\Domain\Helpers\RouterHelper;
use Module\Blog\Domain\Repositories\RssRepository;

class BlogUpdateListener {
    public function __construct($event) {
        cache()->delete(RssRepository::CACHE_KEY);
        cache()->store('pages')->flush();
        RouterHelper::reset();;
    }
}