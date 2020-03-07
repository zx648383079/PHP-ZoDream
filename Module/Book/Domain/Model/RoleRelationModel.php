<?php
namespace Module\Book\Domain\Model;

use Domain\Model\Model;

/**
 * Class BookRoleRelationModel
 * @property integer $id
 * @property integer $role_id
 * @property string $title
 * @property integer $role_link
 */
class RoleRelationModel extends Model {

    public static function tableName() {
        return 'book_role_relation';
    }

    protected function rules() {
        return [
            'role_id' => 'required|int',
            'title' => 'required|string:0,50',
            'role_link' => 'required|int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'role_id' => 'Role Id',
            'title' => 'Title',
            'role_link' => 'Role Link',
        ];
    }
}