<?php
namespace Module\Forum\Domain\Parsers;

use Module\Template\Domain\Weights\Node;


class ImgNode extends Node {

    public function render($type = null) {
        $content = strip_tags($this->attr('content'));
        return <<<HTML
<img src="{$content}"/>
HTML;

    }
}