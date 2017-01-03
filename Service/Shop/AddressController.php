<?php
namespace Service\Shop;

use Domain\Model\Shopping\AddressModel;
use Zodream\Service\Factory;

class AddressController extends Controller {
    public function indexAction() {
        return $this->show([
            'models' => AddressModel::findAll(['user_id' => Factory::user()->getId()])
        ]);
    }

    public function addAction($id = null) {
        return $this->show([
            'model' => AddressModel::findOne($id)
        ]);
    }

    public function defaultAction($id) {
        $model = AddressModel::findOne($id);
        $model->update([
            'user_id' => $model->user_id,
            'is_default' => 1
        ], [
            'is_default' => 0
        ]);
        $model->is_default = 1;
        $model->save();
        return $this->redirect(['address/index']);
    }

    public function deleteAction($id) {
        $model = AddressModel::findOne($id);
        $model->delete();
        return $this->redirect(['address/index']);
    }
}