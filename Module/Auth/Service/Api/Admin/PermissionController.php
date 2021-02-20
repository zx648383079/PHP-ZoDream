<?php
declare(strict_types=1);
namespace Module\Auth\Service\Api\Admin;

use Domain\Model\SearchModel;
use Module\Auth\Domain\Model\RBAC\PermissionModel;
use Module\Auth\Domain\Model\RBAC\RolePermissionModel;
use Module\Auth\Domain\Repositories\RoleRepository;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class PermissionController extends Controller {

    public function indexAction(string $keywords = '') {
        $permission_list = PermissionModel::when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, 'name');
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

    public function saveAction(Request $request) {
        try {
            $model = RoleRepository::savePermission($request->get());
        }catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function deleteAction(int $id) {
        PermissionModel::where('id', $id)->delete();
        RolePermissionModel::where('permission_id', $id)->delete();
        return $this->renderData(true);
    }

    public function allAction() {
        $data = PermissionModel::query()->get('id', 'name', 'display_name');
        return $this->renderData($data);
    }
}