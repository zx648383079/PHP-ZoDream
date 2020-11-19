<?php
namespace Module\Family\Service\Admin;

use Module\Family\Domain\Model\ClanModel;
use Module\Family\Domain\Model\FamilyModel;
use Module\Family\Domain\Model\FamilySpouseModel;
use Zodream\Infrastructure\Http\Request;

class FamilyController extends Controller {

    public function indexAction(Request $request, $keywords = null, $clan_id = null) {
        $model_list = FamilyModel::with('clan')
            ->when(!empty($keywords), function ($query) {
                $query->where(function ($query) {
                    FamilyModel::search($query, 'name');
                });
            })->when(!empty($clan_id), function ($query) use ($clan_id) {
                $query->where('clan_id', intval($clan_id));
            })->orderBy('id', 'desc')->page();
        if ($request->isAjax() && !$request->isPjax()) {
            $this->layout = false;
            return $this->show('dialogBody', compact('model_list'));
        }
        $clan_list = ClanModel::select('id', 'name')->all();
        return $this->show(compact('model_list', 'clan_list', 'clan_id', 'keywords'));
    }

    public function createAction($mother = 0, $father = 0, $clan_id = 0) {
        $id = 0;
        return $this->runMethodNotProcess('edit', compact('id', 'mother', 'father', 'clan_id'));
    }

    public function editAction($id, $mother = 0, $father = 0, $clan_id = 0) {
        $model = FamilyModel::findOrNew($id);
        if ($model->mother_id < 1 && $mother > 0) {
            $model->mother_id = $mother;
        }
        if ($model->parent_id < 1 && $father > 0) {
            $model->parent_id = $father;
        }
        if ($model->clan_id < 1 && $clan_id > 0) {
            $model->clan_id = $clan_id;
        }
        $clan_list = ClanModel::select('id', 'name')->all();
        $parent_list = FamilyModel::query()->where('id', '<>', $id)->get('id', 'name');
        $spouse_list = [];
        if ($model->id > 0) {
            $spouse_list = FamilySpouseModel::with('spouse')->where('family_id', $model->id)->orderBy('start_at', 'asc')->get();
            if ($model->spouse_id > 0) {
                usort($spouse_list, function ($a, $b) use ($model) {
                    if ($a->spouse_id == $model->spouse_id) {
                        return -1;
                    }
                    if ($b->spouse_id == $model->spouse_id) {
                        return 1;
                    }
                    return 0;
                });
            }
        }
        return $this->show(compact('model', 'clan_list', 'parent_list', 'spouse_list'));
    }

    public function saveAction(Request $request, $id = null) {
        $model = FamilyModel::findOrNew($id);
        $isNew = $model->isNewRecord;
        if (!$model->load(null, ['user_id'])) {
            return $this->renderFailure($model->getFirstError());
        }
        $model->parent_id = intval($model->parent_id);
        $model->mother_id = intval($model->mother_id);
        if (!$model->saveIgnoreUpdate()) {
            return $this->renderFailure($model->getFirstError());
        }
        $spouseItems = self::formArr($request->get('spouse', []));
        foreach ($spouseItems as $item) {
            if ($item['spouse_id'] < 1) {
                continue;
            }
            $item['family_id'] = $model->id;
            if ($item['relation'] < 1) {
                $model->spouse_id = $item['spouse_id'];
            }
            if (isset($item['id']) && $item['id'] > 0) {
                FamilySpouseModel::where('id', $item['id'])
                    ->update($item);
                continue;
            }
            unset($item['id']);
            FamilySpouseModel::query()->insert($item);
        }
        $model->save();
        return $this->renderData([
            'url' => $this->getUrl('family'),
            'id' => $model->id,
            'name' => $model->name
        ]);
    }

    public function deleteAction($id) {

        return $this->renderData([
            'url' => $this->getUrl('family')
        ]);
    }

    public function spouseAction($id) {
        $model = FamilyModel::find($id);
        if (empty($model)) {
            return $this->renderFailure('族人不存在');
        }
        $items = FamilySpouseModel::where('family_id', $model->id)->pluck('spouse_id');
        $items[] = $model->spouse_id;
        $items = FamilyModel::whereIn('id', $items)->get();
        return $this->renderData($items);
    }

    public function deleteSpouseAction($id) {
        $model = FamilySpouseModel::find($id);
        if (empty($model)) {
            return $this->renderFailure('失败');
        }
        FamilyModel::where('id', $model->family_id)
            ->where('spouse_id', $model->spouse_id)
            ->update([
                'spouse_id' => 0
            ]);
        $model->delete();
        return $this->renderData(true);
    }
}