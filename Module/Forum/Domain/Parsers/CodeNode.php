<?php
namespace Module\Forum\Domain\Parsers;

use Module\Template\Domain\Weights\Node;

class CodeNode extends Node {

    public function render($type = null) {
        $lang = $this->attr('lang');
        $content = $this->attr('content');
        if ($type === 'json') {
            return  [
                'tag' => 'code',
                'content' => $content,
                'language' => $lang,
            ];
        }
        view()->registerCssFile('@prism.css')
            ->registerJsFile('@prism.js');
        return <<<HTML
<pre><code class="language-{$lang}">{$content}</code></pre>
HTML;

    }
}