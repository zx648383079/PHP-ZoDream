<?php
declare(strict_types=1);
namespace Module\Forum\Domain\Parsers;

use Module\Template\Domain\Weights\Node;
use Zodream\Template\View;

class AudioNode extends Node {

    private bool $booted = false;

    public function render(string $type = ''): mixed {
        $content = $this->attr('content');
        if ($type === 'json') {
            return [
                'tag' => 'audio',
                'src' => $content,
            ];
        }
        $this->boot();
        return <<<HTML
<div class="aplayer" data-url="{$content}"></div>
HTML;
    }

    private function boot() {
        if ($this->booted) {
            return;
        }
        $this->booted = true;
        $js = <<<JS
$('.aplayer').each(function() {
      new APlayer({
        container: this,
        audio: [{
            name: 'music',
            artist: 'artist',
            url: $(this).attr('url'),
            cover: '/assets/images/zd.jpg'
        }]
    });
});
JS;

        view()->registerJsFile('@APlayer.min.js')
            ->registerCssFile('@APlayer.min.css')
            ->registerJs($js, View::JQUERY_READY);
    }
}