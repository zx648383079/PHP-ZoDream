<?php
namespace Module\Legwork\Service\Api;

use Module\Legwork\Domain\Repositories\CategoryRepository;
use Module\Legwork\Domain\Repositories\LegworkRepository;
use Module\Legwork\Domain\Repositories\ServiceRepository;

class HomeController extends Controller {

    public function indexAction(string $keywords = '', int $category = 0) {
        return $this->renderPage(
            ServiceRepository::getList($keywords, $category, 0, 1)
        );
    }

    public function detailAction(int $id) {
        try {
            return $this->render(ServiceRepository::getPublic($id));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function categoryAction() {
        return $this->renderData(CategoryRepository::all());
    }

    public function roleAction() {
        return $this->render(LegworkRepository::role());
    }
}