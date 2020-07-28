<?php
namespace Module\Contact\Domain\Weights;

use Module\Contact\Domain\Model\FriendLinkModel;
use Module\Contact\Domain\Repositories\ContactRepository;
use Module\Template\Domain\Weights\INode;
use Module\Template\Domain\Weights\Node;

class FriendLink extends Node implements INode {

    const KEY = 'friend_link';

    protected function registerAsync() {
        $this->page->on(self::KEY, function () {
           return ContactRepository::friendLink();
        });
    }

    public function render($type = null) {
        return $this->cache()->getOrSet(sprintf('%s-%s', self::KEY, trans()->getLanguage()), function () {
            $data = $this->page->trigger(self::KEY);
            return sprintf('<div class="friend-link"><div>%s</div><div>%s</div></div>', __('friend link'), implode('', array_map(function ($item) {
                return sprintf('<a href="%s" target="_blank" rel="noopener" title="%s">%s</a>', $item['url'], $item['brief'], $item['name']);
            }, $data)));
        });
    }
}