<?php
namespace Module\Book\Service;


use Module\Book\Domain\Model\BookModel;
use Module\Book\Domain\SiteCrawl;
use Module\Book\Domain\Spiders\Txt;
use Module\Book\Domain\Spiders\ZhiShuShenQi;
use Zodream\Infrastructure\Error\Exception;
use Zodream\Spider\Support\Uri;

class SpiderController extends Controller {

    public $layout = false;



    protected function rules() {
        return [
            'search' => '@',
            'async' => '@',
            '*' => 'cli'
        ];
    }



    public function indexAction($site = null, $url = null, $start = null) {
        if (empty($site)) {
            return $this->crawlBook($url, $start);
        }
        return $this->crawlSite($site);
    }


    public function importAction($file, $book = 0) {
        $txt = new Txt();
        $txt->invoke($file, $book);
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

    public function searchAction($keywords) {
        return $this->jsonSuccess((new ZhiShuShenQi())->search($keywords));
    }

    public function asyncAction($id, $name, $description = null, $size = 0) {
        set_time_limit(0);
        $spider = new ZhiShuShenQi();
        try {
            $spider->async($id, $name, $description, $size);
        } catch (Exception $ex) {
            return $this->jsonFailure($ex->getMessage());
        }
        return $this->jsonSuccess();
    }




}