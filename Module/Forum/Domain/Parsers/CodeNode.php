<?php
declare(strict_types=1);
namespace Module\Forum\Domain\Parsers;

use Module\Template\Domain\Weights\Node;

class CodeNode extends Node {

    public function render(string $type = ''): mixed {
        $lang = $this->attr('lang');
        $content = $this->attr('content');
        if ($type === 'json') {
            $res = [
                'tag' => 'code',
                'content' => $content,
                'language' => $lang,
            ];
            if ($this->attr('src')) {
                $res['src'] = $this->attr('src');
            }
            return $res;
        }
        view()->registerCssFile('@prism.min.css')
            ->registerJsFile('@prism.min.js');
        return <<<HTML
<pre><code class="language-{$lang}">{$content}</code></pre>
HTML;

    }
}