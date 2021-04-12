<?php
namespace Module\Forum\Domain\Parsers;

use Infrastructure\Player;
use Module\Template\Domain\Weights\Node;

class VideoNode extends Node {

    public function render($type = null) {
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