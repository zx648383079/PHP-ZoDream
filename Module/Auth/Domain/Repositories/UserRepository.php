<?php
namespace Module\Auth\Domain\Repositories;


use Domain\Model\SearchModel;
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
            SearchModel::searchWhere($query, 'name');
        })->orderBy('id', 'desc')->page();
    }

    public static function searchUser(string $keywords = '') {
        return UserSimpleModel::when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, 'name');
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

    public static function getName(int $id) {
        return UserModel::where('id', $id)->value('name');
    }

    /**
     * 获取用户id
     * @param string $keywords
     * @param array $userId
     * @param bool $checkEmpty true 为先判断 $userId 是否为空
     * @return array
     */
    public static function searchUserId(string $keywords, array $userId = [], bool $checkEmpty = false): array {
        if (empty($keywords)) {
            return $userId;
        }
        if ($checkEmpty && empty($userId)) {
            return [];
        }
        return SearchModel::searchWhere(UserModel::query(), ['name'], false, '', $keywords)
            ->whereIn('id', $userId)->pluck('id');
    }
}