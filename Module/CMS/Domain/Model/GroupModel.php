<?php
namespace Module\CMS\Domain\Model;

/**
 * Class GroupModel
 * @property integer $id
 * @property string $name
 * @property integer $type
 * @property string $description
 */
class GroupModel extends BaseModel {
    const TYPE_CATEGORY = 0;
    const TYPE_CONTENT = 1;

    public static function tableName(): string {
        return 'cms_group';
    }

    protected function rules(): array {
        return [
            'name' => 'required|string:0,20',
            'type' => 'int:0,9',
            'description' => 'string:0,255',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'name' => '名称',
            'type' => '类型',
            'description' => '简介',
        ];
    }

}