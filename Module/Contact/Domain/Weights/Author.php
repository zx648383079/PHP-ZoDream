<?php
namespace Module\Contact\Domain\Weights;

use Module\Contact\Domain\Model\FriendLinkModel;
use Module\Template\Domain\Weights\INode;
use Module\Template\Domain\Weights\Node;

class Author extends Node implements INode {

    const KEY = 'friend_link';

    protected function registerAsync() {
        $this->page->on(self::KEY, function () {
           return FriendLinkModel::where('status', 1)->asArray()->get();
        });
    }

    public function render($type = null) {
        $data = $this->page->trigger(self::KEY);
        return sprintf('<div class="friend-link"><div>%s</div><div>%s</div></div>', __('friend link'), implode('', array_map(function ($item) {
            return sprintf('<a href="%s" target="_blank" rel="noopener noreferrer">%s</a>', $item['url'], $item['name']);
        }, $data)));
    }
}