<?php
namespace Module\Book\Domain\Spiders;

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
     * @param $html
     * @return BookModel
     */
    public function getBook(Html $html) {
        $author = $html->find('#info p', 0)->text;
        $author = explode('：', $author, 2);
        return new BookModel([
            'name' => $html->find('#info h1', 0)->text,
            'cover' => $html->find('#fmimg img', 0)->src,
            'description' => $html->find('#intro', 0)->text
        ]);
    }

    /**
     * @param Html $html
     * @param Uri $baseUri
     * @return Uri[]
     */
    public function getCatalog(Html $html, Uri $baseUri) {
        $uris = [];
        $html->find('#list', 0)->matches('#<a[^<>]+href="/book/(\d+)/(\d+).html"#i',
            function ($match) use (&$uris, $baseUri) {
            if (strpos($match[0], $baseUri->getPath()) === false) {
                return;
            }
            $uris[] = $match[2];
        });
        $uris = sort(array_unique($uris));
        foreach ($uris as &$uri) {
            $chapterUri = clone $baseUri;
            $chapterUri->setPath(trim($baseUri->getPath(), '/').'/'.$uri.'.html');
            $uri = $chapterUri;
        }
        return $uris;
    }

    /**
     * @param $html
     * @return BookChapterModel
     */
    public function getChapter(Html $html) {
        return new BookChapterModel([
            'title' => $html->find('.bookname h1', 0)->text,
            'content' => $html->find('#content', 0)->html
        ]);
    }
}