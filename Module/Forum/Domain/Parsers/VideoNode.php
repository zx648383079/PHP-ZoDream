<?php
declare(strict_types=1);
namespace Module\Forum\Domain\Parsers;

use Infrastructure\Player;
use Module\Template\Domain\Weights\Node;

class VideoNode extends Node {

    public function render(string $type = ''): mixed {
        $content = $this->attr('content');
        if ($type === 'json') {
            return [
                'tag' => 'video',
                'src' => $content
            ];
        }
        return Player::videoPlayer($content, view());
    }

}