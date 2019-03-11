<?php
namespace Module\Book\Domain;

use Module\Book\Domain\Model\BookAuthorModel;
use Module\Book\Domain\Model\BookCategoryModel;
use Module\Book\Domain\Model\BookChapterModel;
use Module\Book\Domain\Model\BookModel;
use Module\Book\Domain\Spiders\BiQuGeC;
use Module\Book\Domain\Spiders\GetBookInterface;
use Module\Book\Domain\Spiders\Sixth;
use Module\Book\Domain\Spiders\XBiQuGe;
use Module\Book\Domain\Spiders\BiQuGe;
use Module\Book\Domain\Spiders\BiQuGeLu;
use Module\Book\Domain\Spiders\CBiQuGe;
use Module\Book\Domain\Spiders\Hun;
use Module\Book\Domain\Spiders\IBiQuGe;
use Module\Book\Domain\Spiders\MBiQuGe;
use Module\Book\Domain\Spiders\SBiQuGe;
use Module\Book\Domain\Spiders\Sj;
use Module\Book\Domain\Spiders\BaseSpider;
use Module\Book\Domain\Spiders\ZhiShuShenQi;
use Zodream\Infrastructure\Error\Exception;
use Zodream\Spider\Spider;
use Zodream\Spider\Support\Html;
use Zodream\Spider\Support\Uri;

class SiteCrawl {

    static $maps = [
        'www.qu.la' => BiQuGe::class,
        'www.biquge5200.cc' => CBiQuGe::class,
        'www.biquge.cc' => BiQuGeC::class,
        'www.biquge.lu' => BiQuGeLu::class,
        'www.biquge.cm' => MBiQuGe::class,
        'www.xxbiquge.com' => XBiQuGe::class,
        'www.sjtxt.la' => Sj::class,
        'www.haohunhun.com' => Hun::class,
        'www.biquge.info' => IBiQuGe::class,
        'www.shuge.la' => SBiQuGe::class,
        'www.booktxt.net' => IBiQuGe::class,
        'www.16book.org' => Sixth::class
    ];

    /**
     * @var Uri
     */
    protected $host;
    /**
     * @var BaseSpider
     */
    protected $spider;

    protected $uri_pool = [];

    protected $old_pool = [];

    public function __construct(Uri $host, BaseSpider $spider) {
        $this->setHost($host)
            ->setSpider($spider)
            ->addUri($host);
    }

    /**
     * @param Uri $host
     * @return SiteCrawl
     */
    public function setHost(Uri $host) {
        $this->host = $host;
        return $this;
    }

    /**
     * @param BaseSpider $spider
     * @return SiteCrawl
     */
    public function setSpider(BaseSpider $spider) {
        $this->spider = $spider;
        return $this;
    }

    public function start() {
        while ($uri = $this->nextUri()) {
            $html = $this->spider->decode(Spider::url($uri));
            $this->addUriFromHtml($html, $uri);
            if ($this->spider->isCatalogPage($uri)) {
                $this->spider->invokeHtml($html, $uri,
                    [$this->spider, 'invoke']);
            }
        }
    }

    /**
     * @return Uri|bool
     */
    public function nextUri() {
        if (count($this->uri_pool) < 1) {
            return false;
        }
        $path = array_shift($this->uri_pool);
        $this->old_pool[] = $path;
        $uri = new Uri($path);
        return $uri->setHost($this->host->getHost())
            ->setScheme($this->host->getScheme());
    }

    /**
     * @param Html $html
     * @param Uri $baseUri
     */
    public function addUriFromHtml(Html $html, Uri $baseUri) {
        $html->matches('#<a[^<>]+href=["\']([^"\'\s<>]+)["\']#i',
            function ($match) use (&$uris, $baseUri) {
                $path = $match[1];
                if (empty($path)) {
                    return;
                }
                if (strpos($path, '#') === 0 ||
                    strpos($path, 'javascript:') === 0) {
                    return;
                }
                $uri = (clone $baseUri)->setData([])->addPath($path);
                if ($uri->getHost() != $baseUri->getHost()) {
                    return;
                }
                if ($this->spider->isContentPage($uri)) {
                    return;
                }
                $this->addUri($uri->setFragment(null));
            });

    }

    public function addUri(Uri $uri) {
        if (!$this->isHost($uri) || $this->hasUri($uri)) {
            return false;
        }
        $this->uri_pool[] = $this->getUri($uri);
        return true;
    }

