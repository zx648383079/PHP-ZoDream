<?php
namespace Module\Auth\Domain\Repositories;

use Exception;
use Module\Auth\Domain\Model\RBAC\PermissionModel;
use Module\Auth\Domain\Model\RBAC\RoleModel;
use Module\Auth\Domain\Model\RBAC\RolePermissionModel;
use Module\Auth\Domain\Model\RBAC\UserRoleModel;
use Zodream\Database\Query\Builder;

class RoleRepository {

    /**
     * @param array $data
     * @param array $permission
     * @return RoleModel
     * @throws Exception
     */
    public static function saveRole(array $data, array $permission = []) {
        if (!isset($data['name']) || empty($data['name'])) {
            throw new Exception('请输入角色名');
        }
        $id = isset($data['id']) ? intval($data['id']) : 0;
        $count = RoleModel::query()->where('id', '<>', $id)
            ->where('name', $data['name'])->count();
        if ($count > 0) {
            throw new Exception('已存在角色');
        }
        $model = new RoleModel();
        if (!$model->load($data) || !$model->autoIsNew()->save()) {
            throw new Exception($model->getFirstError());
        }
        $model->setPermission($permission);
        return $model;
    }

    /**
     * 新增权限
     * @param $name
     * @param $display_name
     * @param array $permission [name => display_name]
     * @throws Exception
     * @return integer
     */
    public static function newRole($name, $display_name, array $permission = []) {
        $id = RoleModel::query()->where('name', $name)->value('id');
        $existBind = [];
        if ($id < 1) {
            $id = RoleModel::query()->insert(compact('name', 'display_name'));
        } elseif (!empty($permission)) {
            $existBind = RolePermissionModel::where('role_id', $id)->pluck('permission_id');
        }
        if (empty($permission) || empty($id)) {
            return $id;
        }
        $bindId = [];
        $items = PermissionModel::whereIn('name', array_keys($permission))->pluck('id', 'name');
        foreach ($permission as $key => $item) {
            if (!isset($items[$key])) {
                $pid = PermissionModel::query()->insert([
                    'name' => $key,
                    'display_name' => $item,
                ]);
            } else {
                $pid = $items[$key];
            }
            if ($pid > 0 && !in_array($pid, $existBind)) {
                $bindId[] = [
                    'role_id' => $id,
                    'permission_id' => $pid,
                ];
            }
        }
        if (!empty($bindId)) {
            RolePermissionModel::query()->insert($bindId);
        }
        return $id;
    }

    /**
     * 获取用户的角色和权限
     * @param $user_id
     * @return array
     */
    public static function userRolePermission($user_id) {
        $role = null;
        $roles = [];
        $permissions = [];
        $roleId = UserRoleModel::where('user_id', $user_id)->pluck('role_id');
        if (empty($roleId)) {
            return compact('role', 'roles', 'permissions');
        }
        $roles = RoleModel::whereIn('id', $roleId)->pluck('name');
        $role = RoleModel::where('id', min($roleId))->first();
        if (in_array('administrator', $roles)) {
            $permissions = PermissionModel::query()->pluck('name');
        } else {
            $permissionId = RolePermissionModel::whereIn('role_id', $roleId)
                ->pluck('permission_id');
            if (!empty($permissionId)) {
                $permissions = PermissionModel::query()->whereIn('id', $permissionId)
                    ->pluck('name');
            }
        }
        return compact('role', 'roles', 'permissions');
    }

    public static function rolePermissions($role_id) {
        $permissionId = RolePermissionModel::where('role_id', $role_id)
            ->pluck('permission_id');
        if (!empty($permissionId)) {
            return PermissionModel::query()->whereIn('id', $permissionId)
                ->get();
        }
        return [];
    }

    public static function savePermission(array $data) {
        if (!isset($data['name']) || empty($data['name'])) {
            throw new Exception('请输入权限名');
        }
        $id = isset($data['id']) ? intval($data['id']) : 0;
        $count = PermissionModel::query()->where('id', '<>', $id)
            ->where('name', $data['name'])->count();
        if ($count > 0) {
            throw new Exception('已存在权限');
        }
        $model = new PermissionModel();
        if (!$model->load($data) || !$model->autoIsNew()->save()) {
            throw new Exception($model->getFirstError());
        }
        return $model;
    }
}