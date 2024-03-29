<?php
declare(strict_types=1);
namespace Module\Contact\Domain\Weights;

use Module\Contact\Domain\Repositories\ContactRepository;
use Module\Template\Domain\Weights\INode;
use Module\Template\Domain\Weights\Node;

class FriendLink extends Node implements INode {

    const KEY = 'friend_link';

    protected function registerAsync(): void {
        $this->page->on(self::KEY, function () {
           return ContactRepository::friendLink();
        });
    }

    public function render(string $type = ''): mixed {
        return $this->cache()->getOrSet(sprintf('%s-%s', self::KEY, app()->getLocale()), function () {
            $data = $this->page->trigger(self::KEY);
            return sprintf('<div class="friend-link"><div class="link-header">%s</div><div class="link-body">%s</div></div>', __('friend link'), implode('', array_map(function ($item) {
                return sprintf('<a href="%s" target="_blank" rel="noopener" title="%s">%s</a>', $item['url'], $item['brief'], $item['name']);
            }, $data)));
        });
    }
}