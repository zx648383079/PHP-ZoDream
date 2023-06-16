<?php
namespace Module\SEO\Service\Admin;

use Module\SEO\Domain\Repositories\SEORepository;

class CacheController extends Controller {

    public function indexAction() {
        $storeItems = SEORepository::storeItems();
        return $this->show(compact('storeItems'));
    }

    public function clearAction(array $store = []) {
        SEORepository::clearCache($store);
        return $this->renderData(null, '清理完成');
    }
}