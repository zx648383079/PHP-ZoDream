<?php
namespace Module\Auth\Service\Admin;


use Module\Auth\Domain\Model\RBAC\PermissionModel;

class PermissionController extends Controller {

    protected function rules() {
        return [
            '*' => 'administrator'
        ];
    }

    public function indexAction($keywords = null) {
        $permission_list = PermissionModel::when(!empty($keywords), function ($query) {
            PermissionModel::search($query, 'name');
        })->page();
        return $this->show(compact('permission_list'));
    }

    public function createAction() {
        return $this->runMethodNotProcess('edit', ['id' => null]);
    }

    public function editAction($id) {
        $model = PermissionModel::findOrNew($id);
        return $this->show(compact('model'));
    }

    public function saveAction() {
        $model = new PermissionModel();
        if (!$model->load() || !$model->autoIsNew()->save()) {
            return $this->jsonFailure($model->getFirstError());
        }
        return $this->jsonSuccess([
            'url' => $this->getUrl('permission')
        ]);
    }

    public function deleteAction($id) {
        PermissionModel::where('id', $id)->delete();
        RolePermissionModel::where('permission_id', $id)->delete();
        return $this->jsonSuccess([
            'url' => $this->getUrl('permission')
        ]);
    }
}