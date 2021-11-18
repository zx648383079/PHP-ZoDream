<?php
declare(strict_types=1);
namespace Module\Auth\Domain\Repositories;

use Domain\Model\ModelHelper;
use Domain\Model\SearchModel;
use Exception;
use Module\Auth\Domain\Events\CancelAccount;
use Module\Auth\Domain\Events\ManageAction;
use Module\Auth\Domain\Model\RBAC\UserRoleModel;
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
        static::saveRoles($model->id, $roles);
        event(new ManageAction('user_edit', $model->name, 5, $model->id));
        return $model;
    }

    public static function saveRoles(int $user, array $roles) {
        list($add, $_, $del) = ModelHelper::splitId($roles,
            UserRoleModel::where('user_id', $user)->pluck('role_id')
        );
        if (!empty($del)) {
            UserRoleModel::where('user_id', $user)
                ->whereIn('role_id', $del)
                ->delete();
        }
        if (!empty($add)) {
            UserRoleModel::query()->insert(array_map(function ($id) use ($user) {
                return [
                    'user_id' => $user,
                    'role_id' => $id
                ];
            }, $add));
        }
    }

    public static function remove(int $id) {
        if ($id == auth()->id()) {
            throw new Exception('不能删除自己！');
        }
        $user = UserModel::find($id);
        $user->delete();
        event(new CancelAccount($user, time()));
        event(new ManageAction('user_remove', $user->name, 5, $user->id));
    }

    /**
     * 缓存用户的权限
     * @param int $user
     * @return array [role => array, roles => string[], permissions => string[]]
     * @throws Exception
     */
    public static function rolePermission(int $user): array {
        return cache()
            ->getOrSet('user_role_permission_'.$user, function () use ($user) {
                return RoleRepository::userRolePermission($user);
            }, 600);
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
        $query = SearchModel::searchWhere(UserModel::query(), ['name'], false, '', $keywords);
        if (!empty($userId)) {
            $query->whereIn('id', $userId);
        }
        return $query->pluck('id');
    }
}