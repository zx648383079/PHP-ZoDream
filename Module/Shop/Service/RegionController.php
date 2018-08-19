<?php
namespace Module\Shop\Service;

use Module\Shop\Domain\Model\RegionModel;

class RegionController extends Controller {

    public function indexAction() {

    }

    public function treeAction() {
        return $this->jsonSuccess(RegionModel::cacheTree());
    }
}