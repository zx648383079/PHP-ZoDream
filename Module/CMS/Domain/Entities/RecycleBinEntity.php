<?php
namespace Module\CMS\Domain\Entities;


use Domain\Entities\Entity;

/**
 * @property integer $id
 * @property integer $site_id
 * @property integer $model_id
 * @property integer $item_type
 * @property integer $item_id
 * @property integer $user_id
 * @property string $title
 * @property string $remark
 * @property string $data
 * @property integer $created_at
 */
class RecycleBinEntity extends Entity {
    public static function tableName(): string {
        return 'cms_recycle_bin';
    }

    protected function rules(): array {
        return [
            'site_id' => 'int',
            'model_id' => 'int',
            'item_type' => 'int:0,127',
            'item_id' => 'required|int',
            'user_id' => 'required|int',
            'title' => 'required|string:0,255',
            'remark' => 'string:0,255',
            'data' => 'required',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'site_id' => 'Site Id',
            'model_id' => 'Model Id',
            'item_type' => 'Item Type',
            'item_id' => 'Item Id',
            'user_id' => 'User Id',
            'title' => 'Title',
            'remark' => 'Remark',
            'data' => 'Data',
            'created_at' => 'Created At',
        ];
    }

}