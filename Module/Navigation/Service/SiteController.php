<?php
declare(strict_types=1);
namespace Module\Navigation\Service;

use Module\Navigation\Domain\Repositories\SiteRepository;

final class SiteController extends Controller {

    public function indexAction(string $keywords = '', int $category = 0, string $domain = '') {
        $categories = SiteRepository::categories();
        $items = SiteRepository::getList($keywords, $category, $domain);
        return $this->show(compact('categories', 'items'));
    }
}