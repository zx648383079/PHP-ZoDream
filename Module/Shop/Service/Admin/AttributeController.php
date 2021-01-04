<?php
namespace Module\Shop\Service\Admin;


use Module\Shop\Domain\Models\AttributeGroupModel;
use Module\Shop\Domain\Models\AttributeModel;

class AttributeController extends Controller {

    public function indexAction($group_id) {
        $model_list = AttributeModel::with('group')->where('group_id', $group_id)->page();
        return $this->show(compact('model_list', 'group_id'));
    }

    public function createAction($group_id = 0) {
        return $this->editAction(0, $group_id);
    }

    public function editAction($id, $group_id = 0) {
        $position = 99;
        $model = AttributeModel::findOrDefault($id, compact('group_id', 'position'));
        if ($group_id > 0) {
            $model->group_id = $group_id;
        }
        $type_list = AttributeGroupModel::all();
        return $this->show('edit', compact('model', 'type_list'));
    }

    public function saveAction() {
        $model = new AttributeModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->renderData([
                'url' => $this->getUrl('attribute', ['group_id' => $model->group_id])
            ]);
        }
        return $this->renderFailure($model->getFirstError());
    }


    public function groupAction() {
        $model_list = AttributeGroupModel::all();
        return $this->show(compact('model_list'));
    }

    public function createGroupAction() {
        return $this->editGroupAction(0);
    }

    public function editGroupAction($id) {
        $model = AttributeGroupModel::findOrNew($id);
        return $this->show('editGroup', compact('model'));
    }

    public function saveGroupAction() {
        $model = new AttributeGroupModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->renderData([
                'url' => $this->getUrl('attribute/group')
            ]);
        }
        return $this->renderFailure($model->getFirstError());
    }

    public function deleteCategoryAction($id) {
        AttributeGroupModel::where('id', $id)->delete();
        return $this->renderData([
            'url' => $this->getUrl('attribute/group')
        ]);
    }
}