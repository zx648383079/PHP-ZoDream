<?php
namespace Module\CMS\Domain\Entities;


use Domain\Entities\Entity;

/**
 * @property integer $id
 * @property string $name
 * @property integer $type
 * @property string $code
 * @property string $language
 * @property string $uri_template
 */
class LinkageEntity extends Entity {
    public static function tableName(): string {
        return 'cms_linkage';
    }

    protected function rules(): array {
        return [
            'name' => 'required|string:0,100',
            'type' => 'int:0,9',
            'code' => 'required|string:0,20',
            'language' => 'string:0,20',
            'uri_template' => 'string:0,250',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'name' => '名称',
            'type' => '类型',
            'code' => '别名(模板调用)',
            'language' => '语言',
            'uri_template' => '网址模板'
        ];
    }
}