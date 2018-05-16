<?php
namespace Module\Auth\Service\Admin;


use Module\Auth\Domain\Model\OAuthModel;
use Module\Auth\Domain\Model\UserModel;
use Zodream\Domain\Access\Auth;

class UserController extends Controller {

    public function indexAction($keywords = null) {
        $user_list = UserModel::where('id', '!=', Auth::id())
            ->when(!empty($keywords), function ($query) {
            OAuthModel::search($query, 'name');
        })->page();
        return $this->show(compact('user_list'));
    }

    public function createAction() {
        return $this->runMethod('edit', ['id' => null]);
    }

    public function editAction($id) {
        $model = UserModel::findOrNew($id);
        return $this->show(compact('model'));
    }

    public function saveAction() {
        $model = new UserModel();
        if (!$model->load() || !$model->autoIsNew()->save()) {
            return $this->jsonFailure($model->getFirstError());

        }
        return $this->jsonSuccess([
            'url' => $this->getUrl('user')
        ]);
    }

    public function deleteAction($id) {
        if ($id == Auth::id()) {
            return $this->jsonFailure('不能删除自己！');
        }
        UserModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'url' => $this->getUrl('user')
        ]);
    }
}