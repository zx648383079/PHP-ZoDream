<?php
namespace Module\Book\Domain\Spiders;


use Module\Book\Domain\Model\BookChapterModel;
use Module\Book\Domain\Model\BookModel;
use Zodream\Helpers\Json;
use Zodream\Spider\Support\Html;
use Zodream\Spider\Support\Uri;

class AutoSpider extends BaseSpider {

    const TITLE = '#<title>(.+?)</title>#i';

    /**
     * @param Uri $uri
     * @return boolean
     */
    public function isCatalogPage(Uri $uri) {
        // TODO: Implement isCatalogPage() method.
    }

    /**
     * @param Uri $uri
     * @return boolean
     */
    public function isContentPage(Uri $uri) {
        // TODO: Implement isContentPage() method.
    }

    /**
     * @param $html
     * @return array
     */
    public function getBook(Html $html, Uri $uri) {
        return [
            'name' => $this->getTitle($html->getHtml()),
            'cover' => '',
            'author' => ''
        ];
    }

    /**
     * @param Html $html
     * @param Uri $baseUri
     * @return array
     */
    public function getCatalog(Html $html, Uri $baseUri) {
        $html = $this->cleanHtml($html->getHtml());
        $tags = $this->getTagMaps($html);
        $data = $this->getContent($tags, $html);
        return array_map(function ($item) use ($baseUri) {
            $chapterUri = clone $baseUri;
            $item['url'] = $chapterUri->setPath(strpos($item['url'], '/') === 0 ? $item['url'] :
                (trim($baseUri->getPath(), '/').'/'.trim($item['url'], '/')))
                ->encode();
            return $item;
        }, $data);
    }

    /**
     * @param $html
     * @return array
     */
    public function getChapter(Html $html) {
        $html = $html->getHtml();
        $title = $this->getTitle($html);
        $html = $this->cleanHtml($html);
        $tags = $this->getTagMaps($html);
        return [
            'title' => $title,
            'content' => $this->getContent($tags, $html)
        ];
    }

    public function getContent(array $tags, $html) {
        for ($index = count($tags) -1; $index >= 0; $index --) {
            if (isset($tags[$index]['center']) && $tags[$index]['center']) {
                break;
            }
        }
        if ($index < 0) {
            $index = 0;
        }
        $tag = $tags[$index];
        if (strpos($tag['tag'], 'a') !== false) {
            return $this->getChapters($tag['index'], $tags[$index + 1]['index'], $html);
        }
        $content = substr($html, $tags[$index - 1]['index'],
            $tags[$index + 1]['index'] - $tags[$index - 1]['index']);
        $content = preg_replace('#^<.+?</[^<>]+?>#', '', $content);
        return Html::toText($content);
    }

    public function getChapters($start, $end, $html) {
        $content = substr($html, $start, $end - $start);
        if (preg_match_all('#<a href="(.+?)">(.+?)</a>#i', $content, $matches, PREG_SET_ORDER)) {
            return array_map(function ($item) {
                return [
                    'url' => $item[1],
                    'title' => $item[2]
                ];
            }, $matches);
        }
        return [];
    }

    /**
     * 获取标签并进行汇总
     * @param $html
     * @return array
     */
    protected function getTagMaps($html) {
        $maps = [];
        $tag = false;
        $len = strlen($html);
        $center = floor($len / 2);
        for ($i = 0; $i < $len; $i ++) {
            if ($i == $center) {
                $maps[count($maps) - 1]['center'] = true;
            }
            $char = $html[$i];
            if ($char === '/') {
                continue;
            }
            if ($char === '<') {
                if ($html[$i + 1] === '/') {
                    continue;
                }
                $tag = '';
                continue;
            }
            if ($tag !== false && ($char === '>' || $char == ' ')) {
                $count = count($maps);
                if ($count > 0 && $maps[$count - 1]['tag'] === $tag) {
                    $maps[$count - 1]['count'] ++;
                } else {
                    $maps[] = [
                        'tag' => $tag,
                        'count' => 1,
                        'index' => $i - strlen($tag) - 2
                    ];
                }
                $tag = false;
                continue;
            }
            if ($tag !== false) {
                $tag .= $char;
            }
        }
        // 倒序合并多个空标签
        $data = [];
        $prev = false;
        for ($i = count($maps) - 1; $i >= 0; $i --) {
            $item = $maps[$i];
            if ($item['count'] > 1) {
                if ($prev !== false) {
                    $data[] = $prev;
                    $prev = false;
                }
                $data[] = $item;
                continue;
            }
            if ($prev === false) {
                $prev = $item;
                continue;
            }

            if ($item['index'] + strlen($item['tag']) + 2 !== $prev['index']) {
                $data[] = $prev;
                $prev = $item;
                continue;
            }
            $item['tag'] = sprintf('%s.%s', $item['tag'], $prev['tag']);
            $prev = array_merge($prev, $item);
            // 合并上一个
            $count = count($data);
            if ($count < 1) {
                continue;
            }
            if ($prev['tag'] !== $data[$count - 1]['tag']) {
                continue;
            }
            $data[$count - 1]['index'] = $prev['index'];
            $data[$count - 1]['count'] ++;
            if (isset($prev['center']) && $prev['center']) {
                $data[$count - 1]['center'] = true;
            }
            $prev = false;
        }
        if ($prev !== false) {
            $data[] = $prev;
        }
        return array_reverse($data);
    }

    protected function getTitle($html) {
        if (!preg_match(self::TITLE, $html, $match)) {
            return '';
        };
        $title = str_replace(['_', '-'], ' ', $match[1]);
        return $title;
    }

    protected function cleanHtml($html) {
        $html = preg_replace('#<head>([\s\S]+?)</head>#', '', $html);
        $html = preg_replace('/<!--[\s\S]*?-->/is', '', $html);
        $html = preg_replace('/\s+/is', ' ', $html);
        $html = preg_replace('/\<style .*?\<\\/style\>/is', '', $html);
        $html = preg_replace('/\<script.*?\<\/script>/is', '', $html);
        return preg_replace_callback('#</?(\w+)([^\<\>]*)/?>#', function ($match) {
            $replace = '';
            if ($match[1] == 'img') {
                $replace = $this->cleanAttribute($match, 'src');
            } elseif ($match[1] == 'a') {
                $replace = $this->cleanAttribute($match, 'href');
            } elseif ($match[1] == 'br') {
                return '<br>';
            }
            return str_replace($match[2], $replace, $match[0]);
        }, $html);

    }

    protected function cleanAttribute($matches, $tag) {
        if (preg_match('/'.$tag.'\s?=[\s"\']?(\S+)["\']/i', $matches[2], $match)) {
            return sprintf(' %s="%s"', $tag, $match[1]);
        }
        return '';
    }
}