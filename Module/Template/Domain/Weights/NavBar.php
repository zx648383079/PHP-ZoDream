<?php
declare(strict_types=1);
namespace Module\Template\Domain\Weights;

use Zodream\Helpers\Html;
use Zodream\Template\View;

class NavBar extends Node implements INode {

    const KEY = 'nav-bar';
    const OS_VERSION = 'win_version';

    protected function registerAsync(): void {
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
        })->on(self::OS_VERSION, function () {
            if (!isset($_SERVER['HTTP_USER_AGENT'])) {
                return 0;
            }
            $agent = $_SERVER['HTTP_USER_AGENT'];
            if (!preg_match('/windows\s+nt\s*([\d\.]+)/i', $agent, $match)) {
                return 0;
            }
            return floatval($match[1]);
        });

    }

    public function render(string $type = ''): mixed {
        $data = $this->page->trigger(self::KEY);
        $js = <<<JS
$('.nav-bar .nav-bar-toggle').on('click', function() {
  $(this).closest('.nav-bar').toggleClass('open');
});
JS;
        view()->registerJs($js, View::JQUERY_READY);
        return sprintf('<div class="nav-bar"><span class="nav-bar-toggle"></span><ul>%s</ul>%s</div>', implode('', array_map(function ($item) {
            return sprintf('<li><a href="%s">%s</a></li>', url($item['url']), __($item['name']));
        }, $data)), $this->renderRight());
    }

    private function renderRight(): string {
        $html = $this->renderAccount();
        $version = $this->page->trigger(self::OS_VERSION);
        $uwp_url = ($version >= 6.2 ? 'ms-windows-store://pdp/?ProductId=' : 'https://www.microsoft.com/store/apps/'). '9MT2DR6PDFG9';
        $download_tip = __('Client Downloads');
        $download = __('Application client');
        $uwp_tip = __('My Timer');
        return <<<HTML
<ul class="nav-right">
    <li>
        <a href="javascript:;" title="{$download_tip}">{$download}
            <i class="fa fa-caret-down"></i>
        </a>
        <div class="sub-nav">
            <ul>
                <li>
                    <a href="{$uwp_url}" target="_blank" title="{$uwp_tip}">Win10 UWP</a>
                </li>
            </ul>
        </div>
    </li>
    <li class="search-icon"><i class="fa fa-search"></i></li>
    
    {$html}
</ul>
HTML;
    }

    private function renderAccount(): string {
        if (auth()->guest()) {
            return '';
        }
        $name = Html::text(auth()->user()->name);
        $bulletin_url = url('/auth/admin/bulletin');
        $account_url = url('/auth/admin/account');
        $logout_url = url('/auth/logout');
        $bulletin_label = __('Bulletin');
        $bulletin_count = auth()->user()->bulletin_count;
        if ($bulletin_count > 0) {
            $bulletin_label .= sprintf('(%d)', $bulletin_count);
            $name .= '<i class="new-tip" title="'.__('You have new Messages').'"></i>';
        }
        $account_label = __('Account Settings');
        $logout = __('Logout');
        return <<<HTML
    <li>
        <a href="javascript:;">{$name}</a>
        <div class="sub-nav">
            <ul>
                <li>
                    <a href="{$bulletin_url}">{$bulletin_label}</a>
                </li>
                <li>
                    <a href="{$account_url}">{$account_label}</a>
                </li>
                <li>
                    <a href="{$logout_url}">{$logout}</a>
                </li>
            </ul>
        </div>
    </li>
HTML;
    }
}