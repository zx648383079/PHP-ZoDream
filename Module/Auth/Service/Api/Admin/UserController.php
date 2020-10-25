<?php
declare(strict_types=1);
namespace Module\Auth\Service\Api\Admin;

use Module\Auth\Domain\Concerns\AdminRole;
use Module\Auth\Domain\Model\RBAC\UserRoleModel;
use Module\Auth\Domain\Repositories\UserRepository;
use Zodream\Infrastructure\Http\Request;
use Zodream\Route\Controller\RestController;

class UserController extends RestController {

    use AdminRole;

    public function indexAction(string $keywords = '') {
        return $this->renderPage(UserRepository::getAll($keywords));
    }

    public function profileAction(int $id) {
        try {
            $model = UserRepository::get($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function detailAction(int $id) {
        try {
            $model = UserRepository::get($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        $data = $model->toArray();
        $data['roles'] = UserRoleModel::where('user_id', $id)->pluck('role_id');
        return $this->render($data);
    }

    public function saveAction(Request $request) {
        try {
            $model = UserRepository::save($request->get(), $request->get('roles', []));
        }catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function deleteAction(int $id) {
        try {
            UserRepository::remove($id);
        }catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

}