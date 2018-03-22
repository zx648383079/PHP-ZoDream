<?php
namespace Module\Book\Domain\Spiders;

use Module\Book\Domain\Model\BookChapterModel;
use Module\Book\Domain\Model\BookModel;
use Zodream\Spider\Spider;
use Zodream\Spider\Support\Html;
use Zodream\Spider\Support\Uri;

abstract class BaseSpider {

    public function invoke(Uri $uri, callable $next = null) {
        if ($this->isContentPage($uri)) {
            $chapter = $this->getChapter(Spider::url($uri));
            $chapter->save();
            return $chapter;
        }
        if (!$this->isCatalogPage($uri)) {
            return null;
        }
        $html = Spider::url($uri);
        $book = $this->getBook($html);
        $book->save();
        $chapters = $this->getCatalog($html, $uri);
        foreach ($chapters as $url) {
            $chapter = call_user_func($next, $url);
            $chapter->book_id = $book->id;
            $chapter->save();
        }
        return;
    }

    /**
     * @param Uri $uri
     * @return boolean
     */
    abstract public function isCatalogPage(Uri $uri);

    /**
     * @param Uri $uri
     * @return boolean
     */
    abstract public function isContentPage(Uri $uri);

    /**
     * @param $html
     * @return BookModel
     */
    abstract public function getBook(Html $html);

    /**
     * @param Html $html
     * @param Uri $baseUri
     * @return Uri[]
     */
    abstract public function getCatalog(Html $html, Uri $baseUri);

    /**
     * @param $html
     * @return BookChapterModel
     */
    abstract public function getChapter(Html $html);

    public static function toText($html) {
        $html = Html::toText($html);
        $args = explode(PHP_EOL, $html);
        return implode(PHP_EOL, array_map(function ($line) {
            $line = trim($line, '　 ');
            if (empty($line)) {
                return '';
            }
            return $line;
        }, $args));
    }
}