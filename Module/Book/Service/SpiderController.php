<?php
namespace Module\Book\Service;


use Module\Book\Domain\SiteCrawl;
use Module\Book\Domain\Spiders\Txt;
use Zodream\Spider\Support\Uri;

class SpiderController extends Controller {

    public $layout = false;



    protected function rules() {
        return [
            '*' => 'cli'
        ];
    }



    public function indexAction($site = null, $url = null, $start = null) {
        if (empty($site)) {
            return $this->crawlBook($url, $start);
        }
        return $this->crawlSite($site);
    }


    public function importAction($file) {
        $txt = new Txt();
        $txt->invoke($file);
        return $this->showContent('导入执行完成！');
    }

    protected function crawlSite($site) {
        $uri = new Uri($site);
        $spider = SiteCrawl::getSpider($uri);
        if (empty($spider)) {
            return $this->showContent('无此相关的爬虫程序！');
        }
        $crawl = new SiteCrawl($uri, $spider);
        $crawl->start();
        return $this->showContent('爬虫执行完成！');
    }

    /**
     * @param $url
     * @param $start
     * @return \Zodream\Infrastructure\Http\Response
     * @throws \Exception
     */
    protected function crawlBook($url, $start = null) {
        $uri = new Uri($url);
        $spider = SiteCrawl::getSpider($uri);
        if (empty($spider)) {
            return $this->showContent('无此相关的爬虫程序！');
        }
        if (!empty($start)) {
            $spider->setStart($start);
        }
        $spider->invoke($uri, [$spider, 'invoke']);
        return $this->showContent('爬虫执行完成！');
    }

}