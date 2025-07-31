<?php
declare(strict_types=1);
namespace Module\Task\Service\Api;

use Module\Task\Domain\Repositories\ShareRepository;
use Zodream\Infrastructure\Contracts\Http\Input as Request;
use Zodream\Validate\ValidationException;

class ShareController extends Controller {

    public function rules() {
        return ['*' => '@'];
    }

    public function indexAction(int $id = 0) {
        if (!empty($id)) {
            return $this->detailAction($id);
        }
        try {
            $page = ShareRepository::getList();
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($page);
    }

    public function detailAction(int $id) {
        try {
            $model = ShareRepository::detail($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function myAction() {
        try {
            $model = ShareRepository::myList();
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function createAction(Request $request) {
        try {
            $data = $request->validate([
                'task_id' => 'required|int',
                'share_type' => 'int:0,127',
                'share_rule' => 'string:0,20',
            ]);
            $model = ShareRepository::create($data);
        } catch (ValidationException $ex) {
            return $this->renderFailure($ex->bag->firstError());
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function deleteAction(int $id) {
        try {
            ShareRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function deleteUserAction(int $id, int $user) {
        try {
            ShareRepository::removeUser($id, $user);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function usersAction(int $id) {
        try {
            $data = ShareRepository::users($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData($data);
    }
}