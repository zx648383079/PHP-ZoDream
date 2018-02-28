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
        // TODO: Implement isCatalogPage() method.
    }

    /**
     * @param Uri $uri
     * @return boolean
     */
    public function isContentPage(Uri $uri)
    {
        // TODO: Implement isContentPage() method.
    }

    /**
     * @param $html
     * @return BookModel
     */
    public function getBook(Html $html)
    {
        // TODO: Implement getBook() method.
    }

    /**
     * @param $html
     * @return BookChapterModel[]
     */
    public function getCatalog(Html $html)
    {
        // TODO: Implement getCatalog() method.
    }

    /**
     * @param $html
     * @return string
     */
    public function getContent(Html $html)
    {
        // TODO: Implement getContent() method.
    }
}