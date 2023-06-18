<?php
declare(strict_types=1);
namespace Module\CMS\Service\Admin;

use Module\CMS\Domain\Repositories\CacheRepository;

class CacheController extends Controller {

    public function indexAction() {
        $storeItems = CacheRepository::storeItems();
        return $this->show(compact('storeItems'));
    }

    public function pageAction() {
        $storeItems = CacheRepository::CACHE_PAGE;
        return $this->show('index', compact('storeItems'));
    }

    public function dataAction() {
        $storeItems = CacheRepository::CACHE_DATA;
        return $this->show('index', compact('storeItems'));
    }

    public function clearAction(array $store = []) {
        CacheRepository::clearCache($store);
        return $this->renderData(null, '清理完成');
    }

}