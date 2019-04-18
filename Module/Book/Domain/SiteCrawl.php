<?php
namespace Module\Book\Domain;

use Module\Book\Domain\Spiders\AutoSpider;
use Module\Book\Domain\Spiders\BiQuGeC;
use Module\Book\Domain\Spiders\Qb;
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
use Zodream\Spider\Spider;
use Zodream\Spider\Support\Html;
use Zodream\Spider\Support\Uri;

class SiteCrawl {

    static $maps = [
        'www.qu.la' => BiQuGe::class,
        'www.biquge5200.cc' => CBiQuGe::class,
        'www.biquge11.com' => CBiQuGe::class,
        'www.paoshu8.com' => CBiQuGe::class,
        'www.biquge.cc' => BiQuGeC::class,
        'www.biquge.lu' => BiQuGeLu::class,
        'www.biquge.cm' => MBiQuGe::class,
        'www.xbiquge.la' => MBiQuGe::class,
        'www.xxbiquge.com' => XBiQuGe::class,
        'www.sjtxt.la' => Sj::class,
        'www.haohunhun.com' => Hun::class,
        'www.biquge.info' => IBiQuGe::class,
        'www.shuge.la' => SBiQuGe::class,
        'www.booktxt.net' => IBiQuGe::class,
        'www.16book.org' => Sixth::class,
        'www.qb5200.tw' => Qb::class
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
        if (isset(self::$maps[$host])) {
            $class = self::$maps[$host];
            return new $class;
        }
        return new AutoSpider();
    }
}