<?php
namespace Module\Book\Domain\Spiders;

use Domain\Model\Model;
use Module\Book\Domain\Model\BookChapterModel;
use Module\Book\Domain\Model\BookModel;
use Zodream\Infrastructure\Http\Request;
use Zodream\Spider\Spider;
use Zodream\Spider\Support\Html;
use Zodream\Spider\Support\Uri;

abstract class BaseSpider {

    protected $url_list = [];

    public function invoke(Uri $uri, callable $next = null) {
        if ($this->isContentPage($uri)) {
            $html = $this->decode(Spider::url($uri));
            $chapter = $this->getChapter($html);
            if ($chapter instanceof Model) {
                $chapter->save();
            }
            return $chapter;
        }
        if (!$this->isCatalogPage($uri)) {
            $this->getSpiderUrl($uri);
            return null;
        }
        $html = $this->decode(Spider::url($uri));
        $book = $this->getBook($html, $uri);
        if ($book->isExist()) {
            $this->debug(sprintf('《%s》 已存在书库', $book->name));
            return;
        }
        $book->save();
        $chapters = $this->getCatalog($html, $uri);
        $this->downloadChapter($book, $chapters, $next);
        return;
    }

    protected function decode(Html $html) {
        return $html;
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
    abstract public function getBook(Html $html, Uri $uri);

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
            //$line = trim($line, '　 ');
            $line = trim($line, ' ');
            if (empty($line)) {
                return '';
            }
            return $line;
        }, $args));
    }

    protected function debug($content) {
        if (!app('request')->isCli()) {
            return;
        }
        echo $content,PHP_EOL;
    }

    /**
     * @param BookModel $book
     * @param array $chapters
     * @param callable $next
     */
    protected function downloadChapter(BookModel $book, array $chapters, callable $next) {
        $this->debug(sprintf('开始下载 《%s》 共 %s 章 ', $book->name, count($chapters)));
        $i = 0;
        $failure = 0;
        $time = time();
        while (true) {
            if (!isset($chapters[$i])) {
                break;
            }
            list($url, $title) = $chapters[$i];
            /** @var BookChapterModel $chapter */
            $chapter = call_user_func($next, $url);
            if (!empty($chapter)) {
                $chapter->book_id = $book->id;
                $chapter->save();
                $this->debug(sprintf('下载章节 《%s》 完成 ', $chapter->title));
                $i++;
                $failure = 0;
                continue;
            }
            if ($failure > 3) {
                $chapter = new BookChapterModel([
                    'title' => $title,
                    'content' => (string)$url,
                    'book_id' => $book->id
                ]);
                $chapter->save();
                $this->debug(sprintf('下载章节 《%s》 失败，跳过。。。', $title));
                $i++;
                $failure = 0;
                continue;
            }
            $failure ++;
            $this->debug(sprintf('下载章节 《%s》 失败，%s 秒后重试', $title, $failure * 2));
            sleep($failure * 2);
            continue;
        }
        $this->debug(sprintf('下载章节完成，用时 %s 秒', time() - $time));
    }

    public function getSpiderUrl(Uri $uri) {
        $html = Spider::url($uri);
        if ($html->isEmpty()) {
            return;
        }
        $data = $this->getUrl($html, $uri);
        foreach ($data as $url) {
            $this->url_list[] = $url->encode();
        }
        foreach ($data as $url) {
            $this->invoke($url);
        }
        return;
    }

    /**
     * @param Html $html
     * @param Uri $baseUri
     * @return Uri[]
     */
    public function getUrl(Html $html, Uri $baseUri) {
        $uris = [];
        $html->find('#list', 0)->matches('#<a[^<>]+href=["\']([^"\'\s<>]+)["\']#i',
            function ($match) use (&$uris, $baseUri) {
                $path = $match[1];
                if (empty($path)) {
                    return;
                }
                if (strpos($path, '#') === 0 || strpos($path, 'javascript:') === 0) {
                    return;
                }
                $uri = (clone $baseUri)->setData([])->addPath($path);
                if ($uri->getHost() != $baseUri->getHost()) {
                    return;
                }
                if ($this->isContentPage($uri)) {
                    return;
                }
                if ($this->isExist($uri)) {
                    return;
                }
                $uris[] = $uri->setFragment(null);
            });
        return $uris;
    }

    public function isExist(Uri $uri) {
        return in_array($uri->encode(), $this->url_list);
    }
}