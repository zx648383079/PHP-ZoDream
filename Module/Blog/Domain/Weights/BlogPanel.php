<?php
namespace Module\Blog\Domain\Weights;

use Module\Blog\Domain\Model\BlogModel;
use Module\Template\Domain\Weights\Node;

class BlogPanel extends Node {

    public function render($type = null) {
        $data = BlogModel::getNew();
        return implode('', array_map(function (BlogModel $item) {
            return sprintf('<div class="list-item"><a class="name" href="%s">%s</a><div class="time">%s</div></div>',
                url('blog', ['id' => $item->id]), $item->title, $item->created_at);
        }, $data));
    }
}