<?php
namespace Module\Family\Service\Admin;

use Module\Family\Domain\Model\ClanModel;
use Module\Family\Domain\Model\FamilyModel;

class ClanController extends Controller {

    protected function rules() {
        return [
            '*' => 'administrator',
            'index' => '@'
        ];
    }

    public function indexAction($keywords = null) {
        $clan_list = ClanModel::query()->page();
        return $this->show(compact('clan_list'));
    }

    public function createAction() {
        return $this->runMethodNotProcess('edit', ['id' => null]);
    }

    public function editAction($id) {
        $model = ClanModel::findOrNew($id);
        return $this->show(compact('model'));
    }

    public function saveAction() {
        $model = new ClanModel();
        $model->load();
        $model->user_id = auth()->id();
        if ($model->autoIsNew()->save()) {
            return $this->jsonSuccess([
                'url' => $this->getUrl('clan')
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        ClanModel::where('id', $id)->delete();
        FamilyModel::where('clan_id', $id)->update(['clan_id' => 0]);
        return $this->jsonSuccess([
            'url' => $this->getUrl('clan')
        ]);
    }
}