<?php
namespace Module\Family\Service\Admin;

use Module\Family\Domain\Model\ClanModel;
use Module\Family\Domain\Model\FamilyModel;
use Module\Family\Domain\Model\FamilySpouseModel;

class FamilyController extends Controller {

    public function indexAction($keywords = null, $clan_id = null) {
        $model_list = FamilyModel::with('clan')
            ->when(!empty($keywords), function ($query) {
                $query->where(function ($query) {
                    FamilyModel::search($query, 'name');
                });
            })->when(!empty($clan_id), function ($query) use ($clan_id) {
                $query->where('clan_id', intval($clan_id));
            })->orderBy('id', 'desc')->page();
        $clan_list = ClanModel::select('id', 'name')->all();
        return $this->show(compact('model_list', 'clan_list'));
    }

    public function createAction($mother = 0, $father = 0) {
        $id = 0;
        return $this->runMethodNotProcess('edit', compact('id', 'mother', 'father'));
    }

    public function editAction($id, $mother = 0, $father = 0) {
        $model = FamilyModel::findOrNew($id);
        if ($model->mother_id < 1 && $mother > 0) {
            $model->mother_id = $mother;
        }
        if ($model->parent_id < 1 && $father > 0) {
            $model->parent_id = $father;
        }
        $clan_list = ClanModel::select('id', 'name')->all();
        $parent_list = FamilyModel::query()->where('id', '<>', $id)->get('id', 'name');
        $spouse_list = [];
        return $this->show(compact('model', 'clan_list', 'parent_list'));
    }

    public function saveAction($id = null) {
        $model = FamilyModel::findOrNew($id);
        $isNew = $model->isNewRecord;
        if (!$model->load(null, ['user_id'])) {
            return $this->jsonFailure($model->getFirstError());
        }
        if (!$model->save()) {
            return $this->jsonFailure($model->getFirstError());
        }
        return $this->jsonSuccess([
            'url' => $this->getUrl('family'),
            'id' => $model->id,
            'name' => $model->name
        ]);
    }

    public function deleteAction($id) {

        return $this->jsonSuccess([
            'url' => $this->getUrl('family')
        ]);
    }

    public function spouseAction($id) {
        $model = FamilyModel::find($id);
        if (empty($model)) {
            return $this->jsonFailure('族人不存在');
        }
        $items = FamilySpouseModel::where('family_id', $model->id)->pluck('spouse_id');
        $items[] = $model->spouse_id;
        $items = FamilyModel::whereIn('id', $items)->get();
        return $this->jsonSuccess($items);
    }
}