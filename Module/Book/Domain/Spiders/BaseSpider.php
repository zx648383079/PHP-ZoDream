<?php
namespace Module\Book\Domain\Spiders;

use Domain\Model\Model;
use Module\Book\Domain\Model\BookChapterBodyModel;
use Module\Book\Domain\Model\BookChapterModel;
use Module\Book\Domain\Model\BookModel;

use Zodream\Spider\Spider;
use Zodream\Spider\Support\Html;
use Zodream\Spider\Support\Uri;

abstract class BaseSpider {

    protected $start = null;

    protected $isNewChapter = true;

    /**
     * @param null $start
     * @return BaseSpider
     */
    public function setStart($start) {
        $this->start = $start;
        return $this;
    }

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
            return null;
        }
        $html = $this->decode(Spider::url($uri));
        return $this->invokeHtml($html, $uri, $next);
    }

    /**
     * @param Uri $uri
     * @param callable $next
     * @param $html
     */
    public function invokeHtml(Html $html, Uri $uri, callable $next) {
        $book = $this->getBook($html, $uri);
        $book = $this->getRealBook($book);
        if (empty($book)) {
            return;
        }
        $this->isNewChapter = false;
        $chapters = $this->getCatalog($html, $uri);
        $this->downloadChapter($book, $chapters, $next);
        return;
    }

    protected function isNewChapter($num) {
        if ($this->isNewChapter || empty($this->start)) {
            return true;
        }
        if ($num == $this->start) {
            $this->isNewChapter = true;
            return true;
        }
        return false;
    }

    protected function getRealBook(BookModel $book) {
        if (!$book->isExist()) {
            $book->save();
            return $book;
        }
        if (empty($this->start)) {
            $this->debug(sprintf('《%s》 已存在书库', $book->name));
            return null;
        }
        return BookModel::where('name', $book->name)->one();
    }

    public function decode(Html $html) {
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
        $ids = BookChapterModel::where('book_id', $book->id)->pluck('id');
        $book->size = BookChapterBodyModel::whereIn('id', $ids)->sum('char_length(content)');
        $book->save();
        $this->debug(sprintf('下载章节完成，用时 %s 秒', time() - $time));
    }

}