<?php
namespace Module\Auth\Domain\Model\RBAC;


use Domain\Model\Model;
use Domain\Model\ModelHelper;

/**
 * Class RoleModel
 * @package Module\Auth\Domain\Model
 * @property integer $id
 * @property string $name
 * @property string $display_name
 * @property string $description
 * @property integer $created_at
 * @property integer $updated_at
 */
class RoleModel extends Model {
    public static function tableName(): string {
        return 'rbac_role';
    }

    protected function rules(): array {
        return [
            'name' => 'required|string:0,40',
            'display_name' => 'string:0,100',
            'description' => 'string:0,255',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'name' => '角色名',
            'display_name' => '别名',
            'description' => '说明',
            'created_at' => '注册时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * @return PermissionModel[]
     * @throws \Exception
     */
    public function cachedPermissions() {
        $cacheKey = 'auth_permissions_for_role_' . $this->id;
        return cache()->getOrSet($cacheKey, function () {
            return $this->perms()->get();
        }, 60);
    }

    public function getPermIdsAttribute() {
        if ($this->id < 1) {
            return [];
        }
        return RolePermissionModel::where('role_id', $this->id)->pluck('permission_id');
    }

    public function perms() {
        return $this->belongsToMany(PermissionModel::class,
            RolePermissionModel::class,
            'role_id', 'permission_id');
    }

    /**
     * 保存权限
     * @param $perms
     * @throws \Exception
     */
    public function setPermission($perms) {
        list($add, $_, $del) = ModelHelper::splitId((array)$perms, $this->perm_ids);
        if (!empty($del)) {
            RolePermissionModel::where('role_id', $this->id)
                ->whereIn('permission_id', $del)
                ->delete();
        }
        if (!empty($add)) {
            RolePermissionModel::query()->insert(array_map(function ($id) {
                return [
                    'role_id' => $this->id,
                    'permission_id' => $id
                ];
            }, $add));
        }
    }

    /**
     * Checks if the role has a permission by its name.
     *
     * @param string|array $name Permission name or array of permission names.
     * @param bool $requireAll All permissions in the array are required.
     *
     * @return bool
     */
    public function hasPermission($name, $requireAll = false) {
        if (is_array($name)) {
            foreach ($name as $permissionName) {
                $hasPermission = $this->hasPermission($permissionName);
                if ($hasPermission && !$requireAll) {
                    return true;
                } elseif (!$hasPermission && $requireAll) {
                    return false;
                }
            }
            // If we've made it this far and $requireAll is FALSE, then NONE of the permissions were found
            // If we've made it this far and $requireAll is TRUE, then ALL of the permissions were found.
            // Return the value of $requireAll;
            return $requireAll;
        } else {
            foreach ($this->cachedPermissions() as $permission) {
                if ($permission->name == $name) {
                    return true;
                }
            }
        }
        return false;
    }
}