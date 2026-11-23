<?php
declare(strict_types=1);
namespace Module\Book\Domain\Spiders;

use Zodream\Spider\Support\Html;
use Zodream\Spider\Support\Uri;

class BiQuGe extends BaseSpider {

    protected string $matchCatalog = '#^/?book/\d+/?$#i';

    protected string $matchContent = '#^/?book/\d+/\d+\.html$#i';

    protected bool $sort = true;

    protected string $matchList = '#<a[^<>]+href="/book/(\d+)/(\d+)\.html"[^<>]*>([\S\s]+?)</a>#i';

    /**
     * @param Uri $uri
     * @return boolean
     */
    public function isCatalogPage(Uri $uri): bool {
        return !!preg_match($this->matchCatalog, $uri->getPath(), $match);
    }

    /**
     * @param Uri $uri
     * @return boolean
     */
    public function isContentPage(Uri $uri): bool {
        return !!preg_match($this->matchContent, $uri->getPath(), $match);
    }

    /**
     * @param Html $html
     * @param Uri $uri
     * @return array
     */
    public function getBook(Html $html, Uri $uri): array {
        $author = $html->find('#info p', 0)->text;
        $author = str_replace('：', ':', $author);
        $author = explode(':', $author, 2)[1];
        $path = $html->find('#fmimg img', 0)->src;
        if (!empty($path) && strpos($path, '//') === false) {
            $path = (clone $uri)->setPath($path)->encode();
        }
        return [
            'name' => $html->find('#info h1', 0)->text,
            'cover' => $path,
            'author' => $author
        ];
    }

    /**
     * @param Html $html
     * @param Uri $baseUri
     * @return array[]
     */
    public function getCatalog(Html $html, Uri $baseUri): array {
        $uris = [];
        $html->find('#list', 0)->matches($this->matchList,
            function ($match) use (&$uris, $baseUri) {
            if (count($match) == 3) {
                if (!$this->isNewChapter($match[1])) {
                    return;
                }
                $uris[$match[1]] = $match[2];
                return;
            }
            if (strpos($match[0], $baseUri->getPath()) === false) {
                return;
            }
            if (!$this->isNewChapter($match[2])) {
                return;
            }
            $uris[$match[2]] = $match[3];
        });
        if ($this->sort) {
            ksort($uris);
        }
        $data = [];
        foreach ($uris as $key => $name) {
            $chapterUri = clone $baseUri;
            $chapterUri->setPath(trim($baseUri->getPath(), '/').'/'.$key.'.html');
            $data[] = [
                'title' => $name,
                'url' => $chapterUri->encode()
            ];
        }
        return $data;
    }

    /**
     * @param Html $html
     * @param Uri|null $uri
     * @return array
     */
    public function getChapter(Html $html, Uri|null $uri = null): array {
        if ($html->isEmpty()) {
            return [];
        }
        $content = $html->find('#content', 0);
        if (empty($content)) {
            return [];
        }
        /// html 转文本还有问题
        return [
            'title' => $html->find('.bookname h1', 0)->text,
            'content' => self::toText($content->html)
        ];
    }
}