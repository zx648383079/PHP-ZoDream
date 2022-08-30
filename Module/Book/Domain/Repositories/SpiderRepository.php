<?php
declare(strict_types=1);
namespace Module\Book\Domain\Repositories;

use Module\Book\Domain\SiteCrawl;
use Module\Book\Domain\SpiderProgress;
use Module\Book\Domain\Spiders\ZhiShuShenQi;
use Zodream\Spider\Support\Uri;
use Zodream\Validate\Validator;

final class SpiderRepository {
    public static function search(string $keywords) {
        if (empty($keywords)) {
            throw new \Exception('请输入搜索内容');
        }
        if (!Validator::url()->validate($keywords)) {
            return (new ZhiShuShenQi())->search($keywords);
        }
        $spider = SiteCrawl::getSpider(new Uri($keywords));
        if (empty($spider)) {
            throw new \Exception('爬虫不存在');
        }
        $book = $spider->book($keywords);
        if (empty($book)) {
            throw new \Exception('爬取失败');
        }
        $book['url'] = $keywords;
        unset($book['chapters']);
        return [$book];
    }

    public static function async(array $data) {
        set_time_limit(0);
        $progress = self::asyncProgress($data);
        if (empty($progress)) {
            throw new \Exception('不能存在进程');
        }
        return $progress();
    }

    protected static function asyncProgress(array $data) {
        if (isset($data['key'])) {
            return cache($data['key']);
        }
        $book = $data;
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