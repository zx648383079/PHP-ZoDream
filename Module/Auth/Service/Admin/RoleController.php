<?php
declare(strict_types=1);
namespace Module\Auth\Service\Admin;

use Domain\Model\SearchModel;
use Module\Auth\Domain\Model\RBAC\PermissionModel;
use Module\Auth\Domain\Model\RBAC\RoleModel;
use Module\Auth\Domain\Model\RBAC\RolePermissionModel;
use Module\Auth\Domain\Model\RBAC\UserRoleModel;
use Module\Auth\Domain\Repositories\RoleRepository;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class RoleController extends Controller {

    public function rules() {
        return [
            '*' => 'administrator'
        ];
    }

    public function indexAction(string $keywords = '') {
        $role_list = RoleModel::when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, 'name');
        })->page();
        return $this->show(compact('role_list'));
    }

    public function createAction() {
        return $this->editAction(0);
    }

    public function editAction(int $id) {
        $model = RoleModel::findOrNew($id);
        $permission_list = PermissionModel::all();
        return $this->show('edit', compact('model', 'permission_list'));
    }

    public function saveAction(Request $request) {
        try {
            RoleRepository::saveRole($request->get(), $request->get('perms'));
        }catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => $this->getUrl('role')
        ]);
    }

    public function deleteAction(int $id) {
        RoleRepository::removeRole($id);
        return $this->renderData([
            'url' => $this->getUrl('role')
        ]);
    }
}