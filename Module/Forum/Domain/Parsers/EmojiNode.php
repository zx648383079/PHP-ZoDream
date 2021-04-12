<?php
declare(strict_types=1);
namespace Module\Forum\Domain\Parsers;

use Module\SEO\Domain\Repositories\EmojiRepository;
use Module\Template\Domain\Weights\Node;
class EmojiNode extends Node {

    public function isGlobe(): bool
    {
        return true;
    }

    public function render($type = null) {
        $content = $this->attr('content');
        if ($type === 'json') {
            return [
                'content' => $content,
                'extra_rule' => EmojiRepository::renderRule($content)
            ];
        }
        return EmojiRepository::render($content);
    }
}