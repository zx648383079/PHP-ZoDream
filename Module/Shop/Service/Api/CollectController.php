<?php
namespace Module\Shop\Service\Api;

use Module\Shop\Domain\Models\CollectModel;
use Module\Shop\Domain\Models\Scene\Collect;

class CollectController extends Controller {

    public function rules() {
        return [
            '*' => '@'
        ];
    }

    public function indexAction() {
        $goods_list = Collect::with('goods')->page();
        return $this->renderPage($goods_list);
    }

    public function addAction($id) {
        if (!CollectModel::exist($id)) {
            CollectModel::add($id);
        }
        return $this->render(['data' => true]);
    }

    public function deleteAction($id) {
        CollectModel::remove($id);
        return $this->render(['data' => false]);
    }

    public function toggleAction($id) {
        $id = intval($id);
        if (CollectModel::exist($id)) {
            CollectModel::remove($id);
            return $this->render(['data' => false]);
        }
        CollectModel::add($id);
        return $this->render(['data' => true]);
    }
}