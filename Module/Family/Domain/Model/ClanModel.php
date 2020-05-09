<?php
namespace Module\Family\Domain\Model;

use Domain\Model\Model;
/**
 * Class ClanModel
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $cover
 * @property integer $status
 * @property integer $user_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class ClanModel extends Model {

	public static function tableName() {
        return 'fy_clan';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,100',
            'description' => 'string:0,255',
            'status' => 'int:0,127',
            'cover' => 'string',
            'user_id' => 'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => '族谱名',
            'description' => '简介',
            'status' => '状态',
            'cover' => '封面',
            'user_id' => 'User Id',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getCoverAttribute() {
        $cover = $this->getAttributeSource('cover');
        if (empty($cover)) {
            $cover = '/assets/images/book_default.jpg';
        }
        return url()->asset($cover);
    }
}