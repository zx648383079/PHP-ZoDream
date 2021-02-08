<?php
declare(strict_types=1);
namespace Module\Shop\Service\Api;

use Module\Shop\Domain\Models\Advertisement\AdModel;
use Module\Shop\Domain\Models\BrandModel;
use Module\Shop\Domain\Repositories\AdRepository;
use Module\Shop\Domain\Repositories\BrandRepository;

class BrandController extends Controller {

    public function indexAction(int $id = 0) {
        if ($id > 0) {
            return $this->detailAction($id);
        }
        return $this->renderPage(BrandModel::page());
    }

    public function detailAction(int $id) {
        return $this->render(BrandModel::find($id));
    }

    public function recommendAction() {
        return $this->renderData(BrandRepository::recommend());
    }
}