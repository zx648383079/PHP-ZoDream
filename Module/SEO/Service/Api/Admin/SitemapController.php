<?php
declare(strict_types=1);
namespace Module\SEO\Service\Api\Admin;

use Module\SEO\Domain\Listeners\SiteMapListener;

class SitemapController extends Controller {

    public function methods()
    {
         return [
             'index' => ['POST']
         ];
    }

    public function indexAction() {
        $map = SiteMapListener::create();
        return $this->renderData($map->getIterator()->getArrayCopy());
    }
}