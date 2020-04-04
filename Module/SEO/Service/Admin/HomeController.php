<?php
namespace Module\SEO\Service\Admin;

use Module\SEO\Domain\Listeners\SiteMapListener;

class HomeController extends Controller {

    public function indexAction() {
        return $this->show();
    }

    public function sitemapAction() {
        $map = SiteMapListener::create();
        return $this->show(compact('map'));
    }

    public function cacheAction() {
        cache()->delete();
        return $this->show();
    }
}