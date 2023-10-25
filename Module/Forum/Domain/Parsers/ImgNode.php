<?php
declare(strict_types=1);
namespace Module\Forum\Domain\Parsers;

use Module\Template\Domain\Weights\Node;

class ImgNode extends Node {

    public function render(string $type = ''): mixed {
        $content = strip_tags($this->attr('content'));
        if ($type === 'json') {
            return [
                'tag' => 'img',
                'src' => $content
            ];
        }
        return <<<HTML
<img src="{$content}"/>
HTML;

    }
}