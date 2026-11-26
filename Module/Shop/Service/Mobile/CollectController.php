<?php
declare(strict_types=1);
namespace Module\Shop\Service\Mobile;

use Module\Shop\Domain\Models\CollectModel;

class CollectController extends Controller {

    public function rules() {
        return [
            '*' => '@'
        ];
    }

    public function indexAction() {
        $goods_list = CollectModel::with('goods')->page();
        return $this->show(compact('goods_list'));
    }

    public function addAction(int $id) {
        if (!CollectModel::exist($id)) {
            CollectModel::add($id);
        }
        return $this->renderData();
    }

    public function deleteAction(int $id) {
        CollectModel::remove($id);
        return $this->renderData(true);
    }

    public function toggleAction(int $id) {
        $id = intval($id);
        if (CollectModel::exist($id)) {
            CollectModel::remove($id);
            return $this->renderData(false);
        }
        CollectModel::add($id);
        return $this->renderData(true);
    }
}