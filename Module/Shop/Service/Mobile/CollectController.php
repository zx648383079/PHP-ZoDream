<?php
namespace Module\Shop\Service\Mobile;

use Module\Shop\Domain\Model\CollectModel;

class CollectController extends Controller {

    protected function rules() {
        return [
            '*' => '@'
        ];
    }

    public function indexAction() {
        $goods_list = CollectModel::with('goods')->page();
        return $this->show(compact('goods_list'));
    }

    public function addAction($id) {
        if (!CollectModel::exist($id)) {
            CollectModel::add($id);
        }
        return $this->jsonSuccess();
    }

    public function deleteAction($id) {
        CollectModel::remove($id);
        return $this->jsonSuccess();
    }

    public function toggleAction($id) {
        $id = intval($id);
        if (CollectModel::exist($id)) {
            CollectModel::remove($id);
            return $this->jsonSuccess(false);
        }
        CollectModel::add($id);
        return $this->jsonSuccess(true);
    }
}