<?php
namespace Module\Blog\Domain\Weights;

use Module\Blog\Domain\Model\BlogModel;
use Module\Blog\Domain\Repositories\BlogRepository;
use Module\Template\Domain\Weights\Node;

class BlogPanel extends Node {

    const KEY = 'home_blog';

    public function render($type = null) {
        $limit = $this->attr('limit');
        $category = intval($this->attr('category'));
        $tag = $this->attr('tag');
        $lang = $this->attr('lang');
        $keywords = $this->attr('keywords');
        $sort = $this->attr('sort') ?: 'new';
        return cache()->getOrSet(
            sprintf('%s-%s-%s-%s-%s-%s-%s', self::KEY, $category, $tag, $lang, $keywords, $sort, $limit)
            , function () use ($category, $tag, $lang, $keywords, $sort, $limit) {
            $data = BlogRepository::getSimpleList($sort, $category, $keywords,
                0, null, $lang,
                $tag, $limit);
            return implode('', array_map(function (BlogModel $item) {
                return sprintf('<div class="list-item"><a class="name" href="%s">%s</a><div class="time">%s</div></div>',
                    url('blog', ['id' => $item->id]), $item->title, $item->created_at);
            }, $data));
        }, 600);
    }
}