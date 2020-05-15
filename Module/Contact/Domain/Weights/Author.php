<?php
namespace Module\Contact\Domain\Weights;

use Module\Auth\Domain\Model\UserModel;
use Module\Template\Domain\Weights\INode;
use Module\Template\Domain\Weights\Node;

class Author extends Node implements INode {

    const KEY = 'zd_profile';

    protected function registerAsync() {
        $this->page->on(self::KEY, function () {
           return UserModel::find(1);
        });
    }

    public function render($type = null) {
        return $this->cache()->getOrSet(self::KEY, function () {
            $user = $this->page->trigger(self::KEY);
            return <<<HTML
<div class="person-box">
    <div class="avatar">
        <img src="{$user->avatar}" alt="{$user->name}">
    </div>
    <div class="name">{$user->name}</div>
    <div class="desc">开心就好！</div>
    <div class="links">
        <a href="https://github.com/zx648383079" target="_blank" title="Github">
            <i class="fab fa-github"></i>
            GitHub
        </a>
        <a href="mailto:{$user->email}" title="发送邮件">
            <i class="fa fa-mail-bulk"></i>
            Email
        </a>
    </div>
</div>
HTML;
        });
    }
}