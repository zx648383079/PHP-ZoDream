<?php
declare(strict_types=1);
namespace Module\Shop\Service\Api;

use Module\Shop\Domain\Models\RegionModel;
use Module\Shop\Domain\Repositories\RegionRepository;

class RegionController extends Controller {

    public function indexAction(int $id = 0, string $keywords = '') {
        return $this->renderData(
            RegionRepository::getList($id, $keywords)
        );
    }

    public function treeAction() {
        return $this->renderData(RegionModel::cacheTree());
    }

    public function pathAction(int $id) {
        return $this->renderData(
            RegionRepository::getPath($id)
        );
    }

    public function searchAction(string $keywords = '', int|array $id = 0) {
        return $this->renderData(RegionRepository::search($keywords, $id));
    }
}