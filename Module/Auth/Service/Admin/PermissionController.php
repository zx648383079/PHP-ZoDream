<?php
declare(strict_types=1);
namespace Module\Auth\Service\Admin;

use Domain\Model\SearchModel;
use Module\Auth\Domain\Model\RBAC\PermissionModel;
use Module\Auth\Domain\Model\RBAC\RolePermissionModel;
use Module\Auth\Domain\Repositories\RoleRepository;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class PermissionController extends Controller {

    public function rules() {
        return [
            '*' => 'administrator'
        ];
    }

    public function indexAction(string $keywords = '') {
        $permission_list = PermissionModel::when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, 'name');
        })->page();
        return $this->show(compact('permission_list'));
    }

    public function createAction() {
        return $this->editAction(0);
    }

    public function editAction($id) {
        $model = PermissionModel::findOrNew($id);
        return $this->show('edit', compact('model'));
    }

    public function saveAction(Request $request) {
        try {
            RoleRepository::savePermission($request->get());
        }catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => $this->getUrl('permission')
        ]);
    }

    public function deleteAction(int $id) {
        PermissionModel::where('id', $id)->delete();
        RolePermissionModel::where('permission_id', $id)->delete();
        return $this->renderData([
            'url' => $this->getUrl('permission')
        ]);
    }
}