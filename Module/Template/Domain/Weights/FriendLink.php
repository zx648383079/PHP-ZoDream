<?php
namespace Module\Template\Domain\Weights;

class FriendLink extends Node implements INode {

    const KEY = 'friend_link';

    protected function registerAsync() {
        $this->page->on(self::KEY, function () {
           return [
                [
                    'name' => '小呆导航',
                    'url' => 'http://webjike.com'
                ]
           ];
        });
    }

    public function render($type = null) {
        $data = $this->page->trigger(self::KEY);
        return sprintf('<div class="friend-link"><div>友情链接</div><div>%s</div></div>', implode('', array_map(function ($item) {
            return sprintf('<a href="%s" target="_blank" rel="noopener noreferrer">%s</a>', $item['url'], $item['name']);
        }, $data)));
    }
}