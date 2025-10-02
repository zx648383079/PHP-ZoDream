<?php
declare(strict_types=1);
namespace Module\Auth\Domain\Repositories;

use Domain\Constants;
use Exception;
use Module\Auth\Domain\Events\ManageAction;
use Module\Auth\Domain\Model\RBAC\PermissionModel;
use Module\Auth\Domain\Model\RBAC\RoleModel;
use Module\Auth\Domain\Model\RBAC\RolePermissionModel;
use Module\Auth\Domain\Model\RBAC\UserRoleModel;

final class RoleRepository {

    /**
     * @param array $data
     * @param array $permission
     * @return RoleModel
     * @throws Exception
     */
    public static function saveRole(array $data, array $permission = []) {
        if (empty($data['name'])) {
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
        event(new ManageAction('role_edit', '', Constants::TYPE_ROLE, $model->id));
        return $model;
    }

    /**
     * 新增角色和权限
     * @param $name
     * @param $display_name
     * @param array $permission [name => display_name]
     * @throws Exception
     * @return integer
     */
    public static function newRole(string $name, string $display_name, array $permission = []) {
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
        $permissionId = static::newPermission($permission);
        foreach ($permissionId as $pid) {
            if (!in_array($pid, $existBind)) {
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
     * 新增权限
     * @param array $permission
     * @return array
     * @throws Exception
     */
    public static function newPermission(array $permission = []): array {
        if (empty($permission)) {
            return [];
        }
        $idItems = [];
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
            if ($pid > 0) {
                $idItems[] = $pid;
            }
        }
        return $idItems;
    }

    /**
     * 获取用户的角色和权限
     * @param $user_id
     * @return array [role => array, roles => string[], permissions => string[]]
     */
    public static function userRolePermission(int $user_id) {
        $role = null;
        $roles = [];
        $permissions = [];
        $roleId = UserRoleModel::where('user_id', $user_id)->pluck('role_id');
        if (empty($roleId)) {
            return compact('role', 'roles', 'permissions');
        }
        $roles = RoleModel::whereIn('id', $roleId)->pluck('name');
        $role = RoleModel::where('id', min($roleId))->first()->toArray();
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

    public static function rolePermissions(int $role_id) {
        $permissionId = RolePermissionModel::where('role_id', $role_id)
            ->pluck('permission_id');
        if (!empty($permissionId)) {
            return PermissionModel::query()->whereIn('id', $permissionId)
                ->get();
        }
        return [];
    }

    public static function savePermission(array $data) {
        if (empty($data['name'])) {
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
        event(new ManageAction('permission_edit', $model->name, Constants::TYPE_ROLE_PERMISSION, $model->id));
        return $model;
    }

    public static function removeRole(int $id) {
        $model = RoleModel::find($id);
        if (empty($model)) {
            return;
        }
        $model->delete();
        UserRoleModel::where('role_id', $id)->delete();
        RolePermissionModel::where('role_id', $id)->delete();
        event(new ManageAction('role_remove', $model->name, Constants::TYPE_ROLE, $model->id));
    }

    public static function all(): array {
        return RoleModel::query()->get('id', 'name', 'display_name');
    }
}