<?php
declare(strict_types=1);
namespace Module\CMS\Service\Admin;

class CacheController extends Controller {

    const CACHE_PAGE = [
        ['name' => '首页缓存', 'value' => 'home'],
        ['name' => '栏目页缓存', 'value' => 'channel'],
        ['name' => '内容页缓存', 'value' => 'content'],
    ];
    const CACHE_DATA = [
        ['name' => '联动项缓存', 'value' => 'linkage'],
        ['name' => '站点配置缓存', 'value' => 'option'],
    ];

    public function indexAction() {
        $storeItems = array_merge(self::CACHE_PAGE, self::CACHE_DATA);
        return $this->show(compact('storeItems'));
    }

    public function pageAction() {
        $storeItems = self::CACHE_PAGE;
        return $this->show('index', compact('storeItems'));
    }

    public function dataAction() {
        $storeItems = self::CACHE_DATA;
        return $this->show('index', compact('storeItems'));
    }

    public function clearAction(array $store = []) {

        return $this->renderData(null, '清理完成');
    }

}