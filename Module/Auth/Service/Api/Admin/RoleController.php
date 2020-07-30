<?php
namespace Module\Auth\Service\Api\Admin;

use Module\Auth\Domain\Model\RBAC\RoleModel;
use Module\Auth\Domain\Model\RBAC\RolePermissionModel;
use Module\Auth\Domain\Model\RBAC\UserRoleModel;
use Zodream\Infrastructure\Http\Request;
use Zodream\Route\Controller\RestController;

class RoleController extends RestController {

    protected function rules() {
        return [
            '*' => 'administrator'
        ];
    }

    public function indexAction(string $keywords = '') {
        $role_list = RoleModel::when(!empty($keywords), function ($query) {
            RoleModel::searchWhere($query, 'name');
        })->page();
        return $this->renderPage($role_list);
    }

    public function editAction(int $id) {
        $model = RoleModel::find($id);
        if (empty($model)) {
            return $this->renderFailure('不存在');
        }
        return $this->render($model);
    }

    public function saveAction(Request $request) {
        $model = new RoleModel();
        if (!$model->load() || !$model->autoIsNew()->save()) {
            return $this->renderFailure($model->getFirstError());
        }
        $model->setPermission($request->get('perms'));
        return $this->render($model);
    }

    public function deleteAction(int $id) {
        RoleModel::where('id', $id)->delete();
        UserRoleModel::where('role_id', $id)->delete();
        RolePermissionModel::where('role_id', $id)->delete();
        return $this->renderData(true);
    }
}