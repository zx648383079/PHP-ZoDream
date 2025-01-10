<?php
namespace Module\Book\Domain\Spiders;

use Module\Book\Domain\Model\BookChapterModel;
use Module\Book\Domain\Model\BookModel;
use Zodream\Helpers\Json;
use Zodream\Spider\Support\Html;
use Zodream\Spider\Support\Uri;

class AutoSpider extends BaseSpider {

    const TITLE = '#<title>(.+?)</title>#i';
    private $isFirst = true;

    /**
     * @param Uri $uri
     * @return boolean
     */
    public function isCatalogPage(Uri $uri) {
        $first = $this->isFirst;
        $this->isFirst = false;
        return $first;
    }

    /**
     * @param Uri $uri
     * @return boolean
     */
    public function isContentPage(Uri $uri) {
        return !$this->isFirst;
    }

    public function decode(Html $html) {
        $charset = $html->matchValue('/<meta[^\<]*charset=([^<]*)[\"\']/', 1);
        if (empty($charset)) {
            return $html;
        }
        if (stripos($charset, 'utf') !== false) {
            return $html;
        }
        $encode = 'GB2312, GBK, ASCII, BIG5';
        if (stripos($charset, 'gbk') !== false) {
            $encode = 'GBK';
        }
        $html->setHtml(mb_convert_encoding($html->getHtml(), 'utf-8', $encode));
        return $html;
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
        $args = [];
        foreach ($data as $item) {
            if (!$this->isNewChapter($item['url'])) {
                continue;
            }
            $chapterUri = clone $baseUri;
            $item['url'] = $this->getAbsoluteUri($item['url'], $chapterUri);
            $args[] = $item;
        }
        return $args;
    }

    protected function getAbsoluteUri($url, Uri $baseUri) {
        if (str_contains($url, '//')) {
            return $url;
        }
        if (str_starts_with($url, '/')) {
            return $baseUri->setPath($url)->encode();
        }
        return $baseUri->setPath(
            (trim($baseUri->getPath(), '/').'/'.trim($url, '/')))
            ->encode();
    }

    /**
     * @param Html $html
     * @param Uri|null $uri
     * @return array
     */
    public function getChapter(Html $html, Uri|null $uri = null) {
        $html = $html->getHtml();
        $title = $this->getTitle($html);
        $html = $this->cleanHtml($html);
        $tags = $this->getTagMaps($html);
        $content = $this->getContent($tags, $html);
        if (is_array($content)) {
            $this->debug(sprintf('提取章节《%s》失败', $title));
            $content = $uri->encode();
        }
        return [
            'title' => $title,
            'content' => $content
        ];
    }

    public function getContent(array $tags, $html)
    {
        for ($index = count($tags) - 1; $index >= 0; $index--) {
            if (isset($tags[$index]['center']) && $tags[$index]['center']) {
                break;
            }
        }
        if ($index < 0) {
            $index = 0;
        }
        if (empty($tags)) {
            return [];
        }
        $tag = $tags[$index];
        if (str_contains($tag['tag'], 'a')) {
            return $this->getChapters(
                $this->getChaptersStart($tags, $index), $this->getChaptersEnd($tags, $index), $html);
        }
        $start = $this->getContentStart($tags, $index);
        $content = substr($html, $start ,
            $tags[$index + 1]['index'] - $start + 1);
        $content = preg_replace('#^<.+?</[^<>]+?>#', '', $content);
        return Html::toText($content);
    }

    protected function getContentStart(array $tags, $index) {
        return $index >= 1 ? $tags[$index - 1]['index'] + 1 : 0;
    }



    protected function getChaptersEnd(array $tags, $index) {
        $count = count($tags);
        while ($index + 2 < $count) {
            if ($tags[$index + 1]['count'] < 10 &&
                $tags[$index + 2]['tag'] === $tags[$index]['tag']) {
                $index += 2;
                continue;
            }
            break;
        }
        return $index + 1 > $count ? -1 : ($tags[$index + 1]['index'] + 1);
    }

    protected function getChaptersStart(array $tags, $index) {
        while ($index >= 2) {
            if ($tags[$index - 1]['count'] < 10 &&
                $tags[$index - 1]['tag'] !== 'dt' &&
                $tags[$index - 2]['tag'] === $tags[$index]['tag']) {
                $index -= 2;
                continue;
            }
            break;
        }
        return $tags[$index]['index'];
    }

    public function getChapters($start, $end, $html) {
        $content = $end > 0 ? substr($html, $start, $end - $start)
            : substr($html, $start);
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

    public function getCleanContent($html) {
        $html = $this->cleanHtml($html);
        return $this->getContent($this->getTagMaps($html), $html);
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
                 // 不能先合并单个字符
//                $count = count($maps);
//                if ($count > 0 && $maps[$count - 1]['tag'] === $tag) {
//                    $maps[$count - 1]['count'] ++;
//                } else {
                    $maps[] = [
                        'tag' => $tag,
                        'count' => 1,
                        'index' => $i - strlen($tag) - 2
                    ];
//                }
                $tag = false;
                continue;
            }
            if ($tag !== false) {
                $tag .= $char;
            }
        }
        return $this->compactTags($this->mergeTags($maps));

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
        $html = preg_replace("#(\>)[\s\n\r]+(\</?\w+)#", '$1$2', $html);
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

    /**
     * 倒序合并多个空标签
     * @param $maps
     * @return array
     */
    protected function mergeTags(array $maps) {
        $data = [];
        $prev = false;
        for ($i = count($maps) - 1; $i >= 0; $i--) {
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
            $data[$count - 1]['count']++;
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

    /**
     * 压缩合并单个标签
     * @param array $maps
     * @return array
     */
    protected function compactTags(array $maps) {
        $i = count($maps);
        while ($i > 1) {
            $i --;
            if ($maps[$i]['tag'] !== $maps[$i - 1]['tag']) {
                continue;
            }
            $maps[$i - 1]['count'] += $maps[$i]['count'];
            if (isset($maps[$i]['center']) && $maps[$i]['center']) {
                $maps[$i - 1]['center'] = true;
            }
            unset($maps[$i]);
        }
        return array_values($maps);
    }
}