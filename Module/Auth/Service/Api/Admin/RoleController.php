<?php
namespace Module\Auth\Service\Api\Admin;

use Domain\Model\SearchModel;
use Module\Auth\Domain\Model\RBAC\RoleModel;
use Module\Auth\Domain\Repositories\RoleRepository;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class RoleController extends Controller {

    public function indexAction(string $keywords = '') {
        $role_list = RoleModel::when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, 'name');
        })->page();
        return $this->renderPage($role_list);
    }

    public function detailAction(int $id) {
        $model = RoleModel::find($id);
        if (empty($model)) {
            return $this->renderFailure('不存在');
        }
        $data = $model->toArray();
        $data['permissions'] = $model->perm_ids;
        return $this->render($data);
    }

    public function saveAction(Request $request) {
        try {
            $model = RoleRepository::saveRole($request->get(), $request->get('permissions'));
        }catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function deleteAction(int $id) {
        RoleRepository::removeRole($id);
        return $this->renderData(true);
    }

    public function allAction() {
        return $this->renderData(RoleRepository::all());
    }
}