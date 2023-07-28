<?php
namespace Module\CMS\Domain\Model;

use Domain\Model\Model;

/**
 * Class LinkageModel
 * @package Module\CMS\Domain\Model
 * @property integer $id
 * @property string $name
 * @property integer $type
 * @property string $code
 * @property string $language
 */
class LinkageModel extends Model {
    public static function tableName() {
        return 'cms_linkage';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,100',
            'type' => 'int:0,9',
            'code' => 'required|string:0,20',
            'language' => 'string:0,20'
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => '名称',
            'type' => '类型',
            'code' => '别名(模板调用)',
            'language' => '语言'
        ];
    }


}