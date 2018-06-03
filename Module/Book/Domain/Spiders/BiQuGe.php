<?php
namespace Module\Book\Domain\Spiders;

use Module\Book\Domain\Model\BookAuthorModel;
use Module\Book\Domain\Model\BookChapterModel;
use Module\Book\Domain\Model\BookModel;
use Zodream\Spider\Support\Html;
use Zodream\Spider\Support\Uri;

class BiQuGe extends BaseSpider {

    /**
     * @param Uri $uri
     * @return boolean
     */
    public function isCatalogPage(Uri $uri) {
        return preg_match('#^/?book/\d+/?$#i', $uri->getPath(), $match);
    }

    /**
     * @param Uri $uri
     * @return boolean
     */
    public function isContentPage(Uri $uri) {
        return preg_match('#^/?book/\d+/\d+\.html$#i', $uri->getPath(), $match);
    }

    /**
     * @param Html $html
     * @param Uri $uri
     * @return BookModel
     */
    public function getBook(Html $html, Uri $uri) {
        $author = $html->find('#info p', 0)->text;
        $author = explode('：', $author, 2);
        $path = $html->find('#fmimg img', 0)->src;
        if (!empty($path)) {
            $path = (clone $uri)->setPath($path)->encode();
        }
        return new BookModel([
            'name' => $html->find('#info h1', 0)->text,
            'cover' => $path,
            'description' => $html->find('#intro', 0)->text,
            'author_id' => BookAuthorModel::findOrNewByName(end($author))->id
        ]);
    }

    /**
     * @param Html $html
     * @param Uri $baseUri
     * @return array[]
     */
    public function getCatalog(Html $html, Uri $baseUri) {
        $uris = [];
        $html->find('#list', 0)->matches('#<a[^<>]+href="/book/(\d+)/(\d+)\.html"[^<>]*>([\S\s]+?)</a>#i',
            function ($match) use (&$uris, $baseUri) {
            if (strpos($match[0], $baseUri->getPath()) === false) {
                return;
            }
            $uris[$match[2]] = $match[3];
        });
        $data = [];
        foreach ($uris as $key => $name) {
            $chapterUri = clone $baseUri;
            $chapterUri->setPath(trim($baseUri->getPath(), '/').'/'.$key.'.html');
            $data[] = [$chapterUri, $name];
        }
        return $data;
    }

    /**
     * @param $html
     * @return BookChapterModel
     */
    public function getChapter(Html $html) {
        if ($html->isEmpty()) {
            return null;
        }
        $content = $html->find('#content', 0);
        if (empty($content)) {
            return null;
        }
        /// html 转文本还有问题
        return new BookChapterModel([
            'title' => $html->find('.bookname h1', 0)->text,
            'content' => self::toText($content->html)
        ]);
    }
}