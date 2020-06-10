<?php
namespace Module\Auth\Domain\Repositories;

use Module\Auth\Domain\Model\RBAC\PermissionModel;
use Module\Auth\Domain\Model\RBAC\RoleModel;
use Module\Auth\Domain\Model\RBAC\RolePermissionModel;

class RoleRepository {

    /**
     * 新增权限
     * @param $name
     * @param $display_name
     * @param array $permission
     * @throws \Exception
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




}