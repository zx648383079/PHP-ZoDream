<?php
namespace Module\Shop\Service\Admin;


use Module\Shop\Domain\Model\AttributeGroupModel;
use Module\Shop\Domain\Model\AttributeModel;

class AttributeController extends Controller {

    public function indexAction($group_id) {
        $model_list = AttributeModel::with('group')->where('group_id', $group_id)->page();
        return $this->show(compact('model_list', 'group_id'));
    }

    public function createAction($group_id = 0) {
        return $this->runMethodNotProcess('edit', ['id' => null, 'group_id' => $group_id]);
    }

    public function editAction($id, $group_id = 0) {
        $model = AttributeModel::findOrNew($id);
        if ($group_id > 0) {
            $model->group_id = $group_id;
        }
        $type_list = AttributeGroupModel::all();
        return $this->show(compact('model', 'type_list'));
    }

    public function saveAction() {
        $model = new AttributeModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->jsonSuccess([
                'url' => $this->getUrl('attribute')
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }


    public function groupAction() {
        $model_list = AttributeGroupModel::all();
        return $this->show(compact('model_list'));
    }

    public function createGroupAction() {
        return $this->runMethodNotProcess('editGroup', ['id' => null]);
    }

    public function editGroupAction($id) {
        $model = AttributeGroupModel::findOrNew($id);
        return $this->show(compact('model'));
    }

    public function saveGroupAction() {
        $model = new AttributeGroupModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->jsonSuccess([
                'url' => $this->getUrl('attribute/group')
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }

    public function deleteCategoryAction($id) {
        AttributeGroupModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'url' => $this->getUrl('attribute/group')
        ]);
    }
}