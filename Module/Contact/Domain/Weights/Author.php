<?php
declare(strict_types=1);
namespace Module\Contact\Domain\Weights;

use Infrastructure\Developer;
use Module\Template\Domain\Weights\INode;
use Module\Template\Domain\Weights\Node;

class Author extends Node implements INode {

    public function render(string $type = ''): mixed {
        $data = Developer::author();
        $link = '';
        foreach ($data['links'] as $item) {
            $link .= <<<HTML
<a href="{$item['url']}" target="_blank" title="{$item['title']}">
    <i class="{$item['icon']}"></i>
    {$item['title']}
</a>
HTML;
        }
        return <<<HTML
<div class="person-box">
    <div class="avatar">
        <img src="{$data['avatar']}" alt="{$data['name']}">
    </div>
    <div class="name">{$data['name']}</div>
    <div class="desc">{$data['description']}</div>
    <div class="links">
        {$link}
    </div>
</div>
HTML;
    }
}