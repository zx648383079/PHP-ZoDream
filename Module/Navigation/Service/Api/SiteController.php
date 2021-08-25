<?php
declare(strict_types=1);
namespace Module\Navigation\Service\Api;

use Module\Navigation\Domain\Repositories\SiteRepository;

final class SiteController extends Controller {

    public function indexAction(string $keywords = '', int $category = 0, string $domain = '') {
        return $this->renderPage(SiteRepository::getList($keywords, $category, $domain));
    }

    public function recommendAction(int $category = 0) {
        return $this->renderData(SiteRepository::recommend($category));
    }

    public function detailAction(int $id) {
        try {
            return $this->render(SiteRepository::get($id));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function categoryAction() {
        return $this->renderData(SiteRepository::categories());
    }
}