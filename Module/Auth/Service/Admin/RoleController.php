<?php
namespace Module\Auth\Service\Admin;

use Module\Auth\Domain\Model\RBAC\PermissionModel;
use Module\Auth\Domain\Model\RBAC\RoleModel;
use Module\Auth\Domain\Model\RBAC\RolePermissionModel;
use Module\Auth\Domain\Model\RBAC\UserRoleModel;

class RoleController extends Controller {

    protected function rules() {
        return [
            '*' => 'administrator'
        ];
    }

    public function indexAction($keywords = null) {
        $role_list = RoleModel::when(!empty($keywords), function ($query) {
            RoleModel::searchWhere($query, 'name');
        })->page();
        return $this->show(compact('role_list'));
    }

    public function createAction() {
        return $this->runMethodNotProcess('edit', ['id' => null]);
    }

    public function editAction($id) {
        $model = RoleModel::findOrNew($id);
        $permission_list = PermissionModel::all();
        return $this->show(compact('model', 'permission_list'));
    }

    public function saveAction() {
        $model = new RoleModel();
        if (!$model->load() || !$model->autoIsNew()->save()) {
            return $this->jsonFailure($model->getFirstError());
        }
        $model->setPermission(app('request')->get('perms'));
        return $this->jsonSuccess([
            'url' => $this->getUrl('role')
        ]);
    }

    public function deleteAction($id) {
        RoleModel::where('id', $id)->delete();
        UserRoleModel::where('role_id', $id)->delete();
        RolePermissionModel::where('role_id', $id)->delete();
        return $this->jsonSuccess([
            'url' => $this->getUrl('role')
        ]);
    }
}