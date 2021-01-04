<?php
namespace Module\Forum\Domain\Parsers;

use Infrastructure\HtmlExpand;
use Module\Template\Domain\Weights\Node;


class LinkNode extends Node {

    public function render($type = null) {
        $href = HtmlExpand::toUrl($this->attr('href'));
        $content = $this->attr('content');
        return <<<HTML
<a href="{$href}" target="_blank" rel="noopener noreferrer">{$content}</a>
HTML;

    }
}