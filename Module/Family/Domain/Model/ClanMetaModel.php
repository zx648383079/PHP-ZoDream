<?php
namespace Module\Family\Domain\Model;

use Domain\Model\Model;

/**
 * Class ClanMetaModel
 * @property integer $id
 * @property integer $clan_id
 * @property string $name
 * @property string $content
 * @property integer $user_id
 * @property string $author
 * @property integer $position
 * @property string $modify_at
 * @property integer $created_at
 * @property integer $updated_at
 */
class ClanMetaModel extends Model {

	public static function tableName() {
        return 'fy_clan_meta';
    }

    protected function rules() {
        return [
            'clan_id' => 'required|int',
            'name' => 'required|string:0,100',
            'content' => '',
            'user_id' => 'required|int',
            'author' => 'string:0,30',
            'position' => 'int:0,127',
            'modify_at' => 'string:0,50',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'clan_id' => 'Clan Id',
            'name' => '名称',
            'content' => '内容',
            'user_id' => 'User Id',
            'author' => '作者',
            'position' => '排序',
            'modify_at' => '修订时间',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}