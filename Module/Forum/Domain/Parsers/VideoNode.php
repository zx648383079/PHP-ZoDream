<?php
namespace Module\Forum\Domain\Parsers;

use Module\Template\Domain\Weights\Node;

class VideoNode extends Node {

    public function render($type = null) {
        $content = $this->attr('content');
        return $this->formatSrc($content);
    }

    private function formatSrc($content) {
        if ($this->isIframe($content)) {
            return <<<HTML
<iframe src="{$content}" scrolling="no" border="0" frameborder="no" framespacing="0" allowfullscreen="true"> </iframe>
HTML;
        }
        $js = <<<JS
var player = videojs('example-video');
JS;

        view()->registerJsFile([
            '@video.min.js',
            '@videojs-contrib-hls.min.js'
        ])->registerCssFile('@video-js.min.css')
            ->registerJs($js);
        return <<<HTML
<video id="example-video" width="100%" class="video-js vjs-default-skin vjs-fluid" controls>
    <source
        src="{$content}"
        type="application/x-mpegURL">
</video>
HTML;

    }

    private function isIframe($url) {
        foreach ([
            'open.iqiyi.com',
            'player.youku.com',
            'v.qq.com',
            'player.bilibili.com',
                 ] as $item) {
            if (strpos($url, $item) !== false) {
                return true;
            }
        }
        return false;
    }
}