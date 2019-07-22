<?php
namespace Module\Book\Domain\Spiders;

use Module\Book\Domain\Model\BookAuthorModel;
use Module\Book\Domain\Model\BookChapterModel;
use Module\Book\Domain\Model\BookModel;
use Zodream\Spider\Support\Html;
use Zodream\Spider\Support\Uri;

class Qb extends BaseSpider {

    protected $matchCatalog = '#^/?xiaoshuo/\d+/\d+/?$#i';

    protected $matchContent = '#^/?xiaoshuo/\d+/\d+/\d+\.html$#i';

    protected $sort = true;

    protected $matchList = '#<a[^<>]+href="/xiaoshuo/\d+/\d+/(\d+)\.html"[^<>]*>([\S\s]+?)</a>#i';

    /**
     * @param Uri $uri
     * @return boolean
     */
    public function isCatalogPage(Uri $uri) {
        return preg_match($this->matchCatalog, $uri->getPath(), $match);
    }

    /**
     * @param Uri $uri
     * @return boolean
     */
    public function isContentPage(Uri $uri) {
        return preg_match($this->matchContent, $uri->getPath(), $match);
    }

    /**
     * @param Html $html
     * @param Uri $uri
     * @return array
     */
    public function getBook(Html $html, Uri $uri) {
        $info = $html->find('.book .info', 0);
        $author = $info->find('.small span', 0)->text;
        $author = explode('：', $author, 2)[1];
        $path = $info->find('.cover img', 0)->src;
        if (!empty($path)) {
            $path = (clone $uri)->setPath($path)->encode();
        }
        return [
            'name' => $info->find('h2', 0)->text,
            'cover' => $path,
            'author' => $author
        ];
    }

    /**
     * @param Html $html
     * @param Uri $baseUri
     * @return array[]
     */
    public function getCatalog(Html $html, Uri $baseUri) {
        $uris = [];
        $html->find('.listmain', 0)->matches($this->matchList,
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
    public function getChapter(Html $html, Uri $uri = null) {
        if ($html->isEmpty()) {
            return [];
        }
        $content = $html->find('#content', 0);
        if (empty($content)) {
            return [];
        }
        /// html 转文本还有问题
        return [
            'title' => $html->find('.content h1', 0)->text,
            'content' => self::toText($content->html)
        ];
    }
}