    protected function getUri($uri) {
        return $uri instanceof Uri ? $uri->setFragment(null)->encode(false) : $uri;
    }

    public function isHost($host) {
        $host = $host instanceof Uri ? $host->getHost() : $host;
        return $host == $this->host->getHost();
    }

    public function hasUri($uri) {
        $uri = $this->getUri($uri);
        return in_array($uri, $this->uri_pool) || in_array($uri, $this->old_pool);
    }

    /**
     * @param $host
     * @return BaseSpider|bool
     */
    public static function getSpider($host) {
        $host = $host instanceof Uri ? $host->getHost() : $host;
        if (!isset(self::$maps[$host])) {
            return false;
        }
        $class = self::$maps[$host];
        return new $class;
    }

    /**
     * @param $spider
     * @param array $book
     * @return bool
     * @throws Exception
     */
    public static function async(GetBookInterface $spider, array $book) {
        if (!isset($book['chapters'])) {
            $book =
                $spider->book($spider instanceof ZhiShuShenQi
                    ? $book['id'] : $book['url']);
        }
        if (empty($book)) {
            throw new Exception('书籍信息错误');
        }
        $model = BookModel::where('name', $book['name'])->first();
        if (empty($model)) {
            return self::asyncCreate($spider, $book);
        }
        return self::asyncUpdate($spider, $model, $book);
    }

    public static function createBook(array $book) {
        return new BookModel([
            'name' => $book['name'],
            'cover' => isset($book['cover']) ? $book['cover'] : '',
            'description' => isset($book['description']) ? $book['description'] : '',
            'author_id' => BookAuthorModel::findIdByName(isset($book['author'])
                ? $book['author'] : ''),
            'cat_id' => BookCategoryModel::findIdByName(isset($book['category'])
                ? $book['category'] : ''),
            'classify' => 1,
            'size' => isset($book['size']) ? intval($book['size']) : 0
        ]);
    }

    /**
     * @param GetBookInterface $spider
     * @param array $book
     * @return bool
     * @throws Exception
     */
    public static function asyncCreate(GetBookInterface $spider, array $book) {
        $model = static::createBook($book);
        if (!$model->save()) {
            throw new Exception('导入失败');
        }
        return [
            $spider, $model->id, $book['chapters'], 0
        ];
    }

    public static function asyncUpdate(
        GetBookInterface $spider, BookModel $model, array $book) {
        return [
            $spider, $model->id, $book['chapters'], self::getStartChapter($model, $book['chapters'])
        ];
    }

    public static function getStartChapter(BookModel $model, array $chapters) {
        $last_chapter = $model->last_chapter;
        if (empty($last_chapter)) {
            return 0;
        }
        $count = $model->chapter_count;
        for ($i = count($chapters) - 1; $i >= 0; $i --) {
            if (strlen($last_chapter['title']) > strlen($chapters[$i]['title'])) {
                if (strpos($last_chapter['title'], $chapters[$i]['title']) >= 0 &&
                    $i - $count > -15) {
                    return $i + 1;
                }
                continue;
            }
            if (strpos($chapters[$i]['title'], $last_chapter['title']) >= 0 &&
                $i - $count > -15) {
                return $i + 1;
            }
        }
        return 0;
    }

    public static function asyncChapters(
        GetBookInterface $spider, $book_id, array $chapters, $start = 0, $length = -1) {
        $length = $length <= $start ? count($chapters) : min(count($chapters), $length);
        for (; $start < $length; $start ++) {
            $item = $spider->chapter($chapters[$start]['url']);
            if (empty($item)) {
                $chapter = new BookChapterModel([
                    'title' => $chapters[$start]['title'],
                    'content' => $chapters[$start]['url'],
                    'book_id' => $book_id
                ]);
            } else {
                $chapter = new BookChapterModel([
                    'title' => $item['title'],
                    'content' => $item['content'],
                    'book_id' => $book_id
                ]);
            }
            $chapter->save();
        }
        return true;
    }

    public static function loopStep($key, $start = 0) {
        $data = cache($key);
        if (empty($data)) {
            throw new Exception('数据不存在！');
        }
        list($spider, $book_id, $chapters) = $data;
        $count = count($chapters);
        if ($start >= $count)  {
            return true;
        }
        $next = min($start + 20, $count);
        static::asyncChapters($spider, $book_id, $chapters, $start, $next);
        if ($next >= $count) {
            return true;
        }
        return compact('next', 'count', 'key');
    }
}