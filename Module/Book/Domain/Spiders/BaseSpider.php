<?php
namespace Module\Book\Domain\Spiders;

use Domain\Model\Model;
use Module\Book\Domain\Model\BookAuthorModel;
use Module\Book\Domain\Model\BookCategoryModel;
use Module\Book\Domain\Model\BookChapterBodyModel;
use Module\Book\Domain\Model\BookChapterModel;
use Module\Book\Domain\Model\BookModel;

use Module\Book\Domain\SiteCrawl;
use Module\Book\Domain\SpiderProgress;
use Zodream\Spider\Spider;
use Zodream\Spider\Support\Html;
use Zodream\Spider\Support\Uri;

abstract class BaseSpider implements GetBookInterface {

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

    public function invoke($uri, callable $next = null) {
        if (!$uri instanceof Uri) {
            $uri = new Uri($uri);
        }
        if ($this->isContentPage($uri)) {
            $html = $this->decode(Spider::url($uri));
            $chapter = $this->getChapter($html, $uri);
            if (empty($chapter)) {
                return false;
            }
            if (is_array($chapter)) {
                $chapter = new BookChapterModel([
                    'title' => $chapter['title'],
                    'content' => $chapter['content']
                ]);
            }
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
        if (empty($book)) {
            return;
        }
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
        if (preg_match('#([^/\.]+)\.[a-z]+$#', $num, $match) && $match[1] === $this->start) {
            $this->isNewChapter = true;
            return true;
        }
        return false;
    }

    protected function getRealBook($book) {
        if (is_array($book)) {
            $book = SpiderProgress::createBook($book);
        }
        if (!$book->isExist()) {
            $book->save();
            return $book;
        }
        if (empty($this->start)) {
            $this->debug(sprintf('《%s》 已存在书库', $book->name));
            if (!request()->isCli()) {
                return null;
            }
            $arg = request()->read('', '是否继续(Y/N):');
            if (strtolower($arg) != 'y') {
                return null;
            }
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
     * @return array
     */
    abstract public function getBook(Html $html, Uri $uri);

    /**
     * @param Html $html
     * @param Uri $baseUri
     * @return array
     */
    abstract public function getCatalog(Html $html, Uri $baseUri);

    /**
     * @param Html $html
     * @param Uri|null $uri
     * @return array
     */
    abstract public function getChapter(Html $html, Uri $uri = null);

    public function book(string $uri): array {
        return cache()->getOrSet('book_spider_book_'.$uri, function () use ($uri) {
            $uri = new Uri($uri);
            $html = $this->decode(Spider::url($uri));
            $data = $this->getBook($html, $uri);
            $data['chapters'] = $this->getCatalog($html, $uri);
            return $data;
        }, 600);
    }

    public function chapter(string $uri): array {
        $html = $this->decode(Spider::url($uri));
        return $this->getChapter($html);
    }


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
        if (!request()->isCli()) {
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
            $title = $chapters[$i]['title'];
            $url = $chapters[$i]['url'];
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