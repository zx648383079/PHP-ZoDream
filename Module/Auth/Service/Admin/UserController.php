<?php
namespace Module\Auth\Service\Admin;


use Module\Auth\Domain\Model\OAuthModel;
use Module\Auth\Domain\Model\RoleModel;
use Module\Auth\Domain\Model\UserModel;


use Zodream\Validate\Validator;

class UserController extends Controller {

    public function indexAction($keywords = null) {
        $user_list = UserModel::where('id', '!=', auth()->id())
            ->when(!empty($keywords), function ($query) {
            OAuthModel::search($query, 'name');
        })->page();
        return $this->show(compact('user_list'));
    }

    public function createAction() {
        return $this->runMethodNotProcess('edit', ['id' => null]);
    }

    public function editAction($id) {
        $model = UserModel::findOrNew($id);
        $role_list = RoleModel::all();
        return $this->show(compact('model', 'role_list'));
    }

    public function saveAction() {
        $id = intval(app('request')->get('id'));
        $rule = $id > 0 ? [
            'name' => 'required|string',
            'email' => 'required|email',
            'sex' => 'int',
            'avatar' => 'string',
            'password' => 'string',
        ] : [
            'name' => 'required|string',
            'email' => 'required|email',
            'sex' => 'int',
            'avatar' => 'string',
            'password' => 'required|string',
        ];
        $data = app('request')->get('name,email,sex,avatar,password,confirm_password');
        if ($id < 1 && $data['password'] != $data['confirm_password']) {
            return $this->jsonFailure('两次密码不一致！');
        }
        $model = UserModel::findOrNew($id);
        if (!$model->load($data) || !$model->validate($rule)) {
            return $this->jsonFailure($model->getFirstError());
        }
        if (!empty($data['password'])) {
            $model->setPassword($data['password']);
        }
        if (!$model->save()) {
            return $this->jsonFailure($model->getFirstError());
        }
        $model->setRole(app('request')->get('roles'));
        return $this->jsonSuccess([
            'url' => $this->getUrl('user')
        ]);
    }

    public function deleteAction($id) {
        if ($id == auth()->id()) {
            return $this->jsonFailure('不能删除自己！');
        }
        UserModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'url' => $this->getUrl('user')
        ]);
    }
}