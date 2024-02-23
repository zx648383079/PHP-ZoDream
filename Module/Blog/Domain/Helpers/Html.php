<?php
declare(strict_types=1);
namespace Module\Blog\Domain\Helpers;

use Infrastructure\Deeplink;
use Infrastructure\HtmlExpand;
use Zodream\Helpers\Html as HtmlHelper;
use Module\Blog\Domain\Model\BlogSimpleModel;
use Zodream\Helpers\Time;
use Zodream\Html\MarkDown;

class Html {
    public static function render($content, array $tags = [], bool $isMarkDown = false,
        bool $imgLazy = false, bool $useDeeplink = false): string {
        if ($isMarkDown) {
            $content = MarkDown::parse($content, true);
        }
        if (empty($content)) {
            return '';
        }
        $host = request()->host();
        $defaultImage = $imgLazy ? url()->asset('assets/images/loading.svg') : '';
        $replace = [];
        $i = 0;
        foreach ([
                     '#<code[^<>]*>[\s\S]+?</code>#',
                     '#<a[^<>]+>[\s\S]+?</a>#',
                     '#<img[^<>]+>#'
                 ] as $j => $pattern) {
            $content = preg_replace_callback($pattern, function ($match) use (&$replace, &$i, $j, $host, $imgLazy, $defaultImage) {
                $tag = '[ZO'.$i ++.'OZ]';
                $val = $match[0];
                if ($j === 1) {
                    $val = static::renderUrl($val, $host);
                } elseif ($j === 2) {
                    $val = static::renderImg($val, $imgLazy, $defaultImage);
                }
                $replace[$tag] = $val;
                return $tag;
            }, $content);
        }
        if (!empty($tags)) {
            $tags = array_column($tags, 'name');
            $content = str_replace($tags, array_map(function ($tag) use ($useDeeplink) {
                $url = $useDeeplink ? Deeplink::encode('blog/search', ['tag' => $tag]) : url('./', ['tag' => $tag]);
                return <<<HTML
<a href="{$url}" title="{$tag}">{$tag}</a>
HTML;
            }, $tags), $content);
        }
        $content = preg_replace_callback('/catalog:([\d, ]+)+/', function ($match) use ($useDeeplink) {
            return static::renderCatalog(explode(',', $match[1]), $useDeeplink);
        }, $content);
        return str_replace(array_keys($replace), array_values($replace), $content);
    }

    /**
     * 生成引入目录
     * @param array $idItems
     * @return string
     * @throws \Exception
     */
    protected static function renderCatalog(array $idItems, bool $useDeeplink = false): string {
        $filters = [];
        foreach ($idItems as $id) {
            $id = intval($id);
            if ($id > 0 && !in_array($id, $filters)) {
                $filters[] = $id;
            }
        }
        if (empty($filters)) {
            return '';
        }
        $items = BlogSimpleModel::whereIn('id', $filters)->get();
        if (empty($items)) {
            return '';
        }
        $html = '';
        foreach ($items as $item) {
            $url = $useDeeplink ? Deeplink::encode('blog/'.$item->id) : url('./', ['id' => $item->id]);
            $title = HtmlHelper::text($item->title);
            $meta = HtmlHelper::text($item->description);
            $ago = Time::isTimeAgo($item->getAttributeSource('created_at'), 2678400);
            $target = $useDeeplink ? '' : ' target="_blank"';
            $html .= <<<HTML
<li class="book-catalog-item">
    <div class="item-title"><a class="name" href="{$url}"{$target}>{$title}</a><div class="time">{$ago}</div></div>
    <div class="item-meta">{$meta}</div>
</li>
HTML;
        }
        return <<<HTML
<ul class="book-catalog-inner">{$html}</ul>
HTML;

    }

    protected static function renderImg(string $line, bool $imgLazy, string $defaultImage): string {
        if (!preg_match('#^<img[^<>]+?src="([^"<>\s]+)#', $line, $match)) {
            return $line;
        }
        $src = $match[1];
        if (!str_contains($src, '//')) {
            $src = url()->asset($src);
        } elseif (!$imgLazy) {
            return $line;
        }
        if (!$imgLazy || str_contains($line, 'data-src')) {
            return str_replace($match[1], $src, $line);
        }
        $tags = ['src="'.$match[1]];
        $replace = [
            'src="'. $defaultImage .'" data-src="'. $src,
        ];
        if (str_contains($line, 'class=')) {
            $tags[] = 'class="';
            $replace[] = 'class="lazy ';
        } else {
            $replace[0] = 'class="lazy" '.$replace[0];
        }
        return str_replace($tags, $replace, $line);
    }

    protected static function renderUrl(string $line, string $host): string {
        if (!preg_match('#^<a[^<>]+?href="([^"<>\s]+)#', $line, $match)) {
            return $line;
        }
        $url = $match[1];
        if (str_starts_with($url, '#') || str_starts_with($url, 'javascript:')) {
            return $line;
        }
        if (!str_contains($url, '//')) {
            $url = url()->to($url);
        } elseif (str_contains($match[1], $host) || self::isLocalhost($match[1])) {
            return $line;
        } else {
            $url = HtmlExpand::toUrl($url);
        }
        $matchLen = strlen($match[0]);
        return sprintf('%s%s%s', substr($match[0], 0,
            $matchLen - strlen($match[1])), $url, substr($line, $matchLen));
    }

    protected static function isLocalhost(string $url): bool {
        $host = parse_url($url, PHP_URL_HOST);
        return empty($host) || str_ends_with($url, 'localhost');
    }

}