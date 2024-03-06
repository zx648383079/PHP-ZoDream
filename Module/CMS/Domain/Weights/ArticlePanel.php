<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Weights;

use Module\Template\Domain\Weights\Node;

class ArticlePanel extends Node {

    const KEY = 'CMS';

    public function render(string $type = ''): mixed {
        $limit = intval($this->attr('limit'));
        $keywords = (string)$this->attr('keywords');
        $site = (string)$this->attr('site');
        $channel = (string)$this->attr('channel');
        $model = (string)$this->attr('model');
        $sort = (string)$this->attr('sort') ?: 'new';
        $cb = function () use ($site, $keywords, $channel, $model, $sort, $limit) {

        };
        if (app()->isDebug()) {
            return $cb();
        }
        return $this->cache()->getOrSet(sprintf('%s-%s-%s-%s-%s-%s-%s-%s',
            self::KEY, $site, $keywords, $channel, $model, $sort, $limit, trans()->getLanguage()),
            $cb, rand(3600, 86400)
        );
    }
}