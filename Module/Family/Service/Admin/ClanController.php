<?php
namespace Module\Family\Service\Admin;

use Module\Family\Domain\Model\ClanMetaModel;
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

    public function metaAction($clan_id) {
        $model_list = ClanMetaModel::where('clan_id', $clan_id)
            ->orderBy('created_at', 'desc')->select('id', 'name')->page();
        return $this->show(compact('model_list', 'clan_id'));
    }

    public function createMetaAction($clan_id) {
        return $this->runMethodNotProcess('editMeta', ['id' => null, 'clan_id' => $clan_id]);
    }

    public function editMetaAction($id, $clan_id = 0) {
        $model = ClanMetaModel::findOrNew($id);
        if ($model->isNewRecord) {
            $model->clan_id = $clan_id;
            $model->position = 10;
        }
        return $this->show(compact('model'));
    }

    public function saveMetaAction() {
        $model = new ClanMetaModel();
        $model->load();
        $model->user_id = auth()->id();
        if ($model->autoIsNew()->save()) {
            return $this->jsonSuccess([
                'url' => $this->getUrl('clan/meta', ['clan_id' => $model->clan_id])
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }

    public function deleteMetaAction($id) {
        $model = ClanMetaModel::find($id);
        $model->delete();
        return $this->jsonSuccess([
            'url' => $this->getUrl('clan/meta', ['clan_id' => $model->clan_id])
        ]);
    }
}