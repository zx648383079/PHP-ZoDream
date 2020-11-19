<?php
namespace Module\Book\Service;


use Module\Book\Domain\Model\BookModel;
use Module\Book\Domain\SiteCrawl;
use Module\Book\Domain\SpiderProgress;
use Module\Book\Domain\Spiders\Txt;
use Module\Book\Domain\Spiders\ZhiShuShenQi;
use Zodream\Infrastructure\Error\Exception;
use Zodream\Spider\Support\Uri;
use Zodream\Validate\Validator;

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
        if (empty($keywords)) {
            return $this->renderFailure('请输入搜索内容');
        }
        if (!Validator::url()->validate($keywords)) {
            return $this->renderData((new ZhiShuShenQi())->search($keywords));
        }
        $spider = SiteCrawl::getSpider(new Uri($keywords));
        if (empty($spider)) {
            return $this->renderFailure('爬虫不存在');
        }
        $book = $spider->book($keywords);
        if (empty($book)) {
            return $this->renderFailure('爬取失败');
        }
        $book['url'] = $keywords;
        unset($book['chapters']);
        return $this->renderData([$book]);
    }

    public function asyncAction() {
        set_time_limit(0);
        try {
            $progress = $this->getProgress();
            if (empty($progress)) {
                return $this->renderFailure('不能存在进程');
            }
            return $this->renderData($progress());
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData();
    }

    private function getProgress() {
        if (isset($_POST['key'])) {
            return cache($_POST['key']);
        }
        $book = $_POST;
        if (isset($book['url'])) {
            $spider = SiteCrawl::getSpider(new Uri($book['url']));
        } else {
            $spider = new ZhiShuShenQi();
        }
        return new SpiderProgress([
            'book' => $book,
            'spider' => $spider
        ]);
    }


}