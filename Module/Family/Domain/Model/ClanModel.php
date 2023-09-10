<?php
namespace Module\Family\Domain\Model;

use Domain\Model\Model;
/**
 * Class ClanModel
 * @property integer $id
 * @property string $name
 * @property string $cover
 * @property string $description
 * @property integer $status
 * @property integer $user_id
 * @property string $modify_at
 * @property integer $created_at
 * @property integer $updated_at
 */
class ClanModel extends Model {

	public static function tableName(): string {
        return 'fy_clan';
    }

    protected function rules(): array {
        return [
            'name' => 'required|string:0,100',
            'cover' => 'required|string:0,100',
            'description' => 'string:0,255',
            'status' => 'int:0,127',
            'user_id' => 'int',
            'modify_at' => 'string:0,50',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'name' => '族谱名',
            'description' => '简介',
            'status' => '状态',
            'cover' => '封面',
            'user_id' => 'User Id',
            'modify_at' => '修订时间',
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