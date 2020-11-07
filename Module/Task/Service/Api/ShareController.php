<?php
namespace Module\Task\Service\Api;

use Module\Task\Domain\Repositories\CommentRepository;
use Module\Task\Domain\Repositories\ShareRepository;
use Zodream\Domain\Upload\UploadFile;
use Zodream\Infrastructure\Http\Request;
use Zodream\Route\Controller\RestController;
use Zodream\Validate\ValidationException;

class ShareController extends RestController {

    public function rules() {
        return ['*' => '@'];
    }

    public function indexAction($id = 0) {
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

    public function detailAction($id) {
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
            return $this->renderFailure($ex->validator->firstError());
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function deleteAction($id) {
        try {
            ShareRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function deleteUserAction($id, $user_id) {
        try {
            ShareRepository::removeUser($id, $user_id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}