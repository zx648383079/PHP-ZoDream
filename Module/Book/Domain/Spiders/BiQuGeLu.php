<?php
namespace Module\Book\Domain\Spiders;

use Module\Book\Domain\Model\BookAuthorModel;
use Module\Book\Domain\Model\BookChapterModel;
use Module\Book\Domain\Model\BookModel;
use Zodream\Spider\Support\Html;
use Zodream\Spider\Support\Uri;

class BiQuGeLu extends BaseSpider {

    protected $matchCatalog = '#^/?book/\d+/?$#i';

    protected $matchContent = '#^/?book/\d+/\d+\.html$#i';

    protected $matchList = '#<a[^<>]+href="/book/(\d+)/(\d+)\.html"[^<>]*>([\S\s]+?)</a>#i';

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
     * @return BookModel
     */
    public function getBook(Html $html, Uri $uri) {
        return new BookModel([
            'name' => $html->find('.info h2', 0)->text,
            'cover' => '/assets/images/book_default.jpg',
            'description' => '',//$html->find('#intro', 0)->text,
            'author_id' => 1,//BookAuthorModel::findOrNewByName(end($author))->id,
            'cat_id' => 1,
            'classify' => 1
        ]);
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
            'title' => $html->find('.content h1', 0)->text,
            'content' => self::toText($content->html)
        ]);
    }
}