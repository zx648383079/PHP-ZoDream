<?php
namespace Module\Book\Domain\Spiders;

use Module\Book\Domain\Model\BookChapterModel;
use Module\Book\Domain\Model\BookModel;
use Zodream\Spider\Support\Html;
use Zodream\Spider\Support\Uri;
use Exception;

class Sixth extends BaseSpider {

    /**
     * @param Uri $uri
     * @return boolean
     */
    public function isCatalogPage(Uri $uri) {
        return preg_match('#^/?\d+/\d+/?$#i', $uri->getPath(), $match);
    }

    /**
     * @param Uri $uri
     * @return boolean
     */
    public function isContentPage(Uri $uri) {
        return preg_match('#^/?\d+/\d+/\d+\.html$#i', $uri->getPath(), $match);
    }

    /**
     * @param Html $html
     * @param Uri $uri
     * @return array
     */
    public function getBook(Html $html, Uri $uri) {
        //$author = $html->find('#info p', 0)->text;
        //$author = explode('：', $author, 2);
        //$path = $html->find('#fmimg img', 0)->src;
//        if (!empty($path)) {
//            $path = (clone $uri)->setPath($path)->encode();
//        }
        return [
            'name' => $html->find('#bookname', 0)->find('h1', 0)->text,
        ];
    }

    /**
     * @param Html $html
     * @param Uri $baseUri
     * @return array[]
     */
    public function getCatalog(Html $html, Uri $baseUri) {
        $uris = [];
        $html->find('#chapterlist', 0)->matches('#<a[^<>]+href="(\d+)\.html"[^<>]*>([\S\s]+?)</a>#i',
            function ($match) use (&$uris, $baseUri) {
                if (!$this->isNewChapter($match[1])) {
                    return;
                }
                $uris[$match[1]] = $match[2];
        });
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
    public function getChapter(Html $html, Uri|null $uri = null) {
        if ($html->isEmpty()) {
            return [];
        }
        try {
            $content = $html->find('#content', 0);
        } catch (Exception $exception) {
            return [];
        }
        if (empty($content)) {
            return [];
        }
        /// html 转文本还有问题
        $text = self::toText($content->html);
        //$encoding = mb_detect_encoding($text, array("ASCII",'UTF-8',"GB2312","GBK",'BIG5'));
        return [
            'title' => $html->find('#main h1', 0)->text,
            'content' => $text
        ];
    }
}