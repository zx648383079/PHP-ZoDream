<?php
namespace Module\SEO\Service\Api;

use Module\SEO\Domain\Listeners\SiteMapListener;

class SitemapController extends Controller {
    public function indexAction() {
        $map = SiteMapListener::create();
        return $this->renderData($map->getIterator());
    }
}