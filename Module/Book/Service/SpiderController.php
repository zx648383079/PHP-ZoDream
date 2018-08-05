<?php
namespace Module\Book\Service;


use Module\Book\Domain\Spiders\BaseSpider;
use Module\Book\Domain\Spiders\BiQuGe;
use Module\Book\Domain\Spiders\BiQuGeLu;
use Module\Book\Domain\Spiders\Hun;
use Module\Book\Domain\Spiders\IBiQuGe;
use Module\Book\Domain\Spiders\MBiQuGe;
use Module\Book\Domain\Spiders\Sj;
use Module\Book\Domain\Spiders\Txt;
use Module\Book\Domain\Spiders\XBiQuGe;
use Zodream\Service\Factory;
use Zodream\Spider\Support\Uri;

class SpiderController extends Controller {

    public $layout = false;

    protected $maps = [
        'www.qu.la' => BiQuGe::class,
        'www.biquge5200.cc' => BiQuGe::class,
        'www.biquge.lu' => BiQuGeLu::class,
        'www.biquge.cm' => MBiQuGe::class,
        'www.xxbiquge.com' => XBiQuGe::class,
        'www.sjtxt.la' => Sj::class,
        'www.haohunhun.com' => Hun::class,
        'www.biquge.info' => IBiQuGe::class,
    ];

    protected function rules() {
        return [
            '*' => 'cli'
        ];
    }



    public function indexAction($url, $start = null) {
        $uri = new Uri($url);
        $spider = $this->getSpider($uri);
        if (empty($spider)) {
            return $this->showContent('无此相关的爬虫程序！');
        }
        if (!empty($start)) {
            $spider->setStart($start);
        }
        $spider->invoke($uri, [$spider, 'invoke']);
        return $this->showContent('爬虫执行完成！');
    }

    /**
     * @param Uri $uri
     * @return bool|BaseSpider
     */
    protected function getSpider(Uri $uri) {
        if (!isset($this->maps[$uri->getHost()])) {
            return false;
        }
        $class = $this->maps[$uri->getHost()];
        return new $class;
    }


    public function importAction($file) {
        $txt = new Txt();
        $txt->invoke($file);
        return $this->showContent('导入执行完成！');
    }

}