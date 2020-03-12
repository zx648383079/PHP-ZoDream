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
        $html = $this->renderAccount();
        return <<<HTML
<ul class="nav-right">
    <li class="search-icon"><i class="fa fa-search"></i></li>
    {$html}
</ul>
HTML;
    }

    private function renderAccount() {
        if (auth()->guest()) {
            return '';
        }
        $name = auth()->user()->name;
        $bulletin_url = url('/auth/admin/bulletin');
        $account_url = url('/auth/admin/account');
        $logout_url = url('/auth/logout');
        $bulletin_label = '私信';
        $bulletin_count = auth()->user()->bulletin_count;
        if ($bulletin_count > 0) {
            $bulletin_label .= sprintf('(%d)', $bulletin_count);
            $name .= '<i class="new-tip" title="您有新的消息"></i>';
        }
        return <<<HTML
    <li>
        <a href="javascript:;">{$name}</a>
        <div class="sub-nav">
            <ul>
                <li>
                    <a href="{$bulletin_url}">{$bulletin_label}</a>
                </li>
                <li>
                    <a href="{$account_url}">账号设置</a>
                </li>
                <li>
                    <a href="{$logout_url}">退出</a>
                </li>
            </ul>
        </div>
    </li>
HTML;
    }
}