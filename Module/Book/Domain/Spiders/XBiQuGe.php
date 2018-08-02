<?php
namespace Module\Book\Domain\Spiders;

use Module\Book\Domain\Model\BookAuthorModel;
use Module\Book\Domain\Model\BookChapterModel;
use Module\Book\Domain\Model\BookModel;
use Zodream\Spider\Support\Html;
use Zodream\Spider\Support\Uri;

class XBiQuGe extends BiQuGe {

    /**
     * @param Uri $uri
     * @return boolean
     */
    public function isCatalogPage(Uri $uri) {
        return preg_match('#^/?[\d_]+/?$#i', $uri->getPath(), $match);
    }

    /**
     * @param Uri $uri
     * @return boolean
     */
    public function isContentPage(Uri $uri) {
        return preg_match('#^/?[\d_]+/\d+\.html$#i', $uri->getPath(), $match);
    }

    /**
     * @param Html $html
     * @param Uri $baseUri
     * @return array[]
     */
    public function getCatalog(Html $html, Uri $baseUri) {
        $uris = [];
        $html->find('#list', 0)->matches('#<a[^<>]+href="/([\d_]+)/(\d+)\.html"[^<>]*>([\S\s]+?)</a>#i',
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
}