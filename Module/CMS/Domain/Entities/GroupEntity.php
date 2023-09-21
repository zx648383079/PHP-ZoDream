<?php
namespace Module\CMS\Domain\Entities;


use Domain\Entities\Entity;

/**
 * @property integer $id
 * @property string $name
 * @property integer $type
 * @property string $description
 */
class GroupEntity extends Entity {
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