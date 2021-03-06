<?php
namespace Module\Shop\Service;

use Module\Shop\Domain\Models\CollectModel;

class CollectController extends Controller {

    public function rules() {
        return [
            '*' => '@'
        ];
    }

    public function indexAction() {
        $goods_list = CollectModel::with('goods')->page();
        return $this->sendWithShare()->show(compact('goods_list'));
    }

    public function addAction($id) {
        if (!CollectModel::exist($id)) {
            CollectModel::add($id);
        }
        return $this->renderData();
    }

    public function deleteAction($id) {
        CollectModel::remove($id);
        return $this->renderData();
    }

    public function toggleAction($id) {
        $id = intval($id);
        if (CollectModel::exist($id)) {
            CollectModel::remove($id);
            return $this->renderData(false);
        }
        CollectModel::add($id);
        return $this->renderData(true);
    }
}