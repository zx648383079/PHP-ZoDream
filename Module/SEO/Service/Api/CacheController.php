<?php
namespace Module\SEO\Service\Api;

use Module\SEO\Domain\Repositories\SEORepository;

class CacheController extends Controller {

    public function indexAction() {
        return $this->renderData(SEORepository::storeItems());
    }

    public function clearAction($store = []) {
        SEORepository::clearCache($store);
        return $this->renderData(true);
    }
}