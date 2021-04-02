<?php
declare(strict_types=1);
namespace Infrastructure;

use Zodream\Helpers\Json;
use Zodream\Template\View;

class Player {

    public static function audioPlayer(string|array $url, View $view): string {
        if (!is_array($url)) {
            $url = compact('url');
        }
        $data = Json::encode($url);
        $js = <<<JS
var player = new APlayer({
    container: document.getElementById('audio-player'),
    audio: [$data]
});
JS;
        $view->registerJs($js)
            ->registerCssFile('@APlayer.min.css')
            ->registerJsFile('@APlayer.min.js');
        return <<<HTML
<div id="audio-player"></div>
HTML;

    }

    public static function videoPlayer(string|array $url, View $view): string {
        $cover = '';
        if (is_array($url)) {
            $cover = isset($url['cover']) ? $url['cover'] : '';
            $url = isset($url['url']) ? $url['url'] : '';
        }
        if (empty($url)) {
            return '';
        }
        $host = parse_url($url, PHP_URL_HOST);
        if (
            in_array($host, [
                'player.youku.com',
                'player.bilibili.com',
                'v.qq.com',
            ])
        ) {
            return static::createIframe($url);
        }
        return static::createVideo($url, $cover, $view);
    }

    protected static function createVideo(string $url, string $cover, View $view): string {
        $js = <<<JS
var player = videojs('video-player');
JS;
        $view->registerJs($js);
        $view->registerCssFile('@video-js.min.css')
            ->registerJsFile('@video.min.js')
            ;// ->registerJsFile('@videojs-http-streaming.min.js');
        return <<<HTML
<video id="video-player" class="video-js vjs-big-play-centered vjs-fluid" controls preload="none"
    width="640"
    height="400"
    poster="{$cover}">
    <source src="{$url}" type="video/mp4">
</video>
HTML;
    }

    protected static function createIframe(string $url): string {
        return <<<HTML
<iframe src="{$url}" scrolling="no" border="0" frameborder="no" framespacing="0" allowfullscreen="true"> </iframe>
HTML;
    }
}