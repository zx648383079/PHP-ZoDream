<?php
namespace Module\Forum\Domain\Parsers;

use Module\Template\Domain\Weights\Node;

class PageNode extends Node {

    public function render($type = null) {
        $blocks = explode('<page/>', $this->attr('content'));

    }
}