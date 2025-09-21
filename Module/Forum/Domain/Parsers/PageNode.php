<?php
declare(strict_types=1);
namespace Module\Forum\Domain\Parsers;

use Module\Template\Domain\Weights\Node;

class PageNode extends Node {

    public function isGlobe(): bool {
        return true;
    }

    public function render(string $type = ''): mixed {
        $blocks = explode('<page/>', $this->attr('content'));
        $block = $this->page->isReviewMode ? implode('', $blocks) : reset($blocks);
        if ($type === 'json') {
            return [
                'content' => $block
            ];
        }
        return $block;
    }
}