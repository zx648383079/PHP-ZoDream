<?php
namespace Module\Book\Domain\Spiders;

use Module\Book\Domain\Model\BookChapterModel;
use Module\Book\Domain\Model\BookModel;
use Zodream\Spider\Support\Html;
use Zodream\Spider\Support\Uri;

abstract class BaseSpider {

    public function invoke(Uri $uri) {
        if ($this->isContentPage($uri)) {

        }
        if ($this->isCatalogPage($uri)) {

        }
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
     * @param $html
     * @return BookChapterModel[]
     */
    abstract public function getCatalog(Html $html);

    /**
     * @param $html
     * @return string
     */
    abstract public function getContent(Html $html);
}