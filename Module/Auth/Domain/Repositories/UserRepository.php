<?php
namespace Module\Auth\Domain\Repositories;


use Exception;
use Module\Auth\Domain\Events\CancelAccount;
use Module\Auth\Domain\Model\UserModel;
use Module\Auth\Domain\Model\UserSimpleModel;

class UserRepository {

    public static function getCurrentProfile() {
        /** @var UserModel $user */
        $user = auth()->user();
        $data = $user->toArray();
        $data['is_admin'] = $user->isAdministrator() || $user->hasRole('shop_admin');
        return $data;
    }

    public static function getAll(string $keywords = '') {
        return UserModel::when(!empty($keywords), function ($query) {
            UserSimpleModel::searchWhere($query, 'name');
        })->orderBy('id', 'desc')->page();
    }

    /**
     * @param int $id
     * @return bool|UserModel
     * @throws Exception
     */
    public static function get(int $id) {
        $model = UserModel::findIdentity($id);
        if (empty($model)) {
            throw new Exception('会员不存在');
        }
        return $model;
    }

    /**
     * 保存用户
     * @param array $data
     * @param array $roles
     * @return UserModel
     * @throws Exception
     */
    public static function save(array $data, array $roles) {
        $id = isset($data['id']) && $data['id'] > 0 ? intval($data['id']) : 0;
        $rule = $id > 0 ? [
            'name' => 'required|string',
            'email' => 'required|email',
            'sex' => 'int',
            'avatar' => 'string',
            'birthday' => 'string',
            'password' => 'string',
        ] : [
            'name' => 'required|string',
            'email' => 'required|email',
            'sex' => 'int',
            'avatar' => 'string',
            'birthday' => 'string',
            'password' => 'required|string',
        ];
        if ($data['password'] != $data['confirm_password']) {
            throw new Exception('两次密码不一致！');
        }
        if (empty($data['password'])) {
            unset($data['password'], $data['confirm_password']);
        }
        $model = UserModel::findOrNew($id);
        if (!$model->load($data) || !$model->validate($rule)) {
            throw new Exception($model->getFirstError());
        }
        if (!empty($data['password'])) {
            $model->setPassword($data['password']);
        }
        if (!$model->save()) {
            throw new Exception($model->getFirstError());
        }
        $model->setRole($roles);
        return $model;
    }

    public static function remove(int $id) {
        if ($id == auth()->id()) {
            throw new Exception('不能删除自己！');
        }
        $user = UserModel::find($id);
        $user->delete();
        event(new CancelAccount($user, time()));
    }
}