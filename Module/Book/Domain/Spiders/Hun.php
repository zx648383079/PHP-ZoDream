<?php
namespace Module\Book\Domain\Spiders;

use Module\Book\Domain\Model\BookAuthorModel;
use Module\Book\Domain\Model\BookChapterModel;
use Module\Book\Domain\Model\BookModel;

use Zodream\Spider\Support\Html;
use Zodream\Spider\Support\Uri;

class Hun extends BaseSpider {

    /**
     * @param Uri $uri
     * @return boolean
     */
    public function isCatalogPage(Uri $uri) {
        return preg_match('#^/?hunhun/\d+/\d+/?$#i', $uri->getPath(), $match);
    }

    /**
     * @param Uri $uri
     * @return boolean
     */
    public function isContentPage(Uri $uri) {
        return preg_match('#^/?hunhun/\d+/\d+/\d+\.html$#i', $uri->getPath(), $match);
    }

    public function decode(Html $html) {
        $content = $html->getHtml();
        //$html->setHtml(iconv('gb2312', 'utf-8//IGNORE', $content));
        $html->setHtml(mb_convert_encoding($content, 'utf-8', 'GB2312, ASCII, UTF-8, GBK, BIG5'));
        return parent::decode($html);
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
            'name' => $html->matchValue('#class="novel_name">(.+?)\</h1\>#', 1),//$html->find('#wp', 0)->find('h1', 0)->text,
        ];
    }

    /**
     * @param Html $html
     * @param Uri $baseUri
     * @return array[]
     */
    public function getCatalog(Html $html, Uri $baseUri) {
        $uris = [];
       $html->matches('#\<li[^\>\<]+\>\<div\sclass="novel_num"\>\</div\>\<a\shref="(\d+)\.html"\>(.+?)\</a\>\</li\>#',
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
        $content = $html->matchValue('#id="novel_content"\>([\s\S]+?)\</div\>#', 1);
        if (empty($content)) {
            return [];
        }
        /// html 转文本还有问题
        $text = self::toText($content);
        //$encoding = mb_detect_encoding($content, array("ASCII",'UTF-8',"GB2312","GBK",'BIG5'));
        return [
            'title' => $html->matchValue('#class="novel_title"\>(.+)\</h1\>#', 1),
            'content' => $text
        ];
    }
}