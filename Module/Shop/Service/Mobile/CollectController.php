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
        $goods_list = CollectGoodsModel::with('goods')->page();
        return $this->show(compact('goods_list'));
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
        $id = intval($id);
        if (CollectGoodsModel::exist($id)) {
            CollectGoodsModel::remove($id);
            return $this->jsonSuccess(false);
        }
        CollectGoodsModel::add($id);
        return $this->jsonSuccess(true);
    }
}