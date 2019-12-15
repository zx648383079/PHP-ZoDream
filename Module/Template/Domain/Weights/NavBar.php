<?php
namespace Module\Template\Domain\Weights;

use Zodream\Service\Factory;
use Zodream\Template\View;

class NavBar extends Node implements INode {

    const KEY = 'nav-bar';

    protected function registerAsync() {
        $this->page->on(self::KEY, function () {
           return [
                [
                    'name' => 'home',
                    'url' => '/'
                ],
               [
                   'name' => 'blog',
                   'url' => 'blog'
               ],
               [
                   'name' => 'friend link',
                   'url' => 'friend_link'
               ],
               [
                   'name' => 'about',
                   'url' => 'about'
               ],
           ];
        });
    }

    public function render($type = null) {
        $data = $this->page->trigger(self::KEY);
        $js = <<<JS
$('.nav-bar .nav-bar-toggle').click(function() {
  $(this).closest('.nav-bar').toggleClass('open');
});
JS;
        Factory::view()->registerJs($js, View::JQUERY_READY);
        return sprintf('<div class="nav-bar"><span class="nav-bar-toggle"></span><ul>%s</ul>%s</div>', implode('', array_map(function ($item) {
            return sprintf('<li><a href="%s">%s</a></li>', url($item['url']), __($item['name']));
        }, $data)), $this->renderRight());
    }

    private function renderRight() {
        if (auth()->guest()) {
            return '';
        }
        $name = auth()->user()->name;
        return <<<HTML
<ul class="nav-right">
    <li>
        <a href="">{$name}</a>
        <div class="sub-nav">
            <ul>
                <li>
                    <a href="">私信</a>
                </li>
                <li>
                    <a href="">账号设置</a>
                </li>
                <li>
                    <a href="">退出</a>
                </li>
            </ul>
        </div>
    </li>
</ul>
HTML;
    }
}