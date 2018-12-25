<?php
namespace Module\Template\Domain\Weights;

class NavBar extends Node implements INode {

    const KEY = 'nav-bar';

    protected function registerAsync() {
        $this->page->on(self::KEY, function () {
           return [
                [
                    'name' => '首页',
                    'url' => '/'
                ],
               [
                   'name' => '博客',
                   'url' => 'blog'
               ],
               [
                   'name' => '友情链接',
                   'url' => 'friend_link'
               ],
               [
                   'name' => '关于',
                   'url' => 'about'
               ],
           ];
        });
    }

    public function render($type = null) {
        $data = $this->page->trigger(self::KEY);
        return sprintf('<div class="nav-bar"><ul>%s</ul></div>', implode('', array_map(function ($item) {
            return sprintf('<li><a href="%s">%s</a></li>', url($item['url']), $item['name']);
        }, $data)));
    }
}