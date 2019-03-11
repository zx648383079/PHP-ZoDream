<?php
namespace Module\Book\Service;


use Module\Book\Domain\Model\BookModel;
use Module\Book\Domain\SiteCrawl;
use Module\Book\Domain\Spiders\Txt;
use Module\Book\Domain\Spiders\ZhiShuShenQi;
use PhpParser\Node\Expr\Empty_;
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
            return $this->jsonFailure('请输入搜索内容');
        }
        if (!Validator::url()->validate($keywords)) {
            return $this->jsonSuccess((new ZhiShuShenQi())->search($keywords));
        }
        $spider = SiteCrawl::getSpider(new Uri($keywords));
        if (empty($spider)) {
            return $this->jsonFailure('爬虫不存在');
        }
        $book = $spider->book($keywords);
        if (empty($book)) {
            return $this->jsonFailure('爬取失败');
        }
        $book['url'] = $keywords;
        return $this->jsonSuccess([$book]);
    }

    public function asyncAction() {
        set_time_limit(0);
        $book = $_POST;
        try {
            if (isset($_POST['next'])) {
                return $this->jsonSuccess(
                    SiteCrawl::loopStep($_POST['key'], intval($_POST['next'])));
            }
            if (isset($book['url'])) {
                $spider = SiteCrawl::getSpider(new Uri($book['url']));
                $data = SiteCrawl::async($spider, $book);
            } else {
                $data = SiteCrawl::async(new ZhiShuShenQi(), $book);
            }
            $key = 'book_spider_async_'.time();
            cache()->set($key, $data, 3600);
            return $this->jsonSuccess([
                'next' => $data[3],
                'count' => count($data[2]),
                'key' => $key
            ]);
        } catch (Exception $ex) {
            return $this->jsonFailure($ex->getMessage());
        }
        return $this->jsonSuccess();
    }




}