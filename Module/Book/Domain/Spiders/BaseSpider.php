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

    public function invoke(Uri $uri, callable $next = null) {
        if ($this->isContentPage($uri)) {
            $chapter = $this->getChapter(Spider::url($uri));
            if ($chapter instanceof Model) {
                $chapter->save();
            }
            return $chapter;
        }
        if (!$this->isCatalogPage($uri)) {
            return null;
        }
        $html = Spider::url($uri);
        $book = $this->getBook($html);
        if ($book->isExist()) {
            $this->debug(sprintf('《%s》 已存在书库', $book->name));
            return;
        }
        $book->save();
        $chapters = $this->getCatalog($html, $uri);
        $this->downloadChapter($book, $chapters, $next);
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

    protected function debug($content) {
        if (!Request::isCli()) {
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
        while (true) {
            $url = $chapters[$i];
            /** @var BookChapterModel $chapter */
            $chapter = call_user_func($next, $url);
            if (!empty($chapter)) {
                $chapter->book_id = $book->id;
                $chapter->save();
                $this->debug(sprintf('下载章节 《%s》 完成 ', $chapter->title));
                $i++;
                $failure = 0;
            }
            if ($failure > 10) {
                $this->debug(sprintf('下载章节 《%s》 失败，跳过。。。', $url));
                $i++;
                $failure = 0;
                continue;
            }
            $failure ++;
            $this->debug(sprintf('下载章节 《%s》 失败，%s 秒后重试', $url, $failure * 2));
            sleep($failure * 2);
            continue;
        }
    }
}