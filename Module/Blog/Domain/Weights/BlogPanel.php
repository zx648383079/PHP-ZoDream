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
        return $this->cache()->getOrSet(
            sprintf('%s-%s-%s-%s-%s-%s-%s-%s', self::KEY, $category, $tag, $lang, $keywords, $sort, $limit, trans()->getLanguage())
            , function () use ($category, $tag, $lang, $keywords, $sort, $limit) {
            $data = BlogRepository::getSimpleList($sort, $category, $keywords,
                0, '', $lang,
                $tag, $limit);
            return implode('', array_map(function (BlogModel $item) {
                return sprintf('<div class="list-item"><a class="name" href="%s">%s</a><div class="time">%s</div></div>',
                    url('/blog', ['id' => $item->id]), Html::text($item->title), $item->created_at);
            }, $data));
        }, 600);
    }
}