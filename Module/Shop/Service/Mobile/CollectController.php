<?php
namespace Module\Shop\Service\Mobile;

use Module\Shop\Domain\Model\CollectGoodsModel;

class CollectController extends Controller {

    protected function rules() {
        return [
            '*' => '@'
        ];
    }

    public function indexAction() {
        return $this->show();
    }

    public function addAction($id) {
        if (!CollectGoodsModel::exist($id)) {
            CollectGoodsModel::add($id);
        }
        return $this->jsonSuccess();
    }

    public function deleteAction($id) {
        CollectGoodsModel::remove($id);
        return $this->jsonSuccess();
    }

    public function toggleAction($id) {
        if (CollectGoodsModel::exist($id)) {
            CollectGoodsModel::remove($id);
            return $this->jsonSuccess(false);
        }
        CollectGoodsModel::add($id);
        return $this->jsonSuccess(true);
    }
}