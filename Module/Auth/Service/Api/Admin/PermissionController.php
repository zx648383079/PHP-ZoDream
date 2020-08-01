<?php
declare(strict_types=1);
namespace Module\Auth\Service\Api\Admin;

use Module\Auth\Domain\Concerns\AdminRole;
use Module\Auth\Domain\Model\RBAC\PermissionModel;
use Module\Auth\Domain\Model\RBAC\RolePermissionModel;
use Zodream\Route\Controller\RestController;

class PermissionController extends RestController {

    use AdminRole;

    public function indexAction(string $keywords = '') {
        $permission_list = PermissionModel::when(!empty($keywords), function ($query) {
            PermissionModel::searchWhere($query, 'name');
        })->page();
        return $this->renderPage($permission_list);
    }

    public function detailAction(int $id) {
        $model = PermissionModel::find($id);
        if (empty($model)) {
            return $this->renderFailure('不存在');
        }
        return $this->render($model);
    }

    public function saveAction() {
        $model = new PermissionModel();
        if (!$model->load() || !$model->autoIsNew()->save()) {
            return $this->renderFailure($model->getFirstError());
        }
        return $this->render($model);
    }

    public function deleteAction(int $id) {
        PermissionModel::where('id', $id)->delete();
        RolePermissionModel::where('permission_id', $id)->delete();
        return $this->renderData(true);
    }
}