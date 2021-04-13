<?php
namespace Module\Blog\Domain\Weights;

use Module\Blog\Domain\Model\BlogModel;
use Module\Blog\Domain\Repositories\BlogRepository;
use Module\Template\Domain\Weights\Node;
use Zodream\Helpers\Html;

class BlogPanel extends Node {

    const KEY = 'home_blog';

    public function render($type = null) {
        $limit = intval($this->attr('limit'));
        $category = intval($this->attr('category'));
        $tag = (string)$this->attr('tag');
        $lang = (string)$this->attr('lang');
        $keywords = (string)$this->attr('keywords');
        $sort = (string)$this->attr('sort') ?: 'new';
        $cb = function () use ($category, $tag, $lang, $keywords, $sort, $limit) {
            $data = BlogRepository::getSimpleList($sort, $category, $keywords,
                0, '', $lang,
                $tag, $limit);
            return implode('', array_map(function (BlogModel $item) {
                $url = url('/blog', ['id' => $item->id]);
                $title = Html::text($item->title);
                $meta = Html::text($item->description);

                return <<<HTML
<div class="list-item">
    <div class="item-title"><a class="name" href="{$url}">{$title}</a><div class="time">{$item->created_at}</div></div>
    <div class="item-meta">{$meta}</div>
</div>
HTML;
            }, $data));
        };
        if (app()->isDebug()) {
            return $cb();
        }
        return $this->cache()->getOrSet(sprintf('%s-%s-%s-%s-%s-%s-%s-%s',
            self::KEY, $category, $tag, $lang, $keywords, $sort, $limit, trans()->getLanguage()),
            $cb, rand(600, 3600)
        );
    }
}