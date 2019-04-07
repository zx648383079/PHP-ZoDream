<?php
namespace Module\Shop\Service\Api;

use Module\Shop\Domain\Models\Advertisement\AdModel;

class AdController extends Controller {

    public function indexAction($id = 0, $position = 0) {
        if ($id > 0) {
            return $this->render(AdModel::find(intval($id)));
        }
        return $this->render(AdModel::getAds(intval($position)));
    }

    public function bannerAction() {
        return $this->render(AdModel::banners());
    }
}