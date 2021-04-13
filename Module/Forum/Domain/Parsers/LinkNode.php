<?php
namespace Module\Forum\Domain\Parsers;

use Infrastructure\HtmlExpand;
use Module\Template\Domain\Weights\Node;


class LinkNode extends Node {

    protected bool $isJson = false;

    public function render($type = null) {
        $this->isJson = $type === 'json';
        $href = $this->attr('href');
        $content = $this->attr('content');
        if ($this->attr('card')) {
            return $this->renderCard($content, $href);
        }
        if ($this->isJson) {
            return [
                'tag' => 'a',
                'href' => $href,
                'content' => $content,
            ];
        }
        $href = HtmlExpand::toUrl($href);
        return <<<HTML
<a href="{$href}" target="_blank" rel="noopener noreferrer">{$content}</a>
HTML;
    }

    public function renderCard(string $text, string $url) {
        $domain = parse_url($url, PHP_URL_HOST);
        $host = request()->host();
        if (empty($domain)) {
            $domain = $host;
        }
        $logo = str_contains($domain, $host) ? url()->asset('/assets/images/favicon.png') : sprintf('//%s/favicon.ico', $domain);
        if ($this->isJson) {
            return [
                'tag' => 'a',
                'href' => $url,
                'content' => $text,
                'card' => true,
                'domain' => $domain,
                'logo' => $logo,
            ];
        }
        $url = HtmlExpand::toUrl($url);
        return <<<HTML
<a href="{$url}" target="_blank" rel="noopener noreferrer" class="link-card">
    <span class="link-body">
        <span class="link-text">
            <span class="link-title">{$text}</span>
            <span class="link-meta">
                <i class="fa fa-link"></i>
                {$domain}
            </span>
        </span>
        <span class="link-logo">
            <img src="{$logo}">
        </span>
    </span>
</a>
HTML;

    }
}