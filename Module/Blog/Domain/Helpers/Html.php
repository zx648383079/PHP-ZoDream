<?php
declare(strict_types=1);
namespace Module\Blog\Domain\Helpers;

use Infrastructure\HtmlExpand;
use Zodream\Html\MarkDown;

class Html {
    public static function render($content, array $tags = [], bool $isMarkDown = false, bool $imgLazy = false): string {
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
            $content = str_replace($tags, array_map(function ($tag) {
                $url = url('./', ['tag' => $tag]);
                return <<<HTML
<a href="{$url}" title="{$tag}">{$tag}</a>
HTML;
            }, $tags), $content);
        }
        return str_replace(array_keys($replace), array_values($replace), $content);
    }

    protected static function renderImg(string $line, bool $imgLazy, string $defaultImage) {
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
        } elseif (str_contains($match[1], $host)) {
            return $line;
        } else {
            $url = HtmlExpand::toUrl($url);
        }
        return str_replace($match[1], $url, $line);
    }

}